<?php
/**
 * Created by IntelliJ IDEA.
 * User: martino
 * Date: 23/02/16
 * Time: 23:58
 */

namespace DigitalAscetic\SimpleTranslatable\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

trait TranslatableBehaviour
{

    /**
     * @var string The language of this entity ("es", "en" etc.)
     */
    #[ORM\Column(name: "locale", type: "string", length: 2)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 2, max: 2)]
    private string $locale;

    private ?Translatable $translationSource = null;

    private ArrayCollection $translations;

    public function getLocale(): string
    {
        return $this->locale;
    }

    public function setLocale(string $locale): void
    {
        $this->locale = $locale;
    }

    public function getTranslations(): ArrayCollection
    {
        return $this->translations;
    }

    public function getTranslationSource(): Translatable
    {
        if ($this->translationSource) {
            return $this->translationSource;
        } else {
            return $this;
        }
    }

    public function setTranslationSource(Translatable $translationSource = null)
    {
        if ($translationSource != null) {
            $this->translationSource = $translationSource;
            $this->translationSource->getTranslations()->add($this);
        }
    }
}
