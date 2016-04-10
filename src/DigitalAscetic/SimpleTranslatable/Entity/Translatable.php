<?php
/**
 * Created by IntelliJ IDEA.
 * User: martino
 * Date: 23/02/16
 * Time: 23:47
 */

namespace DigitalAscetic\SimpleTranslatable\Entity;


use Doctrine\Common\Collections\ArrayCollection;

interface Translatable {

  public function getTranslationSource();

  /**
   * @return ArrayCollection
   */
  public function getTranslations();

  public function getLocale();

}