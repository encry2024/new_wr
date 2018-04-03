@extends ('backend.layouts.app')

@section ('title', __('labels.backend.customers.management') . ' | ' . __('labels.backend.customers.view'))

@section('breadcrumb-links')
    @include('backend.customer.includes.breadcrumb-links')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">

            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        {{ __('labels.backend.customers.management') }}
                        <small class="text-muted">{{ __('labels.backend.customers.view', ['customer' => $model->name]) }}</small>
                    </h4>
                </div><!--col-->

                <div class="col-sm-7">
                    <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                        <a href="{{ route('admin.customer.edit', $model->id) }}" class="btn btn-success ml-1" data-toggle="tooltip" title="Edit Customer"><i class="fa fa-pencil"></i></a>
                    </div><!--btn-toolbar-->
                </div><!--col-->
            </div><!--row-->

            <div class="row mt-4 mb-4">
                <div class="col">
                    <ul class="nav nav-tabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-expanded="true"><i class="fa fa-user"></i> {{ __('labels.backend.customers.tabs.titles.overview') }}</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div class="tab-pane active" id="overview" role="tabpanel" aria-expanded="true">
                            @include('backend.customer.show.tabs.overview')
                        </div><!--tab-->
                    </div><!--tab-content-->
                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer">
            <div class="row">
                <div class="col">
                    <small class="float-right text-muted">
                        <strong>{{ __('labels.backend.customers.tabs.content.overview.created_at') }}:</strong> {{ date('F d, Y (h:i A)', strtotime($model->created_at)) }},
                        <strong>{{ __('labels.backend.customers.tabs.content.overview.updated_at') }}:</strong> {{ date('F d, Y (h:i A)', strtotime($model->updated_at)) }}
                        @if ($model->trashed())
                            <strong>{{ __('labels.backend.customers.tabs.content.overview.deleted_at') }}:</strong> {{ date('F d, Y (h:i A)', strtotime($model->deleted_at)) }}
                        @endif
                    </small>
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
@endsection
