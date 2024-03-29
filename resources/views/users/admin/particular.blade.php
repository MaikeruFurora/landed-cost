@extends('../_layout/app')
@section('moreCss')
<link rel="stylesheet" href="//code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

@endsection
@section('content')
    <!-- Page-Title -->
    <x-page-title title="particular">
        <a class="btn btn-primary btn-sm" href="{{ url()->previous() }}">
            <i class="ti-arrow-left mr-1"></i> Back
        </a>
    </x-page-title>
    <!-- end page title end breadcrumb -->
  
    <div class="row">
    <div class="col-lg-8 col-md-8 col-sm-12">
    <div class="card">
        <div class="card-body">
        <h4 class="mt-0 header-title">List</h4>
            <p class="text-muted m-b-30">Drag & drop hierarchical list with mouse and touch compatibility </p>
            <ul id="sortable" class="list-unstyled list-unstyled">
                @forelse($data as $key => $value)
                <li id="item-{{ $value->id }}" class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $value->p_code.' - '.$value->p_name }}
                    <div class="btn-group" role="group" aria-label="Basic example">
                        @if($value->action)
                            <button type="button" class="btn btn-sm btn-dark"><i class="fas fa-check-circle text-success"></i></button>
                        @endif
                            <button type="button" name="edit" value="{{ $value->id }}" class="btn btn-primary btn-sm" style="font-size:10px;"><i class="far fa-edit"></i> Edit</button>
                    </div>
                </li>
                @empty
                <li id="item" class="list-group-item d-flex justify-content-between align-items-center">
                    NO DATA AVAILABLE
                 </li>
                @endforelse
            </ul>
        </div>
   </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card">
            <div class="card-header">
                Create From
            </div>
            <div class="card-body">
              <form method="POST" action="{{ route('authenticate.particular.store') }}" autocomplete="off">@csrf
              <div class="form-group">
                    <label for="nameInput">Name</label>
                    <input type="hidden" name="id" value="">
                    <input type="hidden" name="sort" value="">
                    <input type="hidden" name="strOrpdt" value="store">
                    <input type="text" class="form-control form-control-sm" id="nameInput" name="name" autofocus required>
                    @error('name')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="nameInput">Code</label>
                    <input type="text" class="form-control form-control-sm" id="nameInput" name="code" required>
                    @error('p_code')
                        <small class="form-text text-danger">{{ $message }}</small>
                    @enderror
                </div>
                <div class="form-group">
                   <div class="row">
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <button type="submit" class="my-2 btn btn-sm btn-block btn-primary">Submit</button>
                        </div>
                        <div class="col-lg-6 col-md-12 col-sm-12">
                            <button type="button" name="btnCancel" class="my-2 btn btn-sm btn-block btn-warning">Cancel</button>
                        </div>
                   </div>
                </div>
              </form>
            </div>
        </div>
        @if (session()->has('msg'))
            <div class="alert alert-{{ session()->get('action') ?? 'success' }}" role="alert">
                {{ session()->get('msg') }}
            </div>
        @endif
        @error('code')
            <div class="alert alert-danger" role="alert">
                {{ $errors->first('code') }}
            </div>
        @enderror
    </div>
    </div>
@endsection
@section('moreJs')
<!-- <script src="https://code.jquery.com/jquery-3.6.0.js"></script> -->
  <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
  <script src="{{ asset('assets/js/admin/particular.js') }}"></script>
@endsection