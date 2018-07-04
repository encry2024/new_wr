@extends ('backend.layouts.app')

@section ('title', app_name() . ' | ' . __('labels.backend.projects.management'))

@section('breadcrumb-links')
    @include('backend.project.includes.breadcrumb-links')
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-5">
                    <h4 class="card-title mb-0">
                        {{ __('labels.backend.projects.management') }} <small class="text-muted">{{ __('labels.backend.projects.list') }}</small>
                    </h4>
                </div><!--col-->

                <div class="col-sm-7">
                    @include('backend.project.includes.header-buttons')
                </div><!--col-->
            </div><!--row-->

            <div class="row mt-4">
                <div class="col">
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>{{ __('labels.backend.projects.table.name') }}</th>
                                    <th>{{ __('labels.backend.projects.table.customer') }}</th>
                                    <th>{{ __('labels.backend.projects.table.tag_number') }}</th>
                                    <th>{{ __('labels.backend.projects.table.serial_number') }}</th>
                                    <th>{{ __('labels.backend.projects.table.final_result') }}</th>
                                    <th>{{ __('labels.backend.projects.table.scanned_file') }}</th>
                                    <th>{{ __('labels.general.actions') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                            @if ($projects->count())
                                @foreach ($projects as $project)
                                    <tr>
                                        <td>{{ $project->name }}</td>
                                        @if ($project->customer->count())
                                            <td>{!! $project->customer->trashed() ? "<label class='badge badge-danger' style='font-size: 10px;'>Customer was deleted</label>" : $project->customer->name !!}</td>
                                        @else
                                            <td>
                                                <label class='badge badge-danger' style='font-size: 10px;'>Customer was deleted permanently</label>
                                            </td>
                                        @endif
                                        <td>{{ $project->tag_number}}</td>
                                        <td>{{ $project->serial_number }}</td>
                                        <td>{{ $project->final_result }}</td>
                                        <td>{{ $project->scanned_file }}</td>
                                        <td>{!! $project->action_buttons !!}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="7"><p class="text-center">There are no stored Projects in the database...</p></td>
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
                        {!! $projects->total() !!} {{ trans_choice('labels.backend.projects.table.total', $projects->total()) }}
                    </div>
                </div><!--col-->

                <div class="col-5">
                    <div class="float-right">
                        {!! $projects->render() !!}
                    </div>
                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->
    </div><!--card-->
@endsection
