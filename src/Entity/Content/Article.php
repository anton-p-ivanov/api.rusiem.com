<?php

namespace App\Entity\Content;

use App\Entity\Catalog\Meta;
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
 * @ORM\Table(name="content_article")
 * @ORM\Entity(repositoryClass="App\Repository\Content\ArticleRepository")
 * @ORM\EntityListeners({
 *     "App\Listener\WorkflowListener",
 *     "App\Listener\Content\ArticleListener"
 * })
 */
class Article implements EntityInterface, SluggerInterface, WorkflowInterface
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
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $source = null;

    /**
     * @var File|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Media\File", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="attachment", referencedColumnName="uuid", nullable=true, onDelete="SET NULL")
     */
    private ?File $attachment = null;

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
     * @ORM\ManyToMany(targetEntity="App\Entity\Catalog\Meta", orphanRemoval=true, cascade={"persist","remove"})
     * @ORM\JoinTable(name="content_article__catalog_meta",
     *     joinColumns={@ORM\JoinColumn(name="article_uuid", referencedColumnName="uuid", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="meta_uuid", referencedColumnName="uuid", onDelete="CASCADE")}
     * )
     */
    private Collection $meta;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Catalog\Tag")
     * @ORM\JoinTable(name="content_article__catalog_tag",
     *     joinColumns={@ORM\JoinColumn(name="article_uuid", referencedColumnName="uuid", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="tag_uuid", referencedColumnName="uuid", onDelete="CASCADE")}
     * )
     */
    private Collection $tags;

    /**
     * @var Collection
     *
     * @ORM\ManyToMany(targetEntity="App\Entity\Site")
     * @ORM\JoinTable(name="content_article__site",
     *     joinColumns={@ORM\JoinColumn(name="article_uuid", referencedColumnName="uuid", onDelete="CASCADE")},
     *     inverseJoinColumns={@ORM\JoinColumn(name="site_uuid", referencedColumnName="uuid", onDelete="CASCADE")}
     * )
     */
    private Collection $sites;

    /**
     * News constructor.
     */
    public function __construct()
    {
        foreach (['tags', 'sites', 'meta'] as $item) {
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
     * @return Article
     */
    public function setTitle(string $title): Article
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
     * @return Article
     */
    public function setDescription(string $description): Article
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
     * @return Article
     */
    public function setContent(string $content): Article
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
     * @return Article
     */
    public function setSort(int $sort): Article
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSource(): ?string
    {
        return $this->source;
    }

    /**
     * @param string|null $source
     *
     * @return Article
     */
    public function setSource(?string $source): Article
    {
        $this->source = $source;

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
     * @return Article
     */
    public function setAttachment(?File $attachment): Article
    {
        $this->attachment = $attachment;

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
     * @return Article
     */
    public function setTags(array $tags): Article
    {
        $this->tags = new ArrayCollection($tags);

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
     * @return Article
     */
    public function setIsPublished(bool $isPublished): Article
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
     * @return Article
     */
    public function setPublishedAt(\DateTimeInterface $publishedAt): Article
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
     * @return Article
     */
    public function setIsPinned(bool $isPinned): Article
    {
        $this->isPinned = $isPinned;

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
     * @return Article
     */
    public function setSites(array $sites): Article
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
     * @return Article
     */
    public function setLocale(?Locale $locale): Article
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
     * @return Article
     */
    public function setMeta(array $meta): Article
    {
        // We need to clear collection first to remove orphan items
        $this->meta->clear();

        $this->meta = new ArrayCollection($meta);

        return $this;
    }
}
