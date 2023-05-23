@extends('../_layout/app')
@section('moreCss')
    <style>
        label{
            font-size: 11px;
        }
    </style>
@endsection
@section('content')
    <!-- Page-Title -->
    <x-page-title title="{{ $title }}">
        <a class="btn btn-primary btn-sm" href="{{ route('authenticate.user') }}">
            <i class="ti-arrow-left mr-1"></i> Back
        </a>
    </x-page-title>
    <!-- end page title end breadcrumb -->
    @if (session()->has('msg'))
        <div class="alert alert-{{ session()->get('action') ?? 'success' }}" role="alert">
        <i class="fas fa-check-circle"></i> {{ session()->get('msg') }}
        </div>
    @endif
<form method="POST" action="{{ route('authenticate.user.store') }}" autocomplete="off">@csrf
   <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-12">
            <div class="card">
            <div class="card-body">
                    <input type="hidden" name="id" value="{{ $user->id ?? '' }}">
                        <div class="row">
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="nameInput">Name</label>
                                    <input type="text" class="form-control form-control-sm" id="nameInput" value="{{ $user->name ??  old('name') }}" autofocus autocomplete="off" name="name">
                                    @error('name')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="usernameInput">Username</label>
                                    <input type="text" class="form-control form-control-sm" id="usernameInput" value="{{  $user->username ?? old('username') }}" autocomplete="off" name="username">
                                    @error('username')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="emailInput">Email</label>
                                    <input type="email" class="form-control form-control-sm" id="emailInput" value="{{  $user->email ?? old('email') }}" autocomplete="off" name="email">
                                    @error('email')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="passwordInput">Password</label>
                                    <input type="password" class="form-control form-control-sm" id="passwordInput" autocomplete="off" name="password">
                                    @error('password')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group">
                                    <label for="passwordInput">Confirm Password</label>
                                    <input type="password" class="form-control form-control-sm" id="passwordInput" autocomplete="off" name="password_confirmation">
                                    @error('confirm-password')
                                        <small class="form-text text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                
                            </div>
                        </div>
            
                        <!-- <div class="form-group form-check">
                            <input type="checkbox" class="form-check-input" id="exampleCheck1">
                            <label class="form-check-label" for="exampleCheck1">Check me out</label>
                        </div> -->
                        <button type="submit" class="btn btn-sm btn-primary mt-3"><i class="fas fa-save"></i>&nbsp;&nbsp;
                            @if($user->id ?? null) Update @else Submit @endif
                        </button>
                </div>
            </div>

        </div>
        <div class="col-lg-4 col-md-4 col-sm-12">
            <div class="card">
                <div class="card-header">
                    User Rights | Privilege
                </div>
                <div class="card-body">
                    <div class="row">
                        @forelse($particular as $key => $data)
                            <div class="col-lg-6 col-md-6 col-sm-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input"
                                            type="checkbox"
                                            name="particular[]"
                                            value="{{ $data->id }}"
                                            id="defaultCheck{{ $data->id }}"

                                            @if(isset($user))
                                                @foreach($user->privileges  as $prvlg)
                                                    {{ $prvlg->particular_id==$data->id?'checked':'' }}
                                                @endforeach
                                            @endif

                                            >
                                    <label class="form-check-label" for="defaultCheck{{ $data->id }}">
                                        {{ $data->p_name }}
                                    </label>
                                </div>
                            </div>
                        @empty
                        <div class="col-12 mb-3 text-center">
                            <p class="mt-4 text-muted">No Data Available</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    Other Previlege
                </div>
                <div class="card-body">
                <ul class="list-group">
                    @foreach(Helper::$otherPrev as $value)
                    <li class="list-group-item">
                        <div class="form-check">
                            <input 
                                class="form-check-input"
                                type="checkbox"
                                name="otherPrivelege[]"
                                value="{{ $value }}" id="{{ $value }}-check"
                                @if(isset($user) && is_array($user->other_prev))
                                    @foreach($user->other_prev  as $op)
                                        {{ $op==$value?'checked':'' }}
                                    @endforeach
                                @endif>
                            <label class="form-check-label" for="{{ $value }}-check">
                                {{ $value }}
                            </label>
                        </div>
                    </li>
                    @endforeach
                </ul>
                </div>
            </div>
        </div>
   </div>
</form>
@endsection