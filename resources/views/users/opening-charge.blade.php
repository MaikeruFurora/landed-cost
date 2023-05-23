@extends('../_layout/app')
@section('moreCss') 
     <!-- DataTables -->
     <link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
     <link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
     <!-- Sweet Alert -->
     <link href="{{ asset('plugins/sweet-alert2/sweetalert2.css') }}" rel="stylesheet" type="text/css">
    <!-- Responsive datatable examples -->
    <link href="{{ asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
<!-- Page-Title -->
    <x-page-title title="L/C Opening Charge">
        <a class="btn btn-primary btn-sm" href="{{ url()->previous() }}">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </x-page-title>
<!-- end page title end breadcrumb -->
<!-- Alert Start -->
@if (session()->has('msg'))
    <div class="alert alert-{{ session()->get('action') ?? 'success' }}" role="alert">
        @if(session()->has('icon'))
            {{ session()->get('icon') }}
        @else
            <i class="far fa-check-circle"></i>
        @endif
        {{ session()->get('msg') }}
    </div>
@endif
<div class="row">
    <div class="col-lg-9 col-md-9 col-sm-12">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive mt-2">
                <table cellpadding="0" cellspacing="0" id="datatable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:11px">
                <thead class="thead-dark">
                        <tr>
                            <th>ID(s)</th>
                            <th>Amount</th>
                            <th>Metric Ton</th>
                            <th>Referece No</th>
                            <th>Invoice</th>
                            <th>Invoice MT</th>
                            <th class="text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3 col-md-3 col-sm-12">
        <div class="card">
            <div class="card-header">
                LC Opening Charge
            </div>
            <div class="card-body">
                <form method="POST" id="formAmount" action="{{ route('authenticate.opening.store') }}" autocomplete="off">@csrf
                    <input type="hidden" name="id">
                    <div class="form-group">
                        <label for="">Total Amount</label>
                        <input type="text" class="needFormat form-control" id="" required name="amount">
                    </div>
                    <div class="form-group">
                        <label for="">Total Metric Ton</label>
                        <input type="text" class="needFormat form-control" id="" required name="mt">
                    </div>
                    <div class="form-group">
                        <label for="">Reference</label>
                        <input type="text" class="form-control" id="" required name="reference">
                    </div>
                    <button type="submit" class="btn btn-primary btn-block">Submit</button>
                    <button type="button" class="btnCancel btn btn-warning btn-block">Cancel</button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- modal -->
    <!-- Modal -->
    <div class="modal fade" id="searchInvoiceModal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="searchInvoiceModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title" id="searchInvoiceModalLabel">Title here</h6>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
               <form id="formSearchInvoice">
                    <div class="input-group mb-2">
                        <input type="text" class="form-control" placeholder="Recipient's username" name="search">
                        <div class="input-group-append">
                            <input type="hidden" name="id_lcopc">
                            <select name="whse" class="custom-select">
                                <option value="manila">Manila</option>
                                <option value="province">Province</option>
                            </select>
                            <button class="btn btn-secondary" type="submit" id="button-addon2">Search</button>
                        </div>
                    </div>
               </form>
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <td>Po Number</td>
                            <td>Description</td>
                            <td>Inv No.</td>
                            <td>Qty(MT)</td>
                            <td>FCL</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody class="showData">
                        <tr class="header text-center">
                            <td colspan="11">No data available</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            </div>
        </div>
    </div>
    <!-- modal-end -->

</div><!-- row -->
@endsection
@section('moreJs')
    <!-- Required datatable js -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jquery-number/jquery.number.js') }}"></script>
    <script src="{{ asset('plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
    <script>
        $('input[name="search"]').on('input', function() {
            let res = /^[^'`"]*$/.test($(this).val());
            res?$(this).val($(this).val()): $(this).val('')
        });
        $('input[name="reference"]').on('input', function() {
            let res = /^[^'`"]*$/.test($(this).val());
            res?$(this).val($(this).val()): $(this).val('')
        });
        const invoiceID     = []
        let openAmountId  = $("input[name=id_lcopc]")
        //  $(function(){
            $('.needFormat').number( true, 2 );
        // })
            let tableOpenAmount = $('#datatable').DataTable({
                "serverSide": true,
                // pageLength: 5,
                paging:true,
                "ajax": {
                    url: "charge/list", 
                    method: "get"
                },
                order: [[0, 'desc']],
                columns:[
                    {
                        data: "updated_at",
                        target: 0,
                        visible: false,
                        searchable: false
                    },
                    {
                        orderable: false,
                        data:null,
                        render:function(data){
                            return `&#8369; `+data.lc_amount
                        }
                    },
                    {   
                         orderable: false,
                         data:"lc_mt"},
                    {   
                         orderable: false,
                         data:"lc_reference"},
                    // {data:"created_at"},
                    {
                        orderable: false,
                        data:null,
                        render:function(data){
                            let hold=``;
                            hold+='<ul class="list-group">'
                            data.lcopening_charge.forEach(val=>{
                                hold+=`
                                <li class="list-group-item d-flex justify-content-between align-items-center p-1"><a href='/landed-cost/public/auth/details/cost/${val.detail.id}'>${val.detail.invoiceno}</a>
                                    <button class="btn btn-sm" value="${val.id}" name="removeInvoice"><i class="text-danger fas fa-times-circle"></i></button>
                                </li>`
                            })
                            hold+='</ul>'

                            return hold
                        }
                    },
                    {
                        orderable: false,
                        data:null,
                        render:function(data){

                            if(data.lcopening_charge.length>0){
                                let hold=``;
                                let sum=0;
                                hold+='<ul class="list-group">'
                                data.lcopening_charge.forEach(val=>{
                                    hold+=`
                                    <li class="list-group-item d-flex justify-content-between align-items-center p-1">
                                        <i class="fas fa-plus ml-2" style="font-size:9px"></i>${val.detail.qtymt}
                                    </li>`
                                    sum+=parseFloat(val.detail.qtymt)
                                })
                                hold+=`<li class="list-group-item d-flex justify-content-between align-items-center p-1">
                                        Total<i class="fas fa-equals" style="font-size:9px"></i> ${sum}
                                        </li>`
                                if (data.lc_mt!=sum) {
                                hold+=`<li class="list-group-item d-flex align-items-center p-1 text-danger">
                                        <i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;Mismatch MT
                                        </li>`
                                }
                                        hold+='</ul>'

                                return hold
                            }else{

                                return '';
                            }
                           
                        }
                    },
                    {
                        orderable:false,
                        data:null,
                        render:function(data){
                            return `
                                <button value="${data.id}" class="m-1 btnEdit btn btn-primary btn-sm"><i class="far fa-edit"></i> Edit</button>
                                
                                <button value="${data.lc_reference}" id="${data.id}" class="m-1 btn btn-primary btn-sm btnAddInvoice"><i class="fas fa-plus-circle"></i> Invoice</button>
                            `
                            //<a href="charge/invoice/${data.id}" class="m-1 btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> Invoice</a>
                        }
                    },
                ]
            });

            $(".btnCancel").hide()
            $(".btnCancel").on('click',function(){
                $(this).hide()
                $("#formAmount")[0].reset()
            })

            $(document).on('click','.btnEdit',function(e){
                e.preventDefault()
                var row = $("#datatable").DataTable().row($(this).closest('tr'));
                $(".btnCancel").show()
                $("input[name=id]").val(row.data()['id'])
                $("input[name=amount]").val(row.data()['lc_amount'])
                $("input[name=mt]").val(row.data()['lc_mt'])
                $("input[name=reference]").val(row.data()['lc_reference'])
            })


            $(document).on('click','button[name=removeInvoice]',function(){
               
                if (confirm("Are you about to remove this invoice?")) {
                    $(this).closest("li.list-group-item").remove();
                    $.ajax({
                        url:`remove/${$(this).val()}`,
                        type:'GET'
                    }).done(function(data){
                        tableOpenAmount.ajax.reload()
                        $.toast({
                            heading: 'Information',
                            text: 'Successfully save the transaction',
                            icon: 'info',
                            loader: true,        // Change it to false to disable loader
                            loaderBg: '#9EC600'  // To change the background
                        })
                    }).fail(function (jqxHR, textStatus, errorThrown) {
                        console.log(errorThrown);
                    })
                }

                return false;
                    
            })

            $(document).on('click','.btnAddInvoice',function(e){
                const obj = $(this).val();
                e.preventDefault();
                $("#searchInvoiceModal").modal('show')
                $("#searchInvoiceModalLabel").text('Reference: ' +obj.toString())
                $("input[name=id_lcopc]").val($(this).attr('id'))
                $(".showData").html(`<tr class="header text-center">  <td colspan="6">No data available</td> </tr>`)
            })

            $("#formSearchInvoice").on('submit',function(e){
                e.preventDefault()
               if($('input[name="search"]').val()!=""){
                    $.ajax({
                    url:`charge/invoice/search/item/${openAmountId.val() }`,
                    type: "GET",
                    data:{
                        whse:$('select[name="whse"]').val(),
                        search:$('input[name="search"]').val(),
                    },
                    beforeSend:function(){
                        $("tbody.showData").html(`
                            <tr class="header text-center">
                                <td colspan="6">
                                    <div class="spinner-border spinner-border-sm" role="status">
                                        <span class="sr-only">Loading...</span>
                                    </div> Getting data...
                                </td>
                            </tr>
                        
                        `)
                        $("#formSearchInvoice *").prop("disabled", true);
                    }
                    }).done(function(data){
                        dataTable(data)
                        $("#formSearchInvoice *").prop("disabled", false);
                    }).fail(function (jqxHR, textStatus, errorThrown) {
                        $("#formSearchInvoice *").prop("disabled", false);
                        console.log(errorThrown);
                        // $.each(errorThrown.responseJSON.errors, function (i, error) {
                        //     alert(error[0])
                        // });
                        // alert('Something went wrong!')
                        $("#formSearchInvoice *").val('')
                         $(".showData").html(` <tr class="header text-center">  <td colspan="6">No data available</td> </tr>`)
                    })
               }else{

                    alert('Please do not leave blank')

               }
            })

            const dataTable = (data)=>{
            let hold=``
            if(data.length>0){
                data.forEach((val,i) => {
                    hold+=` <tr class="header">
                                <form id="poForm" >
                                    <input type="hidden" name="itemcode-${i}" value="${ val['itemcode'] }"> 
                                    <input type="hidden" name="cardname-${i}" value="${ val['cardname'] }"> 
                                    <input type="hidden" name="cardcode-${i}" value="${ val['cardcode'] }">
                                    <input type="hidden" name="pono-${i}" value="${ val['pono'] }">
                                    <input type="hidden" name="vessel-${i}" value="${ val['vessel'] }">
                                    <input type="hidden" name="description-${i}" value="${ val['description'] }">
                                    <input type="hidden" name="invoiceno-${i}" value="${ val['invoiceno'] }">
                                    <input type="hidden" name="broker-${i}" value="${ val['broker'] }"> 
                                    <input type="hidden" name="createdate-${i}" value="${ val['createdate'] }">
                                    <input type="hidden" name="docdate-${i}" value="${ val['docdate'] }">
                                    <input type="hidden" name="weight-${i}" value="${ val['weight'] }">
                                    <input type="hidden" name="quantity-${i}" value="${ val['quantity'] }">
                                    <input type="hidden" name="qtykls-${i}" value="${ val['qtykls'] }">
                                    <input type="hidden" name="qtymt-${i}" value="${ val['qtymt'] }">
                                    <input type="hidden" name="fcl-${i}" value="${ val['fcl'] }"> 
                                    <td>${ val['pono'] }</td>
                                    <td>${ val['description'] } </td>
                                    <td>${ val['invoiceno'] } </td>
                                    <td>${ val['qtymt'] } </td>
                                    <td>${ val['fcl'] } </td>
                                    <td style="font-size: 6;">
                                        <button class="btnSave btn btn-sm btn-primary m-0" type="submit" value="${i}">Add <i class="fas fa-plus"></i></button>
                                    </td>
                                </form>
                            </tr>
                        `
                });
            }else{
                hold=` <tr class="header text-center">
                            <td colspan="6">No data available</td>
                        </tr>`
            }
            
            $(".showData").html(hold)
            
        }


        $(document).on('click','.btnSave',function(){
                let iterate  =$(this).val()
                let pono = $("input[name=pono-"+iterate+"]").val()
                let itemcode = $("input[name=itemcode-"+iterate+"]").val()
                let cardname = $("input[name=cardname-"+iterate+"]").val()
                let cardcode = $("input[name=cardcode-"+iterate+"]").val()
                let vessel = $("input[name=vessel-"+iterate+"]").val()
                let description = $("input[name=description-"+iterate+"]").val()
                let invoiceno = $("input[name=invoiceno-"+iterate+"]").val()
                let broker = $("input[name=broker-"+iterate+"]").val()
                let createdate = $("input[name=createdate-"+iterate+"]").val()
                let docdate = $("input[name=docdate-"+iterate+"]").val()
                let weight = $("input[name=weight-"+iterate+"]").val()
                let quantity = $("input[name=quantity-"+iterate+"]").val()
                let qtykls = $("input[name=qtykls-"+iterate+"]").val()
                let qtymt = $("input[name=qtymt-"+iterate+"]").val()
                let fcl = $("input[name=fcl-"+iterate+"]").val()
                // $(this).closest("tr.header").remove();
            $.ajax({
                    url:`charge/invoice/store/${openAmountId.val()}`,
                    type: "POST",
                    data:{
                        _token:BaseModel._token,
                        pono,itemcode,cardname,cardcode,vessel,description,invoiceno,
                        broker,createdate,docdate,weight,quantity,qtykls,qtymt,fcl,
                    },
                }).done(function(data){
                    tableOpenAmount.ajax.reload()
                    $.toast({
                        heading: 'Information',
                        text: 'Successfully save the transaction',
                        icon: 'info',
                        loader: true,        // Change it to false to disable loader
                        loaderBg: '#9EC600'  // To change the background
                    })
                }).fail(function (jqxHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                })
                
                $(this).closest("tr.header").remove();
        });

        $(document).on('change','input[type="checkbox"]',function(){
            if($(this).is(":checked")){
                invoiceID.push($(this).val());
                invoiceID.find(val=>val==$(this).val())
            }else{
                let index = invoiceID.indexOf($(this).val())
                invoiceID.splice(index,1);
            }
            if (invoiceID.length>0) {
                btnSaved.show()
            } else {
                btnSaved.hide()
            }
        })


    </script>
@endsection