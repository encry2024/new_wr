@extends ('backend.layouts.app')

@section ('title', app_name() . ' | ' . __('labels.backend.indented_proposals.management'))

@section('breadcrumb-links')
    @include('backend.indented_proposal.includes.breadcrumb-links')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-7">
                    <h4 class="card-title mb-0">
                        {{ __('labels.backend.indented_proposals.management') }} <small class="text-muted">{{ __('labels.backend.indented_proposals.list') }}</small>
                    </h4>
                </div><!--col-->

                <div class="col-sm-5">
                    @if (auth()->user()->can('create indented proposal'))
                    @include('backend.indented_proposal.includes.header-buttons')
                    @endif
                </div><!--col-->
            </div><!--row-->

            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('labels.backend.indented_proposals.table.purchase_order') }}</th>
                                    <th>{{ __('labels.backend.indented_proposals.table.customer_id') }}</th>
                                    <th>{{ __('labels.backend.indented_proposals.table.collection_status') }}</th>
                                    <th>{{ __('labels.backend.indented_proposals.table.created_at') }}</th>
                                    <th>{{ __('labels.general.actions') }}</th>
                                </tr>
                            </thead>

                            <tbody>
                            @if (count($indented_proposals))
                                @foreach ($indented_proposals as $indented_proposal)
                                    <tr>
                                        <td>{!! $indented_proposal->purchase_order ? $indented_proposal->purchase_order : '<label class="badge badge-danger">N/A</label>' !!}</td>
                                        <td>{!! $indented_proposal->customer->trashed() ? "<label class='badge badge-danger'>Customer was Deleted</label>" : $indented_proposal->customer->name !!}</td>
                                        <td>{{ $indented_proposal->collection_status }}</td>
                                        <td>{{ date('F d, Y (h:i A)', strtotime($indented_proposal->created_at)) }}</td>
                                        <td>{!! $indented_proposal->action_buttons !!}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6"><p class="text-center">There are no stored Indented Proposals</p></td>
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
