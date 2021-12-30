<?php

namespace App\Entity\Form;

use App\Entity\EntityInterface;
use App\Entity\Mail\Template;
use App\Entity\TranslatorInterface;
use App\Entity\TranslatorTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`form_status`")
 * @ORM\Entity(repositoryClass="App\Repository\Form\StatusRepository")
 * @ORM\EntityListeners({
        "App\Listener\Form\StatusListener"
 * })
 */
class Status implements EntityInterface, TranslatorInterface
{
    use TranslatorTrait;

    public const TYPE_APPROVED = 'A';
    public const TYPE_REJECTED = 'R';
    public const TYPE_CREATED = 'C';

    public const TYPE_DEFAULT = self::TYPE_CREATED;

    /**
     * @var array|string[]
     */
    public static array $types = [
        self::TYPE_APPROVED,
        self::TYPE_CREATED,
        self::TYPE_REJECTED
    ];

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
     * @ORM\Column(type="string", unique=true)
     */
    private string $name = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=1, options={"fixed"=true})
     */
    private string $type = self::TYPE_DEFAULT;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private bool $isDefault = false;

    /**
     * @var Template|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Mail\Template")
     * @ORM\JoinColumn(name="template_uuid", referencedColumnName="uuid", nullable=true)
     */
    private ?Template $template = null;

    /**
     * @var Form|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Form\Form", inversedBy="statuses")
     * @ORM\JoinColumn(name="form_uuid", referencedColumnName="uuid")
     */
    private ?Form $form = null;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Form\StatusLang", orphanRemoval=true, mappedBy="status", indexBy="locale", cascade={"persist", "remove"})
     * @ORM\OrderBy({"locale"="ASC"})
     */
    private Collection $translations;

    /**
     * Status constructor.
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
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return Status
     */
    public function setName(string $name): Status
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsDefault(): bool
    {
        return $this->isDefault;
    }

    /**
     * @param bool $isDefault
     *
     * @return Status
     */
    public function setIsDefault(bool $isDefault): Status
    {
        $this->isDefault = $isDefault;

        return $this;
    }

    /**
     * @return Template|null
     */
    public function getTemplate(): ?Template
    {
        return $this->template;
    }

    /**
     * @param Template|null $template
     *
     * @return Status
     */
    public function setTemplate(?Template $template): Status
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return Form|null
     */
    public function getForm(): ?Form
    {
        return $this->form;
    }

    /**
     * @param Form $form
     *
     * @return Status
     */
    public function setForm(Form $form): Status
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @param string $type
     *
     * @return Status
     */
    public function setType(string $type): Status
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @param StatusLang[] $translations
     *
     * @return self
     */
    public function setTranslations(array $translations): self
    {
        $this->translations->clear();

        foreach ($translations as $translation) {
            $this->translations->add(
                $translation->setStatus($this)
            );
        }

        return $this;
    }
}
