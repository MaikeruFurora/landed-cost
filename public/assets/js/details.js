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
        data:"blno"
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
                                BaseModel.findPrev('LC004')?`
                                <a class="dropdown-item border" id="print"  style="cursor:pointer" value="${data["id"]}"><i class="fas fa-print"></i> Print</a>
                                `:``
                            }
                            ${
                                BaseModel.findPrev('LC005')?`
                                <a class="dropdown-item border" id="posting" style="cursor:pointer; display:${ (data.res.posted_at!=null && !BaseModel.findPrev('LCOO6'))? 'none' :'' }"
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