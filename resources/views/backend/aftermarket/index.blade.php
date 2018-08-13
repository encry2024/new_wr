@extends ('backend.layouts.app')

@section ('title', app_name() . ' | ' . __('labels.backend.aftermarkets.management'))

@section('breadcrumb-links')
    @include('backend.aftermarket.includes.breadcrumb-links')
@endsection

@section('content')
<div class="alert alert-warning">
    <i class="fa fa-warning"></i> Warning <i class="fa fa-warning"></i> please make sure the item you are going to delete is not associated or assigned to any proposals or it will cause an error.
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        {{ __('labels.backend.aftermarkets.management') }} <small class="text-muted">{{ __('labels.backend.aftermarkets.list') }}</small>
                    </h4>
                </div><!--col-->

                <div class="col-sm-7">
                    @include('backend.aftermarket.includes.header-buttons')
                </div><!--col-->
            </div><!--row-->

            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('labels.backend.aftermarkets.table.name') }}</th>
                                    <th>{{ __('labels.backend.aftermarkets.table.model') }}</th>
                                    <th>{{ __('labels.backend.aftermarkets.table.part_number') }}</th>
                                    <th>{{ __('labels.backend.aftermarkets.table.material_number') }}</th>
                                    <th>{{ __('labels.backend.aftermarkets.table.reference_number') }}</th>
                                    <th>{{ __('labels.backend.aftermarkets.table.scanned_file') }}</th>
                                    <th>{{ __('labels.general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if ($aftermarkets->count())
                                @foreach ($aftermarkets as $aftermarket)
                                    <tr>
                                        <td>{{ $aftermarket->name }}</td>
                                        <td>{{ $aftermarket->model }}</td>
                                        <td>{{ $aftermarket->part_number }}</td>
                                        <td>{{ $aftermarket->material_number }}</td>
                                        <td>{{ $aftermarket->reference_number }}</td>
                                        <td>{{ $aftermarket->scanned_file }}</td>
                                        <td>{!! $aftermarket->action_buttons !!}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7"><p class="text-center">There are no stored Aftermarkets in the database...</p></td>
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
                        {!! $aftermarkets->total() !!} {{ trans_choice('labels.backend.aftermarkets.table.total', $aftermarkets->total()) }}
                    </div>
                </div><!--col-->

                <div class="col-5">
                    <div class="float-right">
                        {!! $aftermarkets->render() !!}
                    </div>
                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->
    </div><!--card-->
@endsection
