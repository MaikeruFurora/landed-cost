@extends('../_layout/app')
@section('moreCss')
<link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="{{ asset('plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
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
<div class="card">
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
</div>
<x-export.report :companies="$companies"/>
@endsection
@section('moreJs')
<script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
    const printVar = $("#print")
          printVar.hide()

    $('#date-range').datepicker({
        toggleActive: true,
    });

    $("#searchForm").on('submit',function(e){
        
        e.preventDefault()
        $.ajax({
            url:`report/filter`,
            type:'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            beforeSend:function(){
                $("tbody.showData").html(`
                    <tr class="header text-center">
                        <td colspan="5">
                            <div class="spinner-border spinner-border-sm" role="status">
                                <span class="sr-only">Loading...</span>
                            </div> Getting data...
                        </td>
                    </tr>
                
                `)
                // $("#searchForm *").prop("disabled", true);
            }
            }).done(function(data){
                let html=``;
               
                    if(data.length>0){
                        printVar.show()
                        data.forEach(element => {
                            html+=` <tr style="font-size: 11px;">
                                        <td>${element.doc_date}</td>
                                        <td>${element.invoiceno}</td>
                                        <td>${element.qtymt}</td>
                                        <td>&#36; ${element.priceMetricTon}</td>
                                        <td>&#8369; ${element.avg}</td>
                                    </tr>`
                        })
                        let avgPriceMetricTon = data.reduce(function(prev,current){
                            return prev+=parseFloat(current.priceMetricTon)/data.length
                        },0)
                        let avgExchangeRate = data.reduce(function(prev,current){
                            return prev+=parseFloat(current.avg)/data.length
                        },0)
                        html+=` <tr style="font-size: 15px;">
                                    <th colspan="3" class="text-right">Average</th>
                                    <th>&#36; ${parseFloat(avgPriceMetricTon)}</th>
                                    <th>&#8369; ${parseFloat(avgExchangeRate)}</th>
                                </tr>`
                    }else{
                        printVar.hide()
                        html=` <tr><td colspan="5" class="text-center">No data available</td></tr>`
                    }
                    $(".showData").html(html)

                $("#searchForm *").prop("disabled", false)
            }).fail(function (jqxHR, textStatus, errorThrown) {
                console.log();(textStatus)
            })         
    })

    const select2Source = () =>{

      return   {
        placeholder: 'Select an item',
        ajax: {
            url: 'report/filter/invoice',
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
        placeholder: 'Select an item',
        ajax: {
            url: 'report/filter/invoice',
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
            url: 'report/filter/invoice',
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

</script>
@endsection