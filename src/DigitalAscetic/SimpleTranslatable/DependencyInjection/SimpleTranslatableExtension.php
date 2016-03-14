<?php

namespace DigitalAscetic\SimpleTranslatable\DependencyInjection;


use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SimpleTranslatableExtension extends Extension implements PrependExtensionInterface {

  public function load(array $configs, ContainerBuilder $container) {

    $loader = new XmlFileLoader(
      $container,
      new FileLocator(__DIR__ . '/../Resources/config')
    );
    $loader->load('services.xml');


  }

  /**
   * Allow an extension to prepend the extension configurations.
   *
   * @param ContainerBuilder $container
   */
  public function prepend(ContainerBuilder $container) {

    $configs = $container->getExtensionConfig('jms_i18n_routing');

    if ($configs && count($configs) && isset($configs[0]['locales'])) {

      $locales = $configs[0]['locales'];

      $twigBundleConfig = array(
        'globals' => array(
          'locales' => $locales
        )
      );

      $container->prependExtensionConfig('twig', $twigBundleConfig);

      $container->setParameter('simple_translatable.locales', $locales);
      $container->setParameter('simple_translatable.default_locale', $configs[0]['default_locale']);

    }


  }
}