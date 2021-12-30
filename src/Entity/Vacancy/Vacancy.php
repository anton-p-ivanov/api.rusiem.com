<?php

namespace App\Entity\Vacancy;

use App\Entity\EntityInterface;
use App\Entity\Locale;
use App\Entity\SluggerInterface;
use App\Entity\SluggerTrait;
use App\Entity\Workflow\WorkflowInterface;
use App\Entity\Workflow\WorkflowTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`vacancy`")
 * @ORM\Entity(repositoryClass="App\Repository\Vacancy\VacancyRepository")
 * @ORM\EntityListeners({
 *     "App\Listener\WorkflowListener",
 * })
 */
class Vacancy implements EntityInterface, SluggerInterface, WorkflowInterface
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
     * @var string
     *
     * @ORM\Column(type="text", length=65535)
     */
    private string $description = '';

    /**
     * @var string
     *
     * @ORM\Column(type="text", length=65535)
     */
    private string $content = '';

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"default"=100, "unsigned"=true})
     */
    private int $sort = 100;

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
     * @var Locale|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Locale")
     * @ORM\JoinColumn(name="locale", referencedColumnName="slug")
     */
    private ?Locale $locale = null;

    /**
     * @var Group|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Vacancy\Group", inversedBy="vacancies")
     * @ORM\JoinColumn(name="group_uuid", referencedColumnName="uuid")
     */
    private ?Group $group = null;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Site")
     * @ORM\JoinTable(name="vacancy__site",
     *     joinColumns={@ORM\JoinColumn(name="vacancy_uuid", referencedColumnName="uuid", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="site_uuid", referencedColumnName="uuid", onDelete="CASCADE")}
     * )
     */
    private Collection $sites;

    /**
     * Vacancy constructor.
     */
    public function __construct()
    {
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
     * @return Vacancy
     */
    public function setTitle(string $title): Vacancy
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return int
     */
    public function getSort(): int
    {
        return $this->sort;
    }

    /**
     * @param int $sort
     *
     * @return Vacancy
     */
    public function setSort(int $sort): Vacancy
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     *
     * @return Vacancy
     */
    public function setDescription(string $description): Vacancy
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @param string $content
     *
     * @return Vacancy
     */
    public function setContent(string $content): Vacancy
    {
        $this->content = $content;

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
     * @return Vacancy
     */
    public function setIsPublished(bool $isPublished): Vacancy
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getPublishedAt()
    {
        return $this->publishedAt;
    }

    /**
     * @param \DateTimeInterface|null $publishedAt
     *
     * @return Vacancy
     */
    public function setPublishedAt(?\DateTimeInterface $publishedAt): Vacancy
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * @return Group|null
     */
    public function getGroup(): ?Group
    {
        return $this->group;
    }

    /**
     * @param Group|null $group
     *
     * @return Vacancy
     */
    public function setGroup(?Group $group): Vacancy
    {
        $this->group = $group;

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
     * @param array $sites
     *
     * @return Vacancy
     */
    public function setSites(array $sites): Vacancy
    {
        $this->sites = new ArrayCollection($sites);

        return $this;
    }

    /**
     * @return Locale|null
     */
    public function getLocale(): ?Locale
    {
        return $this->locale;
    }

    /**
     * @param Locale|null $locale
     *
     * @return Vacancy
     */
    public function setLocale(?Locale $locale): Vacancy
    {
        $this->locale = $locale;

        return $this;
    }

}
