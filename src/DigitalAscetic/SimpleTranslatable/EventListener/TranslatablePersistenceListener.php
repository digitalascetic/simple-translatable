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
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Event\LifecycleEventArgs;


class TranslatablePersistenceListener implements EventSubscriber {

  public function getSubscribedEvents() {
    return array(
      Events::prePersist,
      Events::preUpdate
    );
  }

  public function prePersist(LifecycleEventArgs $args) {

    if ($this->isTranslatable($args->getEntity())) {

      /** @var Translatable $entity */
      $entity = $args->getEntity();

      if ($entity->getTranslationSource() != $entity) {

      }

    }
  }

  public function preUpdate(PreUpdateEventArgs $eventArgs) {

    if ($this->isTranslatable($eventArgs->getEntity())) {

      /** @var Translatable $entity */
      $entity = $eventArgs->getEntity();

      if ($entity->getTranslationSource() != $entity) {

      }

    }
  }

  private function isTranslatable($entity) {
    if ($entity && $entity instanceof Translatable) {
      return true;
    }

    return false;
  }
}