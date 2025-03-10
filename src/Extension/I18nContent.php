<?php

namespace DigitalAscetic\SimpleTranslatable\Extension;

use DigitalAscetic\SimpleTranslatable\Entity\Translatable;
use DigitalAscetic\SimpleTranslatable\Service\TranslatableService;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class I18nContent extends AbstractExtension
{
    public function __construct(private TranslatableService $translatableService)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            new TwigFunction('hasTranslations', array($this, 'hasTranslations')),
            new TwigFunction('missTranslations', array($this, 'missTranslations')),
            new TwigFunction('translatedLocales', array($this, 'getTranslatedLocales')),
            new TwigFunction('untranslatedLocales', array($this, 'getUntranslatedLocales')),
            new TwigFunction('translation', array($this, 'getTranslation')),
        );
    }

    public function hasTranslations(Translatable $entity)
    {

        return (count($this->getTranslatedLocales($entity, false)) > 0);

    }

    public function missTranslations(Translatable $entity)
    {

        return (count($this->getUntranslatedLocales($entity)) > 0);

    }

    public function getTranslatedLocales(Translatable $entity, $includeSelf = true)
    {
        return $this->translatableService->getTranslatedLocales($entity, $includeSelf);
    }

    public function getUntranslatedLocales(Translatable $entity)
    {
        return $this->translatableService->getUntranslatedLocales($entity);
    }

    public function getTranslation(Translatable $entity, $locale): ?Translatable
    {
        return $this->translatableService->getTranslation($entity, $locale);
    }

    /**
     * Returns the name of the extension.
     *
     * @return string The extension name
     */
    public function getName(): string
    {
        return 'i18nContent';
    }
}
