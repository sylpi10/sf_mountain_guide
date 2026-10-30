<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    private $defaultLocale;

    public function __construct($defaultLocale = 'fr')
    {
        $this->defaultLocale = $defaultLocale;
    }

    public function onKernelRequest(RequestEvent $event)
    {
        $request = $event->getRequest();

        // page traduite (/en/...) : la langue vient de l'URL, on la retient pour les pages
        // sans version par URL (login, newsletter…)
        if ($locale = $request->attributes->get('_locale')) {
            if ($request->hasPreviousSession()) {
                $request->getSession()->set('_locale', $locale);
            }

            return;
        }

        if (!$request->hasPreviousSession()) {
            return;
        }
        $request->setLocale($request->getSession()->get('_locale',
            $this->defaultLocale));
    }

    public static function getSubscribedEvents(): array
    {
        return [
            // après le routeur (32) pour lire le _locale de la route
            KernelEvents::REQUEST => [['onKernelRequest', 20]],
        ];
    }
}
