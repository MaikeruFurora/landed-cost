 /**
     * 
     * 
     * @abstract
     * 
     * NEGO JAVASCRIPT CODE
     * 
     * 
     */


 const tableNego = (id,qtymt) =>{
       
    let hold,tNego = 0,tSumNego=0;
    $.ajax({
        url:`nego-list/${id}`,
        type:'GET',
    }).done(function(data){
        console.log(data);
        if(data.length){
            sumAmount=0
            sumPercentage=0
            data.forEach(element => {
                getPerMetricTon=element.priceMetricTon
                sumAmount+=element.amount
                sumPercentage+=element.percentage
                tNego=(element.amount*element.exchangeRate)
                tSumNego+=tNego
                hold+=`  <tr class="${element.id}">
                            <td class="priceMetricTon">${element.priceMetricTon}</td>
                            <td class="percentage">${element.percentage}</td>
                            <td class="negAmnt">${element.amount}</td>
                            <td class="exchangeRate">${element.exchangeRate}</td>
                            <td class="exchangeRateDate">${element.exchangeRateDate}</td>
                            <td><b>${BaseModel.commaSeparateNumber(tNego)}</b></td>
                            <td class="text-center">
                                <button class="btn btn-sm pb-0 pt-0 pl-1 pr-2 pencil-nego" style="font-size:12px"><i class="fas fa-pencil-alt text-info "></i></button>
                                ${
                                    BaseModel.myRights? `<button value="${element.id}" id="${id}_${qtymt}" class="btn btn-sm pb-0 pt-0 pl-2 pr-1 times-nego" style="font-size:14px"><i class="fas fa-times text-danger"></i></button>`:``
                                }
                            </td>
                        </tr>`
            });
            hold+=` <tr>
                        <td class="text-right">Total</td>
                        <td ><b>${sumPercentage}%</b></td>
                        <td ><b>$${BaseModel.commaSeparateNumber(sumAmount)}</b></td>
                        <td colspan="2" class="text-right text-white bg-dark">Total Amount</td>
                        <td class="tsumNego"><b>&#8369;&nbsp;${(BaseModel.commaSeparateNumber(tSumNego))}</b></td>
                    </tr>`
            hold+=` <tr>
                        <td class="text-right">Remaining Balance</td>
                        <td ><b>${100-sumPercentage}%</b></td>
                        <td ><b>$${BaseModel.commaSeparateNumber((metricTon.val()*getPerMetricTon)-sumAmount)}</b></td>
                        <td colspan="3"></td>
                    </tr>`
            priceMetricTon.val(getPerMetricTon)
        }else{
            sumPercentage=0
            getPerMetricTon=0
            priceMetricTon.val('')
            hold=`<tr><td colspan="6" class="text-center">No data available</td></tr>`
        }
        
        $("button[name=btnNego]").prop('disabled',(sumPercentage>=100))            
        totalNego.val(tSumNego)
        $("#tableNeg").html(hold)
        $(".percentLeft").text((100-sumPercentage)) //-> show percentage left
    }).fail(function (jqxHR, textStatus, errorThrown) {
        // $("#invoiceForm").find('.btn-success').html('Post').attr('disabled',false)
        console.log(errorThrown);
    })

}



$("button[name=btnNego]").on('click',function(e){
    e.preventDefault()
    let id = $(this).val()
    $.ajax({
        url:`nego-store/${id}`,
        type:'POST',
        data:{
            priceMetricTon:priceMetricTon.val(),
            exchangeRate:exchangeRate.val(),
            exchangeRateDate:exchangeRateDate.val(),
            percentage:percentage.val(),
            amount:negAmnt.val(),
            _token:BaseModel._token,
            id:idNego.val()
        },
    }).done(function(data){
        $("button[name=btnNego]").text('Add Transaction')
        console.log(data);
        negoFields(false,{
            priceMetricTon:null,
            exchangeRate:null,
            exchangeRateDate:null,
            percentage:null,
            amount:null,
            negoTotalAmount:null,
            id:null
        })
        tableNego(id,metricTon.val())
        toasMessage("Information","Successfully saved the transaction","info")
        
    }).fail(function (jqxHR, textStatus, errorThrown) {
        console.log(errorThrown);
    })

})

$(".btnTransaction").on('click',function(){
    switch ($(this).attr("data-code")) {
        case 'NEG':
                tableNego($(this).val(),$(this).attr("id"))
            break;
        case 'FR':
                tableFreight($(this).val())
            break;
        default:
            break;
    }
   
})

$(document).on("click",".pencil-nego",function(){
    $("button[name=btnNego]").text('Update Transaction').attr('disabled',false)
    negoFields(true,{
        priceMetricTon:$(this).closest("tr").find(".priceMetricTon").text(),
        exchangeRate:$(this).closest("tr").find(".exchangeRate").text(),
        exchangeRateDate:$(this).closest("tr").find(".exchangeRateDate").text(),
        percentage:$(this).closest("tr").find(".percentage").text(),
        amount:$(this).closest("tr").find(".negAmnt").text(),
        negoTotalAmount:$(this).closest("tr").find(".negAmnt").text()*$(this).closest("tr").find(".exchangeRate").text(),
        id:$(this).closest("tr").attr('class')
    })
})

const negoFields = (cancel,data) =>{

    cancel? btnNegoCancel.show() : btnNegoCancel.hide()
    priceMetricTon.val(data.priceMetricTon)
    exchangeRate.val(data.exchangeRate)
    exchangeRateDate.val(data.exchangeRateDate)
    percentage.val(data.percentage)
    negAmnt.val(data.amount)
    negoTotalAmount.val(data.amount*data.exchangeRate)
    idNego.val(data.id)
    
}

btnNegoCancel.on('click',function(){
    $("button[name=btnNego]").text('Add Transaction').attr('disabled',(sumPercentage>=100))
    negoFields(false,{
        priceMetricTon:null,
        exchangeRate:null,
        exchangeRateDate:null,
        percentages:null,
        amount:null,
        negoTotalAmount:null,
        id:null
    })
})

$(document).on('click','.times-nego',function(){
    if (confirm('Are you sure you want delete this?')) {
        let idAttr = $(this).attr('id')
        const [id,qtymt] = idAttr.split('_')
        $.ajax({
            url:`nego-delete/${$(this).val()}`,
            type:'DELETE',
            data:{_token:BaseModel._token},
        }).done(function(data){
            console.log(data);
            tableNego(id,qtymt)
            toasMessage("Information","Successfully saved the transaction","info")
        }).fail(function (jqxHR, textStatus, errorThrown) {
            console.log(errorThrown);
        })
    }
        return false
})

const checkPercentage = ()=>{

    let ptotal = parseInt(sumPercentage)+parseInt(percentage.val())
    if(ptotal<=100 ){
        $(".percentLeft").text((100-ptotal))
    }else{
        $(".percentLeft").text(100-sumPercentage)
        percentage.val('')
        amount.val('')
    }

}


const getAmount=()=>{

    let negTotalPercentage = (priceMetricTon.val() * metricTon.val())

    negAmnt.val(((negTotalPercentage*percentage.val())/100))

}

const getPercentage=()=>{

    let negTotalPercentage = (priceMetricTon.val() * metricTon.val())

    if (negTotalPercentage>=negAmnt.val()) {
        perc = (negAmnt.val()/negTotalPercentage);
        percentage.val((perc*100).toFixed(4))
    }else{
        percentage.val(0)
    }
    
}

const negoTotalAmountCompute = () =>{
    
    negoTotalAmount.val((negAmnt.val()*exchangeRate.val()))

}

priceMetricTon.on('input',function(){
    negoTotalAmountCompute()
    getAmount()
    getPercentage()
})

percentage.on('input',function(){
    negoTotalAmountCompute()
    getAmount()
    // getPercentage()
    checkPercentage()
})

negAmnt.on('input',function(){
    negoTotalAmountCompute()
    getPercentage()
})

exchangeRate.on('input',function(){
    negoTotalAmountCompute()
})
