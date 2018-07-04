@extends ('backend.layouts.app')

@section ('title', __('labels.backend.projects.management') . ' | ' . __('labels.backend.projects.create'))

@section('breadcrumb-links')
    @include('backend.project.includes.breadcrumb-links')
@endsection

@section('content')
<div class="form-horizontal">
    <div class="card-group">
        <div class="card" style="flex: none;">
            <div class="card-header bg-dark text-white">
                <h4 class="card-title mb-0">
                    Product
                </h4> <!-- card-title -->
            </div> <!-- card-header -->
            <div class="card-body">
                <h5 class="card-title">Choose Product</h5>
                <hr>
                <div class="row mt-4 mb-4">
                    <div class="col">
                        <div class="form-group row">
                            {{ html()->label('Product Category')->class('col-md-4 form-control-label')->for('po_number') }}
                            <div class="col-sm-8">
                                <select name="product-category" id="product-category" class="form-control" data-placeholder="-- Select Product Category --">
                                    <option value=""></option>
                                    <option value="1">Project</option>
                                    <option value="2">Aftermarket</option>
                                    <option value="3">Seal</option>
                                </select>
                            </div>
                        </div><!--form-group-->
                        <div class="form-group row">
                            <label for="product-name" class="col-md-4 form-control-label">Product Name</label>
                            <div class="col-sm-8">
                                <select name="product" id="product_container" class="form-control" data-placeholder="-- Select Product Category First --" disabled>
                                    <option value=""></option>
                                </select>
                            </div>
                        </div><!--form-group-->
                        <button class="btn btn-dark pull-right" id="add_product_btn">Add Product</button>
                    </div><!--col-->
                </div><!--row-->
                <!-- Pricing History -->
                <h5 class="card-title">Pricing History</h5>
                <div class="row mt-4 mb-4">
                    <div class="col">
                        <table class="table table-striped table-responsive">
                            <thead>
                                <th>PO Number</th>
                                <th>Pricing Date</th>
                                <th>Price</th>
                                <th>Terms</th>
                                <th>Delivery</th>
                                <th>FPD Reference</th>
                                <th>WPC Reference</th>
                            </thead>

                            <tbody id="pricing_history_container">
                                <tr>
                                    <td colspan="7"><p class="text-center">No description to show...</p></td>
                                </tr>
                            </tbody>
                        </table>
                    </div><!-- col -->
                </div><!-- row -->

                <!-- Uploaded Files -->
                <h5 class="card-title">Uploaded Files</h5>
                <hr>

                <div class="row mt-4 mb-4">
                    <div class="col-lg-12">
                        <table class="table table-bordered">
                            <thead>
                                <th>Uploded File</th>
                            </thead>

                            <tbody id="uploaded_files_container">
                                <tr>
                                    <td><p class="text-center">No uploaded files to show...</p></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Spare Parts Description -->
                <h5 class="card-title">Spare Parts Description</h5>
                <hr>

                <div class="row mt-4 mb-4">
                    <div class="col">
                        <div id="spare_parts_description_container">
                            <p class="text-center">No description to show</p>
                        </div>
                    </div> <!-- col -->
                </div> <!-- row -->

                <!-- Pump Details -->
                <h5 class="card-title">Pump Details</h5>
                <hr>

                <div class="row mt-4 mb-4">
                    <div class="col">
                        <div id="pump_details_container">
                            <p class="text-center">No description to show</p>
                        </div> <!-- #pump-details-container -->
                    </div> <!-- col -->
                </div> <!-- row -->

            </div><!--card-body-->
        </div><!--card-->

        <div class="card">
            <div class="card-header bg-dark text-white">
                <h4 class="card-title mb-0">
                    Added Products
                </h4> <!-- card-title -->
            </div> <!-- card-header -->

            <div class="card-body">
                <table class="table table-bordered">
                    <thead>
                        <th>Product</th>
                    </thead>

                    <tbody id="product_list_container">
                    </tbody>
                </table>
            </div><!--card-body-->
        </div><!--card-->
    </div><!-- row -->
</div>

<div class="card">
    <div class="card-footer">
        <div class="row">
            <div class="col">
                <a href="{{ $parent_page }}" class="btn btn-danger">Cancel</a>
            </div><!--col-->

            <div class="col text-right">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Create
                    </button>
                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                        <a href="#" class="dropdown-item" id="indented_btn">Indented Proposal</a>
                        <a href="#" class="dropdown-item" id="buy_and_resale_btn">Buy & Resale Proposal</a>
                    </div>
                </div>
            </div><!--col-->
        </div><!--row-->
    </div>
</div>
@endsection

@push('after-scripts')
    @include('backend.product.scripts')
@endpush