<?php

namespace App\Entity\Workflow;

use Doctrine\ORM\Mapping as ORM;

/**
 * Trait WorkflowTrait
 */
trait WorkflowTrait
{
    /**
     * @var Workflow|null
     *
     * @ORM\OneToOne(targetEntity="App\Entity\Workflow\Workflow", orphanRemoval=true, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="workflow_uuid", referencedColumnName="uuid", nullable=true, onDelete="SET NULL")
     */
    private ?Workflow $workflow = null;

    /**
     * @return Workflow|null
     */
    public function getWorkflow(): ?Workflow
    {
        return $this->workflow;
    }

    /**
     * @param Workflow|null $workflow
     *
     * @return WorkflowTrait
     */
    public function setWorkflow(?Workflow $workflow): self
    {
        $this->workflow = $workflow;

        return $this;
    }
}