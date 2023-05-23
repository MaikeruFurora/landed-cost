@extends('../_layout/app')
@section('moreCss')
<!-- Sweet Alert -->
<link href="{{ asset('plugins/sweet-alert2/sweetalert2.css') }}" rel="stylesheet" type="text/css">
@endsection
@section('content')
    <!-- Page-Title -->
    <x-page-title title="Entry Data">
        <a class="btn btn-primary btn-sm" href="{{ route('authenticate.details') }}">
            <i class="fas fa-arrow-left"></i> Back
        </a>
    </x-page-title>
    <!-- end page title end breadcrumb -->
    <!-- Alert Start -->
    @if (session()->has('msg'))
        <div class="alert alert-{{ session()->get('action') ?? 'success' }}" role="alert">
            @if(session()->has('icon'))
                {!! session()->get('icon') !!}
            @else
                <i class="far fa-check-circle"></i>
            @endif
            {{ session()->get('msg') }}
        </div>
    @endif
    <!-- Aler End -->
    @error('itemcode')
        <div class="alert alert-danger" role="alert">
            {{ $errors->first('itemcode') }}
        </div>
    @enderror
  <form autocomplete="off" id="invoiceForm">@csrf
  <div class="row">
    <div class="col-lg-4 col-md-4 col-sm-12">
        
    <div class="card shadow-sm">
        <div class="card-body">
            <div class="form-group">
            <div class="form-row">
                <div class="form-group col-lg-8 col-md-8 col-sm-12">
                    <label for="">Item Description</label>
                    <input type="text" class="form-control" name="description" required autofocus>
                </div>
                <div class="form-group col-lg-4 col-md-4 col-sm-12">
                    <label for="">Item Code</label>
                    <input type="text" class="form-control" name="itemcode">
                </div>
            </div>
                
            </div>
            <div class="form-group">
                <label for="">Packaging | Weight</label>
                <input type="number" class="form-control" name="weight">
            </div>
            <div class="form-group">
                <label for="">Quantity</label>
                <input type="number" class="form-control" name="quantity">
            </div>
            <div class="form-row">
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label for="">Quantity in kls</label>
                    <input type="number" class="form-control" readonly name="qtykls">
                </div>
                <div class="form-group col-lg-6 col-md-6 col-sm-12">
                    <label for="">Quanity in MT</label>
                    <input type="number" class="form-control" readonly name="qtymt">
                </div>
            </div>
        </div>
    </div>
        
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="form-group">
                    <label for="">PO Number</label>
                    <input type="number" class="form-control" name="pono" required>
                </div>
                <div class="form-group">
                    <label for="">Broker</label>
                    <input type="text" class="form-control" name="broker">
                </div>
                <div class="form-group">
                    <label for="">Invoice No</label>
                    <input type="text" class="form-control" name="invoiceno" required>
                </div>
                <div class="form-group">
                    <label for="">Vessel</label>
                    <input type="text" class="form-control" name="vessel">
                </div>
            </div>
        </div>
        
    </div>
    <div class="col-lg-4 col-md-4 col-sm-12">
        <div class="card shadow-sm">
            <div class="card-body">
                <div class="form-group">
                    <label for="">Supplier's name</label>
                    <input type="text" class="form-control" name="suppliername">
                </div>
                <div class="form-group">
                    <label for="">Full Container Load</label>
                    <input type="number" class="form-control" name="fcl" required>
                </div>
                <div class="form-group">
                    <label for="">BL No.</label>
                    <input type="text" class="form-control" name="blno">
                </div>
                <div class="form-group">
                    <label for="exampleInputPassword1"></label>
                    <button class=" btn btn-block btn-primary" id="sa-success" type="submit">
                        Submit
                    </button>    
                </div>
            </div>
        </div>
    </div>
    </div>
  </form>
@endsection
@section('moreJs')
<!-- Sweet-Alert  -->
<script src="{{ asset('plugins/sweet-alert2/sweetalert2.min.js') }}"></script>
<script>
//    $(function(){
    
//     "use strict"
    
//     $('#sa-success').click(function () {
//         swal(
//             {
//                 title: 'Good job!',
//                 text: 'You clicked the button!',
//                 type: 'success',
//                 showCancelButton: true,
//                 confirmButtonClass: 'btn btn-success',
//                 cancelButtonClass: 'btn btn-danger m-l-10',
//                 showConfirmButton: false,
//                 timer: 1500

//             }
//         )
//     });
   
    
    
// })

$("#invoiceForm").on('submit',function(e){
    e.preventDefault()
    $.ajax({
            url:`/landed-cost/public/auth/po/store`,
            type:'POST',
            data: new FormData(this),
            processData: false,
            contentType: false,
            cache: false,
            beforeSend:function(){
                return $("#invoiceForm").find('.btn-primary').html(`
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="sr-only">Loading...</span>
                    </div> Saving...
                `).attr('disabled',true)
            }
        }).done(function(data){
            console.log(data);
            $("#invoiceForm")[0].reset()
            $("#invoiceForm").find('.btn-primary').html('Submit').attr('disabled',false)
            $.toast({
                heading: 'Information',
                text: 'Successfully saved the transaction',
                icon: 'info',
                loader: true,        // Change it to false to disable loader
                loaderBg: '#9EC600'  // To change the background
            })
        }).fail(function (jqxHR, textStatus, errorThrown) {
            $("#invoiceForm").find('.btn-primary').html('Submit').attr('disabled',false)
            console.log(errorThrown);
            $.toast({
                heading: textStatus.toUpperCase(),
                text: errorThrown,
                icon: 'danger',
                loader: true,        // Change it to false to disable loader
                loaderBg: '#9EC600'  // To change the background
            })
        })
})

    
    const quantityInKLS=(qty,weight) => parseInt(qty)*parseInt(weight)

    const quanityInMT=(qtykls )=> parseFloat(qtykls/1000)

    $("input[name='weight']").on('keyup',function(){
        // if($(this).val()!=""){
            let qtys = $("input[name='quantity']")
            let qtykls = quantityInKLS(qtys.val(),$(this).val());
            if (qtys.val()!="") {
                $('input[name="qtykls"]').val(qtykls)
                $('input[name="qtymt"]').val(quanityInMT(qtykls))
            }
        // }
    });

    $("input[name='quantity']").on('keyup',function(){
        let wgth = $("input[name=weight]")
        let qtykls = quantityInKLS($(this).val(),wgth.val());
        $('input[name="qtykls"]').val(qtykls)

        if (qtykls!="") {
            $('input[name="qtymt"]').val(quanityInMT(qtykls))
        }
    });
   
</script>
@endsection
