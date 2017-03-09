<?php
/**
 * Created by IntelliJ IDEA.
 * User: martino
 * Date: 14/03/16
 * Time: 01:09
 */

namespace DigitalAscetic\SimpleTranslatable\Entity\Repository;


use DigitalAscetic\SimpleTranslatable\Entity\Translatable;
use Doctrine\ORM\EntityRepository;

class TranslatableRepository extends EntityRepository {

    public function getTranslatedLocales(Translatable $entity) {

        $translatedLocales = array();

        if ($entity->getTranslationSource()) {
            /** @var Translatable $source */
            $source = $entity->getTranslationSource();
            foreach ($source->getTranslations() as $translation) {
                $translatedLocales[] = $translation->getLocale();
            }
        }

        return $translatedLocales;

    }

    public function getUntranslatedLocales(Translatable $entity) {


    }

}