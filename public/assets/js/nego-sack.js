let percentage           = $("input[name=percentage]");
let exchangeRate         = $("input[name=exchangeRate]");
let totalDollar          = $("input[name=totalDollar]");
let totalDollarAllocated = $("input[name=totalDollarAllocated]");
let amount               = $("input[name=amount]");
let negoEmptySackTable   = $("#negoEmptySackTable");
let totalPecent          = 0
let totalAmount          = 0

$(".negoPrice").on('keyup',function(){
    let i  = $(this).attr("id")
    let val = $(this).val()
    let qty = $("input[data-qty="+i+"]").val()
    let totalDollarValue = qty*val
    $("input[data-total="+i+"]").val(totalDollarValue)
    $("input[data-cost="+i+"]").val(totalDollarValue*exchangeRate.val())
    totalDollarFunc()
})

const totalDollarFunc = ()=>{
    let sum=0;
    $('.negoTotal').each(function(){
        sum+=parseFloat($(this).val())
    })

    totalDollar.val(sum)   
        // getAmount()
        // getPercentage()
}

$("#negoForm").on('submit',function(e){
    e.preventDefault()
    let url = $("#negoForm").attr("action").split("sample")[0];
    let id  = ($(this).find('button').attr("id"));
    console.log(url);
    $.ajax({
        url:url+id,
        type:'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
    }).done(function(data){
        tableNego(id)
        $('.negoPrice').prop('readonly',true)
        amount.prop('readonly',true)
        percentage.prop('readonly',true)
        $("#negoForm")[0].reset()
        $(".text-danger").text('')
    }).fail(function (jqxHR, textStatus, errorThrown) {
        toasMessage(textStatus,jqxHR,"danger")
    })
})

$(".btnTransaction").on("click",function(){
    tableNego($(this).val())
})


const tableNego = (id) =>{
   
   let hold,tNego = 0,tSumNego=0;
   $.ajax({
       url:`nego-list/${id}`,
       type:'GET',
   }).done(function(data){
       if(data.length){
            totalPecent = data.reduce((total,val)=>{
                return total+=val.percentage
            },0)
            totalAmount = data.reduce((total,val)=>{
                return total+=val.amount
            },0)
            data.forEach((element,i) => {
               tNego=element.exchangeRate*element.amount
               tSumNego+=tNego
               hold+=`  <tr class="${element.id}">
                           <td>${++i}</td>
                           <td>${element.exchangeRate}</td>
                           <td>${BaseModel.commaSeparateNumber(element.amount)}</td>
                           <td>${BaseModel.commaSeparateNumber(tNego)}</td>
                           <td>${element.percentage}%</td>
                           <td>${element.exchangeRateDate}</td>
                           <td>
                                <button name="dataPrice" id="${element.prices}" class="btn btn-default btn-sm p-0 px-2"><i style="font-size:15px" class="far fa-copy"></i></button>
                            </td>
                            <td>
                                <button name="dateRemove" value="${element.id}" class="btn btn-default btn-sm p-0 px-2"><i style="font-size:15px" class="fas fa-times"></i></button>
                           </td>
                        </tr>`
           });
            hold+=`<tr>
                        <td></td>
                        <td></td>
                        <td><input type="text" class="form-control form-control-sm text-center amount-class" value="${BaseModel.commaSeparateNumber(totalAmount)}" readonly></td>
                        <td><input type="text" name="totalNego" class="form-control form-control-sm text-center amount-class" value="${BaseModel.commaSeparateNumber(tSumNego)}" readonly></td>
                        <td><input type="text" class="form-control form-control-sm text-center" value="${totalPecent}%" readonly></td>
                        <td colspan="3"></td>
                    </tr>`
       }else{
          
           hold=`<tr><td colspan="8" class="text-center">No data available</td></tr>`
       }

       $("#tblNego").find("tbody").html(hold)

   }).fail(function (jqxHR, textStatus, errorThrown) {
       // $("#invoiceForm").find('.btn-success').html('Post').attr('disabled',false)
       console.log(errorThrown);
   })

}

exchangeRate.on('keyup',function(){
    let val = $(this).val()  
    console.log(val);
    $('.negoPrice').prop('readonly',(val=="" || val==0))
    totalDollarFunc()
    amount.prop('readonly',(val=="" || val==0))
    percentage.prop('readonly',(val=="" || val==0))
    if (checkPriceValue(false)) {
        $(".negoTotal").each(function(i){
            $("input[data-cost="+i+"]").val($(this).val()*val)
        })
    }
})

percentage.on('keyup',function(){
    let value = $(this).val() 
    let ttper = parseFloat(totalPecent)+parseFloat(value)
    amount.prop("readonly",!(value=="" || value==0 && parseFloat(value)>100 || parseFloat(value)<=0 || ttper>100))
    getAmount()
    if (parseFloat(value)>100 || parseFloat(value)<=0 || ttper>100) {
        $(this).val(0)
        amount.val(0)
    }

    if (!checkPriceValue(true)) {
        amount.val(0)
        $(this).val(0)
    }

    allocatedAmountPerSack()
}).on('focus',function(){
    amount.prop("readonly",false)
})

amount.on('keyup',function(){
    let val = $(this).val()  
    percentage.prop("readonly",!(val=="" || val==0))
    if (parseFloat(totalDollar.val())>=parseFloat(val)) {   
        getPercentage()
        $(this).val($(this).val())
        console.log('condition 1');
    }else 
    
    // if (parseFloat(val)>=parseFloat(totalAmount) ) {
    //     console.log("condition 2");
    //     getPercentage()
    //     $(this).val($(this).val())
    // }else
    
    {
        console.log("false conditin");
        percentage.val(0)
        $(this).val(0)
        percentage.prop('readonly',false)
    }
    if (!checkPriceValue(true)) {
        percentage.val(0)
        $(this).val(0)
    }

    allocatedAmountPerSack()

}).on('select',function(){
    percentage.prop("readonly",false)
})

const checkPriceValue=(validate)=>{
    let reqlength = $('.negoPrice').length;
    let value = $(".negoPrice").filter(function(){
        if (validate) {
            (this.value=='' || this.value=='0.0000')? $(this).addClass('is-invalid') : $(this).removeClass('is-invalid')
        }
        return (this.value=='' || this.value=='0.0000')
    })
    return (value.length===0);
}

const getAmount = () =>{
    $("input[name=amount]").val((parseFloat(totalDollar.val())*parseFloat(percentage.val()))/100)
}

const getPercentage = () =>{
    $("input[name=percentage]").val((parseFloat(amount.val())/parseFloat(totalDollar.val()))*100)
}

const allocatedAmountPerSack=()=>{
    let data    = JSON.parse(negoEmptySackTable.attr('data-item'))
    let dataVal = 0;
    let sum     = 0;
    let allocatedTotal =0;
    data.forEach((item,i) => {
        dataVal         = parseFloat($("input[data-total="+i+"]").val())/parseFloat(totalDollar.val())
        allocatedTotal  = dataVal*amount.val()
        sum+=allocatedTotal
        $("input[data-allocated="+i+"]").val(allocatedTotal)
    });

    totalDollarAllocated.val(sum)
}

$(document).on('click','button[name="dataPrice"]',function(){
    console.log(exchangeRate.val());
   if(exchangeRate.val()!='' || exchangeRate.val()!=0){
        $(this).attr('id').split(',').forEach((element,i) => {  
            $("input[data-price="+i+"]").val(element)
            let qty = $("input[data-qty="+i+"]").val()
            let price = $("input[data-price="+i+"]").val()
            $("input[data-total="+i+"]").val(qty*price)
            $("input[data-cost="+i+"]").val((qty*price)*exchangeRate.val())
        })
        totalDollarFunc()
    }else{
        toasMessage("Information","Before copying the price, enter the dollar rate.","warning")
    }
})

$(".btnNegoSubmit").on("click",function(){
    let id    = $(this).val()
    let totalNego =  $('input[name=totalNego]').val()
    $("input[name=amount-"+id+"]").val(totalNego)
    $.ajax({
        url:`form-store`,
        type:'POST',
        data:{
            _token:BaseModel._token,totalNego,id,spcPrtclr:'NEG'
        },
        beforeSend:function(){
            $(".btnSubmit").html(`
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                Saving
            `)
        }
    }).done(function(data){
        $(".btnSubmit").html(`Save transaction`)

        autoTotalLandedCost()

        toasMessage("Information","Successfully saved the transaction","info")

    }).fail(function (jqxHR, textStatus, errorThrown) {
        console.log(errorThrown);
    })
})

$(document).on("click","button[name=dateRemove]",function(){
    let id = $(this).val()
    if (confirm('Are you sure you want delete this?')) {
        $.ajax({
            url:`nego-delete/${id}`,
            type:'DELETE',
            data:{_token:BaseModel._token},
        }).done(function(data){
            totalPecent=0;
            totalAmount=0;
            console.log(data);
            tableNego($(".btnTransaction").val())
            toasMessage("Information","Successfully saved the transaction","info")
        }).fail(function (jqxHR, textStatus, errorThrown) {
            console.log(errorThrown);
        })
    }
        return false
})