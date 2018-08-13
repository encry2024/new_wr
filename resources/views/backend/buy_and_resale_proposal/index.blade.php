@extends ('backend.layouts.app')

@section ('title', app_name() . ' | ' . __('labels.backend.buy_and_resale_proposals.management'))

@section('breadcrumb-links')
    @include('backend.buy_and_resale_proposal.includes.breadcrumb-links')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-7">
                    <h4 class="card-title mb-0">
                        {{ __('labels.backend.buy-and-resale-proposals.management') }} <small class="text-muted">{{ __('labels.backend.buy-and-resale-proposals.list') }}</small>
                    </h4>
                </div><!--col-->

                <div class="col-sm-5">
                    @if (auth()->user()->can('create indented proposal'))
                    @include('backend.buy_and_resale_proposal.includes.header-buttons')
                    @endif
                </div><!--col-->
            </div><!--row-->

            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('labels.backend.buy-and-resale-proposals.table.purchase_order') }}</th>
                                    <th>{{ __('labels.backend.buy-and-resale-proposals.table.customer_id') }}</th>
                                    <th>{{ __('labels.backend.buy-and-resale-proposals.table.collection_status') }}</th>
                                    <th>{{ __('labels.backend.buy-and-resale-proposals.table.created_at') }}</th>
                                    <th>{{ __('labels.general.actions') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                            @if (count($buy_and_resale_proposals))
                                @foreach ($buy_and_resale_proposals as $buy_and_resale_proposal)
                                    <tr>
                                        <td>{!! $buy_and_resale_proposal->purchase_order ? $buy_and_resale_proposal->purchase_order : '<label class="badge badge-danger">N/A</label>' !!}</td>
                                        <td>{!! $buy_and_resale_proposal->customer->trashed() ? "<label class='badge badge-danger'>Customer was Deleted</label>" : $indented_proposal->customer->name !!}</td>
                                        <td>{{ $buy_and_resale_proposal->collection_status }}</td>
                                        <td>{{ date('F d, Y (h:i A)', strtotime($buy_and_resale_proposal->created_at)) }}</td>
                                        <td>{!! $buy_and_resale_proposal->action_buttons !!}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6"><p class="text-center">There are no stored Buy and Resale Proposals</p></td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div><!--col-->
            </div><!--row-->
            <div class="row">
                <div class="col-7">
                    <div class="float-left">

                    </div>
                </div><!--col-->

                <div class="col-5">
                    <div class="float-right">

                    </div>
                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->
    </div><!--card-->
@endsection
