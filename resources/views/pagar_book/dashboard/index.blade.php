@extends('pagar_book.layouts.app')
@section('style')
<style>
    .box_icon_custom{
        font-size: 42px !important;
        color: #ffffff !important;
    }
    .dasbord_card{
        background-color: #272757;
    }
    .text-muted {
        color: #fff !important;
        font-weight: 900 !important;
    }
    .dashboard_count{
        color: #fff;
    }
    .dashboard_text_heading{
        color: #272757 !important;
        font-weight: bolder !important;
    }
</style>
@endsection

@section('content')
<div class="container-fluid flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-md-12 text-start">
            <h3 class="py-2 mb-2">
                <span class="text-primary fw-light dashboard_text_heading">Dashboard</span>
            </h3>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection
