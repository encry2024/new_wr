<?php

namespace App\Models\Auth\Traits\Relationship;

use App\Models\System\Session;
use App\Models\Auth\SocialAccount;
use App\Models\TargetRevenue\TargetRevenue;
use App\Models\Customer\Customer;

/**
 * Class UserRelationship.
 */
trait UserRelationship
{
    /**
     * @return mixed
     */
    public function providers()
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * @return mixed
     */
    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    /**
     * @return mixed
     */
    public function target_revenues()
    {
        return $this->hasMany(TargetRevenue::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }
}
