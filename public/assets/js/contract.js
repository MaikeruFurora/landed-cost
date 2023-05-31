const formContract      = $("#formContract")
const formSearchInvoice = $("#formSearchInvoice")
let mt                  = formContract.find("input[name=metricTon]")
let pmt                 = formContract.find("input[name=priceMetricTon]")
let amntUSD             = formContract.find("input[name=amountUSD]")
let percentage          = formContract.find("input[name=percentage]")
let exchangeRate        = formContract.find("input[name=exchangeRate]")
let amountPHP           = formContract.find("input[name=amountPHP]")
let paidAmountUSD       = formContract.find("input[name=paidAmountUSD]")
let contract_id         = $("input[name=contract_id]")
let input               = [];
/* The above code is using jQuery to loop through all the form controls inside an element with the ID
"formContract". It is then pushing the name attribute of each form control into an array called
"input". */ 
$.each($('#formContract .form-control'),(ind, value) => {
      input.push(value.name);
});
formContract.find(".btn-warning").hide().on('click',function(){
    formContract[0].reset()
    formContract.find('input[name=id]').val('')
    $(this).hide()
})
$('input').on('click',function(){
    $(this).select()
})

const getTotalAmountUSD = () => {

    if ((mt.val()!="" || mt.val()!=0 ) && (pmt.val()!="" || pmt.val()!=0)) {
        amntUSD.val(pmt.val() * mt.val())
    } else {
        // amntUSD.val(0)
        // percentage.val(0)
    }

}

const getAmount=()=>{

    if ((mt.val()!="" || mt.val()!=0 ) && (pmt.val()!="" || pmt.val()!=0) && (percentage.val()!="" || percentage.val()!=0)) {

        paidAmountUSD.val((amntUSD.val()*percentage.val())/100)

    }else{
        paidAmountUSD.val(0)
        percentage.val(0)
        // alert("Unable to Calculate Amount!");
    }

}

const getPercentage=()=>{

    if ((mt.val()!="" || mt.val()!=0 ) && (pmt.val()!="" || pmt.val()!=0) && (paidAmountUSD.val()!="" || paidAmountUSD.val()!=0)) {
        perc = ((paidAmountUSD.val()/amntUSD.val())*100);
        if ((parseInt(paidAmountUSD.val()) <= parseInt(amntUSD.val())) && parseInt(perc)<=100) {
            percentage.val((perc).toFixed(4))
        }else{
            paidAmountUSD.val(0)
            percentage.val(0)
        }

    }else{
        paidAmountUSD.val(0)
        percentage.val(0)
        // alert("Unable to Calculate Percentage!");   
    }

}

const getAmountPHP = () => {

        amountPHP.val(
            ((paidAmountUSD.val()!="" || parseInt(paidAmountUSD.val())!=0 ) && (exchangeRate.val()!="" || parseInt(exchangeRate.val())!=0)) ? (paidAmountUSD.val()*exchangeRate.val()) : 0
        )
}

amntUSD.number(true,4)
amountPHP.number(true,4)

paidAmountUSD.number(true,4).on('keyup',function(){
    getPercentage()
})

mt.number(true,4).on('keyup',function(){
    getPercentage()
    getAmount()
    getTotalAmountUSD()
    getAmountPHP()
})
pmt.number(true,4).on('keyup',function(){
    getPercentage()
    getAmount()
    getTotalAmountUSD()
    getAmountPHP()
})

exchangeRate.number(true,4).on('keyup',function(){
    getAmount()
    getAmountPHP()
})

formContract.find('input[name=exchangeRateDate]').datepicker({
    toggleActive: true,
    autoclose: true,
    format:'yyyy-mm-dd'
})

percentage.on('keyup',function(){
    if(percentage.val()<=100){
        getAmount() 
        getAmountPHP()
    } else {
        $(this).val(0)
    }
})

formContract.on('submit',function(e){
    e.preventDefault()
    $.ajax({
        url:"contract/store",
        type:'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        beforeSend:function(){
            BaseModel.disabledProperties('formContract')
        }
    }).done(function(data){
        
        BaseModel.disabledProperties('formContract',false)
        formContract.find(".btn-warning").hide()
        console.log(data);
        formContract[0].reset()
        formContract.find('input[name="id"]').val('')
        tblContract.ajax.reload()
        $($.fn.dataTable.tables( true ) ).css('width', '100%');
        $($.fn.dataTable.tables( true ) ).DataTable().columns.adjust().draw();

    }).fail(function(jxHR,textStatus,c){
        alert(textStatus)
        BaseModel.disabledProperties('formContract',false)
        formContract.find(".btn-warning").hide()
    })
})

let tblContract = $('#datatable').DataTable({
        responsive: true,
        serverSide: true,
        paging:true,
        ajax: {
            url: "contract/list", 
            method: "get"
        },
        columns:[
             {
                className: 'dt-control',
                orderable: false,
                data: null,
                defaultContent: '',
            },
            {
                orderable:false,
                data:null,
                render:function(data){
                    return `
                        ${(data.advance_payment.length==0)?` <button value="${data.id}" class="m-0 py-1 btnEdit btn btn-primary btn-sm" style="font-size:10px"><i class="far fa-edit"></i> Edit</button>`:''}
                        <button value="${data.id}" id="${data.contract_no}" class="m-0 py-1 btn btn-primary btn-sm btnAddInvoice" style="font-size:10px"><i class="fas fa-plus-circle"></i> Invoice</button>
                    `
                }
            },
            {   
                visible:false,
                orderable: false,
                data:"id"
            },
            {   
                visible:false,
                orderable: false,
                data:"amountPHP"
            },
            {   
                orderable: false,
                data:"contract_no"
            },
           
            {   
                orderable: false,
                data:"metricTon"
            },
            {   
                orderable: false,
                data:"priceMetricTon"
            },
            {   
                orderable: false,
                data:"amountUSD"
            },
            {   
                orderable: false,
                data:"amountPHP"
            },
            {   
                orderable: false,
                data:null,
                render:function(data){
                    return data.percentage + "%"
                }
            },
           
            {   
                orderable: false,
                data:"exchangeRate"
            },
            {   
                orderable: false,
                data:"exchangeRateDate"
            },
            {
                orderable: false,
                data:null,
                render:function(data){
                    if (data.advance_payment.length>0) {
                        let hold=`<table class="table table-bordered" style="font-size:10px">
                                    <tr>
                                        <th>Invoice</th>
                                        <th>MT</th>
                                        <th>Percent</th>
                                        <th>Allocated Amount</th>
                                        <th>Amount(USD)</th>
                                    </tr>`;
                            data.advance_payment.forEach(val=>{
                                hold+=` <tr>
                                            <td>${val.detail.invoiceno}</td>
                                            <td>${val.detail.qtymt}</td>
                                            <td>${val.percentage}%</td>
                                            <td>${$.number(val.allocatedAmount,4)}</td>
                                            <td>${$.number(val.amount,4)}</td>
                                        </tr>`
                            })

                            return hold;
                    } else {
                        return ''
                    }
              
                }
            },
        ]
});



$("#formSearchInvoice").on('submit',function(e){
    e.preventDefault()
   if($('input[name="search"]').val()!=""){
        $.ajax({
        url:`contract/search`,
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

$(document).on('click','.btnEdit',function(e){
    e.preventDefault()
    var data = tblContract.row( $(this).closest('tr') ).data();
    formContract.find(".btn-warning").show()
    input.filter(e => Object.keys(data).indexOf(e) !== -1).forEach(element => {
        $("input[name="+element+"]").val(data[element])
    });
})

$(document).on('click','.btnAddInvoice',function(){
    formSearchInvoice[0].reset()
    $("#searchInvoiceModalLabel").text('Ref: ' + $(this).attr("id"))
    $("input[name=contract_id]").val($(this).val())
    $(".showData").html(`<tr class="header text-center">  <td colspan="6">No data available</td> </tr>`)
    $("#searchInvoiceModal").modal("show")
})

$(document).on('click','button[name=btnSave]',function(){
        let iterate     =$(this).val()
        let pono        = $("input[name=pono-"+iterate+"]").val()
        let itemcode    = $("input[name=itemcode-"+iterate+"]").val()
        let cardname    = $("input[name=cardname-"+iterate+"]").val()
        let cardcode    = $("input[name=cardcode-"+iterate+"]").val()
        let vessel      = $("input[name=vessel-"+iterate+"]").val()
        let description = $("input[name=description-"+iterate+"]").val()
        let invoiceno   = $("input[name=invoiceno-"+iterate+"]").val()
        let broker      = $("input[name=broker-"+iterate+"]").val()
        let createdate  = $("input[name=createdate-"+iterate+"]").val()
        let docdate     = $("input[name=docdate-"+iterate+"]").val()
        let weight      = $("input[name=weight-"+iterate+"]").val()
        let quantity    = $("input[name=quantity-"+iterate+"]").val()
        let qtykls      = $("input[name=qtykls-"+iterate+"]").val()
        let qtymt       = $("input[name=qtymt-"+iterate+"]").val()
        let fcl         = $("input[name=fcl-"+iterate+"]").val()
        // $(this).closest("tr.header").remove();
        $.ajax({
            url:`contract/invoice/store/${contract_id.val()}`,
            type: "POST",
            data:{
                _token:BaseModel._token,pono,itemcode,cardname,cardcode,vessel,description,
                invoiceno,broker,createdate,docdate,weight,quantity,qtykls,qtymt,fcl,
            },
        }).done(function(data){
            tblContract.ajax.reload()
            toasMessage('Information','Successfully save the transaction','info')
        }).fail(function (jqxHR, textStatus, errorThrown) {
            console.log(errorThrown);
        })
        
        $(this).closest("tr.header").remove();
});

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
                                <button name="btnSave" class="btn btn-sm btn-primary m-0" type="submit" value="${i}" style="font-size:9px"><i class="fas fa-plus"></i> Add</button>
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