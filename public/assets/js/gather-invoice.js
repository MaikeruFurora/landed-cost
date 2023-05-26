const btnSaved      = $(".btn-saved")
const invoiceID     = []
const _token        = $('meta[name="_token"]').attr('content');
const openAmountId  = $("input[name=open_amount_id]").val()
const abortBtn      = $('button[name=abort]')
btnSaved.hide()
abortBtn.hide()

const dataTable = (data)=>{
    let hold=``
    if(data.length>0){
        data.forEach((val,i) => {
            hold+=` <tr class="header">
                        <form id="poForm" >
                            <input type="hidden" name="suppliername-${i}" value="${ val['suppliername'] }"> 
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
                            <input type="hidden" name="doc_date-${i}" value="${ val['doc_date'] }"> 
                            <td>${ val['pono'] }</td>
                            <td>${ val['suppliername']}<td>
                            <td>${ val['itemcode'] }</td>
                            <td>${ val['vessel'] } </td>
                            <td>${ val['description'] } </td>
                            <td>${ val['invoiceno'] } </td>
                            <td>${ val['broker'] ?? 'N/A'}</td>
                            <td>${ val['quantity'] } </td>
                            <td>${ val['qtykls'] } </td>
                            <td>${ val['qtymt'] } </td>
                            <td>${ val['fcl'] } </td>
                            <td style="font-size: 6;">
                                <button class="btnSave btn btn-sm btn-primary m-0" type="submit" value="${i}">Save</button>
                            </td>
                        </form>
                    </tr>
                `
        });
    }else{
        hold=` <tr class="header text-center">
                    <td colspan="18">No data available</td>
                </tr>`
    }
    
    $(".showData").html(hold)
    
}

$("#searchForm").on("submit",function(e){
    e.preventDefault()
    // alert("Dasad")
    $.ajax({
    url:`search/item/${openAmountId}`,
    type: "GET",
    data:{
        search:$('input[name="search"]').val(),
    },
    beforeSend:function(){
        $("tbody.showData").html(`
            <tr class="header text-center">
                <td colspan="18">
                    <div class="spinner-border spinner-border-sm" role="status">
                        <span class="sr-only">Loading...</span>
                    </div> Getting data...
                </td>
            </tr>
        
        `)
        $("#searchForm *").prop("disabled", true);
        abortBtn.prop('disabled',false).show()
    }
    }).done(function(data){
        dataTable(data)
        abortBtn.hide()
        $("#searchForm *").prop("disabled", false);
    }).fail(function (jqxHR, textStatus, errorThrown) {
        $("#searchForm *").prop("disabled", false);
        console.log(errorThrown);
    })
})

$(document).on('click','.btnSave',function(){
        let iterate         =$(this).val()
        let suppliername    = $("input[name=suppliername-"+iterate+"]").val()
        let pono            = $("input[name=pono-"+iterate+"]").val()
        let itemcode        = $("input[name=itemcode-"+iterate+"]").val()
        let cardname        = $("input[name=cardname-"+iterate+"]").val()
        let cardcode        = $("input[name=cardcode-"+iterate+"]").val()
        let vessel          = $("input[name=vessel-"+iterate+"]").val()
        let description     = $("input[name=description-"+iterate+"]").val()
        let invoiceno       = $("input[name=invoiceno-"+iterate+"]").val()
        let broker          = $("input[name=broker-"+iterate+"]").val()
        let createdate      = $("input[name=createdate-"+iterate+"]").val()
        let docdate         = $("input[name=docdate-"+iterate+"]").val()
        let weight          = $("input[name=weight-"+iterate+"]").val()
        let quantity        = $("input[name=quantity-"+iterate+"]").val()
        let qtykls          = $("input[name=qtykls-"+iterate+"]").val()
        let qtymt           = $("input[name=qtymt-"+iterate+"]").val()
        let fcl             = $("input[name=fcl-"+iterate+"]").val()
        let doc_date        = $("input[name=doc_date-"+iterate+"]").val()
        // $(this).closest("tr.header").remove();
    $.ajax({
            url:`store/${openAmountId}`,
            type: "POST",
            data:{
                _token,suppliername,
                pono,itemcode,cardname,cardcode,vessel,description,invoiceno,
                broker,createdate,docdate,weight,quantity,qtykls,qtymt,fcl,doc_date
            },
        }).done(function(data){
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

btnSaved.on('click',function(){
        $.ajax({
            url:`store/${$(this).val()}`,
            type: "POST",
            data:{
                id:invoiceID,
                _token
            },
            beforeSend:function(){
                $(this).html(`
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">Loading...</span>
                </div> Sending ticket...
                `).attr('disabled',true)
            }
        }).done(function(data){
            btnSaved.hide()
            // InvoiceTable.ajax.reload()
        }).fail(function (jqxHR, textStatus, errorThrown) {
            console.log(errorThrown);
        })
})

// abortBtn.on('click',function(){
//     $("#searchForm *").prop("disabled", false);
//     $(".showData").html(`<tr class="header text-center"><td colspan="18">No data available</td></tr>`)
//     $(this).hide()
// })

