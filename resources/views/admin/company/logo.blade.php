@extends('admin.layouts.app')
@section('content')
<style>
    .thumb-image {
        height: 50px;
        width: 50px;
        border: 1px solid lightgray;
        padding: 1px;
    }
    .header-title {
        text-transform: capitalize;
        font-size:;
    }
</style>
<div class="container-fluid flex-grow-1 container-p-y">
    <h5 class="py-2 mb-2">
        <span class="text-primary fw-light">Company Logo</span>
    </h5>
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <form role="form" action="{{ route('admin.company.update.logo') }}" method="post" id="coustomer_add" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" value="{{ $comId }}" name="id" id="">

                                <!-- Display Validation Errors -->
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                                <div class="mb-3">
                                    <label for="formFile" class="form-label">Upload Logo</label>
                                    <input class="form-control" type="file" name="image" id="formFile">
                                </div>
                                <button type="submit" class="btn btn-primary" id="AddComapany">Save</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
@endsection
