/**
     * 
     * @abstract
     * LC/DP NEGO
     * 
     */
    
    
const EXCEPT_CODE = ['LC','NEG','FR']

const btnSaveParticular     = $(".btn-save-particular")

const btnSaveRerefenceNo    = $(".btn-save-referenceno")

const btnNegoCancel         = $("button[name=btnNegoCancel]")

const btnFreightCancel      = $("button[name=btnFreightCancel]")

btnNegoCancel.hide()

btnFreightCancel.hide()

btnSaveParticular.hide()

btnSaveRerefenceNo.hide()

/**
* 
* variable of LC/DP Nego
* 
* 
* 
*/

let qtymtT                  = $("input[name='qtymt']");

let cardname                = $("select[name='cardname']");

let fcl                     = $("input[name='fcl']");

let actualQtyKLS            = $("input[name='actualQtyKLS']");

let actualQtyMT             = $("input[name='actualQtyMT']");

let metricTon               = $("input[name='metricTon']");

let totalNego               = $("input[name='totalNego']");

let priceMetricTon          = $('input[name=priceMetricTon]')

let exchangeRate            = $('input[name=exchangeRate]')

let exchangeRateDate        = $('input[name=exchangeRateDate]')

let lc_amount               = $('input[name=lc_amount]')

let lc_mt                   = $('input[name=lc_mt]')

let percentage              = $('input[name="percentage"]')

let negAmnt                 = $("input[name='amount']")

let idNego                  = $("input[name='id-nego']")

let idFreight               = $("input[name='id-freight']")

let negoTotalAmount         = $("input[name='negoTotalAmount']")

//company name modal
let selectCompany           = $("select[name='selectCompany']")

let btnMinusCom             = $("button[name='btnMinusCom']")

let btnAddCom               = $("button[name='btnAddCom']")

// freight variable

let vesselType              = $("input[name='vesselType']")

let freightDollarRate       = $("input[name='freightDollarRate']")

let freightExhangeRate      = $("input[name='freightExhangeRate']")

let freightExhangeRateDate  = $("input[name='freightExhangeRateDate']")

let freightTotalAmount      = $("input[name='freightTotalAmount']")

let totalOpenCharge         = $('input[name="totalOpenCharge"]')

let totalFreight            = $('input[name="totalFreight"]')

let sumPercentage           = 0

let sumAmount               = 0

let getPerMetricTon         = 0

/**
 * 
 *  FOR NUMBER FORMAT
 * 
 */

$('.amount-class').number( true, 4 );
$('.opening-charge').number( true, 4 );
$('.freight-amount').number( true, 4 );
$('#totalLandedCost').number( true, 4 );
$('#projectedCost').number( true, 4 );
$("input[name=priceMetricTon]").number( true, 4  )
$("input[name=exchangeRate]").number( true, 4  )
$("input[name=amount]").number( true, 4  )
$("input[name=negoTotalAmount]").number( true, 4  )
/**
 * 
 *  FOR DATE FORMAT PLUGIN
 * 
 */

$('input[name=freightExhangeRateDate]').datepicker({
    toggleActive: true,
});
$('input[name=exchangeRateDate]').datepicker({
    toggleActive: true,
});

$('.transaction-date-class').datepicker({
    toggleActive: true,
    autoclose: true
}).on('change',function(){
    
    console.log($(this).val());
    
    let id = $(this).attr("id");
    
    requestToSave(id,$("input[name=amount-"+id+"]").val(),$("input[name=referenceno-"+id+"]").val(), $(this).val())

});
/**
 * avoid alphabet
 */
$('.amount-class').keypress(function(event) {
  if ((event.which != 46 || $(this).val().indexOf('.') != -1) && (event.which < 48 || event.which > 57)) {
    event.preventDefault();
  }
});


//NOTE

$(".btn-note").on('click',function(){
    $('.btn-note').popover('hide');
    $.ajax({
        url:`particular-notes/`+$(this).val(),
        type:'GET',
     }).done(function(data){
        $(".btn-note").html(`
        <i class="fas fa-comment-alt text-info mt-1" style="font-size:16px"></i>
        <small style="font-size: 10px;display:block">Notes</small>
        `)
        $('textarea[name=note-'+data.id+']').val(data.notes)
    }).fail(function (jqxHR, textStatus, errorThrown) {
        console.log(errorThrown);
    })
})

$(document).on("click",".pop-save",function(){
    let id = $(this).val()
    $.ajax({
        url:`particular-notes/store/`+id,
        type:'POST',
        data:{
            notes:$('textarea[name=note-'+id+']').val(),
            _token:BaseModel._token
        },
        beforeSend:function(data){
            $(".pop-save").html(`
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
                Saving
            `)
        }
     }).done(function(data){
        $('.btn-note').popover('hide');
        $(".pop-save").html(`&nbsp;&nbsp;<i class="fas fa-pencil-alt"></i>&nbsp;Save&nbsp;&nbsp;`)
        $('textarea[name=note-'+data.id+']').val(data.notes)
        $.toast({
            heading: 'Information',
            text: 'Successfully saved',
            icon: 'info',
            loader: true,        // Change it to false to disable loader
            loaderBg: '#9EC600'  // To change the background
        })
    }).fail(function (jqxHR, textStatus, errorThrown) {
        console.log(errorThrown);
    })
})

$(document).on('click','.pop-close',function() {
    $('.btn-note').popover('hide');
});


////// TOTAL LANDED COST

const autoTotalLandedCost = () =>{

    let sum_amount = 0;

    $('.amount-class').each(function(){

        if($(this).val()!=''){

            sum_amount += parseFloat(($(this).val()).replace(/\,/g,''));   

        }
    })

    $('#totalLandedCost').val(sum_amount);
    console.log(actualQtyKLS.val()!=0);
    if (actualQtyKLS.val()!="" && actualQtyKLS.val()!=0) {
        $('#projectedCost').val((sum_amount/($('input[name="actualQtyKLS"]').val())));
    } else {
        $('#projectedCost').val((sum_amount/($('input[name="qtykls"]').val())));
    }

}

const autoTotalOpenCharge = () =>{

    let sumOpenCharge = 0;
    
    $('.opening-charge').each(function(){

        if($(this).val()!=''){

            sumOpenCharge += parseFloat(($(this).val()).replace(/\,/g,'')); 

        }

    })

    $('#totalLandedCost').val(sumOpenCharge);
}




const totalMTxPMTxER = (metricTon,priceMetricTon, exchangeRate) => {

    if (priceMetricTon!="" || exchangeRate!="") {

        return ((metricTon * priceMetricTon) * exchangeRate)

    }

    return false;

}

const numberWithCommas = (amount) => amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")

actualQtyKLS.on('input',function(){
    actualQtyMT.val($(this).val()/1000)
    autoTotalLandedCost()
})
actualQtyMT.on('input',function(){
    actualQtyKLS.val($(this).val()*1000)
    autoTotalLandedCost()
})

$(".btnSubmit").on('click',function(e){
    e.preventDefault()
    //nego
    let id = $('input[name=id-'+$(this).val()+']').val()
    //global
    let spcPrtclr = $('input[name=spcPrtclr-'+$(this).val()+']').val()

    switch (spcPrtclr) {
        case EXCEPT_CODE[0]:
            $("input[name=amount-"+ id +"]").val(totalOpenCharge.val())
            break;
        case EXCEPT_CODE[1]:
            $("input[name=amount-"+ id +"]").val(totalNego.val())
            break;
        case EXCEPT_CODE[2]:
            $("input[name=amount-"+ id +"]").val(totalFreight.val())
            break;
        default:
            return false;
            break;
    }


    $.ajax({
        url:`form-store`,
        type:'POST',
        data:{
            _token:BaseModel._token,
            totalNego: totalNego.val(),
            lc_amount:lc_amount.val(),
            lc_mt:lc_mt.val(),
            id,spcPrtclr,
            totalFreight:totalFreight.val(),
            freightExhangeRateDate:freightExhangeRateDate.val(),
            freightExhangeRate:freightExhangeRate.val(),
            freightDollarRate:freightDollarRate.val()
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
});

///////// REFERENCE NUMBER

const requestToSave = (id,amount,referenceno,transactionDate) =>{
    let _token = BaseModel._token
    $.ajax({
        url:`particular-input/`+id,
        type:'POST',
        data:{
            _token,
            amount,
            referenceno,
            transactionDate
        },
        beforeSend:function(data){
            $(".btn-outline-success").html(`
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            `)
        }
     }).done(function(data){
        $(".btn-outline-success").html(`<i class="fas fa-check"></i>`)
        $(".btnCheckRef-"+id).hide()
        $(".btnCheckAmnt-"+id).hide()
        autoTotalLandedCost()
        toasMessage("Information","Successfully saved the transaction","info")
    }).fail(function (jqxHR, textStatus, errorThrown) {
        console.log(errorThrown);
    })
}


$(".amount-class").on('keyup',function(e){

    if (e.which === 13) {

        let id = $(this).attr("id");

        requestToSave( 
            id, 
            $("input[name=amount-"+id+"]").val(),
            $("input[name=referenceno-"+id+"]").val(),
            $("input[name=transaction-date-"+id+"]").val()
        )

    }

})

$(".refrence-class").on('keyup',function(e){

    if (e.which === 13) {
    
        let id = $(this).attr("id");

        requestToSave( 
            id, 
            $("input[name=amount-"+id+"]").val(),
            $("input[name=referenceno-"+id+"]").val(),
            $("input[name=transaction-date-"+id+"]").val()
        )
    
    }

})

$(".transaction-date-class").on('keyup',function(e){

    if (e.which === 13) {

        let id = $(this).attr("id");

        requestToSave( 
            id, 
            $("input[name=amount-"+id+"]").val(),
            $("input[name=referenceno-"+id+"]").val(),
            $("input[name=transaction-date-"+id+"]").val()
        )
    
    }

})

autoTotalLandedCost()

//// LCDP OPENNING CHARGE


/**
 * 
 * 
 * INVOICE FORM (DETAILS DATA)
 * 
 * 
 *  */

 $("#invoiceForm").on('submit',function(e){
    e.preventDefault();
    $.ajax({
        url:`invoice-detail/store`,
        type:'POST',
        data: new FormData(this),
        processData: false,
        contentType: false,
        cache: false,
        beforeSend:function(){
            return $("#invoiceForm").find('.btn-success').html(`
                <div class="spinner-border spinner-border-sm" role="status">
                    <span class="sr-only">Loading...</span>
                </div> Saving...
            `).attr('disabled',true)
        }
    }).done(function(data){
        console.log(data);
        $("#invoiceForm").find('.btn-success').html('<i class="fas fa-user-shield"></i> Save').attr('disabled',false)
        toasMessage("Information","Successfully saved the transaction","info")
    }).fail(function (jqxHR, textStatus, errorThrown) {
        $("#invoiceForm").find('.btn-success').html('<i class="fas fa-user-shield"></i> Save').attr('disabled',false)
        console.log(errorThrown);
        toasMessage(textStatus,jqxHR,"danger")
    })
 })

 $("button[name=postBtn]").on('click',function(e){
    let id = $("#invoiceForm").find('input[name=id]').val()
    e.preventDefault();
    $.ajax({
        url:`invoice-detail/post`,
        type:'POST',
        data:{
            _token:BaseModel._token,
            invoice:id
        }
    }).done(function(data){
       window.location.reload()
    }).fail(function (jqxHR, textStatus, errorThrown) {
        // $("#invoiceForm").find('.btn-success').html('Post').attr('disabled',false)
        console.log(errorThrown);
    })
 })
  
$("button[name=print]").on('click',function(){
    BaseModel.loadToPrint($(this).val())
})