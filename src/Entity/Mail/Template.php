<?php

namespace App\Entity\Mail;

use App\Entity\EntityInterface;
use App\Entity\Media\File;
use App\Entity\TranslatorInterface;
use App\Entity\TranslatorTrait;
use App\Entity\Workflow\WorkflowInterface;
use App\Entity\Workflow\WorkflowTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`mail_template`")
 * @ORM\Entity(repositoryClass="App\Repository\Mail\TemplateRepository")
 * @ORM\EntityListeners({
 *     "App\Listener\WorkflowListener",
 *     "App\Listener\Mail\TemplateListener"
 * })
 */
class Template implements EntityInterface, WorkflowInterface, TranslatorInterface
{
    use TranslatorTrait;
    use WorkflowTrait;

    /**
     * @var string|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="UUID")
     * @ORM\Column(type="guid")
     */
    private ?string $uuid = null;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", unique=true)
     */
    private ?string $code = null;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $sender = null;

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $recipient = null;

    /**
     * @var array|null
     *
     * @ORM\Column(type="json", nullable=true)
     */
    private ?array $extraHeaders = null;

    /**
     * @var File|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Media\File", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="file_uuid", referencedColumnName="uuid", nullable=true, onDelete="SET NULL")
     */
    private ?File $attachment = null;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Mail\TemplateLang", orphanRemoval=true, mappedBy="template", indexBy="locale", cascade={"persist", "remove"})
     * @ORM\OrderBy({"locale"="ASC"})
     */
    private Collection $translations;

    /**
     * Template constructor.
     */
    public function __construct()
    {
        $this->translations = new ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @return string|null
     */
    public function getSender(): ?string
    {
        return $this->sender;
    }

    /**
     * @param string|null $sender
     *
     * @return Template
     */
    public function setSender(?string $sender): Template
    {
        $this->sender = $sender;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRecipient(): ?string
    {
        return $this->recipient;
    }

    /**
     * @param string|null $recipient
     *
     * @return Template
     */
    public function setRecipient(?string $recipient): Template
    {
        $this->recipient = $recipient;

        return $this;
    }

    /**
     * @return array|null
     */
    public function getExtraHeaders(): ?array
    {
        return $this->extraHeaders;
    }

    /**
     * @param array|null $extraHeaders
     *
     * @return Template
     */
    public function setExtraHeaders(?array $extraHeaders): Template
    {
        $this->extraHeaders = $extraHeaders;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string|null $code
     *
     * @return Template
     */
    public function setCode(?string $code): Template
    {
        $this->code = $code;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getAttachment(): ?File
    {
        return $this->attachment;
    }

    /**
     * @param File|null $attachment
     *
     * @return Template
     */
    public function setAttachment(?File $attachment): Template
    {
        $this->attachment = $attachment;

        return $this;
    }

    /**
     * @param TemplateLang[] $translations
     *
     * @return self
     */
    public function setTranslations(array $translations): self
    {
        $this->translations->clear();

        foreach ($translations as $translation) {
            $this->translations->add(
                $translation->setTemplate($this)
            );
        }

        return $this;
    }

    /**
     * @return string[]
     */
    public function getLookup(): array
    {
        return [
            'value' => $this->getUuid(),
            'label' => $this->getCode(),
        ];
    }
}
