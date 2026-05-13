<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

class LocaleSubscriber implements EventSubscriberInterface
{
    private const AVAILABLE_LOCALES = ['en', 'fr'];

    public static function getSubscribedEvents(): array
    {
        return [
            KernelEvents::REQUEST => ['onKernelRequest', 20],
        ];
    }

    public function onKernelRequest(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        $request = $event->getRequest();
        $locale = $request->query->get('_locale') ?? $request->query->get('locale');

        if (\is_string($locale) && \in_array($locale, self::AVAILABLE_LOCALES, true)) {
            $request->setLocale($locale);

            if ($request->hasSession()) {
                $request->getSession()->set('_locale', $locale);
            }

            return;
        }

        if ($request->hasSession()) {
            $sessionLocale = $request->getSession()->get('_locale');

            if (\is_string($sessionLocale) && \in_array($sessionLocale, self::AVAILABLE_LOCALES, true)) {
                $request->setLocale($sessionLocale);
            }
        }
    }
}
