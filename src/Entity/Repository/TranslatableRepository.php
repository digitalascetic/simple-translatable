<?php
/**
 * Created by IntelliJ IDEA.
 * User: martino
 * Date: 14/03/16
 * Time: 01:09
 */

namespace DigitalAscetic\SimpleTranslatable\Entity\Repository;


use DigitalAscetic\SimpleTranslatable\Entity\Translatable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class TranslatableRepository extends ServiceEntityRepository
{

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Translatable::class);
    }

    public function getTranslatedLocales(Translatable $entity)
    {

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

    public function getUntranslatedLocales(Translatable $entity)
    {


    }

}
