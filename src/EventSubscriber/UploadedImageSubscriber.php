<?php

namespace App\EventSubscriber;

use App\Entity\Discipline;
use App\Service\ImageOptimizer;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Vich\UploaderBundle\Event\Event;
use Vich\UploaderBundle\Event\Events;

/**
 * Images des disciplines : converties en WebP (plusieurs largeurs) juste après l'upload Vich,
 * déclinaisons supprimées avec l'image (Vich supprime lui-même l'image principale).
 */
class DisciplineImageSubscriber implements EventSubscriberInterface
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

        $this->optimizer->process($path, $webpPath, Discipline::IMAGE_WIDTHS[$mapping->getMappingName()]);

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

    private function imagePath(Event $event): ?string
    {
        $object = $event->getObject();
        $mapping = $event->getMapping();

        if (!$object instanceof Discipline || !isset(Discipline::IMAGE_WIDTHS[$mapping->getMappingName()])) {
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
