@extends ('backend.layouts.app')

@section ('title', __('labels.backend.indented_proposals.management') . ' | ' . __('labels.backend.indented_proposals.create'))

@section('breadcrumb-links')
    @include('backend.customer.includes.breadcrumb-links')
@endsection

@section('content')
    {{ html()->form('POST', route('admin.indented-proposal.store'))->class('form-horizontal')->open() }}

    <div class="alert alert-info" role="alert">
        <i class="fa fa-info-circle"></i> Please fill up all fields to avoid any errors...
    </div>

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-7">
                    <h4 class="card-title mb-0">
                        {{ __('labels.backend.indented_proposals.management') }}
                        <small class="text-muted">{{ __('labels.backend.indented_proposals.create') }}</small>
                    </h4>
                </div><!--col-->

                <div class="col-sm-5">
                    <div class="btn-toolbar float-right" role="toolbar" aria-label="Toolbar with button groups">
                        <a href="{{ route('admin.product.index') }}" class="btn btn-dark" data-toggle="tooltip" data-placement="top" title="Add Product"><i class="fa fa-plus"></i></a>
                    </div><!--btn-toolbar-->
                </div><!--col-->
            </div><!--row-->

            <hr />

            <div class="row mt-4 mb-4">
                <div class="col">
                    <div class="form-group row">
                        {{ html()->label(__('validation.attributes.backend.indented-proposals.order_entry_no'))->class('col-md-2 form-control-label')->for('order_entry_no') }}

                        <div class="col-sm-10">
                            {{
                                html()->text('order_entry_no')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.indented-proposals.order_entry_no'))
                                ->attribute('maxlength', 60)
                                ->required()
                            }}
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('validation.attributes.backend.indented-proposals.wpc_reference'))->class('col-md-2 form-control-label')->for('wpc_reference') }}

                        <div class="col-sm-10">
                            {{
                                html()->text('wpc_reference')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.indented-proposals.wpc_reference'))
                                ->attribute('maxlength', 60)
                                ->required()
                            }}
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label">Date</label>

                        <div class="col-sm-10">
                            <input type="text" id="date" value="{{ date('Y') }}" name="date" class="form-control" readOnly>
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('validation.attributes.backend.indented-proposals.sold_to'))->class('col-md-2 form-control-label')->for('sold_to') }}

                        <div class="col-sm-10">
                            <select name="customer" id="customer-dropdown" class="form-control chosen-select">
                                <option value=""></option>
                                @foreach ($customers as $customer)
                                    <option value="{{ $customer->id }}">{{ $customer->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="" class="col-md-2 form-control-label"></label>

                        <div class="col-sm-10">
                            <textarea name="sold_to_address" id="sold_to_address" class="form-control" placeholder="Sold to Address"></textarea>
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('validation.attributes.backend.indented-proposals.rfq_number'))->class('col-md-2 form-control-label')->for('rfq_number') }}

                        <div class="col-sm-10">
                            {{
                                html()->text('rfq_number')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.indented-proposals.rfq_number'))
                                ->attribute('maxlength', 100)
                                ->required()
                            }}
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('validation.attributes.backend.indented-proposals.invoice_to'))->class('col-md-2 form-control-label')->for('invoice_to') }}

                        <div class="col-sm-10">
                            {{
                                HTML()->text('invoice_to')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.indented-proposals.invoice_to'))
                                ->attribute('maxlength', 60)
                                ->required()
                            }}
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label for="" class="col-md-2 form-control-label"></label>

                        <div class="col-sm-10">
                            <textarea name="invoice_to_address" id="invoice_to_address" class="form-control" placeholder="Invoice to Address"></textarea>
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        {{ html()->label(__('validation.attributes.backend.indented-proposals.ship_to'))->class('col-md-2 form-control-label')->for('ship_to') }}

                        <div class="col-sm-10">
                            {{
                                html()->text('ship_to')
                                ->class('form-control')
                                ->placeholder(__('validation.attributes.backend.indented-proposals.ship_to'))
                                ->attribute('maxlength', 60)
                                ->required()
                            }}
                        </div>
                    </div><!--form-group-->

                    <div class="form-group row">
                        <label class="col-md-2 form-control-label"></label>

                        <div class="col-sm-10">
                            <textarea name="ship_to_address" id="ship_to_address" class="form-control" placeholder="Ship to Address"></textarea>
                        </div>
                    </div><!--form-group-->

                    <table class="table table-striped">
                        <thead>
                            <th>ITEM NO#</th>
                            <th>SERIAL NO#</th>
                            <th>NAME</th>
                            <th>QUANTITY</th>
                            <th>PRICE</th>
                            <th>DELIVERY</th>
                        </thead>

                        <tbody>
                            @if (!empty($ordered_products))
                                @foreach ($ordered_products as $ordered_product)
                                <tr>
                                    <input type="hidden" name="indented_proposal_itemmable_id[]" value="{{ $ordered_product->id }}-{{ $ordered_product->data_model }}">
                                    <td>{{ $ordered_product->id }}</td>
                                    <td>{{ $ordered_product->data_5 }}</td>
                                    <td>{{ $ordered_product->name }}</td>
                                    <td>
                                        <input type="text" class="form-control numeric-input" name="quantity[]">
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <select name="currency[]" id="currency-dropdown" data-amount="{{ is_null($ordered_product->latest_price) ? '' : $ordered_product->latest_price->price }}" class="form-control chosen-select currency-dropdown">
                                                    <option value="PHP" selected>PHP</option>
                                                    <option value="JPY">JPY</option>
                                                    <option value="USD">USD</option>
                                                    <option value="SGD">SGD</option>
                                                    <option value="EUR">EUR</option>
                                                </select>
                                            </div>
                                            <input type="text" name="price[]" class="form-control numeric-input price_input"
                                            id="price"
                                            value="{{ is_null($ordered_product->latest_price) ? '' : $ordered_product->latest_price->price }}">
                                        </div>
                                    </td>
                                    <td>
                                        <input type="text" class="form-control numeric-input" name="delivery_date[]">
                                    </td>
                                </tr>
                                @endforeach
                            @endif
                        </tbody>
                    </table>

                </div><!--col-->
            </div><!--row-->
        </div><!--card-body-->

        <div class="card-footer clearfix">
            <div class="row">
                <div class="col">
                    {{ form_cancel(route('admin.indented-proposal.index'), __('buttons.general.cancel')) }}
                </div><!--col-->

                <div class="col text-right">
                    {{ form_submit(__('buttons.general.crud.create')) }}
                </div><!--col-->
            </div><!--row-->
        </div><!--card-footer-->
    </div><!--card-->
    {{ html()->form()->close() }}
@endsection

@push('after-scripts')
    <script>
        const current_currency = $(".currency-dropdown");
        current_currency.data("previous_currency", current_currency.val()),
        price_input = $(".price_input");

        current_currency.change(function(data) {
            var currency = $(this);
            var old_curr = currency.data("previous_currency");
            currency.data("previous_currency", currency.val());
            var new_curr = currency.val();
            let amount = currency.data('amount');

            $.ajax({
                type: 'POST',
                url: "{{ route('admin.currency.convert') }}",
                data: {
                    _token: '{{ csrf_token() }}',
                    from: old_curr,
                    to: new_curr
                },
                dataType: 'JSON',
                success: function(ratio) {

                    total_currency = parseFloat(currency.attr('data-amount')).toFixed(2) * ratio;
                    currency.attr('data-amount', total_currency.toFixed(2));
                    currency.closest('tr').find('input#price').val(total_currency.toFixed(2));
                }
            });
        });

        price_input.on('keyup', function() {
            current_currency.attr('data-amount', $(this).val());
        });
    </script>
@endpush