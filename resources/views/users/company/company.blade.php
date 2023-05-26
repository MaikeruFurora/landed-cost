@extends('../_layout/app')
@section('moreCss')
    <!-- DataTables -->
    <link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- Responsive datatable examples -->
    <link href="{{ asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <style>
         .table>thead>tr>th, .table>tbody>tr>th, .table>tfoot>tr>th, .table>thead>tr>td, .table>tbody>tr>td, .table>tfoot>tr>td{
            padding: 1px;
        }
    </style>
@endsection
@section('content')
<x-page-title title="Company Profile"/>
<div class="row">
    <div class="col-lg-7 col-sm-8 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                <table id="datatable" class="table table-sm table-bordered" width="100%">
                    <thead class="bg-secondary text-white">
                    <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                        <td>Status</td>
                        <td>Company Name</td>
                        <td>Acronym</td>
                        <td>Created at</td>
                        <td>Action</td>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($companies as $company)
                        <tr>
                            <td>{{ $company->companyStatus }}</td>
                            <td>{{ $company->companyAddress }}</td>
                            <td>{{ $company->tinNo }}</td>
                            <td>{{ $company->registrationDate }}</td>
                            <td>
                                <span class="badge badge-{{ $company->companyStatus?'success':'danger' }} pb-1" style="font-size: 11px">{{ $company->companyStatus?'Active':'Inactive' }}</span></td>
                            <td>{{ $company->companyname }}</td>
                            <td>{{ $company->acronym }}</td>
                            <td>{{ $company->created_at->format("F d, Y") }}</td>
                            <td class="p-0 text-center">
                                <div class="btn-group btn-group-sm" role="group" style="cursor:pointer">
                                    <button type="button" class="btn btn-secondary btn-sm dropdown-toggle" style="font-size:11px" data-toggle="dropdown" aria-expanded="false">
                                        Options&nbsp;&nbsp;
                                    </button>
                                    <div class="dropdown-menu" style="font-size:12px">
                                        <a class="dropdown-item btnEdit" id="{{ $company->id }}"><i class="fas fa-edit"></i> Edit Company</a>
                                        <a class="dropdown-item bankInfo" id="{{ $company->id }}"><i class="fas fa-university"></i> Bank Details</a>
                                    </div>
                                </div>
                            </td>
                        </tr>   
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>
        </div>

        {{--  --}}

        <div class="card">
            <div class="card-header">
                <div class="row justify-content-between">
                    <div class="col-10">
                        <span class="bdCompanyName font-weight-bolder"></span>
                    </div>
                    <div class="col-2 text-right font-16">
                        <i class="fas fa-university text-primary addBank mr-2"></i>
                        <i class="fas fa-times text-danger clearBankDetails"></i>
                    </div>
                  </div>
            </div>
            <div class="card-body">
                <input type="hidden" name="bankId">
                <p class="badge badge-info mb-0"></p>
                <form id="bankForm" autocomplete="off">
                    <input type="hidden" name="company_id">
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control" placeholder="Bank name" aria-label="Bank name" maxlength="30" name="bankName" required>
                        <input type="text" class="form-control" placeholder="Acronym" aria-label="Acronym" maxlength="30" name="acronym" required>
                        <div class="input-group-append">
                            <button class="btn btn-success pl-3 pr-3" type="submit" name="bankSave"><i class="fas fa-plus"></i></button>
                            <button class="btn btn-warning pl-3 pr-3" type="button" name="bankCancel"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </form>
                <form id="branchForm" autocomplete="off">
                    <input type="hidden" name="branchId">
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" class="form-control" placeholder="Branch" aria-label="Branch" maxlength="30" name="branchName" required>
                        <div class="input-group-append">
                            <button class="btn btn-success pl-3 pr-3" type="submit" name="branchSave"><i class="fas fa-plus"></i></button>
                            <button class="btn btn-warning pl-3 pr-3" type="button" name="branchCancel"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </form>
                <form id="accountForm" autocomplete="off">
                    <input type="hidden" name="accountId">
                    <div class="input-group input-group-sm mb-3">
                        <input type="text" pattern="\d*" maxlength="12" class="form-control" placeholder="Account No." aria-label="Account No." name="accountNo" required>
                        <select name="currencyType" id="" class="form-control form-control-sm">
                            @foreach (Helper::$currencyType as $item)
                                <option value="{{ ($item['acronym']) }}">{{ ($item['acronym']) }}</option>
                            @endforeach
                        </select>
                        <div class="input-group-append">
                            <button class="btn btn-success pl-3 pr-3" type="submit" name="accountSave"><i class="fas fa-plus"></i></button>
                            <button class="btn btn-warning pl-3 pr-3" type="button" name="accountCancel"><i class="fas fa-times"></i></button>
                        </div>
                    </div>
                </form>
                <table class="table table-bordered tableBank" style="font-size:11px">
                   <thead>
                    <tr>
                        <td>Bank Name</td>
                        <td>Acronym</td>
                        <td class="text-center">Action</td>
                    </tr>
                   </thead>
                   <tbody>
                        <tr class="text-center font-weight-bold"><td colspan="3">No data available</td></tr>
                   </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-lg-5 col-md-12 col-sm-12">
        <div class="card">
            <div class="card-header">Company Profile</div>
            <div class="card-body">
                <form action="{{ route('authenticate.company.store') }}" method="POST" autocomplete="off">@csrf
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label for="companyname">Company Name</label>
                        <input type="text" class="form-control form-control-sm" maxlength="50" id="companyname" name="companyname" required>
                        @error('companyname')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-row">
                        <div class="form-group col-6">
                            <label for="companyacronym">Acronym</label>
                            <input type="text" class="form-control form-control-sm" maxlength="10" id="companyacronym" name="companyacronym">
                            @error('companyacronym')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="form-group col-6">
                            <label for="tinNo">TIN No.</label>
                            <input type="text" class="form-control form-control-sm" maxlength="15" id="tinNo" name="tinNo">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="registrationDate">Registered Date</label>
                        <input type="text" class="form-control form-control-sm" maxlength="10" id="registrationDate" name="registrationDate">
                    </div>
                    <div class="form-group">
                        <label for="companyAddress">Address</label>
                        <textarea name="companyAddress" class="form-control" cols="100" rows="3"></textarea>
                    </div>
                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" name="companystatus" id="exampleCheck1">
                        <label class="form-check-label" for="exampleCheck1">Active</label>
                    </div>
                    <div class="row">
                        <div class="col-lg-6 col-md-12"><button type="submit" class="btn btn-primary btn-block btn-sm">Submit</button></div>
                        <div class="col-lg-6 col-md-12"><span class="cancel-btn"></span></div>
                    </div>
                </form>
            </div>
        </div>
        <!---->
       
    </div>
</div>
@endsection
@section('moreJs')
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/js/company-detail.js') }}"></script>
@endsection
