<?php

namespace App\Entity\Form;

use App\Entity\Context;
use App\Entity\EntityInterface;
use App\Entity\Mail\Template;
use App\Entity\Site;
use App\Entity\SluggerInterface;
use App\Entity\SluggerTrait;
use App\Entity\Workflow\WorkflowInterface;
use App\Entity\Workflow\WorkflowTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`form`")
 * @ORM\Entity(repositoryClass="App\Repository\Form\FormRepository")
 * @ORM\EntityListeners({
 *     "App\Listener\WorkflowListener"
 * })
 */
class Form implements EntityInterface, SluggerInterface, WorkflowInterface
{
    use SluggerTrait;
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
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $title = '';

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private bool $isPublished = false;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $publishedAt;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $activeFrom;

    /**
     * @var \DateTimeInterface|null
     *
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $activeTo;

    /**
     * @var Context
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Context")
     * @ORM\JoinColumn(name="context", referencedColumnName="slug")
     */
    private Context $context;

    /**
     * @var Template|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Mail\Template", cascade={"persist"})
     * @ORM\JoinColumn(name="template_uuid", nullable=true, referencedColumnName="uuid")
     */
    private ?Template $template = null;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Form\Status", mappedBy="form", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $statuses;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Form\Response", mappedBy="form")
     */
    private Collection $responses;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Site")
     * @ORM\JoinTable(name="form__site",
     *     joinColumns={@ORM\JoinColumn(name="form_uuid", referencedColumnName="uuid", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="site_uuid", referencedColumnName="uuid", onDelete="CASCADE")}
     * )
     */
    private Collection $sites;

    /**
     * Form constructor.
     */
    public function __construct()
    {
        $this->responses = new ArrayCollection();
        $this->statuses = new ArrayCollection();
        $this->sites = new ArrayCollection();

        $this->publishedAt = new \DateTime();
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
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     *
     * @return Form
     */
    public function setTitle(string $title): Form
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getResponses(): Collection
    {
        return $this->responses;
    }

    /**
     * @param Collection $responses
     *
     * @return Form
     */
    public function setResponses(Collection $responses): Form
    {
        $this->responses = $responses;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsPublished(): bool
    {
        return $this->isPublished;
    }

    /**
     * @param bool $isPublished
     *
     * @return Form
     */
    public function setIsPublished(bool $isPublished): Form
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->publishedAt;
    }

    /**
     * @param \DateTimeInterface|null $publishedAt
     *
     * @return Form
     */
    public function setPublishedAt(?\DateTimeInterface $publishedAt): Form
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getSites(): Collection
    {
        return $this->sites;
    }

    /**
     * @param Site[] $sites
     *
     * @return Form
     */
    public function setSites(array $sites): Form
    {
        $this->sites = new ArrayCollection($sites);

        return $this;
    }

    /**
     * @return Context
     */
    public function getContext(): Context
    {
        return $this->context;
    }

    /**
     * @param Context $context
     *
     * @return Form
     */
    public function setContext(Context $context): Form
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getActiveFrom(): ?\DateTimeInterface
    {
        return $this->activeFrom;
    }

    /**
     * @param \DateTimeInterface|null $activeFrom
     *
     * @return Form
     */
    public function setActiveFrom(?\DateTimeInterface $activeFrom): Form
    {
        $this->activeFrom = $activeFrom;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getActiveTo(): ?\DateTimeInterface
    {
        return $this->activeTo;
    }

    /**
     * @param \DateTimeInterface|null $activeTo
     *
     * @return Form
     */
    public function setActiveTo(?\DateTimeInterface $activeTo): Form
    {
        $this->activeTo = $activeTo;

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
     * @return Form
     */
    public function setTemplate(?Template $template): Form
    {
        $this->template = $template;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getStatuses(): Collection
    {
        return $this->statuses;
    }

    /**
     * @param Collection $statuses
     *
     * @return Form
     */
    public function setStatuses(Collection $statuses): Form
    {
        $this->statuses = $statuses->map(function (Status $status) {
            return $status->setForm($this);
        });

        return $this;
    }

    /**
     * @return array
     */
    public function getLookup(): array
    {
        return [
            'value' => $this->getUuid(),
            'label' => $this->getTitle()
        ];
    }
}
