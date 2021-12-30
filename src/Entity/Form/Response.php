<?php

namespace App\Entity\Form;

use App\Entity\EntityInterface;
use App\Entity\Workflow\WorkflowInterface;
use App\Entity\Workflow\WorkflowTrait;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="`form_response`")
 * @ORM\Entity(repositoryClass="App\Repository\Form\ResponseRepository")
 * @ORM\EntityListeners({
 *     "App\Listener\WorkflowListener"
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
     * @var array|null
     *
     * @ORM\Column(type="json")
     */
    private ?array $data = null;

    /**
     * @var Form
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Form\Form", inversedBy="responses")
     * @ORM\JoinColumn(name="form_uuid", referencedColumnName="uuid")
     */
    private Form $form;

    /**
     * @var Status|null
     *
     * @ORM\ManyToOne(targetEntity="App\Entity\Form\Status")
     * @ORM\JoinColumn(name="status", referencedColumnName="uuid")
     */
    private ?Status $status = null;

    /**
     * @return string|null
     */
    public function getUuid(): ?string
    {
        return $this->uuid;
    }

    /**
     * @return array|null
     */
    public function getData(): ?array
    {
        return $this->data;
    }

    /**
     * @param array|null $data
     *
     * @return Response
     */
    public function setData(?array $data): Response
    {
        $this->data = $data;

        return $this;
    }

    /**
     * @return Form
     */
    public function getForm(): Form
    {
        return $this->form;
    }

    /**
     * @param Form $form
     *
     * @return Response
     */
    public function setForm(Form $form): Response
    {
        $this->form = $form;

        return $this;
    }

    /**
     * @return Status|null
     */
    public function getStatus(): ?Status
    {
        return $this->status;
    }

    /**
     * @param Status $status
     *
     * @return Response
     */
    public function setStatus(Status $status): Response
    {
        $this->status = $status;

        return $this;
    }
}
