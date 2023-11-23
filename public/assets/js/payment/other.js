const Other = {
    table           : $("#otherPaymentTable"),
    form            : $("#otherPaymentForm"),
    computeTotal    : ()=>{
        let val = Other.form.find("input[name=quantity]").val()
        if (val=="" || val==0) {
            return parseFloat(Other.form.find("input[name=dollar]").val())*parseFloat(Other.form.find("input[name=exchangeRate]").val())
        } else {
            return parseFloat(Other.form.find("input[name=dollar]").val())*parseFloat(Other.form.find("input[name=exchangeRate]").val())*parseFloat(Other.form.find("input[name=quantity]").val())
        }
    },
}

let otherPaymentTable;
Other.form.find("button[name=cancelButton]").on('click',function(){
    Other.form[0].reset()
    Other.form.find('input[name=id]').val('')
    Other.form.find("button[name=cancelButton]").hide()
}).hide()

Other.table.DataTable({
    paging:false,
    searching:false,
    ordering:false,
})


const invoiceOtherPaymentDetail = (invoice_payment) =>{

    otherPaymentTable = Other.table.DataTable({
       serverSide: true,
       searching:false,
       ordering:false,
       paging:false,
       destroy:true,
       "ajax": {
           url: Other.table.attr("data-url").replace("ip",invoice_payment),
           method: "get"
       },
       language: {
           'loadingRecords': '&nbsp;',
           'processing': 'Loading...'
       },  
       // order: [[0, 'desc']],
       columns:[
           { 
                data:null,
                render:function(data){
                    return `<b>${data.p_name}</b>`
                }
            },
           { data:'exchangeDate' },
           { data:'dollar' },
           { data:'exchangeRate' },
           { data:'quantity' },
           { data:'totalAmountInPHP' },
           { 
               data:null,
               render:function(data){
                   return `<button
                   name="editOtherPaymentDetail"
                   value="${data.id}"
                   type="button"
                   class="btn btn-outline-secondary btn-sm btn-block">
                   <i class="fas fa-plus-circle"></i> Edit
                   </button>
                   `
               }
           },
       ],
       
   })

}

$(document).on('click','button[name=editOtherPaymentDetail]',function(){
    Other.form.find("button[name=cancelButton]").show()
    let data = otherPaymentTable.row( $(this).closest('tr') ).data()
    $.each($('#otherPaymentTable .form-control'),(ind, value) => {
        Other.form.find("input[name="+value.name+"]").val(data[value.name])
        Other.form.find("select[name="+value.name+"]").val(data[value.name])
    });
    Other.form.find("input[name=id]").val($(this).val())
})


Other.form.on('submit',function(e){
    e.preventDefault()
    $.ajax({
        url:  Other.form.attr("action"),
        type:'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
    }).done(function(data){
        if (data.msg) {
            Other.form[0].reset()
            Other.form.find('input[name=id]').val('')
            Other.form.find("button[name=cancelButton]").hide()
            toasMessage(data.msg,"success")
            otherPaymentTable.ajax.reload()
        }
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(data.msg,jqxHR,"danger")
    })
})


Other.form.find("input[name=dollar]").on('input',function(){
    Other.form.find("input[name=totalAmountInPHP]").val(Other.computeTotal())
})

Other.form.find("input[name=quantity]").on('input',function(){
    Other.form.find("input[name=totalAmountInPHP]").val(Other.computeTotal())
})

Other.form.find("input[name=exchangeRate]").on('input',function(){
    Other.form.find("input[name=totalAmountInPHP]").val(Other.computeTotal())
})


