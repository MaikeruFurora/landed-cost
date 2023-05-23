@extends('../_layout/app')
@section('moreCss')
    <!-- DataTables -->
    <link href="{{ asset('plugins/datatables/dataTables.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('plugins/datatables/buttons.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />

    <!-- Responsive datatable examples -->
    <link href="{{ asset('plugins/datatables/responsive.bootstrap4.min.css') }}" rel="stylesheet" type="text/css" />
@endsection
@section('content')
    <!-- Page-Title -->
    <x-page-title title="Invoice">
        @if(auth()->user()->findOtherPrev('Gather-SAP'))
        <button type="button" class="btn btn-sm btn-success pl-2 pr-2 mr-2">
            <i class="far fa-check-circle mr-1" style="font-size: 12px;"></i>Post
        </button>
        
        <a class="btn btn-primary btn-sm" href="{{ route('authenticate.po.search') }}">
            <i class="fas fa-plus"></i>&nbsp;&nbsp;Gather Data (<b>SAP</b>)
        </a>
        @endif
        <button class="btn btn-sm btn-secondary" name="refreshTable"><i class="fas fa-sync-alt"></i> Refresh</button>
        <!-- <a class="btn btn-primary btn-sm" href="{{ route('authenticate.details.create') }}">
            <i class="fas fa-pencil-alt"></i>&nbsp;&nbsp;Entry Data
        </a> -->
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

    <!-- Aler End -->
   <div class="card">
        <div class="card-body">
            
            <div class="table-responsive mt-2">
            <table cellpadding="0" cellspacing="0" id="datatable" class="table table-bordered table-hover dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;font-size:10px">
               
               <thead class="bg-secondary text-white">
                   <tr>
                       <th>&nbsp;</th>
                       <th>ID(s)</th>
                       <th>PO No.</th>
                       <!-- <th>Item Code.</th> -->
                       <th>Vessel</th>
                       <th>Description</th>
                       <th>Inv No.</th>
                       <th>Broker</th>
                       <th>Qty</th>
                       <th>Qty(KLS)</th>
                       <th>Qty(MT)</th>
                       <th>FCL</th>
                       <th>Action</th>
                   </tr>
               </thead>
               <tbody>
                  
               </tbody>
              
           </table>
            </div>
        </div>
   </div>
    <div class="modal fade" id="propMessage" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="propMessageLabel" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header p-2">
                    <p class="modal-title" id="propMessageLabel"></p>
                </div>
                <div class="modal-body text-center">
                   <span id="promptText"></span>
                </div>
                <div class="modal-footer p-1">
                    <button type="button" class="btn btn-sm btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-sm btn-primary pl-3 pr-3">Post</button>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('moreJs')
    <!-- Required datatable js -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <!-- Responsive examples -->
    <script src="{{ asset('plugins/datatables/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables/responsive.bootstrap4.min.js') }}"></script>
    <script>

        const btnSuccess = $(".btn-success")
        const invoice  = []
        btnSuccess.hide()

        $(document).on('change','input[type="checkbox"]',function(){
        if($(this).is(":checked")){
            invoice.push($(this).val());
            invoice.find(val=>val==$(this).val())
        }else{
            let index = invoice.indexOf($(this).val())
            invoice.splice(index,1);
        }
        if (invoice.length>0) {
            btnSuccess.show()
        } else {
            btnSuccess.hide()
        }
    })


    let dataTableInvoice =  $('#datatable').DataTable({
        "serverSide": true,
        // pageLength: 5,
        createdRow:function( row, data, dataIndex){
            if (data.res.posted_at!=null) {
                $(row).find("td").addClass('highlight');
            }
        },
        
       order: [[0, 'desc']],
        paging:true,
        "ajax": {
            url: "details/list", 
            method: "get"
        },
        columns:[
            {
                orderable:false,
                searchable: false,
                data:null,
                render:function(data){
                    if (data.res.posted_at==null) {
                       return `<input type="checkbox" class="form-check" value="${data["id"]}" ${BaseModel.findPrev('posting') ? '':'disabled'}>`
                    }else{
                        return '<i class="fas fa-check-circle text-secondary" style="font-size:13px"></i>'
                    }
                }
            },
            {
                data: "updated_at",
                // target: 0,
                visible: false,
                searchable: false
            },
            {
                orderable: false,
                data:"pono"
            },
            {
                orderable: false,
                data:null,
                render:function(data){
                    return (data["vessel"]!="null")?data["vessel"]:""
                }
            },
            {
                orderable: false,
                data:"description"
            },
            {
                orderable: false,
                data:"invoiceno"
            },
            {
                orderable: false,
                data:null,
                render:function(data){
                    return (data["broker"]!="null")?data["broker"]:""
                }
            },
            {
                orderable: false,
                data:"quantity"
            },
            {
                orderable: false,
                data:"qtykls"
            },
            {
                orderable: false,
                data:null,
                render:function(data){
                    return data.qtymt.toFixed(2)
                }
            },
            {
                orderable: false,
                data:"fcl"
            },
            {
                orderable: false,
                data:null,
                render:function(data){
                    return `<div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-primary btn-sm dropdown-toggle" style="font-size:11px" data-toggle="dropdown" aria-expanded="false">
                                    Action
                                </button>
                                <div class="dropdown-menu" style="font-size:11px">
                                    <a href="details/cost/${data["id"]}" class="dropdown-item"><i class="fas fa-project-diagram"></i> Particular</a>
                                    ${
                                        BaseModel.findPrev('print')?`
                                        <a class="dropdown-item border" id="print"  style="cursor:pointer" value="${data["id"]}"><i class="fas fa-print"></i> Print</a>
                                        `:``
                                    }
                                    ${
                                        BaseModel.findPrev('posting')?`
                                        <a class="dropdown-item border" id="posting" style="cursor:pointer; display:${ (data.res.posted_at!=null && !BaseModel.findPrev('unposting'))? 'none' :'' }"
                                         value="${data.res.id}"
                                         data-title="${data.res.invoiceno}"
                                         data-post="${data.res.posted_at}">
                                         ${data.res.posted_at!=null?'<i class="fas fa-check-circle"></i>&nbsp;Unpost':'<i class="far fa-check-circle"></i>&nbsp;Post'}
                                        </a>
                                        
                                        `:``
                                    }
                                    <a class="dropdown-item"  aria-expanded="false" style="cursor:pointer"><i class="fas fa-times"></i> Close Action</a>
                                   
                                </div>
                            </div>`
                }
            },
        ]
    });

    $("button[name=refreshTable]").on('click',function(){
        dataTableInvoice.ajax.reload()
    })


    $(document).on('click','a#print',function(){
        let id = $(this).attr('value')
        let url = `print/${id}`
        BaseModel.loadToPrint(url)
    })

    $(document).on('click',"#posting",function(){
        $("#propMessage").modal("show")
        $("#propMessageLabel").text('Confirmation Msg: '+$(this).attr('data-title'))
        $("#propMessage span[id=promptText]").text("Are you sure?")
        let postStatus = $(this).attr('data-post')
        let pText = postStatus!='null'?'<i class="fas fa-check-circle"></i>&nbsp;Unpost':'<i class="far fa-check-circle"></i>&nbsp;Post'
        $("#propMessage .btn-primary").html(pText).val($(this).attr('value'))

    })

    btnSuccess.on('click',function(){
        $("#propMessage").modal("show")
        $("#propMessageLabel").text('Confirmation Message')
        $("#propMessage span[id=promptText]").text("Are you sure to post all the selected?")
    })

    $("#propMessage .btn-primary").on('click',function(){
        requestToPost($(this).val()!=""? $(this).val() : invoice)
    })

    const requestToPost =(invc)=>{
        $.ajax({
            url:`details/cost/invoice-detail/post`,
            type:'POST',
            data:{
                _token:BaseModel._token,
                invoice:invc
            }
        }).done(function(data){
            dataTableInvoice.ajax.reload()
            $("#propMessage").modal("hide")
            invoice.length=0
            btnSuccess.hide()
            $("#propMessage .btn-primary").val('')
        }).fail(function (jqxHR, textStatus, errorThrown) {
            console.log(errorThrown);
        })
    }
    

    </script>
@endsection
