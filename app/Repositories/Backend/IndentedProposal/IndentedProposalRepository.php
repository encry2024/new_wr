<?php
/**
 * Created by PhpStorm.
 * User: christanjake2024
 * Date: 1/26/18
 * Time: 2:06 PM
 */

namespace App\Repositories\Backend\IndentedProposal;

# Facades
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\LengthAwarePaginator;
# Models
use App\Models\IndentedProposal\IndentedProposal;
use App\Models\IndentedProposal\IndentedProposalItem;
use App\Models\Project\Project;
use App\Models\Project\ProjectPricingHistory;
use App\Models\Aftermarket\Aftermarket;
use App\Models\Aftermarket\AftermarketPricingHistory;
use App\Models\Seal\Seal;
use App\Models\TargetRevenue\TargetRevenue;
use App\Models\TargetRevenue\TargetRevenueHistory;
use App\Models\Seal\SealPricingHistory;
use App\Models\Auth\User;
use Auth;
# Exceptions
use App\Exceptions\GeneralException;
# Repository
use App\Repositories\BaseRepository;
# Events
use App\Events\Backend\IndentedProposal\IndentedProposalAccepted;
use App\Events\Backend\IndentedProposal\IndentedProposalCollected;
use App\Events\Backend\IndentedProposal\IndentedProposalCreated;
use App\Events\Backend\IndentedProposal\IndentedProposalPermanentlyDeleted;
use App\Events\Backend\IndentedProposal\IndentedProposalRestored;
use App\Events\Backend\IndentedProposal\IndentedProposalUpdated;
use App\Events\Backend\IndentedProposal\IndentedProposalUploaded;

/**
 * Class IndentedProposalRepository.
 */
class IndentedProposalRepository extends BaseRepository
{
    /**
     * @return string
     */
    public function model()
    {
        return IndentedProposal::class;
    }

    /**
     * @param int    $paged
     * @param string $orderBy
     * @param string $sort
     *
     * @return mixed
     */
    public function getPaginatedIndentedProposal($paged = 25, $orderBy = 'created_at', $sort = 'desc') : LengthAwarePaginator
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
     * @return IndentedProposal
     */
    public function create(array $data) : IndentedProposal
    {
        $total_ordered_products = count($data['indented_proposal_itemmable_id']);

        return DB::transaction(function () use ($data, $total_ordered_products) {
            $products = $data['indented_proposal_itemmable_id'];

            $indented_proposal = parent::create([
                'order_entry_no'        => $data['order_entry_no'] ? $data['order_entry_no'] : 'N/A',
                'wpc_reference'         => $data['wpc_reference'],
                'customer_id'           => $data['customer'],
                'to'                    => $data['customer'],
                'sold_to'               => $data['customer'],
                'wpcoc'                 => '12039105102',
                'user_id'               => Auth::user()->id,
                'rfq_number'            => $data['rfq_number'],
                'invoice_to'            => $data['invoice_to'],
                'invoice_to_address'    => $data['invoice_to_address'],
                'ship_to'               => $data['ship_to'],
                'ship_to_address'       => $data['ship_to_address'],
                'status'                => 'SENT',
                'collection_status'     => 'PENDING'
            ]);

            if ($indented_proposal) {
                $i = 0;

                while($i < $total_ordered_products) {
                    $product = explode('-', $data['indented_proposal_itemmable_id'][$i]);
                    $product_id  = $product[0];
                    $product_model = 'App\\Models\\'.$product[1].'\\'.$product[1];

                    $indented_proposal_item                                     = new IndentedProposalItem;
                    $indented_proposal_item->indented_proposal_id               = $indented_proposal->id;
                    $indented_proposal_item->indented_proposal_itemmable_id     = $product_id;
                    $indented_proposal_item->indented_proposal_itemmable_type   = $product_model;
                    $indented_proposal_item->quantity                           = $data['quantity'][$i];
                    $indented_proposal_item->price                              = str_replace(',','',$data['price'][$i]);
                    $indented_proposal_item->currency                           = $data['currency'][$i];
                    $indented_proposal_item->delivery                           = $data['delivery_date'][$i] * 7;
                    $indented_proposal_item->status                             = 'PROCESSING';
                    $indented_proposal_item->notify_me_after                    = ($data['delivery_date'][$i] - 2) * 7;
                    $indented_proposal_item->notification_date                  = date('Y-m-d');

                    if ($indented_proposal_item->save()) {
                        $product_object = $product_model::find($product_id);
                        $foreign_key = strtolower($product_object->data_model).'_id';
                        $pricing_history_model = $product_model.'PricingHistory';

                        $pricing_history                = new $pricing_history_model;
                        $pricing_history->po_number     = 'N/A';
                        $pricing_history->$foreign_key  = $product_id;
                        $pricing_history->pricing_date  = date('Y-m-d');
                        $pricing_history->price         = $indented_proposal_item->price;
                        $pricing_history->terms         = "30-days";
                        $pricing_history->delivery      = "TEST DELIVERY";
                        $pricing_history->fpd_reference = "TEST_FPD_REFERENCE";
                        $pricing_history->wpc_reference = $indented_proposal->wpc_reference;

                        if ($pricing_history->save()) {
                            $product_object->update(['price' => $pricing_history->price]);
                        }
                    }

                    $i++;
                }

                $auth_link = "<a href='".route('admin.auth.user.show', auth()->id())."'>".Auth::user()->full_name.'</a>';
                $asset_link = "<a href='".route('admin.indented-proposal.show', $indented_proposal->id)."'>".$indented_proposal->name.'</a>';

                event(new IndentedProposalCreated($auth_link, $asset_link));

                return $indented_proposal;
            }

            throw new GeneralException(__('exceptions.backend.indented_proposals.update_error'));
        });
    }

    /**
     * @param IndentedProposal  $indented_proposal
     * @param array $data
     *
     * @return IndentedProposal
     */
    public function update(IndentedProposal $indented_proposal, array $data) : IndentedProposal
    {
        return DB::transaction(function () use ($indented_proposal, $data) {
            if ($indented_proposal->update([
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

                'scanned_file'          =>   $indented_proposal->scanned_file
            ]))

            {
                $auth_link = "<a href='".route('admin.auth.user.show', auth()->id())."'>".Auth::user()->full_name.'</a>';
                $asset_link = "<a href='".route('admin.indented_proposal.show', $indented_proposal->id)."'>".$indented_proposal->name.'</a>';

                event(new IndentedProposalUpdated($auth_link, $asset_link));

                return $indented_proposal;
            }

            throw new GeneralException(__('exceptions.backend.indented_proposals.update_error'));
        });
    }

    /**
     * @param IndentedProposal $indented_proposal
     *
     * @return IndentedProposal
     * @throws GeneralException
     */
    public function forceDelete(IndentedProposal $indented_proposal) : IndentedProposal
    {
        if (is_null($indented_proposal->deleted_at)) {
            throw new GeneralException(__('exceptions.backend.indented_proposals.delete_first'));
        }

        return DB::transaction(function () use ($indented_proposal) {

            if ($indented_proposal->forceDelete()) {
                $auth_link = "<a href='".route('admin.auth.user.show', auth()->id())."'>".Auth::user()->full_name.'</a>';

                event(new IndentedProposalPermanentlyDeleted($auth_link, $indented_proposal->name));

                return $indented_proposal;
            }

            throw new GeneralException(__('exceptions.backend.indented_proposals.delete_error'));
        });
    }

    /**
     * @param IndentedProposal $indented_proposal
     *
     * @return IndentedProposal
     * @throws GeneralException
     */
    public function restore(IndentedProposal $indented_proposal) : IndentedProposal
    {
        if (is_null($indented_proposal->deleted_at)) {
            throw new GeneralException(__('exceptions.backend.indented_proposals.cant_restore'));
        }

        if ($indented_proposal->restore()) {
            $auth_link = "<a href='".route('admin.auth.user.show', auth()->id())."'>".Auth::user()->full_name.'</a>';
            $asset_link = "<a href='".route('admin.indented_proposal.show', $indented_proposal->id)."'>".$indented_proposal->name.'</a>';

            event(new IndentedProposalRestored(Auth::user()->full_name, $asset_link));

            return $indented_proposal;
        }

        throw new GeneralException(__('exceptions.backend.indented_proposals.restore_error'));
    }

    public function accept(IndentedProposal $indented_proposal) : IndentedProposal
    {
        return DB::transaction(function () use ($indented_proposal) {
            if ($indented_proposal->update([
                'collection_status' => 'ACCEPTED'
            ]))

            {
                /*$auth_link = "<a href='".route('admin.auth.user.show', auth()->id())."'>".Auth::user()->full_name.'</a>';
                $asset_link = "<a href='".route('admin.indented_proposal.show', $indented_proposal->id)."'>".$indented_proposal->name.'</a>';

                event(new IndentedProposalUpdated($auth_link, $asset_link));*/

                return $indented_proposal;
            }

            throw new GeneralException(__('exceptions.backend.indented_proposals.update_error'));
        });
    }

    public function sendToAssistant(IndentedProposal $indented_proposal, $data) : IndentedProposal
    {
        return DB::transaction(function () use ($indented_proposal, $data) {
            if ($indented_proposal->update([
                'collection_status'         => 'DELIVERY',
                'purchase_order'            => $data['purchase_order'],
                'wpc_reference'             => $data['wpc_reference'],
                'special_instruction'       => $data['special_instruction'],
                'ship_via'                  => $data['ship_via'],
                'amount'                    => $data['amount'],
                'packing'                   => $data['packing'],
                'documents'                 => $data['documents'],
                'insurance'                 => $data['insurance'],
                'bank_detail_name'          => $data['bank_detail_name'],
                'bank_detail_address'       => $data['bank_detail_address'],
                'bank_detail_account_no'    => $data['bank_detail_account_no'],
                'bank_detail_swift_code'    => $data['bank_detail_swift_code'],
                'commission_note'           => $data['commission_note'],
                'commission_address'        => $data['commission_address'],
                'commission_account_number' => $data['commission_account_number'],
                'commission_swift_code'     => $data['commission_swift_code'],
            ]))

            {
                /*$auth_link = "<a href='".route('admin.auth.user.show', auth()->id())."'>".Auth::user()->full_name.'</a>';
                $asset_link = "<a href='".route('admin.indented_proposal.show', $indented_proposal->id)."'>".$indented_proposal->name.'</a>';

                event(new IndentedProposalUpdated($auth_link, $asset_link));*/

                return $indented_proposal;
            }

            throw new GeneralException(__('exceptions.backend.indented_proposals.update_error'));
        });
    }

    public function sendToCollection(IndentedProposal $indented_proposal) : IndentedProposal
    {
        return DB::transaction(function () use ($indented_proposal) {
            if ($indented_proposal->update([
                'collection_status'   => 'FOR-COLLECTION'
            ]))

            {
                /*$auth_link = "<a href='".route('admin.auth.user.show', auth()->id())."'>".Auth::user()->full_name.'</a>';
                $asset_link = "<a href='".route('admin.indented_proposal.show', $indented_proposal->id)."'>".$indented_proposal->name.'</a>';

                event(new IndentedProposalUpdated($auth_link, $asset_link));*/

                return $indented_proposal;
            }

            throw new GeneralException(__('exceptions.backend.indented_proposals.update_error'));
        });
    }

    public function changeItemDeliveryStatus(IndentedProposalItem $item, $data)
    {
        return DB::transaction(function () use ($item, $data) {
            if ($item->update(['status' => $data['delivery_status']])) {
                return $item;
            }

            throw new GeneralException('Updating item delivery status failed.');
        });
    }

    public function sendToCollector(IndentedProposal $indented_proposal, $data) : IndentedProposal
    {
        return DB::transaction(function () use ($indented_proposal, $data) {
            if ($indented_proposal->update(['collection_status' => 'FOR-COLLECTION'])) {
                return $indented_proposal;
            }

            throw new Exception('Updating Proposal\'s collection status failed. Please contact the administrator.');
        });
    }

    public function collect(IndentedProposal $indented_proposal) : IndentedProposal
    {
        return DB::transaction(function () use ($indented_proposal) {
            $user = User::find($indented_proposal->user_id);
            $target_revenue = TargetRevenue::where('user_id', $user->id)->latest()->first();


            $total_collected = 0;
            $total_price = 0;

            if ($indented_proposal->update([
                'collection_status' => 'COMPLETED'
            ]))

            {
                $items = $indented_proposal->indented_proposal_items;

                foreach ($items as $item) {
                    $from = $item->currency;

                    $url = file_get_contents('https://free.currencyconverterapi.com/api/v5/convert?q=' . $from . '_USD&compact=ultra');
                    $json = json_decode($url, true);
                    $rate = implode(" ", $json);

                    $total_collected += ($item->price * $rate) * $item->quantity;
                    $total_price = ($item->price * $rate) * $item->quantity;

                    $target_revenue_history = new TargetRevenueHistory();
                    $target_revenue_history->target_revenue_id = $target_revenue->id;
                    $target_revenue_history->collected = $total_price;
                    $target_revenue_history->date = date('Y-m-d');
                    $target_revenue_history->target_revenue_historable_type = 'App\\Models\\IndentedProposal\\IndentedProposal';
                    $target_revenue_history->target_revenue_historable_id = $indented_proposal->id;
                    $target_revenue_history->save();
                }

                $target_revenue->current_sale = $total_collected + $target_revenue->current_sale;
                $target_revenue->save();

                return $indented_proposal;
            }

            throw new GeneralException('Updating proposal status failed.');
        });
    }
}
