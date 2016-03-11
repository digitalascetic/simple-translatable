<?php
/**
 * Created by IntelliJ IDEA.
 * User: martino
 * Date: 23/02/16
 * Time: 23:58
 */

namespace DigitalAscetic\SimpleTranslatable\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

trait TranslatableBehaviour {

  /**
   * @var string The language of this entity ("es", "en" etc.)
   *
   * @ORM\Column(name="locale", type="string", length=2)
   *
   * @Assert\NotBlank()
   * @Assert\Length(
   *      min = 2,
   *      max = 2
   * )
   */
  private $locale;

  private $translationSource;

  private $translations;

  /**
   * @return mixed
   */
  public function getLocale() {
    return $this->locale;
  }

  /**
   * @return mixed
   */
  public function getTranslationSource() {
    return $this->translationSource;
  }

  /**
   * @return mixed
   */
  public function getTranslations() {
    return $this->translations;
  }

  /**
   * @return mixed
   */
  public function getTranslation($locale) {

    if ($this->locale == $locale) {
      return $this;
    }

    if ($this->translationSource && $this->translationSource->getLocale() == $locale) {
      return $this->translationSource;
    }

    if ($this->translationSource) {
      $translations = $this->translationSource->getTranslations();
    }
    else {
      $translations = $this->translations;

    }

    //dump($translations);
    /** @var TranslatableBehaviour $translation */
    foreach ($translations as $translation) {
      if ($translation->getLocale() == $locale) {
        return $translation;
      }
    }

    return null;
  }

  /**
   * @param mixed $translationSource
   */
  public function setTranslationSource($translationSource) {
    $this->translationSource = $translationSource;
  }

  /**
   * @param mixed $locale
   */
  public function setLocale($locale) {
    $this->locale = $locale;
  }

}