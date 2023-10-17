@extends('../_layout/app')
@section('moreCss')
<link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<!-- DataTables -->
<link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
<style>label{font-size: 11px;}.datepicker.dropdown-menu {z-index: 9999 !important;}</style>
@endsection
@section('content')
<!-- Page-Title -->
    <x-page-title title="REPORT">
        @if (Helper::usrChckCntrl(['RP003']) || auth()->user()->type)
        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#dutiesModal">
            <i class="fas fa-download mr-1"></i> Report
        </button>
        @endif
    </x-page-title>
<!-- end page title end breadcrumb -->
{{-- <div class="card">
    <div class="card-body">
        <div class="row justify-content-between mb-1">
            <div class="col-lg-4 col-md-4 col-sm-12">
                <h6>ITEM(s)</h6>
                <button class="btn btn-outline-success btn-sm" id="print">Print&nbsp;<i class="fas fa-print"></i></button>
            </div>
            @if (Helper::usrChckCntrl(['RP002']))
                <div class="col-lg-4 col-md-4 col-sm-12">
                        <!-- action="{{ route('authenticate.report') }}" -->
                    <form id="searchForm" method="GET" autocomplete="off">@csrf
                        <select name="item" class="form-control"></select>
                        <!-- <input type="text" class="form-control" name="item" placeholder="ITEM" /> -->
                        <div class="input-group mb-3 mt-2">
                            <div class="input-daterange input-group" id="date-range">
                                <input type="text" class="form-control form-control-sm" name="start" placeholder="Start Date" required/>
                                <input type="text" class="form-control form-control-sm" name="end" placeholder="End Date"  read_exif_data/>
                                <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-search"></i> Filter</button>
                            </div>
                        </div>
                    </form>
                </div>
            @endif
        </div>
        <div class="table-responsive">
            <table class="table table-bordered table-sm" style="font-size: 11px;">
                <tr>
                    <td>PO DATE</td>
                    <td>Invoice</td>
                    <td>Qty Metric Ton</td>
                    <td>Price MT</td>
                    <td>Avg Exchange Rate</td>
                </tr>
               
                <tbody class="showData">
                    <tr>
                        <td colspan="5" class="text-center">No data available</th>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div> --}}

<div class="card">
    <div class="card-header">
        Report
    </div>
    <div class="card-body">
        @if (Helper::usrChckCntrl(['RP002']))
                <div class="col-lg-4 col-md-4 col-sm-12 ">
                        <!-- action="{{ route('authenticate.report') }}" -->
                    <form id="searchForm" action="{{ route("authenticate.report.filter") }}" autocomplete="off">@csrf
                        <div class="form-group mb-2">
                            <select id="{{ route("authenticate.report.filter.description") }}" name="item" class="form-control"></select>
                        </div>
                        <div class="form-group mb-1">
                            <select id="{{ route("authenticate.report.filter.supplier") }}" name="supplier" class="form-control"></select>
                        </div>
                        <div class="input-group mb-3 mt-2">
                            <div class="input-daterange input-group" id="date-range">
                                <input type="text" class="form-control form-control-sm" name="start" placeholder="Start Date" required/>
                                <input type="text" class="form-control form-control-sm" name="end" placeholder="End Date"  read_exif_data/>
                                <button type="submit" class="btn btn-primary btn-sm">Search</button>
                            </div>
                        </div>
                    </form>
                </div>
        @endif
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
              <button class="nav-link active" id="nav-nego-tab" data-toggle="tab" data-target="#nav-nego" type="button" role="tab" aria-controls="nav-nego" aria-selected="true">LCDP Nego</button>
              <button class="nav-link" id="nav-freight-tab" data-toggle="tab" data-target="#nav-freight" type="button" role="tab" aria-controls="nav-freight" aria-selected="false">Freight</button>
            </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-nego" role="tabpanel" aria-labelledby="nav-nego-tab">
               <div class="mt-4">
                <div class="table-responsive">
                    <table id="negoTable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:10px">
                        <thead>
                            <tr>
                                <th width="7%">DATE</th>
                                <th>ITEM</th>
                                <th>SUPPLIER</th>
                                <th>MT(Qty)</th>
                                <th>MT(USD)</th>
                                <th>TOTAL(USD)</th>
                                <th>ExRate(PHP)</th>
                                <th>Total(PHP)</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot class="bg-secondary text-white">
                            <tr>
                                <th colspan="3">TOTAL</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
               </div>
            </div>
            <div class="tab-pane fade" id="nav-freight" role="tabpanel" aria-labelledby="nav-freight-tab">
               <div class="mt-4">
                <div class="table-responsive">
                    <table id="freightTable" class="table table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:10px">
                        <thead>
                            <tr>
                                <th width="7%">DATE</th>
                                <th>ITEM</th>
                                <th>SUPPLIER</th>
                                <th>MT(Qty/FCL)</th>
                                <th>MT(USD)</th>
                                <th>TOTAL(USD)</th>
                                <th>ExRate(PHP)</th>
                                <th>Total(PHP)</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                        <tfoot class="bg-secondary text-white">
                            <tr>
                                <th colspan="3">TOTAL</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                                <th>0</th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
               </div>
            </div>
          </div>
       
    </div>
</div>
<x-export.report :companies="$companies"/>
@endsection
@section('moreJs')
<script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<!-- Required datatable js -->
<script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
<!-- Responsive examples -->
<script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
<script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
<script src="{{ asset('plugins/jquery-number/jquery.number.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const printVar = $("#print")
          printVar.hide()

    $('#date-range').datepicker({
        toggleActive: true,
    });

    // $("#searchForm").on('submit',function(e){
        
    //     e.preventDefault()
    //     $.ajax({
    //         url:`report/filter`,
    //         type:'POST',
    //         data: new FormData(this),
    //         processData: false,
    //         contentType: false,
    //         cache: false,
    //         beforeSend:function(){
    //             $("tbody.showData").html(`
    //                 <tr class="header text-center">
    //                     <td colspan="5">
    //                         <div class="spinner-border spinner-border-sm" role="status">
    //                             <span class="sr-only">Loading...</span>
    //                         </div> Getting data...
    //                     </td>
    //                 </tr>
                
    //             `)
    //             // $("#searchForm *").prop("disabled", true);
    //         }
    //         }).done(function(data){
    //             let html=``;
               
    //                 if(data.length>0){
    //                     printVar.show()
    //                     data.forEach(element => {
    //                         html+=` <tr style="font-size: 11px;">
    //                                     <td>${element.exchangeRateDate}</td>
    //                                     <td>${element.invoiceno}</td>
    //                                     <td>${element.qtymt}</td>
    //                                     <td>&#36; ${element.priceMetricTon}</td>
    //                                     <td>&#8369; ${element.avg}</td>
    //                                 </tr>`
    //                     })
    //                     let avgPriceMetricTon = data.reduce(function(prev,current){
    //                         return prev+=parseFloat(current.priceMetricTon)/data.length
    //                     },0)
    //                     let avgExchangeRate = data.reduce(function(prev,current){
    //                         return prev+=parseFloat(current.avg)/data.length
    //                     },0)
    //                     html+=` <tr style="font-size: 15px;">
    //                                 <th colspan="3" class="text-right">Average</th>
    //                                 <th>&#36; ${parseFloat(avgPriceMetricTon)}</th>
    //                                 <th>&#8369; ${parseFloat(avgExchangeRate)}</th>
    //                             </tr>`
    //                 }else{
    //                     printVar.hide()
    //                     html=` <tr><td colspan="5" class="text-center">No data available</td></tr>`
    //                 }
    //                 $(".showData").html(html)

    //             $("#searchForm *").prop("disabled", false)
    //         }).fail(function (jqxHR, textStatus, errorThrown) {
    //             console.log();(textStatus)
    //         })         
    // })


    $("#searchForm").on('submit',function(e){
        e.preventDefault()
        $.ajax({
            url:$(this).attr("action"),
            type:'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            beforeSend:function(){
                // $("#searchForm *").prop("disabled", true);
            }
            }).done(function(data){
                negoTable(data[0])
                freightTable(data[1])
                $("#searchForm *").prop("disabled", false)
            }).fail(function (jqxHR, textStatus, errorThrown) {
                console.log(textStatus)
            })         
    })

    const select2Source = () =>{

      return   {
        placeholder: 'Select an item',
        ajax: {
            url: 'report/filter/description',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.description,
                            id: item.description,
                        }
                    })
                };
            },
            cache: true
        }
    }

    }

    $('select[name="item"]').select2({
        tags:true,
        allowClear:true,
        placeholder: 'Select an item',
        ajax: {
            url: $('select[name="item"]').attr("id"),
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.description,
                            id: item.description,
                        }
                    })
                };
            },
            cache: true
        }
    }).on('select2:close', function(){
        var element = $(this);
        var new_category = $.trim(element.val());
        element.append('<option value="'+new_category+'">'+new_category+'</option>').val(new_category);
    });

    $('select[name="supplier"]').select2({
        tags:true,
        allowClear:true,
        placeholder: 'Select supplier',
        ajax: {
            url: $('select[name="supplier"]').attr("id"),
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.suppliername,
                            id: item.suppliername,
                        }
                    })
                };
            },
            cache: true
        }
    }).on('select2:close', function(){
        var element = $(this);
        var new_category = $.trim(element.val());
        element.append('<option value="'+new_category+'">'+new_category+'</option>').val(new_category);

    });
  

    $('select[name="itemName"]').select2({
        // minimumResultsForSearch: -1,
        placeholder: function(){
            $(this).data('placeholder');
        },
        dropdownParent: $('#dutiesModal'),
        closeOnSelect: true,
        allowClear: true,
        ajax: {
            url: 'report/filter/description',
            dataType: 'json',
            delay: 250,
            processResults: function (data) {
                return {
                    results:  $.map(data, function (item) {
                        return {
                            text: item.description,
                            id: item.description,
                        }
                    })
                };
            },
            cache: true
        }
    })

    $('#print').on('click',function(){
        
        let url_string = "report/print";
        let start      = $("input[name='start']").val()
        let end        = $("input[name='end']").val()
        let item       = $("select[name='item']").val()
        let _token     = BaseModel._token
        let adsURL     = url_string+"?_token="+_token+"&start="+start+"&end="+end+"&item="+item;
        
        BaseModel.loadToPrint(adsURL)
    
    })


    $('#report-range-modal').datepicker({
        toggleActive: true,
        autoclose: true
    });

    // $("#exportForm").on('submit',function(){
    //     setTimeout(() => {
    //         $("input[type=search]").val('')
    //     }, 3000);
    // })

    $(".itemName_details").hide()
    $("select[name=type]").on('change',function(){

        switch ($(this).val()) {
            case "projectedCostReport":
                    $(".company_details").hide()
                    $(".itemName_details").show()
                break;
            case "dollarBook":
                    $(".company_details").hide()
                    $(".itemName_details").hide()
                break;
        
            default:
                $(".company_details").show()
                $(".itemName_details").hide()
                break;
        }
    })

    $("table").DataTable({
        ordering:false,
        paging:false,
    })

    const negoTable = (data)=>{
        $("#negoTable").DataTable({
            responsive:true,
            ordering:false,
            paging:false,
            destroy:true,
            data:data,
            footerCallback: function ( row, data, start, end, display ) {
                var api = this.api(), data;
                    console.log(api);
                // converting to interger to find total
                let intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
    
                // computing column Total of the complete result 
                let monTotal = api.column(3,{page:'current'}).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b.qtymt);
                    }, 0 );
                    
                let tueTotal = api.column(4,{ page:'current' }).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b.priceMetricTon);
                    }, 0 );
                    
                let wedTotal = api.column(5,{ page:'current' }).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b.amountUSD);
                    }, 0 ) /  api.column(5,{ page:'current' }).data().count();
                    
                let thuTotal = api.column(4,{ page:'current' }).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b.exchangeRate);
                    }, 0 ) /  api.column(4,{ page:'current' }).data().count();

                let friTotal = api.column(5,{ page:'current' }).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b.amountPHP);
                    }, 0 );
                
                // Update footer by showing the total with the reference of the column index 
                $( api.column( 0 ).footer() ).html('Total');
                $( api.column( 3 ).footer() ).html($.number(monTotal,4));
                $( api.column( 4 ).footer() ).html($.number(tueTotal,4));
                $( api.column( 5 ).footer() ).html('Avg. '+$.number(wedTotal,4));
                $( api.column( 6 ).footer() ).html('Avg. '+$.number(thuTotal));
                $( api.column( 7 ).footer() ).html($.number(friTotal,2));
        },
            columns:[
                { data:'exchangeRateDate' },
                { data:'description' },
                { data:'suppliername' },
                { 
                    data:null,
                    render:function(data){
                        return $.number(data.qtymt,2)
                    }
                },
                { 
                    data:null,
                    render:function(data){
                        return $.number(data.priceMetricTon,2)
                    }
                },
                { 
                    data:null,
                    render:function(data){
                        return $.number(data.amountUSD,2)
                    }
                },
                { 
                    data:null,
                    render:function(data){
                        return $.number(data.exchangeRate,2)
                    }
                },
                { 
                    data:null,
                    render:function(data){
                        return $.number(data.amountPHP,2)
                    }
                },
            ],

           
        })
        
    }

    const freightTable = (data)=>{
        $("#freightTable").DataTable({
            responsive:true,
            ordering:false,
            paging:false,
            destroy:true,
            data:data,
            footerCallback: function ( row, data, start, end, display ) {
                var api = this.api(), data;
                    console.log(api);
                // converting to interger to find total
                let intVal = function ( i ) {
                    return typeof i === 'string' ?
                        i.replace(/[\$,]/g, '')*1 :
                        typeof i === 'number' ?
                            i : 0;
                };
    
                // computing column Total of the complete result 
                let monTotal = api.column(3,{page:'current'}).data().reduce( function (a, b) {
                    return intVal(a) + intVal(b.qtymt);
                    }, 0 );
                    
                let tueTotal = api.column(4,{page:'current'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b.priceMetricTon);
                    }, 0 );
                    
                let wedTotal = api.column(5,{page:'current'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b.amountUSD);
                    }, 0 ) /  api.column(5,{page:'current'}).data().count();
                    
                let thuTotal = api.column(4,{page:'current'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b.exchangeRate);
                    }, 0 ) /  api.column(4,{page:'current'}).data().count();

                let friTotal = api.column(5,{page:'current'}).data().reduce( function (a, b) {
                        return intVal(a) + intVal(b.amountPHP);
                    }, 0 );
                
                // Update footer by showing the total with the reference of the column index 
                $( api.column( 0 ).footer() ).html('Total');
                $( api.column( 3 ).footer() ).html($.number(monTotal,4));
                $( api.column( 4 ).footer() ).html($.number(tueTotal,4));
                $( api.column( 5 ).footer() ).html('Avg. '+$.number(wedTotal,4));
                $( api.column( 6 ).footer() ).html('Avg. '+$.number(thuTotal));
                $( api.column( 7 ).footer() ).html($.number(friTotal,2));
            },
            columns:[
                { data:'exchangeRateDate' },
                { data:'description' },
                { data:'suppliername' },
                { 
                    data:null,
                    render:function(data){
                        return $.number(data.qtymt,2)
                    }
                },
                { 
                    data:null,
                    render:function(data){
                        return $.number(data.priceMetricTon,2)
                    }
                },
                { 
                    data:null,
                    render:function(data){
                        return $.number(data.amountUSD,2)
                    }
                },
                { 
                    data:null,
                    render:function(data){
                        return $.number(data.exchangeRate,2)
                    }
                },
                { 
                    data:null,
                    render:function(data){
                        return $.number(data.amountPHP,2)
                    }
                },
            ],
        })
        
    }


</script>
@endsection