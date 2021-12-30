<?php

namespace App\Entity\Mail;

use App\Entity\Locale;
use App\Entity\TranslatorLangInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`mail_template_lang`")
 * @ORM\Entity()
 */
class TemplateLang implements TranslatorLangInterface
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
     * @var Locale
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Locale")
     * @ORM\JoinColumn(name="locale", referencedColumnName="slug")
     */
    private Locale $locale;

    /**
     * @var Template
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Mail\Template", inversedBy="translations")
     * @ORM\JoinColumn(name="template_uuid", referencedColumnName="uuid")
     */
    private Template $template;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $subject = '';

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private string $text = '';

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private string $html = '';

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
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
     * @return TemplateLang
     */
    public function setLocale(Locale $locale): TemplateLang
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return Template
     */
    public function getTemplate(): Template
    {
        return $this->template;
    }

    /**
     * @param Template $template
     *
     * @return TemplateLang
     */
    public function setTemplate(Template $template): TemplateLang
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @param string $subject
     *
     * @return TemplateLang
     */
    public function setSubject(string $subject): TemplateLang
    {
        $this->subject = $subject;

        return $this;
    }

    /**
     * @return string
     */
    public function getText(): string
    {
        return $this->text;
    }

    /**
     * @param string $text
     *
     * @return TemplateLang
     */
    public function setText(string $text): TemplateLang
    {
        $this->text = $text;

        return $this;
    }

    /**
     * @return string
     */
    public function getHtml(): string
    {
        return $this->html;
    }

    /**
     * @param string $html
     *
     * @return TemplateLang
     */
    public function setHtml(string $html): TemplateLang
    {
        $this->html = $html;

        return $this;
    }

}
