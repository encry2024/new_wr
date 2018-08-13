<?php

namespace App\Models\BuyAndResaleProposal\Traits\Relationship;

use App\Models\Customer\Customer;
use App\Models\BuyAndResaleProposal\BuyAndResaleProposalItem;

/**
 * Trait BuyAndResaleProposalRelationship.
 */
trait BuyAndResaleProposalRelationship
{
    /**
     * @return mixed
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class)->withTrashed();
    }

    /**
     * @return mixed
     */
    public function buy_and_resale_proposal_items()
    {
        return $this->hasMany(BuyAndResaleProposalItem::class);
    }
}
