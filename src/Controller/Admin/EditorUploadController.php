<?php

namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Upload des images insérées dans le texte des articles et des pages secteurs (CKEditor 5, SimpleUploadAdapter).
 * Les images sont réduites à 1600 px de large et converties en webp pour rester légères.
 */
#[IsGranted('ROLE_ADMIN')]
class EditorUploadController extends AbstractController
{
    public const CSRF_ID = 'editor_upload';
    private const MAX_WIDTH = 1600;
    private const UPLOAD_DIR = '/uploads/blog-content';

    #[Route(path: '/admin/editor/upload', name: 'admin_editor_upload', methods: ['POST'])]
    public function upload(Request $request, ValidatorInterface $validator): JsonResponse
    {
        if (!$this->isCsrfTokenValid(self::CSRF_ID, $request->headers->get('X-CSRF-Token'))) {
            return $this->error('Session expirée, rechargez la page.', 403);
        }

        $file = $request->files->get('upload');
        if (!$file instanceof UploadedFile || !$file->isValid()) {
            return $this->error('Aucune image reçue (fichier trop lourd ?).');
        }

        $violations = $validator->validate($file, new Image(
            maxSize: '10M',
            mimeTypes: ['image/jpeg', 'image/png', 'image/webp', 'image/gif'],
            mimeTypesMessage: 'Formats acceptés : JPG, PNG, WebP ou GIF.',
        ));
        if (\count($violations) > 0) {
            return $this->error($violations[0]->getMessage());
        }

        $dir = $this->getParameter('kernel.project_dir').'/public'.self::UPLOAD_DIR;
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $name = bin2hex(random_bytes(8));
        $filename = $this->saveAsWebp($file, $dir, $name);
        if (null === $filename) {
            // GD sans webp ou GIF animé : on garde le fichier tel quel
            $filename = $name.'.'.$file->guessExtension();
            $file->move($dir, $filename);
        }

        return new JsonResponse(['url' => self::UPLOAD_DIR.'/'.$filename]);
    }

    private function saveAsWebp(UploadedFile $file, string $dir, string $name): ?string
    {
        $mime = $file->getMimeType();
        if ('image/gif' === $mime || !\function_exists('imagewebp')) {
            return null;
        }

        $path = $file->getPathname();
        $image = match ($mime) {
            'image/jpeg' => @imagecreatefromjpeg($path),
            'image/png' => @imagecreatefrompng($path),
            'image/webp' => @imagecreatefromwebp($path),
            default => false,
        };
        if (false === $image) {
            return null;
        }

        // photos de téléphone : appliquer la rotation EXIF avant de perdre les métadonnées
        if ('image/jpeg' === $mime && \function_exists('exif_read_data')) {
            $orientation = @exif_read_data($path)['Orientation'] ?? 1;
            $angle = [3 => 180, 6 => -90, 8 => 90][$orientation] ?? 0;
            if (0 !== $angle) {
                $image = imagerotate($image, $angle, 0);
            }
        }

        if (imagesx($image) > self::MAX_WIDTH) {
            $image = imagescale($image, self::MAX_WIDTH);
        }

        imagepalettetotruecolor($image);
        imagealphablending($image, false);
        imagesavealpha($image, true);

        $filename = $name.'.webp';
        $saved = imagewebp($image, $dir.'/'.$filename, 82);
        imagedestroy($image);

        return $saved ? $filename : null;
    }

    // format d'erreur attendu par le SimpleUploadAdapter de CKEditor
    private function error(string $message, int $status = 400): JsonResponse
    {
        return new JsonResponse(['error' => ['message' => $message]], $status);
    }
}
