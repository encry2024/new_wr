<?php

namespace App\Models\IndentedProposal\Traits\Relationship;

/**
 * Class IndentedProposalItemRelationship.
 */
trait IndentedProposalItemRelationship
{
    /**
     * @return mixed
     */
    public function indented_proposal_itemmable()
    {
        return $this->morphTo()->withTrashed();
    }
}
