<?php
namespace DigitalAscetic\SimpleTranslatable\EventListener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 *
 */
class LocaleListener implements EventSubscriberInterface {
    private $defaultLocale;

    private $availableLocales;

    public function __construct($defaultLocale = 'en', $availableLocales = array('en')) {
        $this->defaultLocale = $defaultLocale;
        $this->availableLocales = $availableLocales;
    }

    public function onKernelRequest(GetResponseEvent $event) {

        $request = $event->getRequest();

        // If no session is present try to detect the right locale from the
        // browser allowed locales.
        if (!$request->hasPreviousSession()) {
            foreach ($request->getLanguages() as $locale) {
                if (in_array($locale, $this->availableLocales)) {
                    $request->getSession()->set('_locale', $locale);
                    break;
                }
            }
            return;
        }

        // try to see if the locale has been set as a _locale routing parameter
        if ($locale = $request->attributes->get('_locale')) {
            $request->getSession()->set('_locale', $locale);
        }
        else {
            // if no explicit locale has been set on this request, use one from the session
            $request->setLocale($request->getSession()->get('_locale', $this->defaultLocale));
        }
    }

    public static function getSubscribedEvents() {
        return array(
            // must be registered before the default Locale listener
            KernelEvents::REQUEST => array(array('onKernelRequest', 17)),
        );
    }
}