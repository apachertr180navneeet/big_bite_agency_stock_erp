@extends('company.layouts.app')
@section('style')
<style>
    /* Premium Dashboard Styles */
    .dashboard_text_heading {
        background: linear-gradient(135deg, #272757 0%, #696cff 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        font-weight: 800 !important;
        letter-spacing: -0.5px;
    }

    .dasbord_card {
        border: none;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        position: relative;
        z-index: 1;
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.08);
        text-decoration: none !important;
        display: block;
    }
    
    .dasbord_card::before {
        content: '';
        position: absolute;
        top: 0; left: 0; right: 0; bottom: 0;
        z-index: -1;
        transition: opacity 0.3s ease;
        opacity: 0.9;
    }

    .dasbord_card:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 30px rgba(0, 0, 0, 0.15);
    }

    .dasbord_card:hover::before {
        opacity: 1;
    }

    /* Gradients for cards */
    .card-grad-1::before { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); }
    .card-grad-2::before { background: linear-gradient(135deg, #2af598 0%, #009efd 100%); }
    .card-grad-3::before { background: linear-gradient(135deg, #f5576c 0%, #f093fb 100%); }
    .card-grad-4::before { background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%); }

    .box_icon_custom {
        font-size: 52px !important;
        color: rgba(255, 255, 255, 0.7) !important;
        transition: all 0.3s ease;
        margin-bottom: 10px;
    }

    .dasbord_card:hover .box_icon_custom {
        transform: scale(1.1) rotate(5deg);
        color: rgba(255, 255, 255, 1) !important;
        text-shadow: 0 0 15px rgba(255,255,255,0.4);
    }

    .card-title-custom {
        color: rgba(255, 255, 255, 0.85) !important;
        font-weight: 600 !important;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        font-size: 0.85rem;
        margin-bottom: 4px !important;
    }

    .dashboard_count {
        color: #ffffff;
        font-weight: 800;
        font-size: 2.5rem;
        letter-spacing: -1px;
    }

    /* Animation on load */
    .fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
        opacity: 0;
        transform: translateY(20px);
    }

    .delay-1 { animation-delay: 0.1s; }
    .delay-2 { animation-delay: 0.2s; }
    .delay-3 { animation-delay: 0.3s; }
    .delay-4 { animation-delay: 0.4s; }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    
    .card-body-custom {
        padding: 1.8rem !important;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }
    
    a.text-decoration-none {
        text-decoration: none !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12 text-start">
            <h3 class="py-3 mb-3">
                <span class="text-primary fw-light dashboard_text_heading">Dashboard Overview</span>
            </h3>
        </div>
        <div class="row mt-2">
            <!-- Category Card -->
            <div class="col-sm-6 col-lg-3 mb-4 text-center fade-in-up delay-1">
                <a href="{{ route('company.variation.index') }}" class="text-decoration-none">
                    <div class="card widget-flat dasbord_card card-grad-1">
                        <div class="card-body card-body-custom">
                            <i class='bx bx-category box_icon_custom'></i>
                            <h5 class="card-title-custom mt-0" title="Number of Categories">Category</h5>
                            <h3 class="mt-0 mb-0 dashboard_count">{{ $categoryCount }}</h3>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Item Card -->
            <div class="col-sm-6 col-lg-3 mb-4 text-center fade-in-up delay-2">
                <a href="{{ route('company.item.index') }}" class="text-decoration-none">
                    <div class="card widget-flat dasbord_card card-grad-2">
                        <div class="card-body card-body-custom">
                            <i class='bx bxl-product-hunt box_icon_custom'></i>
                            <h5 class="card-title-custom mt-0" title="Number of Items">Item</h5>
                            <h3 class="mt-0 mb-0 dashboard_count">{{ $itemCount }}</h3>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Vendor Card -->
            <div class="col-sm-6 col-lg-3 mb-4 text-center fade-in-up delay-3">
                <a href="{{ route('company.vendor.index') }}" class="text-decoration-none">
                    <div class="card widget-flat dasbord_card card-grad-3">
                        <div class="card-body card-body-custom">
                            <i class='bx bxs-truck box_icon_custom'></i>
                            <h5 class="card-title-custom mt-0" title="Number of Vendors">Vendor</h5>
                            <h3 class="mt-0 mb-0 dashboard_count">{{ $vendorCount }}</h3>
                        </div>
                    </div>
                </a>
            </div>

            <!-- Customer Card -->
            <div class="col-sm-6 col-lg-3 mb-4 text-center fade-in-up delay-4">
                <a href="{{ route('company.customer.index') }}" class="text-decoration-none">
                    <div class="card widget-flat dasbord_card card-grad-4">
                        <div class="card-body card-body-custom">
                            <i class='bx bx-user-pin box_icon_custom'></i>
                            <h5 class="card-title-custom mt-0" title="Number of Customers">Customer</h5>
                            <h3 class="mt-0 mb-0 dashboard_count">{{ $customerCount }}</h3>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection
