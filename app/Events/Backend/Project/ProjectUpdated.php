<?php

namespace App\Events\Backend\Project;

use Illuminate\Queue\SerializesModels;

/**
 * Class ProjectUpdated.
 */
class ProjectUpdated
{
    use SerializesModels;

    /**
     * @var
     */
    public $project;
    public $doer;

    /**
     * @param $project
     */
    public function __construct($doer, $project)
    {
        $this->doer     = $doer;
        $this->project  = $project;
    }
}
