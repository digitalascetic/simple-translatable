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

  private $translations = array();

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
    if ($this->translationSource) {
      return $this->translationSource;
    }
    else {
      return $this;
    }
  }

  /**
   * @return mixed
   */
  public function getTranslations() {
    return $this->translations;
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