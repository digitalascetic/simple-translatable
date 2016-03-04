<?php
/**
 * Created by IntelliJ IDEA.
 * User: martino
 * Date: 23/02/16
 * Time: 23:47
 */

namespace SimpleTranslatable\Entity;


interface Translatable {

  public function getTranslationSource();

  public function getTranslations();

  public function getLocale();

}