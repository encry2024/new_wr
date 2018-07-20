<?php

namespace App\Http\Controllers\Backend\IndentedProposal;

# Facades
use Illuminate\Http\Request;
use Session;
use Auth;
use Excel;
use DB;
use Akaunting\Money\Money;
# Controller
use App\Http\Controllers\Controller;
# Models
use App\Models\Customer\Customer;
use App\Models\Project\Project;
use App\Models\Aftermarket\Aftermarket;
use App\Models\Seal\Seal;
use App\Models\IndentedProposal\IndentedProposal;
use App\Models\IndentedProposal\IndentedProposalItem;
# Requests
use App\Http\Requests\Backend\IndentedProposal\ManageIndentedProposalRequest;
use App\Http\Requests\Backend\IndentedProposal\StoreIndentedProposalRequest;
use App\Http\Requests\Backend\IndentedProposal\CreateIndentedProposalRequest;
use App\Http\Requests\Backend\IndentedProposal\EditIndentedProposalRequest;
use App\Http\Requests\Backend\IndentedProposal\UpdateIndentedProposalRequest;
use App\Http\Requests\Backend\IndentedProposal\DeleteIndentedProposalRequest;
# Repositories
use App\Repositories\Backend\IndentedProposal\IndentedProposalRepository;


class IndentedProposalController extends Controller
{
    protected $indentedProposalRepository;
    protected  $excel;

    public function __construct(IndentedProposalRepository $indentedProposalRepository,
        Excel $excel)
    {
        return $this->indentedProposalRepository = $indentedProposalRepository;
        return $this->excel = $excel;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(ManageIndentedProposalRequest $request)
    {
        return view('backend.indented_proposal.index')
        ->with('indented_proposals', $this->indentedProposalRepository->getPaginatedIndentedProposal());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(CreateIndentedProposalRequest $request)
    {
        if (Auth::user()->customers->count() != 0) {
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

                return view('backend.indented_proposal.create')->withCustomers($customers)->with('ordered_products', $ordered_products);
            }

            if (Auth::user()->roles_label == "Sales Agent") {
                $customers = Customer::where('user_id', Auth::user()->id)->get();
            } else if (Auth::user()->roles_label == "Administrator") {
                $customers = Customer::all();
            }

            return view('backend.indented_proposal.create')->withCustomers($customers)->with('ordered_products', $ordered_products);
        } else {
            return redirect()->back()->withFlashDanger('There are no assigned customers to your account.');
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request->all());

        $this->indentedProposalRepository->create($request->except('_token'));

        return redirect()->back()->withFlashSuccess(__('alerts.backend.indented_proposals.created', ['indented_proposal' => $request->po_number]));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(IndentedProposal $indented_proposal, ManageIndentedProposalRequest $request)
    {
        return view('backend.indented_proposal.show')->with('model', $indented_proposal);
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

    public function changeItemDeliveryStatus(IndentedProposalItem $item, Request $request)
    {
        $this->indentedProposalRepository->changeItemDeliveryStatus($item, $request->only('delivery_status'));

        return redirect()->back();
    }

    public function exportOrderEntry(IndentedProposal $indented_proposal)
    {
        $excel = Excel::create('Test Files', function($excel) use($indented_proposal) {
            $excel->sheet('WorthRand Inventory PO', function($sheet) use ($indented_proposal, $excel) {
                $ctr = 0;

                $selectedItems = DB::table('indented_proposal_items')
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
                //
                // DB::raw('seals.drawing_number as "seal_drawing_number"'),
                DB::raw('seals.tag as "seal_tag_number"'),
                DB::raw('seals.price as "seal_price"'),
                'indented_proposal_items.*',
                DB::raw('indented_proposal_items.id as "indented_proposal_item_id"'),
                DB::raw('indented_proposal_items.quantity as "indented_proposal_item_quantity"'),
                DB::raw('indented_proposal_items.delivery as "indented_proposal_item_delivery"'),
                DB::raw('indented_proposal_items.price as "indented_proposal_item_price"'),
                DB::raw('indented_proposal_items.notify_me_after as "indented_proposal_item_notify_me_after"'))
                ->leftJoin('projects', function($join) {
                    $join->on('indented_proposal_items.indented_proposal_itemmable_id', '=', 'projects.id')
                    ->where('indented_proposal_items.indented_proposal_itemmable_type', '=', 'App\Models\Project\Project');
                })
                ->leftJoin('aftermarkets', function($join) {
                    $join->on('indented_proposal_items.indented_proposal_itemmable_id', '=', 'aftermarkets.id')
                    ->where('indented_proposal_items.indented_proposal_itemmable_type', '=', 'App\Models\Aftermarket\Aftermarket');
                })
                ->leftJoin('seals', function($join) {
                    $join->on('indented_proposal_items.indented_proposal_itemmable_id', '=', 'seals.id')
                    ->where('indented_proposal_items.indented_proposal_itemmable_type', '=', 'App\Models\Seals\Seals');
                })
                ->where('indented_proposal_items.indented_proposal_id', '=', $indented_proposal->id)->get();
                $total_count = 14 + count($selectedItems);
                $sheet->cell('A14:E'. $total_count, function($cells) {

                    $cells->setValignment(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    // Set vertical alignment to middle
                    $cells->setAlignment(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                });

                $sheet->loadView('backend.indented_proposal.export_order_entry', array('indented_proposal' => $indented_proposal, 'selectedItems' => $selectedItems, 'ctr' => $ctr));
            });
            $lastrow= $excel->getActiveSheet()->getHighestRow();
            $excel->getActiveSheet()->getStyle('A1:J'.$lastrow)->getAlignment()->setWrapText(true);
            })->export('xlsx');
    }

    public function exportPendingProposal(IndentedProposal $indented_proposal)
    {
        $excel = Excel::create('Test Files', function($excel) use($indented_proposal) {
            $excel->sheet('WorthRand Inventory PO', function($sheet) use ($indented_proposal, $excel) {
                $ctr = 0;

                $selectedItems = DB::table('indented_proposal_items')
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
                //
                // DB::raw('seals.drawing_number as "seal_drawing_number"'),
                DB::raw('seals.tag as "seal_tag_number"'),
                DB::raw('seals.price as "seal_price"'),
                'indented_proposal_items.*',
                DB::raw('indented_proposal_items.id as "indented_proposal_item_id"'),
                DB::raw('indented_proposal_items.quantity as "indented_proposal_item_quantity"'),
                DB::raw('indented_proposal_items.delivery as "indented_proposal_item_delivery"'),
                DB::raw('indented_proposal_items.price as "indented_proposal_item_price"'),
                DB::raw('indented_proposal_items.notify_me_after as "indented_proposal_item_notify_me_after"'))
                ->leftJoin('projects', function($join) {
                    $join->on('indented_proposal_items.indented_proposal_itemmable_id', '=', 'projects.id')
                    ->where('indented_proposal_items.indented_proposal_itemmable_type', '=', 'App\Models\Project\Project');
                })
                ->leftJoin('aftermarkets', function($join) {
                    $join->on('indented_proposal_items.indented_proposal_itemmable_id', '=', 'aftermarkets.id')
                    ->where('indented_proposal_items.indented_proposal_itemmable_type', '=', 'App\Models\Aftermarket\Aftermarket');
                })
                ->leftJoin('seals', function($join) {
                    $join->on('indented_proposal_items.indented_proposal_itemmable_id', '=', 'seals.id')
                    ->where('indented_proposal_items.indented_proposal_itemmable_type', '=', 'App\Models\Seals\Seals');
                })
                ->where('indented_proposal_items.indented_proposal_id', '=', $indented_proposal->id)->get();
                $total_count = 14 + count($selectedItems);
                $sheet->cell('A14:E'. $total_count, function($cells) {

                    $cells->setValignment(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
                    // Set vertical alignment to middle
                    $cells->setAlignment(\PHPExcel_Style_Alignment::VERTICAL_CENTER);

                });

                $sheet->loadView('backend.indented_proposal.proposal_to_xls', array('indented_proposal' => $indented_proposal, 'selectedItems' => $selectedItems, 'ctr' => $ctr));
            });
            $lastrow= $excel->getActiveSheet()->getHighestRow();
            $excel->getActiveSheet()->getStyle('A1:J'.$lastrow)->getAlignment()->setWrapText(true);
            })->export('xlsx');
    }
}
