<?php

namespace DigitalAscetic\SimpleTranslatable\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Created by Roberto Noris.
 * Project: simple-translatable
 * Date: 09/03/2017
 */
class LocaleSwitcherController extends Controller {

    /**
     * @param string $_locale
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function changeLocaleAction($_locale = 'en', Request $request) {

        $this->get('session')->set('_locale', $_locale);
        $request->setLocale($_locale);

        // If we have a referer try to replace the locale in the url and redirect.
        if ($request->headers->has('referer')) {
            $referer = $request->headers->get('referer');
            $base = $request->getScheme() . '://' . $request->getHttpHost() . $request->getBaseUrl();

            $rest = str_replace($base, '', $referer);
            $rest = ltrim($rest, '/');
            $oldLocale = substr($rest, 0, 2);

            $locales = $this->get('simple_translatable.translatable_service')->getLocales();
            if (in_array($oldLocale, $locales)) {
                $referer = str_replace('/' . $oldLocale . '/', '/' . $_locale . '/', $referer);
            }

            $response = new RedirectResponse($referer, 302);
        }
        // Otherwise redirect to the home.
        else {
            $response = new RedirectResponse($request->getBaseUrl() . '/' . $_locale, 302);
        }

        return $response;

    }

}