@extends('backend.layouts.app')

@section('title', app_name() . ' | ' . __('strings.backend.dashboard.title'))

@section('content')
    @if ($logged_in_user->roles_label == 'Sales Agent')
    <div class="row">
        <div class="col">

        </div>
    </div>
    @endif

    @if (($logged_in_user->roles_label == 'Sales Agent') || ($logged_in_user->roles_label == 'Administrator'))
    <div class="row">
        <div class="col-lg-12">
            <div class="row">
                @if ($logged_in_user->roles_label == 'Sales Agent')
                    @if (count($logged_in_user->target_revenues) != 0)
                        <div class="col-lg-4">
                            <div class="col">
                                <div class="card">
                                    <div class="card-body">
                                        <h5 class="card-title">Your Sales Record</h5>
                                        <br>
                                        <div id="breakdown_container"></div>
                                        <hr>
                                        <h5 class="card-title">Current Currency:</h5>
                                        <div class="input-group-prepend">
                                            <select name="currency" id="currency-dropdown" data-amount="{{ Auth::user()->target_revenues->last()->current_sale }}" class="form-control chosen-select currency-dropdown">
                                                <option value="PHP">PHP</option>
                                                <option value="JPY">JPY</option>
                                                <option value="USD" selected>USD</option>
                                                <option value="SGD">SGD</option>
                                                <option value="EUR">EUR</option>
                                            </select>
                                        </div>
                                        <hr>
                                        <h5 class="card-title">Current Sale:</h5>
                                        <h3 id="current_sale_label">USD {{ number_format(Auth::user()->target_revenues->last()->current_sale, 2) }}</h3>
                                        <hr>
                                        <h5 class="card-title">Target Sale:</h5>
                                        <h3><span id="target_currency">USD</span> <span id="target_sale_label" data-target-sale="{{ Auth::user()->target_revenues->last()->target_sale }}">{{ number_format(Auth::user()->target_revenues->last()->target_sale, 2) }}</span></h3>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-8">
                            <div class="card">
                                <div class="card-body">
                                    <div id="indented_proposal_container"></div>
                                </div>
                            </div><!--card-->

                            <div class="card">
                                <div class="card-body">
                                    <div id="buy_and_resale_proposal_container"></div>
                                </div>
                            </div><!--card-->
                        </div> <!-- col-lg-10 -->
                    </div>
                @else
                <div class="alert alert-danger">No target revenues was found in your account. Tell to the administrator to set your target revenue(s) first before logging in again.</div>
                @endif
            @elseif ($logged_in_user->roles_label == 'Administrator')
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div id="indented_proposal_container"></div>
                        </div>
                    </div><!--card-->

                    <div class="card">
                        <div class="card-body">
                            <div id="buy_and_resale_proposal_container"></div>
                        </div>
                    </div><!--card-->
                </div> <!-- col-lg-10 -->
            @endif
        </div><!--col-->
    </div><!--row-->

    <br>
    @endif
    <br>
@endsection

@push('after-scripts')
    @if (($logged_in_user->roles_label == 'Sales Agent') || ($logged_in_user->roles_label == 'Administrator'))
        @include('backend.scripts.dashboard.scripts')
    @endif
@endpush