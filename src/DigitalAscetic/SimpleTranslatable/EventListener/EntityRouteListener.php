<?php
/**
 * Created by IntelliJ IDEA.
 * User: martino
 * Date: 24/02/16
 * Time: 01:40
 */

namespace DigitalAscetic\SimpleTranslatable\EventListener;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Router;

class EntityRouteListener implements EventSubscriberInterface {

  /**
   * @var ContainerInterface $_container
   */
  private $_container;

  /**
   * Returns an array of events this subscriber wants to listen to.
   *
   * @return array
   */
  public static function getSubscribedEvents() {
    return array(
      KernelEvents::CONTROLLER => 'onKernelController'
    );
  }

  public function __construct(ContainerInterface $container) {
    $this->_container = $container;
  }

  public function onKernelController(FilterControllerEvent $event) {

    $request = $event->getRequest();

    /** @var Router $router */
    $router = $this->_container->get('router');
    /** @var RouteCollection $routeCollection */
    $routeCollection = $router->getOriginalRouteCollection();

    $routeName = $request->get('_route');

    /** @var Route $route */
    $route = $routeCollection->get($routeName);

    $params = $request->get('_route_params');

    //$allOptions = $route->getOptions();

    //print_r($routeName);
    //print_r($params);
   // print_r($route->getOptions());
   // exit();

  }
}