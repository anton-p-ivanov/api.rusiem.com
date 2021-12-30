<?php

namespace App\Entity\Vacancy;

use App\Entity\EntityInterface;
use App\Entity\Media\File;
use App\Entity\Workflow\WorkflowInterface;
use App\Entity\Workflow\WorkflowTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="vacancy_response")
 * @ORM\Entity(repositoryClass="App\Repository\Vacancy\ResponseRepository")
 * @ORM\EntityListeners({
 *     "App\Listener\WorkflowListener",
 * })
 */
class Response implements EntityInterface, WorkflowInterface
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
    private string $fullName = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $email = '';

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private string $phone = '';

    /**
     * @var Vacancy|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Vacancy\Vacancy")
     * @ORM\JoinColumn(name="vacancy_uuid", referencedColumnName="uuid", nullable=true, onDelete="SET NULL")
     */
    private ?Vacancy $vacancy = null;

    /**
     * @var File|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Media\File", cascade={"persist"})
     * @ORM\JoinColumn(name="file_uuid", referencedColumnName="uuid", nullable=true, onDelete="SET NULL")
     */
    private ?File $file = null;

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
    public function getFullName(): string
    {
        return $this->fullName;
    }

    /**
     * @param string $fullName
     *
     * @return Response
     */
    public function setFullName(string $fullName): Response
    {
        $this->fullName = $fullName;

        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $email
     *
     * @return Response
     */
    public function setEmail(string $email): Response
    {
        $this->email = $email;

        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     *
     * @return Response
     */
    public function setPhone(string $phone): Response
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return Vacancy|null
     */
    public function getVacancy(): ?Vacancy
    {
        return $this->vacancy;
    }

    /**
     * @param Vacancy|null $vacancy
     *
     * @return Response
     */
    public function setVacancy(?Vacancy $vacancy): Response
    {
        $this->vacancy = $vacancy;

        return $this;
    }

    /**
     * @return File|null
     */
    public function getFile(): ?File
    {
        return $this->file;
    }

    /**
     * @param File|null $file
     *
     * @return Response
     */
    public function setFile(?File $file): Response
    {
        $this->file = $file;

        return $this;
    }

}