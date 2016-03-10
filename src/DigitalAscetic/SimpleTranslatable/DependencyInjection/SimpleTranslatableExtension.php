<?php

namespace DigitalAscetic\SimpleTranslatable\DependencyInjection;


use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SimpleTranslatableExtension extends Extension {

  public function load(array $configs, ContainerBuilder $container) {

    $loader = new XmlFileLoader(
      $container,
      new FileLocator(__DIR__ . '/../Resources/config')
    );
    $loader->load('services.xml');

  }

}