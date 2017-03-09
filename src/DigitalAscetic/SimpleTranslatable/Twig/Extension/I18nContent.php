<?php

namespace DigitalAscetic\SimpleTranslatable\Twig\Extension;

use DigitalAscetic\SimpleTranslatable\Entity\Translatable;
use DigitalAscetic\SimpleTranslatable\Service\TranslatableService;
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
            new \Twig_SimpleFunction('translation', array($this, 'getTranslation')),
        );
    }

    public function hasTranslations(Translatable $entity) {

        return (count($this->getTranslatedLocales($entity, false)) > 0);

    }

    public function missTranslations(Translatable $entity) {

        return (count($this->getUntranslatedLocales($entity)) > 0);

    }

    public function getTranslatedLocales(Translatable $entity, $includeSelf = true) {
        return $this->translatableService->getTranslatedLocales($entity, $includeSelf);
    }

    public function getUntranslatedLocales(Translatable $entity) {
        return $this->translatableService->getUntranslatedLocales($entity);
    }

    public function getTranslation(Translatable $entity, $locale) {
        return $this->translatableService->getTranslation($entity, $locale);
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