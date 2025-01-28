@extends('company.layouts.app')
@section('style')
<style>
    .box_icon_custom{
        font-size: 42px !important;
        color: #313a46 !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12 text-start">
            <h3 class="py-2 mb-2">
                <span class="text-primary fw-light">Dashboard</span>
            </h3>
        </div>
        <hr>
        <div class="col-md-12 text-start">
            <h5 class="py-2 mb-2">
                <span class="text-primary fw-light">Master</span>
            </h5>
        </div>
        <div class="row">
            <div class="col-sm-3 text-center">
                <a href="{{ route('company.variation.index') }}">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class='bx bx-category box_icon_custom'></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Category</h5>
                            <h3 class="mt-0 mb-0">{{ $categoryCount }}</h3>
                        </div>
                        <!-- end card-body-->
                    </div>
                </a>
                <!-- end card-->
            </div>
            <!-- end col-->

            <div class="col-sm-3 text-center">
                <a href="{{ route('company.item.index') }}">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class='bx bxl-product-hunt box_icon_custom'></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Item</h5>
                            <h3 class="mt-0 mb-0">{{ $itemCount }}</h3>
                        </div>
                        <!-- end card-body-->
                    </div>
                </a>
                <!-- end card-->
            </div>
            <!-- end col-->

            <div class="col-sm-3 text-center">
                <a href="{{ route('company.vendor.index') }}">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class='bx bx-user box_icon_custom'></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Vendor</h5>
                            <h3 class="mt-0 mb-0">{{ $vendorCount }}</h3>
                        </div>
                        <!-- end card-body-->
                    </div>
                </a>
                <!-- end card-->
            </div>
            <!-- end col-->

            <div class="col-sm-3 text-center">
                <a href="{{ route('company.customer.index') }}">
                    <div class="card widget-flat">
                        <div class="card-body">
                            <div class="float-end">
                                <i class='bx bx-user box_icon_custom'></i>
                            </div>
                            <h5 class="text-muted fw-normal mt-0" title="Number of Customers">Customer</h5>
                            <h3 class="mt-0 mb-0">{{ $customerCount }}</h3>
                        </div>
                        <!-- end card-body-->
                    </div>
                </a>
                <!-- end card-->
            </div>
            <!-- end col-->
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection
