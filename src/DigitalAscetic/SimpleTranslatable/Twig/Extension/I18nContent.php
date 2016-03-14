<?php

namespace DigitalAscetic\SimpleTranslatable\Twig\Extension;

use DigitalAscetic\SimpleTranslatable\Entity\Translatable;
use DigitalAscetic\SimpleTranslatable\Service\TranslatableService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use JMS\I18nRoutingBundle\Router\I18nRouter;
use DigitalAscetic\SimpleTranslatable\Entity\TranslatableBehaviour;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;
use Twig_Extension;


class I18nContent extends Twig_Extension {

  /** @var  TranslatableService $translatableService */
  private $translatableService;


  public function __construct(TranslatableService $translatableService) {
    $this->translatableService = $translatableService;
  }

  /**
   * {@inheritdoc}
   */
  public function getFunctions() {
    return array(
      new \Twig_SimpleFunction('hasTranslations', array($this, 'hasTranslations')),
      new \Twig_SimpleFunction('missTranslations', array($this, 'missTranslations')),
      new \Twig_SimpleFunction('translatedLocales', array($this, 'getTranslatedLocales')),
      new \Twig_SimpleFunction('untranslatedLocales', array($this, 'getUntranslatedLocales')),
    );
  }

  public function hasTranslations(Translatable $entity, $includeSelf = true) {

    return (count($this->getTranslatedLocales($entity, $includeSelf)) > 0);

  }

  public function missTranslations(Translatable $entity) {

    return count($this->getUntranslatedLocales($entity) > 0);

  }

  public function getTranslatedLocales(Translatable $entity, $includeSelf = true) {
    return $this->translatableService->getTranslatedLocales($entity, $includeSelf);
  }

  public function getUntranslatedLocales(Translatable $entity) {
    return $this->translatableService->getUntranslatedLocales($entity);
  }

  /**
   * Returns the name of the extension.
   *
   * @return string The extension name
   */
  public function getName() {
    return 'i18nContent';
  }
}
