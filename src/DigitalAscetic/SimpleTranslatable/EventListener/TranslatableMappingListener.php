<?php

namespace DigitalAscetic\SimpleTranslatable\EventListener;


use DigitalAscetic\SimpleTranslatable\Entity\Translatable;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Events;
use Doctrine\ORM\Event\LoadClassMetadataEventArgs;

/**
 * This listener dynamically establish the correct mapping
 * for translatable entity relations
 */
class TranslatableMappingListener implements EventSubscriber {

  public function getSubscribedEvents() {
    return array(
      Events::loadClassMetadata
    );
  }

  public function loadClassMetadata(LoadClassMetadataEventArgs $eventArgs) {

    /** @var ClassMetadata $metadata */
    $metadata = $eventArgs->getClassMetadata();

    $reflectionClass = $metadata->getReflectionClass();

    if (!$reflectionClass->implementsInterface(Translatable::class)) {
      return;
    }

    $className = $reflectionClass->getName();

    $metadata->mapManyToOne(
      array(
        'targetEntity' => $className,
        'fieldName' => 'translationSource',
        'inversedBy' => 'translations',
        'cascade' => array('persist', 'remove', 'merge'),
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
        'mappedBy' => 'translationSource',
        'cascade' => array('persist', 'remove', 'merge')
      )
    );
  }
}