<?php

namespace App\Entity\Vacancy;

use App\Entity\EntityInterface;
use App\Entity\SluggerInterface;
use App\Entity\SluggerTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="vacancy_group")
 * @ORM\Entity(repositoryClass="App\Repository\Vacancy\GroupRepository")
 */
class Group implements EntityInterface, SluggerInterface
{
    use SluggerTrait;

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
     * @var int
     *
     * @ORM\Column(type="integer", options={"default"=100, "unsigned"=true})
     */
    private int $sort = 100;

    /**
     * @var Collection
     *
     * @ORM\OneToMany(targetEntity="App\Entity\Vacancy\Vacancy", mappedBy="group")
     */
    private Collection $vacancies;

    /**
     * Group constructor.
     */
    public function __construct()
    {
        $this->vacancies = new ArrayCollection();
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
     * @return Group
     */
    public function setTitle(string $title): Group
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
     * @return Group
     */
    public function setSort(int $sort): Group
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
     * @return Group
     */
    public function setDescription(string $description): Group
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection
     */
    public function getVacancies(): Collection
    {
        return $this->vacancies;
    }
}
