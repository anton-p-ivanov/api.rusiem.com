<?php

namespace App\Entity\Media;

use App\Entity\Context;
use App\Entity\EntityInterface;
use App\Entity\Workflow\WorkflowInterface;
use App\Entity\Workflow\WorkflowTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="media_file")
 * @ORM\Entity()
 * @ORM\EntityListeners({
 *     "App\Listener\FileListener",
 *     "App\Listener\WorkflowListener"
 * })
 */
class File implements EntityInterface, WorkflowInterface
{
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
    private string $originalName = '';

    /**
     * @var string|null
     *
     * @ORM\Column(type="string", nullable=true)
     */
    private ?string $mimeType = null;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $storedName = '';

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private int $size = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private int $imageWidth = 0;

    /**
     * @var int
     *
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private int $imageHeight = 0;

    /**
     * @var array
     */
    private array $image = [];

    /**
     * @var Context|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Context")
     * @ORM\JoinColumn(name="context", referencedColumnName="slug")
     */
    private ?Context $context = null;

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
    public function getMimeType(): ?string
    {
        return $this->mimeType;
    }

    /**
     * @param string|null $mimeType
     *
     * @return File
     */
    public function setMimeType(?string $mimeType): File
    {
        $this->mimeType = $mimeType;

        return $this;
    }

    /**
     * @return int
     */
    public function getSize(): int
    {
        return $this->size;
    }

    /**
     * @param int $size
     *
     * @return File
     */
    public function setSize(int $size): File
    {
        $this->size = $size;

        return $this;
    }

    /**
     * @return int
     */
    public function getImageWidth(): int
    {
        return $this->imageWidth;
    }

    /**
     * @param int $imageWidth
     *
     * @return File
     */
    public function setImageWidth(int $imageWidth): File
    {
        $this->imageWidth = $imageWidth;

        return $this;
    }

    /**
     * @return int
     */
    public function getImageHeight(): int
    {
        return $this->imageHeight;
    }

    /**
     * @param int $imageHeight
     *
     * @return File
     */
    public function setImageHeight(int $imageHeight): File
    {
        $this->imageHeight = $imageHeight;

        return $this;
    }

    /**
     * @return string
     */
    public function getOriginalName(): string
    {
        return $this->originalName;
    }

    /**
     * @param string $originalName
     *
     * @return File
     */
    public function setOriginalName(string $originalName): File
    {
        $this->originalName = $originalName;

        return $this;
    }

    /**
     * @return string
     */
    public function getRelativePath(): string
    {
        return $this->getContext()->getSlug() . '/' . $this->getStoredName();
    }

    /**
     * @return Context|null
     */
    public function getContext(): ?Context
    {
        return $this->context;
    }

    /**
     * @param Context|null $context
     *
     * @return File
     */
    public function setContext(?Context $context): File
    {
        $this->context = $context;

        return $this;
    }

    /**
     * @return string
     */
    public function getStoredName(): string
    {
        return $this->storedName;
    }

    /**
     * @param string $storedName
     *
     * @return File
     */
    public function setStoredName(string $storedName): File
    {
        $this->storedName = $storedName;

        return $this;
    }

    /**
     * @return array
     */
    public function getImage(): array
    {
        return [
            'width' => $this->imageWidth,
            'height' => $this->imageHeight,
        ];
    }
}
