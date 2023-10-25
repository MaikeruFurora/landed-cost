  /**
     * 
     * 
     *  FREIGHT  TRANSACTION
     * 
     * 
     */

  const freightTotalAmountCompute = () =>{
        
    freightTotalAmount.val((vesselType.val()*freightExhangeRate.val())*freightDollarRate.val())

}

freightExhangeRate.on('input',function(){

    freightTotalAmountCompute()

})

freightDollarRate.on('input',function(){

    freightTotalAmountCompute()

})

const tableFreight = (id) =>{
    vesselType.val(
        (cardname.val()=="MIP")?fcl.val():qtymtT.val()
    )
    let holdfFreight=''
    let totalFreightTable = 0
    let freightTotalRow = 0
    $.ajax({
        url:`freight-list/${id}`,
        type:'GET',
    }).done(function(data){
        console.log(data);
        if(data.length){
            
            data.forEach(element => {
                totalFreightTable+=((vesselType.val()*element.dollarRate)*element.exchangeRate)
                holdfFreight+=`
                        <tr class="${element.id}">
                            <td class="dollarRate">${element.dollarRate}</td>
                            <td class="exchangeRate">${element.exchangeRate}</td>
                            <td class="freightTotalRow">${((vesselType.val()*element.dollarRate)*element.exchangeRate)}</td>
                            <td class="exchangeRateDate">${element.exchangeRateDate}</td>
                            <td class="text-center">
                                <button class="btn btn-sm pb-0 pt-0 pl-1 pr-2 pencil-freight" style="font-size:12px"><i class="fas fa-pencil-alt text-info "></i></button>
                                <button class="btn btn-sm pb-0 pt-0 pl-1 pr-2 times-freight" value="${element.id}" id="${id}" style="font-size:12px"><i class="fas fa-times text-danger "></i></button>
                            </td>
                        </tr>`
            });

            holdfFreight+=`<tr><td colspan="2"></td><td>${totalFreightTable}</td><td></td></tr>`
            // priceMetricTon.val(getPerMetricTon)
        }else{
            holdfFreight=`<tr><td colspan="6" class="text-center">No data available</td></tr>`
        }

        $(".freightTable").html(holdfFreight)
        totalFreight.val(totalFreightTable)
    }).fail(function (jqxHR, textStatus, errorThrown) {
        console.log(errorThrown);
    })
    
}

$("button[name=btnFreight]").on('click',function(e){
    e.preventDefault()
    let id = $(this).val()
    $.ajax({
        url:`freight-store/${id}`,
        type:'POST',
        data:{
            vesselType:vesselType.val(),
            dollarRate:freightDollarRate.val(),
            exchangeRate:freightExhangeRate.val(),
            exchangeRateDate:freightExhangeRateDate.val(),
            _token:BaseModel._token,
            id:idFreight.val()
        },
    }).done(function(data){
        $("input[name=referenceno-"+ id +"]").val(vesselType.val()+" * "+freightExhangeRate.val()+" * "+freightDollarRate.val())
        tableFreight(id)
        $("button[name=btnFreight]").text('Add Transaction')
        freightFields(false,{
            dollarRate:'',
            exchangeRate:'',
            exchangeRateDate:'',
            id:'',
            freightTotalAmount:'',
        })
        
        tableNego(id,metricTon.val())
        toasMessage("Information","Successfully saved the transaction","info")
    }).fail(function (jqxHR, textStatus, errorThrown) {
        console.log(errorThrown);
    })
        
})


const freightFields = (cancel,data) =>{
    cancel? btnFreightCancel.show() : btnFreightCancel.hide()
    freightDollarRate.val(data.dollarRate)
    freightExhangeRate.val(data.exchangeRate)
    freightExhangeRateDate.val(data.exchangeRateDate)
    freightTotalAmount.val(data.freightTotalAmount)
    idFreight.val(data.id)
}

$(document).on("click",".pencil-freight",function(){
    $("button[name=btnFreight]").text('Update Transaction').attr('disabled',false)
    freightFields(true,{
        dollarRate:$(this).closest("tr").find(".dollarRate").text(),
        exchangeRate:$(this).closest("tr").find(".exchangeRate").text(),
        exchangeRateDate:$(this).closest("tr").find(".exchangeRateDate").text(),
        freightTotalAmount:$(this).closest("tr").find(".freightTotalRow ").text(),
        id:$(this).closest("tr").attr('class')
    })
})

btnFreightCancel.on('click',function(){
    $("button[name=btnFreight]").text('Add Transaction')
    freightFields(false,{
        freightDollarRate:null,
        dollarRate:null,
        exchangeRate:null,
        exchangeRateDate:null,
        freightTotalAmount:null,
        id:null,
    })
})

$(document).on('click','.times-freight',function(){
    let idAttr = $(this).attr('id')
    if (confirm('Are you sure you want delete this?')) {
        $.ajax({
            url:`freight-delete/${$(this).val()}`,
            type:'DELETE',
            data:{_token:BaseModel._token},
        }).done(function(data){
            tableFreight(idAttr)
            toasMessage("Information","Successfully saved the transaction","info")
        }).fail(function (jqxHR, textStatus, errorThrown) {
            console.log(errorThrown);
        })
    }
        return false
})