<?php

namespace App\Command;

use App\Entity\PricingGroup;
use App\Entity\PricingLine;
use App\Entity\PricingSection;
use App\Enum\PricingSeason;
use App\Repository\PricingPageRepository;
use App\Repository\PricingSectionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Attribute\Option;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Import unique : convertit l'ancien texte riche de la page tarifs (PricingPage::content) en sections / blocs / lignes.
 *  - chaque <h1> ouvre une section,
 *  - chaque ligne entièrement en gras ouvre un bloc,
 *  - les lignes avec un prix (« Voie normale 1000€ », « 400€/j pour 2 personnes ») deviennent libellé + prix,
 *  - les lignes en italique deviennent la note du bloc, le reste des libellés seuls.
 * Le texte avant le premier <h1> reste comme introduction de la page.
 * La version EN est rapprochée de la FR par position (section n, bloc n, ligne n).
 * Saison : « hiver » / « été » dans le titre de section (les sections suivantes héritent de la précédente),
 * et ce préfixe est retiré du titre (« Tarif été – Massif des Écrins » → onglet été, « Massif des Écrins »).
 */
#[AsCommand(name: 'app:pricing:import', description: 'Convertit le texte de la page tarifs en sections / blocs / lignes de prix')]
class PricingImportCommand
{
    private const PRICE = '(?:\d[\d\s.,]*\s?€|€\s?\d[\d\s.,]*)';

    public function __construct(
        private PricingPageRepository $pageRepository,
        private PricingSectionRepository $sectionRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    public function __invoke(
        SymfonyStyle $io,
        #[Option(description: 'Supprime les sections existantes avant l\'import')] bool $force = false,
        #[Option(description: 'Affiche le résultat sans rien enregistrer')] bool $dryRun = false,
    ): int {
        $page = $this->pageRepository->findPage();
        if (!$page || !$page->getContent()) {
            $io->error('Pas de texte à importer sur la page tarifs.');

            return Command::FAILURE;
        }
        $existing = $this->sectionRepository->findAll();
        if ($existing && !$force && !$dryRun) {
            $io->error(sprintf('%d section(s) existent déjà : relancer avec --force pour les remplacer.', count($existing)));

            return Command::FAILURE;
        }

        $fr = $this->parse($page->getContent());
        $en = $this->parse($page->getContentEn());

        $season = PricingSeason::Summer;
        foreach ($fr['sections'] as $s => $sectionData) {
            $sectionEn = $en['sections'][$s] ?? null;
            $season = $this->season($sectionData['title']) ?? $season;
            $section = (new PricingSection())
                ->setSeason($season)
                ->setTitle($this->withoutSeason($sectionData['title']))
                ->setTitleEn($this->withoutSeason($sectionEn['title'] ?? null))
                ->setPosition(($s + 1) * 10);
            $io->section((string) $section);

            foreach ($sectionData['groups'] as $g => $groupData) {
                $groupEn = $sectionEn['groups'][$g] ?? null;
                [$rows, $note] = $this->splitNote($groupData['rows']);
                [$rowsEn, $noteEn] = $this->splitNote($groupEn['rows'] ?? []);
                $group = (new PricingGroup())
                    ->setTitle($groupData['title'])
                    ->setTitleEn($groupEn['title'] ?? null)
                    ->setNote($note)
                    ->setNoteEn($noteEn)
                    ->setPosition(($g + 1) * 10);
                $section->addGroup($group);

                foreach ($rows as $l => $row) {
                    $rowEn = $rowsEn[$l] ?? null;
                    $group->addLine((new PricingLine())
                        ->setLabel($row['label'] ?: null)
                        ->setPrice($row['price'])
                        ->setLabelEn(($rowEn['label'] ?? null) ?: null)
                        ->setPriceEn($rowEn['price'] ?? null));
                }
                $group->renumberLines();

                $io->text(sprintf('<info>%s</info> (%d lignes)%s', $group->getTitle() ?? '—', $group->getLines()->count(), $note ? ' + note' : ''));
                foreach ($group->getLines() as $line) {
                    $io->text(sprintf('    %s  |  %s', $line->getLabel() ?? '', $line->getPrice() ?? ''));
                }
                $this->entityManager->persist($group);
            }
            $this->entityManager->persist($section);
        }

        if ($dryRun) {
            $io->note('Dry-run : rien n\'a été enregistré.');

            return Command::SUCCESS;
        }

        foreach ($existing as $section) {
            $this->entityManager->remove($section);
        }
        // seul le texte d'introduction reste dans PricingPage
        $page->setContent($fr['intro'])->setContentEn($en['intro'])->setUpdatedAt(new \DateTimeImmutable());
        $this->entityManager->flush();

        $io->success(sprintf('%d sections importées.', count($fr['sections'])));

        return Command::SUCCESS;
    }

    private function season(?string $title): ?PricingSeason
    {
        return match (true) {
            (bool) preg_match('#hiver|winter#iu', (string) $title) => PricingSeason::Winter,
            (bool) preg_match('#été|ete\b|summer#iu', (string) $title) => PricingSeason::Summer,
            default => null,
        };
    }

    // « Tarif hiver » → null ; « Tarif été – Massif des Écrins » → « Massif des Écrins »
    private function withoutSeason(?string $title): ?string
    {
        $title = preg_replace('#^\s*(?:tarifs?\s+(?:d\'\s*)?(?:hiver|été|ete)|(?:winter|summer)\s+rates?)\s*[–-]?\s*#iu', '', (string) $title);

        return trim((string) $title) ?: null;
    }

    /**
     * Lignes « prix » et « texte » → libellé / prix ; lignes en italique → note du bloc.
     *
     * @return array{0: list<array{label: string, price: ?string}>, 1: ?string}
     */
    private function splitNote(array $rows): array
    {
        $lines = [];
        $notes = [];
        foreach ($rows as $row) {
            match ($row['type']) {
                'note' => $notes[] = $row['text'],
                'price' => $lines[] = ['label' => $row['label'], 'price' => $row['price']],
                default => $lines[] = ['label' => $row['text'], 'price' => null],
            };
        }

        return [$lines, $notes ? implode("\n", $notes) : null];
    }

    /**
     * @return array{intro: ?string, sections: list<array{title: ?string, groups: list<array>}>}
     */
    private function parse(?string $html): array
    {
        // espaces insécables / zéro-largeur laissés par l'éditeur
        $html = str_replace(["\u{200B}", '&nbsp;'], ['', ' '], (string) $html);
        // gras/italique vide entre deux mots (« Ailefroide<strong> </strong>Orientale ») : garder l'espace
        $html = preg_replace('#<(strong|em)>(\s*)</\1>#i', '$2', $html) ?? $html;

        $parts = preg_split('#<h1[^>]*>(.*?)</h1>#is', $html, -1, PREG_SPLIT_DELIM_CAPTURE);
        if (count($parts) === 1) {
            $groups = $this->groups($html);

            return ['intro' => null, 'sections' => $groups ? [['title' => null, 'groups' => $groups]] : []];
        }

        $sections = [];
        for ($i = 1; $i < count($parts); $i += 2) {
            // « Tarif été<br>Massif des Écrins » → « Tarif été – Massif des Écrins »
            $lines = array_filter(array_map([$this, 'text'], preg_split('#<br\s*/?>#i', $parts[$i])));
            $title = implode(' – ', $lines) ?: null;
            $sections[] = [
                'title' => $title,
                'groups' => $this->groups($parts[$i + 1] ?? ''),
            ];
        }

        $intro = trim(preg_replace('#^(\s|<br\s*/?>|<div>\s*</div>)+|(\s|<br\s*/?>)+(?=</div>\s*$)|(\s|<br\s*/?>)+$#i', '', $parts[0]) ?? '');

        return ['intro' => $this->text($intro) !== '' ? $intro : null, 'sections' => $sections];
    }

    private function groups(string $html): array
    {
        // « <strong>Titre<br></strong> » : le saut de ligne doit rester hors du gras
        $html = preg_replace('#(?:\s|<br\s*/?>)+(</(?:strong|em)>)#i', '$1', $html) ?? $html;
        $lines = preg_split('#<br\s*/?>|</?div[^>]*>|</?p[^>]*>#i', $html);

        $groups = [];
        $current = null;
        foreach ($lines as $line) {
            $text = $this->text($line);
            if ($text === '') {
                continue;
            }
            $bold = $this->wrappedText($line, 'strong');

            // ligne tout en gras : nouveau bloc ; gras + prix (« <strong>Ponteil</strong> 300€ ») : bloc d'une seule ligne
            if ($bold !== null && $bold !== '') {
                $rest = trim(mb_substr($text, mb_strlen($bold)));
                if ($current !== null) {
                    $groups[] = $current;
                }
                $current = ['title' => $bold, 'rows' => []];
                if ($rest !== '') {
                    $current['rows'][] = $this->row($rest);
                }
                continue;
            }

            $current ??= ['title' => null, 'rows' => []];
            $current['rows'][] = $this->wrappedText($line, 'em') !== null
                ? ['type' => 'note', 'text' => $text]
                : $this->row($text);
        }
        if ($current !== null) {
            $groups[] = $current;
        }

        return array_values(array_filter($groups, fn ($g) => $g['title'] !== null || $g['rows'] !== []));
    }

    /**
     * « Voie normale A partir de 1350€ » → libellé + prix ; « 400€/j pour 1 à 2 personnes » → prix en tête.
     */
    private function row(string $text): array
    {
        if (preg_match('#^(.*?)[\s:–-]*((?:[aà] partir de|from)\s*)?(' . self::PRICE . ')\s*$#iu', $text, $m) && $m[3] !== '') {
            $from = $m[2] !== '' ? (preg_match('#^from#i', $m[2]) ? 'from ' : 'à partir de ') : '';

            return ['type' => 'price', 'label' => trim($m[1]), 'price' => $from . trim($m[3])];
        }
        if (preg_match('#^(' . self::PRICE . '(?:\s?/\s?[^\s]+)?)\s*(.*)$#u', $text, $m)) {
            return ['type' => 'price', 'label' => trim($m[2]), 'price' => trim($m[1])];
        }

        return ['type' => 'text', 'text' => $text];
    }

    /**
     * Texte en début de ligne entièrement dans la balise donnée (ex. « <strong>Titre</strong> 300€ » → « Titre »),
     * null si la ligne ne commence pas par cette balise.
     */
    private function wrappedText(string $line, string $tag): ?string
    {
        if (!preg_match('#^\s*<' . $tag . '[^>]*>(.*?)</' . $tag . '>#is', $line, $m)) {
            return null;
        }
        $inner = $this->text($m[1]);
        $after = $this->text(preg_replace('#^\s*<' . $tag . '[^>]*>.*?</' . $tag . '>#is', '', $line) ?? '');

        // italique : toute la ligne ; gras : titre seul ou suivi d'un prix
        if ($tag === 'em') {
            return $after === '' ? $inner : null;
        }

        return $after === '' || preg_match('#' . self::PRICE . '#u', $after) ? $inner : null;
    }

    private function text(string $html): string
    {
        $text = html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8');

        return trim(preg_replace('/[\s\x{00A0}]+/u', ' ', $text) ?? $text);
    }
}
