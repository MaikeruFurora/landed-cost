@extends('../_layout/app')
@section('content')
<div class="account-pages">
            <div class="container">
                <div class="row justify-content-center" style="display: flex;align-items: center;align-content: center;height:50vh">
                    <div class="col-lg-7">
                        <div class="text-center mb-5">
                            <div class="mb-5">
                                <img src="{{ asset('assets/images/landed-icon-black.png') }}" height="32" alt="logo">
                            </div>
                            <h4 class="mt-4">505 PAGE NOT FOUND</h4>
                            <p>505 is a error that is signaled by browser when HTTP version is not supported by server i.e. when server not able to identify the HTTP protocol version used in request.</p>
                            <a href="{{ route('authenticate.details') }}" class="btn btn-primary btn-sm">Back home</a>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
        </div>
@endsection