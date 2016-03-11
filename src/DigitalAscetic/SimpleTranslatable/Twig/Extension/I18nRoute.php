<?php

namespace DigitalAscetic\SimpleTranslatable\Twig\Extension;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JMS\I18nRoutingBundle\Router\I18nRouter;
use DigitalAscetic\SimpleTranslatable\Entity\TranslatableBehaviour;
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
      new \Twig_SimpleFunction('i18nRoute', array($this, 'getI18nRoute')),
    );
  }

  public function getI18nRoute($routeName = null, $params = null, $locale = null) {

    /** @var I18nRouter $router */
    $router = $this->container->get('router');

    /** @var Request $request */
    $request = $this->container->get('request');

    /** @var RouteCollection $routeCollection */
    $routeCollection = $router->getOriginalRouteCollection();

    if (!$routeName) {
      $routeName = $request->get('_route');
      if (!$params) {
        $params = $request->get('_route_params');
      }
    }

    if (!$params) {
      $params = array();
    }

    if (!$locale) {
      $locale = $request->getLocale();
    }

    /** @var Route $route */
    $route = $routeCollection->get($routeName);

    $translatable_class = $route->getOption('translatable_class');

    if ($translatable_class) {

      $translatable_slug = $route->getOption('translatable_slug') ? $route->getOption('translatable_slug') : 'slug';
      $translatable_slug_param = $route->getOption('translatable_slug_param') ? $route->getOption(
        'translatable_slug_param'
      ) : $translatable_slug;

      /** @var EntityRepository $repo */
      $repo = $this->em->getRepository($translatable_class);

      /** @var TranslatableBehaviour $translatableEntity */
      $translatableEntity = $repo->findOneBy(
        array($translatable_slug => $params[$translatable_slug_param])
      );

      $translatedEntity = $translatableEntity->getTranslation($locale);

      if (!$translatedEntity) {
        return null;
      }

      $translatedSlug = call_user_func(array($translatedEntity, 'get' . ucfirst($translatable_slug)));

      $params = array_merge($params, array($translatable_slug_param => $translatedSlug));

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
