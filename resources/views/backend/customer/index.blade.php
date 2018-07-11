@extends ('backend.layouts.app')

@section ('title', app_name() . ' | ' . __('labels.backend.customers.management'))

@section('breadcrumb-links')
    @include('backend.customer.includes.breadcrumb-links')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        {{ __('labels.backend.customers.management') }} <small class="text-muted">{{ __('labels.backend.customers.list') }}</small>
                    </h4>
                </div><!--col-->

                <div class="col-sm-7">
                    @include('backend.customer.includes.header-buttons')
                </div><!--col-->
            </div><!--row-->

            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                            <tr>
                                <th>{{ __('labels.backend.customers.table.name') }}</th>
                                <th>{{ __('labels.backend.customers.table.city') }}</th>
                                <th>{{ __('labels.backend.customers.table.address') }}</th>
                                <th>{{ __('labels.backend.customers.table.postal_code') }}</th>
                                <th>{{ __('labels.backend.customers.table.updated_at') }}</th>
                                <th>{{ __('labels.backend.customers.table.created_at') }}</th>
                                <th>{{ __('labels.general.actions') }}</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if ($customers->count())
                                @foreach ($customers as $customer)
                                    <tr>
                                        <td>{{ $customer->name }}</td>
                                        <td>{{ $customer->city}}</td>
                                        <td>{{ $customer->address}}</td>
                                        <td>{{ $customer->postal_code }}</td>
                                        <td>{{ date('F d, Y h:i A', strtotime($customer->updated_at))}}</td>
                                        <td>{{ date('F d, Y h:i A', strtotime($customer->created_at)) }}</td>
                                        <td>{!! $customer->action_buttons !!}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                <td colspan="7"><p class="text-center">There are no stored customers in the database...</p></td>
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
                        {!! $customers->total() !!} {{ trans_choice('labels.backend.customers.table.total', $customers->total()) }}
                    </div>
                </div><!--col-->

                <div class="col-5">
                    <div class="float-right">
                        {!! $customers->render() !!}
                    </div>
                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->
    </div><!--card-->
@endsection
