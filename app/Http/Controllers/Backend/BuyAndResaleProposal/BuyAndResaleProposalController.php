<?php

namespace App\Http\Controllers\Backend\BuyAndResaleProposal;

# Facades
use Illuminate\Http\Request;
use Session;
use Auth;
use Excel;
use DB;
# Controller
use App\Http\Controllers\Controller;
# Models
use App\Models\Customer\Customer;
use App\Models\Project\Project;
use App\Models\Aftermarket\Aftermarket;
use App\Models\Seal\Seal;
use App\Models\BuyAndResaleProposal\BuyAndResaleProposal;
use App\Models\BuyAndResaleProposal\BuyAndResaleProposalItem;
# Requests
use App\Http\Requests\Backend\BuyAndResaleProposal\ManageBuyAndResaleProposalRequest;
use App\Http\Requests\Backend\BuyAndResaleProposal\StoreBuyAndResaleProposalRequest;
use App\Http\Requests\Backend\BuyAndResaleProposal\CreateBuyAndResaleProposalRequest;
use App\Http\Requests\Backend\BuyAndResaleProposal\EditBuyAndResaleProposalRequest;
use App\Http\Requests\Backend\BuyAndResaleProposal\UpdateBuyAndResaleProposalRequest;
use App\Http\Requests\Backend\BuyAndResaleProposal\DeleteBuyAndResaleProposalRequest;
# Repositories
use App\Repositories\Backend\BuyAndResaleProposal\BuyAndResaleProposalRepository;

class BuyAndResaleProposalController extends Controller
{
    protected $buyAndResaleProposalRepository;

    public function __construct(BuyAndResaleProposalRepository $buyAndResaleProposalRepository)
    {
        $this->buyAndResaleProposalRepository = $buyAndResaleProposalRepository;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ManageBuyAndResaleProposalRequest $request)
    {
        return view('backend.buy_and_resale_proposal.index')
        ->with('buy_and_resale_proposals', $this->buyAndResaleProposalRepository->getPaginatedBuyAndResaleProposal());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $ordered_products = [];

        if (session()->has('products')) {
            $products = session()->get('products');
            $ordered_products = [];

            foreach($products as $key => $product) {
                $product = explode('-', $product);
                // Set Model
                $model = 'App\Models\\'.$product[1].'\\'.$product[1];
                // Set ID
                $id = $product[0];

                $ordered_products[] = $model::find($id);
            }

            if (Auth::user()->roles_label == "Sales Agent") {
                $customers = Customer::where('user_id', Auth::user()->id)->get();
            } else if (Auth::user()->roles_label == "Administrator") {
                $customers = Customer::all();
            }

            return view('backend.buy_and_resale_proposal.create')->withCustomers($customers)->with('ordered_products', $ordered_products);
        }

        if (Auth::user()->roles_label == "Sales Agent") {
            $customers = Customer::where('user_id', Auth::user()->id)->get();
        } else if (Auth::user()->roles_label == "Administrator") {
            $customers = Customer::all();
        }

        return view('backend.buy_and_resale_proposal.create')->withCustomers($customers)->with('ordered_products', $ordered_products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->buyAndResaleProposalRepository->create($request->except('_token'));

        return redirect()->back()->withFlashSuccess(__('alerts.backend.indented_proposals.created', ['indented_proposal' => $request->wpc_reference]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(BuyAndResaleProposal $buyAndResaleProposal, ManageBuyAndResaleProposalRequest $request)
    {
        return view('backend.buy_and_resale_proposal.show')->withModel($buyAndResaleProposal);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeItemDeliveryStatus(BuyAndResaleProposalItem $item, Request $request)
    {
        $this->buyAndResaleProposalRepository->changeItemDeliveryStatus($item, $request->only('delivery_status'));

        return redirect()->back();
    }

    public function exportBuyAndResaleProposal(BuyAndResaleProposal $buyAndSellProposal)
    {
        $excel = Excel::create('Test Files', function($excel) use($buyAndSellProposal) {
            $excel->sheet('WorthRand Inventory PO', function($sheet) use ($buyAndSellProposal, $excel) {
               $ctr = 0;

               $selectedItems = DB::table('buy_and_resale_proposal_items')
               ->select('projects.*',
                DB::raw('projects.name as "project_name"'),
                DB::raw('projects.status as "project_md"'),
                DB::raw('projects.serial_number as "project_sn"'),
                DB::raw('projects.epc_award as "project_pn"'),
                // DB::raw('projects.drawing_number as "project_dn"'),
                DB::raw('projects.tag_number as "project_tn"'),
                DB::raw('projects.final_result as "project_mn"'),
                DB::raw('projects.price as "project_price"'),
               'aftermarkets.*',
               DB::raw('aftermarkets.name as "after_market_name"'),
                DB::raw('aftermarkets.model as "after_market_md"'),
                DB::raw('aftermarkets.part_number as "after_market_pn"'),
                // DB::raw('aftermarkets.drawing_number as "after_market_dn"'),
                DB::raw('aftermarkets.material_number as "after_market_mn"'),
                DB::raw('aftermarkets.material_number as "after_market_sn"'),
                DB::raw('aftermarkets.tag_number as "after_market_tn"'),
                DB::raw('aftermarkets.price as "after_market_price"'),
               'seals.*',
               DB::raw('seals.name as "seal_name"'),
               DB::raw('seals.bom_number as "seal_bom_number"'),
               DB::raw('seals.model as "seal_model"'),
               DB::raw('seals.drawing_number as "seal_drawing_number"'),
               DB::raw('seals.tag as "seal_tag_number"'),
               DB::raw('seals.price as "seal_price"'),
               DB::raw('seals.material_number as "seal_material_number"'),
               'buy_and_resale_proposal_items.*',
               DB::raw('buy_and_resale_proposal_items.id as "buy_and_sell_proposal_item_id"'),
               DB::raw('buy_and_resale_proposal_items.quantity as "buy_and_sell_proposal_item_quantity"'),
               DB::raw('buy_and_resale_proposal_items.delivery as "buy_and_sell_proposal_item_delivery"'),
               DB::raw('buy_and_resale_proposal_items.price as "buy_and_sell_proposal_item_price"'),
               DB::raw('buy_and_resale_proposal_items.notify_me_after as "buy_and_sell_proposal_item_notify_me_after"'))
               ->leftJoin('projects', function($join) {
                  $join->on('buy_and_resale_proposal_items.buy_and_resale_proposal_itemmable_id', '=', 'projects.id')
                  ->where('buy_and_resale_proposal_items.buy_and_resale_proposal_itemmable_type', '=', 'App\Models\Project\Project');
               })
               ->leftJoin('aftermarkets', function($join) {
                  $join->on('buy_and_resale_proposal_items.buy_and_resale_proposal_itemmable_id', '=', 'aftermarkets.id')
                  ->where('buy_and_resale_proposal_items.buy_and_resale_proposal_itemmable_type', '=', 'App\Models\Aftermarket\Aftermarket');
               })
               ->leftJoin('seals', function($join) {
                  $join->on('buy_and_resale_proposal_items.buy_and_resale_proposal_itemmable_id', '=', 'seals.id')
                  ->where('buy_and_resale_proposal_items.buy_and_resale_proposal_itemmable_type', '=', 'App\Models\Seal\Seal');
               })
               ->where('buy_and_resale_proposal_items.buy_and_resale_proposal_id', '=', $buyAndSellProposal->id)->get();
               $total_count = 14 + count($selectedItems);
               $sheet->cell('A14:E'. $total_count, function($cells) {

                  $cells->setValignment(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                  // Set vertical alignment to middle
                  $cells->setAlignment(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

               });

               $sheet->loadView('backend.buy_and_resale_proposal.proposal_to_xls', array('buyAndSellProposal' => $buyAndSellProposal, 'selectedItems' => $selectedItems, 'ctr' => $ctr));
            });
            $lastrow= $excel->getActiveSheet()->getHighestRow();
            $excel->getActiveSheet()->getStyle('A1:J'.$lastrow)->getAlignment()->setWrapText(true);
         })->export('xlsx');
    }
}
