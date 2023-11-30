//company name modal
let selectCompany           = $("select[name='selectCompany']")

let btnMinusCom             = $("button[name='btnMinusCom']")

let btnAddCom               = $("button[name='btnAddCom']")

var $myGroup = $('#accordionExample');
    $myGroup.on('show.bs.collapse','.collapse', function() {
    $myGroup.find('.collapse.in').collapse('hide');
});

$(".amount-class").number(true, 4)
$(".number-class").number(true, 4)
$('#totalLandedCost').number( true, 4 );
$('.transaction-date-class').datepicker({
    toggleActive: true,
    autoclose: true
}).on('change',function(){
    
    console.log($(this).val());
    
    let id = $(this).attr("id");
    if (id!=undefined) {
        requestToSave(id,$("input[name=amount-"+id+"]").val(),$("input[name=referenceno-"+id+"]").val(), $(this).val())
    }

});

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
        // $(".btn-outline-success").html(`<i class="fas fa-check"></i>`)
        // $(".btnCheckRef-"+id).hide()
        // $(".btnCheckAmnt-"+id).hide()
        // autoTotalLandedCost()
        toasMessage("Information","Successfully saved the transaction","info")
    }).fail(function (jqxHR, textStatus, errorThrown) {
        console.log(errorThrown);
    })
}


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

$(".amount-class").on('keyup',function(){
let id = $(this).attr("id")
let amnt = $("input[name=amount-"+id+"]").val()
let data = JSON.parse($("input[name=sackItem]").val())
let totalpcs = data.reduce((total,item)=>{
        return total+=parseFloat(item.qtypcs)
},0)
data.forEach(element => {
    $("input[name='amntPerPCS-"+element.id+"']").val((element.qtypcs/totalpcs)*amnt)
});
})


$(".expand").on('click',function(){
let id = $(this).attr("id")
let amnt = $("input[name=amount-"+id+"]").val()
let data = JSON.parse($("input[name=sackItem]").val())
let totalpcs = data.reduce((total,item)=>{
    return total+=parseFloat(item.qtypcs)
},0)
data.forEach(element => {
    $("input[name='amntPerPCS-"+element.id+"']").val((element.qtypcs/totalpcs)*amnt)
});

})


////// TOTAL LANDED COST

const autoTotalLandedCost = () =>{

    let sum_amount = 0;

    $('.amount-class').each(function(){

        if($(this).val()!=''){

            sum_amount += parseFloat(($(this).val()).replace(/\,/g,''));   

        }
    })

    $('#totalLandedCost').val(sum_amount);
    let value = $(".amount-sack-class").filter(function(){
            return (this.value=='' || this.value=='0.0000')
    })
    console.log(sum_amount);
    $('#averageCost').val((parseFloat(sum_amount)/$("input[name=quantity]").val()));


}

autoTotalLandedCost()

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

$('select[name="suppliername"]').select2({
    tags:true,
    allowClear:true,
    placeholder: 'Select supplier',
    ajax: {
      url: $('select[name="suppliername"]').attr("id"),
      dataType: 'json',
      delay: 250,
      processResults: function (data) {
          return {
              results:  $.map(data, function (item) {
                  return {
                      text: item.suppliername,
                      id: item.suppliername,
                  }
              })
          };
      },
      cache: true
    }
    }).on('select2:close', function(){
    var element = $(this);
    var new_category = $.trim(element.val());
    element.append('<option value="'+new_category+'">'+new_category+'</option>').val(new_category);
    $(".supplier_reflect").text(new_category)
});

$('select[name="suppliername"]').append( new Option($('select[name="suppliername"]').attr('data-name'), $('select[name="suppliername"]').attr('data-id'), true, true)).trigger('change');