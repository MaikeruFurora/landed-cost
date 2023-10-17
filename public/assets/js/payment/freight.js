const Freight = {

    form: $("#freightForm"),

    table: $("#freightTable"),

    cancelButton: $("button[name=cancelButton]"),

    totalAmountInPHP:()=>{
        Freight.form.find("input[name=totalAmountInPHP]").val(
           parseFloat(Freight.form.find("input[name=quantity]").val())*parseFloat(Freight.form.find("input[name=dollar]").val())*parseFloat(Freight.form.find("input[name=exchangeRate]").val())
        )
    }

}


Freight.form.find("input[name=quantity]").on('input',function(){
    Freight.totalAmountInPHP()
})

Freight.form.find("input[name=dollar]").on('input',function(){
    Freight.totalAmountInPHP()
})

Freight.form.find("input[name=exchangeRate]").on('input',function(){
    Freight.totalAmountInPHP()
})

Freight.form.on('submit',function(e){
    e.preventDefault()
    $.ajax({
        url:  Freight.form.attr("action"),
        type:'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
    }).done(function(data){
        if (data.msg) {
            Freight.form[0].reset()
            Freight.form.find('input[name=id]').val('')
            toasMessage(data.msg,"success")
            FreightTable.ajax.reload()
            $('select[name="description"]').val(null).trigger('change');
            Freight.cancelButton.hide()
        }
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(data.msg,jqxHR,"danger")
    })
})


let FreightTable = Freight.table.DataTable({
    serverSide:true,
    paging:true,
    searching:true,
    destroy:true,
    ordering:false,
    "ajax": {
        url:Freight.table.attr("data-url"),
        method: "get"
    },
    // order: [[0, 'desc']],
    columns:[
        { data:'exchangeDate' },
        { data:'suppliername' },
        { data:'description' },
        { data:'quantity' },
        { data:'dollar' },
        { data:'exchangeRate' },
        { data:'totalAmountInPHP' },
       
        { 
            data:null,
            render:function(data){
                return ` ${data.reference==null
                            ?`<button
                                name="editFreightPayment"
                                value="${data.id}"
                                type="button"
                                class="btn btn-outline-secondary btn-sm">
                                <i class="fas fa-plus-circle"></i> Edit
                            </button>
                            <button
                                name="searchInvoiceFreight"
                                value="${data.id}"
                                type="button"
                                class="btn btn-outline-secondary btn-sm">Invoice
                            </button>`
                            :`<a target="_blank" href="${Freight.table.attr("data-cost").replace("invoice",data.invoice)}">${data.reference}</a>`}
                        
                        `
            }
        },
    ],
    
})

Freight.cancelButton.on('click',function(){
    Freight.form[0].reset()
    Freight.form.find('input[name=id]').val('')
    Freight.table.find("select[name=description]").val(null).trigger('change');
    Freight.cancelButton.hide()
}).hide()

$(document).on('click','button[name=editFreightPayment]',function(){
    Freight.cancelButton.show()
    let data = FreightTable.row( $(this).closest('tr') ).data()
    $.each($('#freightTable .form-control'),(ind, value) => {
        console.log(data[value.name]);
        Freight.table.find("input[name="+value.name+"]").val(data[value.name]);
        Freight.table.find("select[name=description]").append(new Option(data.description, data.description, true, true)).trigger('change');
    });
    $("input[name=id]").val($(this).val())
})

$(document).on('click','button[name=searchInvoiceFreight]',function(){
    $("#searchInvoiceForm")[0].reset()
    $("#searchInvoiceTable").DataTable().clear().draw()
    $("#modalPayment").modal("show")
    $("#modalPayment").find("#searchInvoiceForm input[name=control]").val("freight")
    $("#searchInvoiceTable").attr('data-invoicePayment',$(this).val())
})

$(document).on('click','button[name=addInvoiceFreight]',function(data){
    $.ajax({
        url:  Freight.table.attr("data-invoice"),
        type: 'POST',
        data: {
            _token:BaseModel._token,
            freight_payment: $("#searchInvoiceTable").attr("data-invoicePayment"),
            invoiceno:$(this).val()
        }
    }).done(function(data){
        if (data.msg) {
            invPayDetailForm[0].reset()
            FreightTable.ajax.reload()
            toasMessage(data.msg,"success")
            $("#modalPayment").modal("hide")
            $("#searchInvoiceTable").DataTable()
        }
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(data.msg,jqxHR,"danger")
    })
})