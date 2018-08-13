@extends ('backend.layouts.app')

@section ('title', app_name() . ' | ' . __('labels.backend.seals.management'))

@section('breadcrumb-links')
    @include('backend.seal.includes.breadcrumb-links')
@endsection

@section('content')
<div class="alert alert-warning">
    <i class="fa fa-warning"></i> Warning <i class="fa fa-warning"></i> please make sure the item you are going to delete is not associated or assigned to any proposals or it will cause an error. Delete the proposal first before deleting the item you want to remove.
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        {{ __('labels.backend.seals.management') }} <small class="text-muted">{{ __('labels.backend.seals.list') }}</small>
                    </h4>
                </div><!--col-->

                <div class="col-sm-7">
                    @include('backend.seal.includes.header-buttons')
                </div><!--col-->
            </div><!--row-->

            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('labels.backend.seals.table.name') }}</th>
                                    <th>{{ __('labels.backend.seals.table.seal_type') }}</th>
                                    <th>{{ __('labels.backend.seals.table.bom_number') }}</th>
                                    <th>{{ __('labels.backend.seals.table.material_number') }}</th>
                                    <th>{{ __('labels.backend.seals.table.model') }}</th>
                                    <th>{{ __('labels.backend.seals.table.serial_number') }}</th>
                                    <th>{{ __('labels.backend.seals.table.scanned_file') }}</th>
                                    <th>{{ __('labels.general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if ($seals->count())
                                @foreach ($seals as $seal)
                                    <tr>
                                        <td>{{ $seal->name }}</td>
                                        <td>{{ $seal->seal_type }}</td>
                                        <td>{{ $seal->bom_number }}</td>
                                        <td>{{ $seal->material_number }}</td>
                                        <td>{{ $seal->model }}</td>
                                        <td>{{ $seal->serial_number }}</td>
                                        <td>{{ $seal->scanned_file }}</td>
                                        <td>{!! $seal->action_buttons !!}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7"><p class="text-center">There are no stored Seals in the database...</p></td>
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
                        {!! $seals->total() !!} {{ trans_choice('labels.backend.seals.table.total', $seals->total()) }}
                    </div>
                </div><!--col-->

                <div class="col-5">
                    <div class="float-right">
                        {!! $seals->render() !!}
                    </div>
                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->
    </div><!--card-->
@endsection
