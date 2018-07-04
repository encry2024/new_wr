<script>
    $(document).ready(function() {
        const pump_details_container      = $("#pump_details_container"),
        product_container                 = $("#product_container"),
        spare_parts_description_container = $("#spare_parts_description_container"),
        pricing_history_container         = $("#pricing_history_container"),
        uploaded_files_container          = $("#uploaded_files_container");
        let product_array                 = [];
        let product_id_list               = [];

        $("#product-category").on('change', function() {
            const category_id = $(this).val();
            let i = 0;

            clear_containers();

            $.ajax({
                type: 'POST',
                url: "{{ route('admin.product.get-product') }}",
                data: {
                    _token:  '{{ csrf_token() }}',
                    category_id: $(this).val()
                },
                dataType: 'JSON',
                success: function(data) {
                    let product_options = "";
                    product_array = data;
                    product_details(category_id);

                    console.log(data);

                    if (data.length > 0) {
                        product_container.append('<option value=""></option>');
                        product_container.removeAttr('disabled');
                        product_container.attr('data-placeholder', '-- Select a Product --');

                        for(i; i<Object.keys(data).length; i++) {
                            // Pass object
                            product = data[i];
                            // Create select:option
                            product_options = '<option value="'+ product.id + "-" + product.data_model +'">'+ product.name +'</option>';
                            // Append options to container
                            product_container.append(product_options);
                            // Update container
                            product_container.chosen();
                            product_container.trigger('chosen:updated');
                        }
                    } else {
                        swal({
                            title: 'This category doesn\'t have any stored products.',
                            confirmButtonText: 'Yes',
                            type: 'warning'
                        });
                    }
                }
            });
        });

        // Product Dropdown
        product_container.on('change', function() {
            const product_id = parseInt($(this).val());
            let selected_product = product_array.find(selected_product => selected_product.id === product_id);
            let pricing_history_data = "";

            pricing_history_container.empty();

            spare_parts_description_container.find('input#data_1').val(selected_product.data_1);
            spare_parts_description_container.find('input#data_2').val(selected_product.data_2);
            spare_parts_description_container.find('input#data_3').val(selected_product.data_3);
            spare_parts_description_container.find('input#data_4').val(selected_product.data_4);

            pump_details_container.find('input#data_5').val(selected_product.data_5);
            pump_details_container.find('input#data_6').val(selected_product.data_6);
            pump_details_container.find('input#data_7').val(selected_product.data_7);

            for (let i=0; i<Object.keys(selected_product.latest_pricing_histories).length; i++) {
                let data = selected_product.latest_pricing_histories[i];

                if (data.length != 0) {
                    pricing_history_data = '<tr>';
                    pricing_history_data += '<td>'+ data.po_number +'</td>';
                    pricing_history_data += '<td>'+ data.pricing_date +'</td>';
                    pricing_history_data += '<td>'+ data.price +'</td>';
                    pricing_history_data += '<td>'+ data.terms +'</td>';
                    pricing_history_data += '<td>'+ data.delivery +'</td>';
                    pricing_history_data += '<td>'+ data.fpd_reference +'</td>';
                    pricing_history_data += '<td>'+ data.wpc_reference +'</td>';
                    pricing_history_data += '</tr>';
                } else {
                    pricing_history_data = '<tr>';
                    pricing_history_data += '<td colspan="7">No records found...</td>';
                    pricing_history_data += '</tr>';
                }

                pricing_history_container.append(pricing_history_data);
            }

            // let files = selected_product.model_files;
            //console.log(selected_product.model_files);
            uploaded_files_container.empty();

            if (selected_product.model_files.length > 0) {
                _.forEach(selected_product.model_files, function(element, i) {
                    uploaded_files_container.append(element);
                });
            }
        });

        $("#add_product_btn").on('click', function () {
            let product_list = "";
            let product = $("#product_container option:selected");
            const remove_btn = "<div class='btn-group btn-group-sm pull-right' role='group' aria-label='Project Actions'><button class='btn btn-danger remove_product'><i class='fa fa-remove'></i></button></div>";

            if ( ! product.val()) {
                swal({
                    title: 'There is no selected product to add.',
                    confirmButtonText: 'Ok',
                    type: 'error'
                });
            } else {
                if ($.inArray(product.val(), product_id_list) != '-1') {
                    swal({
                        title: '"'+ product.text() +'" is already on the list.',
                        confirmButtonText: 'Ok',
                        type: 'info'
                    });

                    console.log('Product already exists.');
                    console.log(product_id_list);
                } else {
                    product_id_list.push(product.val());

                    product_list = "<tr data-value='"+ product.val() +"'>";
                    product_list += "<td style='line-height: 27px;'>"+ product.text() + remove_btn +"</td>";
                    // product_list += "<td>"+ remove_btn +"</td>";
                    product_list += "</tr>";

                    $("#product_list_container").append(product_list);
                }
            }
        });

        // Remove Product
        $(document).on('click', '.remove_product', function() {
            const remove_button = $(this);
            const selected_product = remove_button.closest('tr').data('value');

            swal({
                title: 'Are you sure you want to remove this product on the list?',
                showCancelButton: true,
                confirmButtonText: 'Remove',
                cancelButtonText: 'Cancel',
                type: 'warning'
            }).then(result => {
                if (result.value) {
                    _.remove(product_id_list, function(value) {
                       return value == selected_product;
                    });

                    remove_button.closest('tr').remove();

                    swal('Product was successfully removed from the list.', '', 'success');
                }
            });
        });

        // Pass the data to Create Proposal page
        $("#indented_btn").on('click', function () {
            if (product_id_list.length <= 0) {
                swal({
                    title: 'There are no added products to submit. Add at least 1 product.',
                    confirmButtonText: 'Ok',
                    type: 'warning'
                });
            } else {
                swal({
                    title: 'Are you sure you have confirmed the products on the list?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    type: 'warning'
                }).then(result => {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route("admin.product.add-product") }}',
                            data: {
                                _token: '{{ csrf_token() }}',
                                requests: product_id_list,
                                redirect_to: "{{ route('admin.indented-proposal.create') }}"
                            },
                            dataType: 'JSON',
                            success: function (data) {
                                window.location = data;
                            }
                        });
                    }
                });
            }
        });

        // Pass the data to Create Proposal page
        $("#buy_and_resale_btn").on('click', function () {
            if (product_id_list.length <= 0) {
                swal({
                    title: 'There are no added products to submit. Add at least 1 product.',
                    confirmButtonText: 'Ok',
                    type: 'warning'
                });
            } else {
                swal({
                    title: 'Are you sure you have confirmed the products on the list?',
                    showCancelButton: true,
                    confirmButtonText: 'Yes',
                    cancelButtonText: 'No',
                    type: 'warning'
                }).then(result => {
                    if (result.value) {
                        $.ajax({
                            type: 'POST',
                            url: '{{ route("admin.product.add-product") }}',
                            data: {
                                _token: '{{ csrf_token() }}',
                                requests: product_id_list,
                                redirect_to: "{{ route('admin.buy-and-resale-proposal.create') }}"
                            },
                            dataType: 'JSON',
                            success: function (data) {
                                window.location = data;
                            }
                        });
                    }
                })
            }
        });

        function clear_containers() {
            pricing_history_container.empty();
            uploaded_files_container.empty();
            product_container.empty();
            product_container.chosen();
            product_container.trigger('chosen:updated');
        }

        function clear_details() {
            spare_parts_description_container.empty();
            pump_details_container.empty();
        }

        function product_details(category_id) {
            let spare_parts_label = "",
            pump_details_label    = "";

            clear_details();

            if (category_id == 1) {
                // Spare Parts Label
                spare_parts_label = '<div class="animated fadeIn">';
                spare_parts_label += '<div class="form-group row">';
                spare_parts_label += '<label for="data_1" class="col-md-4 form-control-label">Status</label>';
                spare_parts_label += '<div class="col-sm-8">';
                spare_parts_label += '<input id="data_1" type="text" class="form-control" disabled/>';
                spare_parts_label += '</div>';
                spare_parts_label += '</div>';

                spare_parts_label += '<div class="form-group row">';
                spare_parts_label += '<label for="data_2" class="col-md-4 form-control-label">EPC</label>';
                spare_parts_label += '<div class="col-sm-8">';
                spare_parts_label += '<input id="data_2" type="text" class="form-control" disabled/>';
                spare_parts_label += '</div>';
                spare_parts_label += '</div>';

                spare_parts_label += '<div class="form-group row">';
                spare_parts_label += '<label for="data_3" class="col-md-4 form-control-label">Reference Number</label>';
                spare_parts_label += '<div class="col-sm-8">';
                spare_parts_label += '<input id="data_3" type="text" class="form-control" disabled/>';
                spare_parts_label += '</div>';
                spare_parts_label += '</div>';

                spare_parts_label += '<div class="form-group row">';
                spare_parts_label += '<label for="data_4" class="col-md-4 form-control-label">Final Result</label>';
                spare_parts_label += '<div class="col-sm-8">';
                spare_parts_label += '<input id="data_4" type="text" class="form-control" disabled/>';
                spare_parts_label += '</div>';
                spare_parts_label += '</div>';
                spare_parts_label += '</div>';

                // Pump Details
                pump_details_label = '<div class="animated fadeIn">';
                pump_details_label += '<div class="form-group row">';
                pump_details_label += '<label for="data_5" class="col-md-4 form-control-label">Serial Number</label>';
                pump_details_label += '<div class="col-sm-8">';
                pump_details_label += '<input id="data_5" type="text" class="form-control" disabled/>';
                pump_details_label += '</div>';
                pump_details_label += '</div>';

                pump_details_label += '<div class="form-group row">';
                pump_details_label += '<label for="data_6" class="col-md-4 form-control-label">Pump Model</label>';
                pump_details_label += '<div class="col-sm-8">';
                pump_details_label += '<input id="data_6" type="text" class="form-control" disabled/>';
                pump_details_label += '</div>';
                pump_details_label += '</div>';

                pump_details_label += '<div class="form-group row">';
                pump_details_label += '<label for="data_7" class="col-md-4 form-control-label">Tag Number</label>';
                pump_details_label += '<div class="col-sm-8">';
                pump_details_label += '<input id="data_7" type="text" class="form-control" disabled/>';
                pump_details_label += '</div>';
                pump_details_label += '</div>';
                pump_details_label += '</div>';

                spare_parts_description_container.append(spare_parts_label);
                pump_details_container.append(pump_details_label);
            } else if (category_id == 2) {
                // Spare Parts Label
                spare_parts_label = '<div class="animated fadeIn">';
                spare_parts_label += '<div class="form-group row">';
                spare_parts_label += '<label for="data_1" class="col-md-4 form-control-label">CCN Number</label>';
                spare_parts_label += '<div class="col-sm-8">';
                spare_parts_label += '<input id="data_1" type="text" class="form-control" disabled/>';
                spare_parts_label += '</div>';
                spare_parts_label += '</div>';

                spare_parts_label += '<div class="form-group row">';
                spare_parts_label += '<label for="data_2" class="col-md-4 form-control-label">Model</label>';
                spare_parts_label += '<div class="col-sm-8">';
                spare_parts_label += '<input id="data_2" type="text" class="form-control" disabled/>';
                spare_parts_label += '</div>';
                spare_parts_label += '</div>';

                spare_parts_label += '<div class="form-group row">';
                spare_parts_label += '<label for="data_3" class="col-md-4 form-control-label">Part Number</label>';
                spare_parts_label += '<div class="col-sm-8">';
                spare_parts_label += '<input id="data_3" type="text" class="form-control" disabled/>';
                spare_parts_label += '</div>';
                spare_parts_label += '</div>';

                spare_parts_label += '<div class="form-group row">';
                spare_parts_label += '<label for="data_4" class="col-md-4 form-control-label">Reference Number</label>';
                spare_parts_label += '<div class="col-sm-8">';
                spare_parts_label += '<input id="data_4" type="text" class="form-control" disabled/>';
                spare_parts_label += '</div>';
                spare_parts_label += '</div>';
                spare_parts_label += '</div>';

                // Pump Details
                pump_details_label = '<div class="animated fadeIn">';
                pump_details_label += '<div class="form-group row">';
                pump_details_label += '<label for="data_5" class="col-md-4 form-control-label">Serial Number</label>';
                pump_details_label += '<div class="col-sm-8">';
                pump_details_label += '<input id="data_5" type="text" class="form-control" disabled/>';
                pump_details_label += '</div>';
                pump_details_label += '</div>';

                pump_details_label += '<div class="form-group row">';
                pump_details_label += '<label for="data_6" class="col-md-4 form-control-label">SAP Number</label>';
                pump_details_label += '<div class="col-sm-8">';
                pump_details_label += '<input id="data_6" type="text" class="form-control" disabled/>';
                pump_details_label += '</div>';
                pump_details_label += '</div>';

                pump_details_label += '<div class="form-group row">';
                pump_details_label += '<label for="data_7" class="col-md-4 form-control-label">Tag Number</label>';
                pump_details_label += '<div class="col-sm-8">';
                pump_details_label += '<input id="data_7" type="text" class="form-control" disabled/>';
                pump_details_label += '</div>';
                pump_details_label += '</div>';
                pump_details_label += '</div>';

                spare_parts_description_container.append(spare_parts_label);
                pump_details_container.append(pump_details_label);
            } else {
                // Spare Parts Label
                spare_parts_label = '<div class="animated fadeIn">';
                spare_parts_label += '<div class="form-group row">';
                spare_parts_label += '<label for="data_1" class="col-md-4 form-control-label">Drawing Number</label>';
                spare_parts_label += '<div class="col-sm-8">';
                spare_parts_label += '<input id="data_1" type="text" class="form-control" disabled/>';
                spare_parts_label += '</div>';
                spare_parts_label += '</div>';

                spare_parts_label += '<div class="form-group row">';
                spare_parts_label += '<label for="data_2" class="col-md-4 form-control-label">BOM Number</label>';
                spare_parts_label += '<div class="col-sm-8">';
                spare_parts_label += '<input id="data_2" type="text" class="form-control" disabled/>';
                spare_parts_label += '</div>';
                spare_parts_label += '</div>';

                spare_parts_label += '<div class="form-group row">';
                spare_parts_label += '<label for="data_3" class="col-md-4 form-control-label">End User</label>';
                spare_parts_label += '<div class="col-sm-8">';
                spare_parts_label += '<input id="data_3" type="text" class="form-control" disabled/>';
                spare_parts_label += '</div>';
                spare_parts_label += '</div>';

                spare_parts_label += '<div class="form-group row">';
                spare_parts_label += '<label for="data_4" class="col-md-4 form-control-label">Seal Type</label>';
                spare_parts_label += '<div class="col-sm-8">';
                spare_parts_label += '<input id="data_4" type="text" class="form-control" disabled/>';
                spare_parts_label += '</div>';
                spare_parts_label += '</div>';
                spare_parts_label += '</div>';

                // Pump Details
                pump_details_label = '<div class="animated fadeIn">';
                pump_details_label += '<div class="form-group row">';
                pump_details_label += '<label for="data_5" class="col-md-4 form-control-label">Size</label>';
                pump_details_label += '<div class="col-sm-8">';
                pump_details_label += '<input id="data_5" type="text" class="form-control" disabled/>';
                pump_details_label += '</div>';
                pump_details_label += '</div>';

                pump_details_label += '<div class="form-group row">';
                pump_details_label += '<label for="data_6" class="col-md-4 form-control-label">Code</label>';
                pump_details_label += '<div class="col-sm-8">';
                pump_details_label += '<input id="data_6" type="text" class="form-control" disabled/>';
                pump_details_label += '</div>';
                pump_details_label += '</div>';

                pump_details_label += '<div class="form-group row">';
                pump_details_label += '<label for="data_7" class="col-md-4 form-control-label">Model</label>';
                pump_details_label += '<div class="col-sm-8">';
                pump_details_label += '<input id="data_7" type="text" class="form-control" disabled/>';
                pump_details_label += '</div>';
                pump_details_label += '</div>';
                pump_details_label += '</div>';

                spare_parts_description_container.append(spare_parts_label);
                pump_details_container.append(pump_details_label);
            }
        }
    });
</script>