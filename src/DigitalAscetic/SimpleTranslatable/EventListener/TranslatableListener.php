<?php

namespace DigitalAscetic\SimpleTranslatable\EventListener;


use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

/**
 * This listener dynamically establish the correct mapping
 * for translatable entity relations
 */
class TranslatableListener implements EventSubscriber {

  public function getSubscribedEvents() {
    return array(
      Events::loadClassMetadata
    );
  }

  public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs) {

    /** @var ClassMetadata $metadata */
    $metadata = $eventArgs->getClassMetadata();

    $reflectionClass = $metadata->getReflectionClass();

    if (!$reflectionClass->implementsInterface('DigitalAscetic\SimpleTranslatable\Entity\Translatable')) {
      return;
    }

    $className = $reflectionClass->getName();

    $metadata->mapManyToOne(
      array(
        'targetEntity' => $className,
        'fieldName' => 'translationSource',
        'inversedBy' => 'translations',
        'cascade' => array('persist'),
        'joinColumns' => array(
          array(
            'name' => 'translationSource',
            'referencedColumnName' => 'id',
            'onDelete' => 'CASCADE',
            'onUpdate' => 'CASCADE',
            'nullable' => true
          ),
        )
      )
    );

    $metadata->mapOneToMany(
      array(
        'targetEntity' => $className,
        'fieldName' => 'translations',
        'mappedBy' => 'translationSource'
      )
    );
  }
}