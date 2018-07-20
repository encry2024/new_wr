<?php
/**
 * Created by PhpStorm.
 * User: christanjake2024
 * Date: 1/26/18
 * Time: 2:06 PM
 */

namespace App\Repositories\Backend\Project;

# Facades
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
# Models
use App\Models\Project\Project;
use App\Models\Project\ProjectPricingHistory;
use Auth;
# Exceptions
use App\Exceptions\GeneralException;
# Repository
use App\Repositories\BaseRepository;
# Events
use App\Events\Backend\Project\ProjectCreated;
use App\Events\Backend\Project\ProjectUpdated;
use App\Events\Backend\Project\ProjectRestored;
use App\Events\Backend\Project\ProjectUploaded;
use App\Events\Backend\Project\ProjectPermanentlyDeleted;
use App\Events\Backend\PricingHistory\GeneralPricingHistoryCreated;
use App\Events\Backend\PricingHistory\GeneralPricingHistoryUpdated;
use App\Events\Backend\PricingHistory\GeneralPricingHistoryRestored;
use App\Events\Backend\PricingHistory\GeneralPricingHistoryPermanentlyDeleted;

/**
 * Class ProjectRepository.
 */
class ProjectRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return Project::class;
    }

    /**
     * @param int    $paged
     * @param string $orderBy
     * @param string $sort
     *
     * @return mixed
     */
    public function getPaginatedProject($paged = 25, $orderBy = 'created_at', $sort = 'desc') : LengthAwarePaginator
    {
        return $this->model
            ->orderBy($orderBy, $sort)
            ->paginate($paged);
    }

    /**
     * @param int    $paged
     * @param string $orderBy
     * @param string $sort
     *
     * @return LengthAwarePaginator
     */
    public function getDeletedPaginated($paged = 25, $orderBy = 'created_at', $sort = 'desc') : LengthAwarePaginator
    {
        return $this->model
            ->onlyTrashed()
            ->orderBy($orderBy, $sort)
            ->paginate($paged);
    }

    public function strip_currency($price)
    {
        return str_replace(',','',$price);
    }

    /**
     * @param array $data
     *
     * @return Project
     */
    public function create(array $data) : Project
    {
        return DB::transaction(function () use ($data) {
            $project = parent::create([
                'customer_id'           =>  $data['customer'],
                'name'                  =>  $data['name'],
                'source'                =>  $data['source'],
                'address_1'             =>  $data['address_1'],
                'contact_person_1'      =>  $data['contact_person_1'],
                'contact_number_1'      =>  $data['contact_number_1'],
                'email_1'               =>  $data['email_1'],

                'consultant'            =>  $data['consultant'],
                'address_2'             =>  $data['address_2'],
                'contact_person_2'      =>  $data['contact_person_2'],
                'contact_number_2'      =>  $data['contact_number_2'],
                'email_2'               =>  $data['email_2'],

                'shorted_list_epc'      =>  $data['shorted_list_epc'],
                'address_3'             =>  $data['address_3'],
                'contact_person_3'      =>  $data['contact_person_3'],
                'contact_number_3'      =>  $data['contact_number_3'],
                'email_3'               =>  $data['email_3'],

                'approved_vendors_list' =>  $data['approved_vendors_list'],
                'requirements_coor'     =>  $data['requirements_coor'],
                'epc_award'             =>  $data['epc_award'],
                'award_date'            =>  date('Y-m-d', strtotime($data['award_date'])),
                'implementation_date'   =>  date('Y-m-d', strtotime($data['implementation_date'])),
                'execution_date'        =>  date('Y-m-d', strtotime($data['execution_date'])),

                'bu'                    =>  $data['bu'],
                'bu_reference'          =>  $data['bu_reference'],
                'wpc_reference'         =>  $data['wpc_reference'],
                'affinity_reference'    =>  $data['affinity_reference'],
                'value'                 =>  $data['value'],

                'reference_number'      =>  $data['reference_number'],
                'types_of_sales'        =>  $data['types_of_sales'],
                'tag_number'            =>  $data['tag_number'],
                'application'           =>  $data['application'],
                'pump_model'            =>  $data['pump_model'],
                'serial_number'         =>  $data['serial_number'],
                'competitors'           =>  $data['competitors'],
                'final_result'          =>  $data['final_result'],

                'status'                =>  $data['status'],
                'scanned_file'          =>  'N/A'
            ]);

            if ($project) {
                $auth_link = "<a href='".route('admin.auth.user.show', auth()->id())."'>".Auth::user()->full_name.'</a>';
                $asset_link = "<a href='".route('admin.project.show', $project->id)."'>".$project->name.'</a>';

                event(new ProjectCreated($auth_link, $asset_link));

                return $project;
            }

            throw new GeneralException(__('exceptions.backend.projects.update_error'));
        });
    }

    /**
     * @param Project  $project
     * @param array $data
     *
     * @return Project
     */
    public function update(Project $project, array $data) : Project
    {
        return DB::transaction(function () use ($project, $data) {
            if ($project->update([
                'customer_id'           =>   $data['customer'],
                'name'                  =>   $data['name'],
                'source'                =>   $data['source'],
                'address_1'             =>   $data['address_1'],
                'contact_person_1'      =>   $data['contact_person_1'],
                'contact_number_1'      =>   $data['contact_number_1'],
                'email_1'               =>   $data['email_1'],

                'consultant'            =>   $data['consultant'],
                'address_2'             =>   $data['address_2'],
                'contact_person_2'      =>   $data['contact_person_2'],
                'contact_number_2'      =>   $data['contact_number_2'],
                'email_2'               =>   $data['email_2'],

                'shorted_list_epc'      =>   $data['shorted_list_epc'],
                'address_3'             =>   $data['address_3'],
                'contact_person_3'      =>   $data['contact_person_3'],
                'contact_number_3'      =>   $data['contact_number_3'],
                'email_3'               =>   $data['email_3'],

                'approved_vendors_list' =>   $data['approved_vendors_list'],
                'requirements_coor'     =>   $data['requirements_coor'],
                'epc_award'             =>   $data['epc_award'],
                'award_date'            =>  date('Y-m-d', strtotime($data['award_date'])),
                'implementation_date'   =>  date('Y-m-d', strtotime($data['implementation_date'])),
                'execution_date'        =>  date('Y-m-d', strtotime($data['execution_date'])),

                'bu'                    =>   $data['bu'],
                'bu_reference'          =>   $data['bu_reference'],
                'wpc_reference'         =>   $data['wpc_reference'],
                'affinity_reference'    =>   $data['affinity_reference'],
                'value'                 =>   $data['value'],

                'reference_number'      =>   $data['reference_number'],
                'types_of_sales'        =>   $data['types_of_sales'],
                'tag_number'            =>   $data['tag_number'],
                'application'           =>   $data['application'],
                'pump_model'            =>   $data['pump_model'],
                'serial_number'         =>   $data['serial_number'],
                'competitors'           =>   $data['competitors'],
                'final_result'          =>   $data['final_result'],

                'status'                =>   'Undefined',

                'scanned_file'          =>   $project->scanned_file
            ]))

            {
                $auth_link = "<a href='".route('admin.auth.user.show', auth()->id())."'>".Auth::user()->full_name.'</a>';
                $asset_link = "<a href='".route('admin.project.show', $project->id)."'>".$project->name.'</a>';

                event(new ProjectUpdated($auth_link, $asset_link));

                return $project;
            }

            throw new GeneralException(__('exceptions.backend.projects.update_error'));
        });
    }

    /**
     * @param Project $project
     *
     * @return Project
     * @throws GeneralException
     */
    public function forceDelete(Project $project) : Project
    {
        if (is_null($project->deleted_at)) {
            throw new GeneralException(__('exceptions.backend.projects.delete_first'));
        }

        return DB::transaction(function () use ($project) {

            if ($project->forceDelete()) {
                $auth_link = "<a href='".route('admin.auth.user.show', auth()->id())."'>".Auth::user()->full_name.'</a>';

                event(new ProjectPermanentlyDeleted($auth_link, $project->name));

                return $project;
            }

            throw new GeneralException(__('exceptions.backend.projects.delete_error'));
        });
    }

    /**
     * @param Project $project
     *
     * @return Project
     * @throws GeneralException
     */
    public function restore(Project $project) : Project
    {
        if (is_null($project->deleted_at)) {
            throw new GeneralException(__('exceptions.backend.projects.cant_restore'));
        }

        if ($project->restore()) {
            $auth_link = "<a href='".route('admin.auth.user.show', auth()->id())."'>".Auth::user()->full_name.'</a>';
            $asset_link = "<a href='".route('admin.project.show', $project->id)."'>".$project->name.'</a>';

            event(new ProjectRestored(Auth::user()->full_name, $asset_link));

            return $project;
        }

        throw new GeneralException(__('exceptions.backend.projects.restore_error'));
    }

    /**
     * @param PricingHistory $project
     *
     * @return PricingHistory
     * @throws GeneralException
     */
    public function add_pricing_history(array $data, $project) : ProjectPricingHistory
    {
        return DB::transaction(function () use ($data, $project) {
            $pricing_history    = ProjectPricingHistory::create([
                'project_id'    =>  $project->id,
                'po_number'     =>  $data['po_number'],
                'pricing_date'  =>  date('Y-m-d', strtotime($data['pricing_date'])),
                'price'         =>  $this->strip_currency($data['price']),
                'terms'         =>  $data['terms'],
                'delivery'      =>  $data['delivery'],
                'fpd_reference' =>  $data['fpd_reference'],
                'wpc_reference' =>  $data['wpc_reference']
            ]);

            if ($pricing_history) {

                $project->update(['price' => $pricing_history->price]);

                $auth_link  = "<a href='".route('admin.auth.user.show', auth()->id())."'>".Auth::user()->full_name.'</a>';
                $asset_link = "<a href='".route('admin.project.show', $project->id)."'>".$project->name.'</a>';
                $model      = class_basename($this->model);

                event(new GeneralPricingHistoryCreated($auth_link, $model, $pricing_history->po_number, $asset_link));

                return $pricing_history;
            }

            throw new GeneralException('There was a problem creating this pricing history. Please try again.');
        });
    }

    /**
     * @param PricingHistory $project
     *
     * @return PricingHistory
     * @throws GeneralException
     */
    public function update_pricing_history(array $data, $project, $pricing_history) : ProjectPricingHistory
    {
        return DB::transaction(function () use ($data, $project, $pricing_history) {
            if($pricing_history->update([
                'po_number'     =>  $data['po_number'],
                'pricing_date'  =>  date('Y-m-d', strtotime($data['pricing_date'])),
                'price'         =>  $this->strip_currency($data['price']),
                'terms'         =>  $data['terms'],
                'delivery'      =>  $data['delivery'],
                'fpd_reference' =>  $data['fpd_reference'],
                'wpc_reference' =>  $data['wpc_reference']
            ]))

            {

                $auth_link  = "<a href='".route('admin.auth.user.show', auth()->id())."'>".Auth::user()->full_name.'</a>';
                $asset_link = "<a href='".route('admin.project.show', $project->id)."'>".$project->name.'</a>';
                $model      = class_basename($project);

                event(new GeneralPricingHistoryUpdated($auth_link, $pricing_history->po_number, $model, $asset_link));

                return $pricing_history;
            }

            throw new GeneralException('There was a problem updating this pricing history. Please try again.');
        });
    }

    /**
     * @param PricingHistory $project_pricing_history
     *
     * @return PricingHistory
     * @throws GeneralException
     */
    public function forceDeletePricingHistory(Project $project, ProjectPricingHistory $project_pricing_history) : ProjectPricingHistory
    {
        if (is_null($project_pricing_history->deleted_at)) {
            throw new GeneralException('Project Pricing History "'. $project_pricing_history->po_number .'" must be deleted first before can it can be destroyed permanently.');
        }

        return DB::transaction(function () use ($project_pricing_history) {
            if ($project_pricing_history->forceDelete()) {
                $auth_link  = "<a href='".route('admin.auth.user.show', auth()->id())."'>".Auth::user()->full_name.'</a>';
                $asset_link = "<a href='".route('admin.project.show', $project->id)."'>".$project->name.'</a>';
                $model      = class_basename($project);

                event(new GeneralPricingHistoryPermanentlyDeleted($auth_link, $pricing_history->po_number, $model, $asset_link));

                return $project_pricing_history;
            }

            throw new GeneralException('There was a problem deleting this Pricing History. Please try again.');
        });
    }

    /**
     * @param Project $project
     *
     * @return Project
     * @throws GeneralException
     */
    public function restorePricingHistory(Project $project, ProjectPricingHistory $project_pricing_history) : ProjectPricingHistory
    {
        if (is_null($project_pricing_history->deleted_at)) {
            throw new GeneralException('Project Pricing History "'. $project_pricing_history->po_number .'" must be deleted first before can it can be restored.');
        }

        if ($project_pricing_history->restore()) {
            $auth_link  = "<a href='".route('admin.auth.user.show', auth()->id())."'>".Auth::user()->full_name.'</a>';
            $asset_link = "<a href='".route('admin.project.show', $project->id)."'>".$project->name.'</a>';
            $model      = class_basename($project);

            event(new GeneralPricingHistoryRestored($auth_link, $project_pricing_history->po_number, $model, $asset_link));

            return $project_pricing_history;
        }

        throw new GeneralException('There was a problem restoring this Pricing History. Please try again.');
    }
}
