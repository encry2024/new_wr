<?php

namespace App\Http\Controllers\Backend\IndentedProposal;

# Facades
use Illuminate\Http\Request;
use Session;
use Auth;
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

    public function __construct(IndentedProposalRepository $indentedProposalRepository)
    {
        return $this->indentedProposalRepository = $indentedProposalRepository;
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
}
