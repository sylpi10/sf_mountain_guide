<?php

namespace App\Command;

use App\Entity\Blog;
use App\Entity\Discipline;
use App\Service\ImageOptimizer;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

/**
 * Convertit en WebP les images des disciplines et des articles uploadées avant la conversion automatique.
 * Sans effet sur une image déjà en .webp : peut être relancée sans risque.
 */
#[AsCommand(name: 'app:images:webp', description: 'Convertit en WebP (plusieurs largeurs) les images des disciplines et du blog', aliases: ['app:discipline:webp'])]
class ImagesWebpCommand
{
    // entité => [propriété => mapping Vich] ; les largeurs viennent de la constante IMAGE_WIDTHS de l'entité
    private const FIELDS = [
        Discipline::class => [
            'image' => 'discipline_images',
            'imageDetail' => 'discipline_images_detail',
        ],
        Blog::class => [
            'image' => 'blog_images',
        ],
    ];

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ImageOptimizer $optimizer,
        // les trois mappings Vich partagent le même dossier /uploads
        #[Autowire('%kernel.project_dir%/public%app.path.discipline_images%')] private string $uploadDir,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Option(description: 'Supprime les fichiers d\'origine (jpg…) une fois convertis')] bool $deleteOriginals = false,
    ): int {
        $converted = 0;

        foreach (self::FIELDS as $class => $fields) {
            foreach ($this->entityManager->getRepository($class)->findAll() as $entity) {
                foreach ($fields as $property => $mapping) {
                    $getter = 'get'.ucfirst($property);
                    $fileName = $entity->$getter();

                    if (!$fileName || str_ends_with(strtolower($fileName), '.webp')) {
                        continue;
                    }

                    $source = $this->uploadDir.'/'.$fileName;
                    if (!is_file($source)) {
                        $io->warning(sprintf('%s : fichier introuvable (%s)', $entity->getTitle(), $fileName));
                        continue;
                    }

                    $webpName = preg_replace('/\.[^.]+$/', '', $fileName).'.webp';
                    $this->optimizer->process($source, $this->uploadDir.'/'.$webpName, $class::IMAGE_WIDTHS[$mapping]);

                    $setter = 'set'.ucfirst($property);
                    $entity->$setter($webpName);
                    // flush à chaque image : la base ne pointe jamais vers un fichier pas encore créé
                    $this->entityManager->flush();

                    if ($deleteOriginals) {
                        @unlink($source);
                    }

                    $io->writeln(sprintf('%s (%s) : %s → %s', $entity->getTitle(), $property, $fileName, $webpName));
                    ++$converted;
                }
            }
        }

        $io->success($converted ? "$converted image(s) convertie(s)." : 'Rien à convertir : tout est déjà en WebP.');

        return Command::SUCCESS;
    }
}
