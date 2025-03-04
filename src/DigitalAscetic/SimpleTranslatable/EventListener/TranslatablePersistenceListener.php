<?php
/**
 * Created by IntelliJ IDEA.
 * User: martino
 * Date: 24/02/16
 * Time: 01:40
 */

namespace DigitalAscetic\SimpleTranslatable\EventListener;

use DigitalAscetic\SimpleTranslatable\Entity\Translatable;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PreUpdateEventArgs;

class TranslatablePersistenceListener implements EventSubscriber
{

    public function getSubscribedEvents(): array
    {
        return array(
            Events::prePersist,
            Events::preUpdate
        );
    }

    public function prePersist(PrePersistEventArgs $args)
    {

        if ($this->isTranslatable($args->getObject())) {

            /** @var Translatable $entity */
            $entity = $args->getObject();

            if ($entity->getTranslationSource() != $entity) {

            }

        }
    }

    public function preUpdate(PreUpdateEventArgs $eventArgs)
    {

        if ($this->isTranslatable($eventArgs->getObject())) {

            /** @var Translatable $entity */
            $entity = $eventArgs->getObject();

            if ($entity->getTranslationSource() != $entity) {

            }

        }
    }

    private function isTranslatable($entity): bool
    {
        return $entity && $entity instanceof Translatable;
    }
}
