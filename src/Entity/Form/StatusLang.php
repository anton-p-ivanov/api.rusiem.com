<?php

namespace App\Entity\Form;

use App\Entity\Locale;
use App\Entity\TranslatorLangInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`form_status_lang`")
 * @ORM\Entity()
 */
class StatusLang implements TranslatorLangInterface
{
    /**
     * @var string|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private ?string $uuid = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $title = '';

    /**
     * @var Locale
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Locale")
     * @ORM\JoinColumn(name="locale", referencedColumnName="slug")
     */
    private Locale $locale;

    /**
     * @var Status|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Form\Status", inversedBy="translations")
     * @ORM\JoinColumn(name="status_uuid", referencedColumnName="uuid")
     */
    private ?Status $status = null;

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return StatusLang
     */
    public function setTitle(string $title): StatusLang
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Locale
     */
    public function getLocale(): Locale
    {
        return $this->locale;
    }

    /**
     * @param Locale $locale
     *
     * @return StatusLang
     */
    public function setLocale(Locale $locale): StatusLang
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return Status|null
     */
    public function getStatus(): ?Status
    {
        return $this->status;
    }

    /**
     * @param Status|null $status
     *
     * @return StatusLang
     */
    public function setStatus(?Status $status): StatusLang
    {
        $this->status = $status;

        return $this;
    }

}
