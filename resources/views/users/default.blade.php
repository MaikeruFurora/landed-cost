@extends('../_layout/app')
@section('content')
<div class="account-pages">
            <div class="container">
                <div class="row justify-content-center" style="display: flex;align-items: center;align-content: center;height:30vh">
                    <div class="col-lg-7">
                        <div class="text-center mb-5">
                            <div class="mb-3">
                                <img src="{{ asset('assets/images/favicon.ico') }}" height="70" alt="logo">
                            </div>
                            <h4 class="mt-2">ACCESS DENIED!</h4>
                            <p>" HTTP 403 is an HTTP status code meaning access to the requested resource is forbidden. The server understood the request, but will not fulfill it."</p>
                            <div class="mt-4 ">
                                <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>Perhaps you aren't allowed to view that page.</p>
                                <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>If you continue to receive an access denied, please contact IT for assistance. </p>
                                <p><i class="mdi mdi-arrow-right text-primary mr-2"></i>Use the <a target="_blank" href="http://202.57.58.188:81/portal">AIMI SERVICE DESCK PORTAL</a> to submit a support request</p>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- end row -->
            </div>
        </div>
@endsection