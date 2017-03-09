<?php

namespace DigitalAscetic\SimpleTranslatable\Twig\Extension;

use DigitalAscetic\SimpleTranslatable\Service\TranslatableService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JMS\I18nRoutingBundle\Router\I18nRouter;
use DigitalAscetic\SimpleTranslatable\Entity\TranslatableBehaviour;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Twig_Extension;


class I18nPath extends Twig_Extension {

    /**
     * @var ContainerInterface $container
     */
    private $container;

    /**
     * @var EntityManager $em
     */
    private $em;

    /** @var  TranslatableService $translatableService */
    private $translatableService;


    public function __construct(
        ContainerInterface $container,
        EntityManager $em,
        TranslatableService $translatableService
    ) {
        $this->container = $container;
        $this->em = $em;
        $this->translatableService = $translatableService;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions() {
        return array(
            new \Twig_SimpleFunction('path', array($this, 'getI18nPath')),
        );
    }

    public function getI18nPath($routeName = null, $params = null) {

        $locale = null;

        /** @var Request $request */
        $request = $this->container->get('request');

        if (!$routeName) {
            $routeName = $request->get('_route');
            if (!$params) {
                $params = $request->get('_route_params');
            }
        }

        if (!$params) {
            $params = array();
        }

        if (isset($params['_locale'])) {
            $locale = $params['_locale'];
        }

        if (!$locale) {
            $locale = $request->getLocale();
        }

        if (!$locale) {
            $locale = $this->container->getParameter('default_locale');
        }

        /** @var I18nRouter $router */
        $router = $this->container->get('router');

        /** @var RouteCollection $routeCollection */
        $routeCollection = $router->getOriginalRouteCollection();

        /** @var Route $route */
        $route = $routeCollection->get($routeName);

        if (!$route) {
            throw new RouteNotFoundException("Cannot find route with name " . $routeName);
        }

        $translatable_class = $route->getOption('translatable_class');

        if ($translatable_class) {

            $translatable_slug = $route->getOption('translatable_slug') ? $route->getOption('translatable_slug') : 'slug';
            $translatable_slug_param = $route->getOption('translatable_slug_param') ? $route->getOption(
                'translatable_slug_param'
            ) : $translatable_slug;

            // Act just in case there's no "slug" parameter explicitly set
            if (!isset($params[$translatable_slug_param])) {

                /** @var EntityRepository $repo */
                $repo = $this->em->getRepository($translatable_class);

                $params = array_merge($params, $router->matchRequest($request));
                unset($params['_route']);

                /** @var TranslatableBehaviour $translatableEntity */
                $translatableEntity = $repo->findOneBy(
                    array($translatable_slug => $params[$translatable_slug_param])
                );

                if (!$translatableEntity) {
                    return null;
                }

                $translatedEntity = $this->translatableService->getTranslation($translatableEntity, $locale);

                if (!$translatedEntity) {
                    return null;
                }

                $translatedSlug = call_user_func(array($translatedEntity, 'get' . ucfirst($translatable_slug)));

                $params = array_merge($params, array($translatable_slug_param => $translatedSlug));

            }

        }

        return $router->generate(
            $routeName,
            array_merge($params, array('_locale' => $locale))
        );

    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName() {
        return 'i18nRoute';
    }
}