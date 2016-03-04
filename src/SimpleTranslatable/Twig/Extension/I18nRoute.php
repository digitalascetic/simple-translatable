<?php

namespace LocaleSupport\Twig\Extension;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JMS\I18nRoutingBundle\Router\I18nRouter;
use SimpleTranslatable\Entity\TranslatableBehaviour;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Twig_Extension;


class I18nRoute extends Twig_Extension {

  /**
   * @var ContainerInterface $container
   */
  private $container;

  /**
   * @var EntityManager $em
   */
  private $em;


  public function __construct(
    ContainerInterface $container,
    EntityManager $em
  ) {
    $this->container = $container;
    $this->em = $em;
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return array(
      new \Twig_SimpleFunction('i18nRoute', array($this, 'i18nRoute')),
    );
  }

  public function i18nRoute($locale = 'en') {

    /** @var I18nRouter $router */
    $router = $this->container->get('router');

    /** @var Request $request */
    $request = $this->container->get('request');

    /** @var RouteCollection $routeCollection */
    $routeCollection = $router->getOriginalRouteCollection();

    $routeName = $request->get('_route');

    /** @var Route $route */
    $route = $routeCollection->get($routeName);

    $params = $request->get('_route_params');

    $translatable_class = $route->getOption('translatable_class');

    if ($translatable_class) {
      $translatable_slug = $route->getOption('translatable_slug') || 'slug';
      $translatable_slug_param = $route->getOption('translatable_slug_param') || $translatable_slug;

      /** @var EntityRepository $repo */
      $repo = $this->em->getRepository($translatable_class);

      /** @var TranslatableBehaviour $translatableEntity */
      $translatableEntity = $repo->findOneBy(array($translatable_slug => $params[$translatable_slug_param]));

      $translatedEntity = $translatableEntity->getTranslation($locale);

      $translatedSlug = call_user_func($translatedEntity, 'get' . ucfirst($translatable_slug));

      return $router->generate(
        $routeName,
        array_merge($params, array($translatable_slug_param => $translatedSlug))
      );

    }

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
