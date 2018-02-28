<?php
/**
 * Created by IntelliJ IDEA.
 * User: martino
 * Date: 14/03/16
 * Time: 09:13
 */

namespace DigitalAscetic\SimpleTranslatable\Service;

use Symfony\Component\DependencyInjection\ContainerInterface;
use DigitalAscetic\SimpleTranslatable\Entity\Translatable;

class TranslatableService {

    /** @var  \string[] $locales */
    private $locales;

    /** @var  ContainerInterface $container */
    private $container;

    /**
     * TranslatableService constructor.
     * @param ContainerInterface $container
     * @param array $locales
     */
    public function __construct(ContainerInterface $container, $locales) {
        $this->container = $container;
        $this->locales = $locales;
    }

    public function getTranslatedLocales(Translatable $entity, $includeSelf = true) {

        /** @var Translatable $source */
        $source = $entity->getTranslationSource();

        if ($includeSelf) {
            $translatedLocales = array($entity->getLocale());
        }
        else {
            $translatedLocales = array();
        }

        // Add source locale
        if (($source != $entity)) {
            $translatedLocales[] = $source->getLocale();
        }


        if ($source->getTranslations()) {
            foreach ($source->getTranslations() as $translation) {
                if (!$includeSelf && $translation->getLocale() == $entity->getLocale() || in_array(
                        $translation->getLocale(),
                        $translatedLocales
                    )
                ) {
                    continue;
                }
                $translatedLocales[] = $translation->getLocale();
            }
        }

        return $translatedLocales;

    }

    public function getUntranslatedLocales(Translatable $entity) {

        return array_diff($this->locales, $this->getTranslatedLocales($entity, true));

    }

    /**
     * @return Translatable|null
     */
    public function getTranslation(Translatable $entity, $locale) {

        if ($entity->getLocale() == $locale) {
            return $entity;
        }

        if ($entity->getTranslationSource() && $entity->getTranslationSource()->getLocale() == $locale) {
            return $entity->getTranslationSource();
        }

        if ($entity->getTranslationSource()) {
            $translations = $entity->getTranslationSource()->getTranslations();
        }
        else {
            $translations = $entity->getTranslations();

        }

        /** @var Translatable $translation */
        foreach ($translations as $translation) {
            if ($translation->getLocale() == $locale) {
                return $translation;
            }
        }

        return null;
    }

    /**
     * @return \string[]
     */
    public function getLocales() {
        return $this->locales;
    }

}