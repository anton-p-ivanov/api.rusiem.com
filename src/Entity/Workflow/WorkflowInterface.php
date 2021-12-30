<?php

namespace App\Entity\Workflow;

/**
 * Interface WorkflowInterface
 */
interface WorkflowInterface
{
    /**
     * @return Workflow|null
     */
    public function getWorkflow(): ?Workflow;

    /**
     * @param Workflow $workflow
     */
    public function setWorkflow(Workflow $workflow);
}
