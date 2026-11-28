<?php

namespace App\EventSubscriber;

use App\Entity\Blog;
use App\Entity\Discipline;
use App\Entity\Outing;
use App\Service\ImageOptimizer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;

/**
 * Images des disciplines, des sorties et des articles du blog : converties en WebP (plusieurs largeurs) juste après l'upload Vich,
 * déclinaisons supprimées avec l'image (Vich supprime lui-même l'image principale).
 */
class UploadedImageSubscriber implements EventSubscriberInterface
{
    public function __construct(private ImageOptimizer $optimizer)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            Events::POST_UPLOAD => 'onPostUpload',
            Events::PRE_REMOVE => 'onPreRemove',
        ];
    }

    public function onPostUpload(Event $event): void
    {
        if (!$path = $this->imagePath($event)) {
            return;
        }

        $mapping = $event->getMapping();
        $webpName = preg_replace('/\.[^.\/]+$/', '', $mapping->getFileName($event->getObject())).'.webp';
        $webpPath = dirname($path).'/'.basename($webpName);

        $this->optimizer->process($path, $webpPath, $this->widths($event));

        if ($webpPath !== $path) {
            @unlink($path);
        }
        $mapping->setFileName($event->getObject(), $webpName);
    }

    public function onPreRemove(Event $event): void
    {
        if ($path = $this->imagePath($event)) {
            $this->optimizer->deleteVariants($path);
        }
    }

    /**
     * Largeurs WebP du champ uploadé (Discipline::IMAGE_WIDTHS, Outing::IMAGE_WIDTHS, Blog::IMAGE_WIDTHS), vide pour les autres entités.
     *
     * @return int[]
     */
    private function widths(Event $event): array
    {
        $object = $event->getObject();
        if (!$object instanceof Discipline && !$object instanceof Outing && !$object instanceof Blog) {
            return [];
        }

        return $object::IMAGE_WIDTHS[$event->getMapping()->getMappingName()] ?? [];
    }

    private function imagePath(Event $event): ?string
    {
        $object = $event->getObject();
        $mapping = $event->getMapping();

        if (!$this->widths($event)) {
            return null;
        }

        $fileName = $mapping->getFileName($object);
        if (!$fileName) {
            return null;
        }

        $dir = $mapping->getUploadDir($object);

        return rtrim($mapping->getUploadDestination(), '/').($dir ? '/'.trim($dir, '/') : '').'/'.$fileName;
    }
}
