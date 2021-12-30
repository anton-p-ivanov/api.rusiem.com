<?php

namespace App\Entity\Content;

use App\Entity\EntityInterface;
use App\Entity\Locale;
use App\Entity\Media\File;
use App\Entity\SluggerInterface;
use App\Entity\SluggerTrait;
use App\Entity\Workflow\WorkflowInterface;
use App\Entity\Workflow\WorkflowTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="content_news")
 * @ORM\Entity(repositoryClass="App\Repository\Content\NewsRepository")
 * @ORM\EntityListeners({
 *     "App\Listener\Content\NewsListener",
 *     "App\Listener\WorkflowListener",
 * })
 */
class News implements EntityInterface, SluggerInterface, WorkflowInterface
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
     * @var \DateTimeInterface
     *
     * @ORM\Column(type="datetime")
     */
    private \DateTimeInterface $publishedAt;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $title = '';

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private string $description = '';

    /**
     * @var string
     *
     * @ORM\Column(type="text")
     */
    private string $content = '';

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned"=true,"default"=100})
     */
    private int $sort = 100;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=true})
     */
    private bool $isPublished = true;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean", options={"default"=false})
     */
    private bool $isPinned = false;

    /**
     * @var File|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Media\File", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_small", referencedColumnName="uuid", nullable=true, onDelete="SET NULL")
     */
    private ?File $imageSmall = null;

    /**
     * @var File|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Media\File", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_large", referencedColumnName="uuid", nullable=true, onDelete="SET NULL")
     */
    private ?File $imageLarge = null;

    /**
     * @var Locale|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Locale")
     * @ORM\JoinColumn(name="locale", referencedColumnName="slug")
     */
    private ?Locale $locale = null;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Catalog\Meta", orphanRemoval=true, inversedBy="news", cascade={"persist","remove"})
     * @ORM\JoinTable(name="content_news__catalog_meta",
     *     joinColumns={@ORM\JoinColumn(name="news_uuid", referencedColumnName="uuid")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="meta_uuid", referencedColumnName="uuid", unique=true)}
     * )
     */
    private Collection $meta;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Site")
     * @ORM\JoinTable(name="content_news__site",
     *     joinColumns={@ORM\JoinColumn(name="news_uuid", referencedColumnName="uuid", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="site_uuid", referencedColumnName="uuid", onDelete="CASCADE")}
     * )
     */
    private Collection $sites;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Catalog\Tag")
     * @ORM\JoinTable(name="content_news__catalog_tag",
     *     joinColumns={@ORM\JoinColumn(name="news_uuid", referencedColumnName="uuid", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_uuid", referencedColumnName="uuid", onDelete="CASCADE")}
     * )
     */
    private Collection $tags;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        foreach (['meta', 'sites', 'tags'] as $item) {
            $this->$item = new ArrayCollection();
        }

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
     * @return self
     */
    public function setTitle(string $title): self
    {
        $this->title = $title;

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
     * @return self
     */
    public function setDescription(string $description): self
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
     * @return self
     */
    public function setContent(string $content): self
    {
        $this->content = $content;

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
     * @return self
     */
    public function setSort(int $sort): self
    {
        $this->sort = $sort;

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
     * @return self
     */
    public function setIsPublished(bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * @return \DateTimeInterface
     */
    public function getPublishedAt(): \DateTimeInterface
    {
        return $this->publishedAt;
    }

    /**
     * @param \DateTimeInterface $publishedAt
     *
     * @return self
     */
    public function setPublishedAt(\DateTimeInterface $publishedAt): self
    {
        $this->publishedAt = $publishedAt;

        return $this;
    }

    /**
     * @return bool
     */
    public function getIsPinned(): bool
    {
        return $this->isPinned;
    }

    /**
     * @param bool $isPinned
     *
     * @return self
     */
    public function setIsPinned(bool $isPinned): self
    {
        $this->isPinned = $isPinned;

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
     * @return News
     */
    public function setLocale(?Locale $locale): News
    {
        $this->locale = $locale;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getMeta(): Collection
    {
        return $this->meta;
    }

    /**
     * @param array $meta
     *
     * @return News
     */
    public function setMeta(array $meta): News
    {
        // We need to clear collection first to remove orphan items
        $this->meta->clear();

        $this->meta = new ArrayCollection($meta);

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
     * @return self
     */
    public function setSites(array $sites): self
    {
        $this->sites = new ArrayCollection($sites);

        return $this;
    }

    /**
     * @return Collection
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    /**
     * @param array $tags
     *
     * @return self
     */
    public function setTags(array $tags): self
    {
        $this->tags = new ArrayCollection($tags);

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageSmall(): ?File
    {
        return $this->imageSmall;
    }

    /**
     * @param File|null $imageSmall
     *
     * @return self
     */
    public function setImageSmall(?File $imageSmall): self
    {
        $this->imageSmall = $imageSmall;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageLarge(): ?File
    {
        return $this->imageLarge;
    }

    /**
     * @param File|null $imageLarge
     *
     * @return self
     */
    public function setImageLarge(?File $imageLarge): self
    {
        $this->imageLarge = $imageLarge;

        return $this;
    }
}
