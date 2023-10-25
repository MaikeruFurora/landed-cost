$('input[name="search"]').on('input', function() {
    let res = /^[^'`"]*$/.test($(this).val());
    res?$(this).val($(this).val()): $(this).val('')
});
$('input[name="reference"]').on('input', function() {
    let res = /^[^'`"]*$/.test($(this).val());
    res?$(this).val($(this).val()): $(this).val('')
});
const invoiceID     = []
let openAmountId  = $("input[name=id_lcopc]")
//  $(function(){
    $('.needFormat').number( true, 2 );
// })
    let tableOpenAmount = $('#datatable').DataTable({
        "serverSide": true,
        ordering:false,
        paging:true,
        "ajax": {
            url: "charge/list", 
            method: "get"
        },
        order: [[0, 'desc']],
        columns:[
            {
                data: "updated_at",
                target: 0,
                visible: false,
                searchable: false
            },
            {
                data: "transaction_date",
            },
            {
                data:null,
                render:function(data){
                    return `&#8369; `+data.lc_amount
                }
            },
            {   
                 data:"lc_mt"},
            {   
                 data:"lc_reference"},
            // {data:"created_at"},
            {
                data:null,
                render:function(data){
                    let hold=``;
                    let sum=0;
                    let tot = data.lcopening_charge.reduce((total,num)=> total+=num.detail.qtymt,0)
                    hold+='<ul class="list-group">'
                    data.lcopening_charge.forEach(val=>{
                        sum+=parseFloat(val.detail.qtymt)
                        hold+=`
                        <li class="list-group-item d-flex justify-content-between align-items-center p-1"><a href='/landed-cost/public/auth/details/cost/${val.detail.id}'>${val.detail.invoiceno}</a>
                            ${
                                (data.lc_mt!=tot) ?
                                 (BaseModel.findPrev('OA005')?`<button class="btn btn-sm" value="${val.id}" name="removeInvoice"><i class="text-danger fas fa-times-circle"></i></button>`:'')
                                :
                                '<i class="text-success fas fa-check-circle"></i>'
                            }
                            
                        </li>`
                    })
                    hold+='</ul>'

                    return hold
                }
            },
            {
                orderable: false,
                data:null,
                render:function(data){

                    if(data.lcopening_charge.length>0){
                        let hold=``;
                        let sum=0;
                        hold+='<ul class="list-group">'
                        data.lcopening_charge.forEach(val=>{
                            hold+=`
                            <li class="list-group-item d-flex justify-content-between align-items-center p-1">
                                <i class="fas fa-plus ml-2" style="font-size:9px"></i>${val.detail.qtymt}
                            </li>`
                            sum+=parseFloat(val.detail.qtymt)
                        })
                        hold+=`<li class="list-group-item d-flex justify-content-between align-items-center p-1">
                                Total<i class="fas fa-equals" style="font-size:9px"></i> ${sum}
                                </li>`
                        if (data.lc_mt!=sum) {
                        hold+=`<li class="list-group-item align-items-center p-1 text-white text-center bg-secondary">
                                <i class="fas fa-exclamation-triangle"></i>&nbsp;&nbsp;Incomplete
                               </li>`
                        }
                                hold+='</ul>'

                        return hold
                    }else{

                        return '';
                    }
                   
                }
            },
            {
                orderable:false,
                data:null,
                render:function(data){
                    return `
                        ${  BaseModel.findPrev('OA003')?`<button value="${data.id}" class="m-1 btnEdit btn btn-primary btn-sm"><i class="far fa-edit"></i> Edit</button>`:``}
                        ${  BaseModel.findPrev('OA004')?`<button value="${data.lc_reference}" id="${data.id}" class="m-1 btn btn-primary btn-sm btnAddInvoice"><i class="fas fa-plus-circle"></i> Invoice</button>`:``}
                    `
                    //<a href="charge/invoice/${data.id}" class="m-1 btn btn-primary btn-sm"><i class="fas fa-plus-circle"></i> Invoice</a>
                }
            },
        ]
    });

    $(".btnCancel").hide().on('click',function(){
        $(this).hide()
        $("#formAmount")[0].reset()
    })

    $(document).on('click','.btnEdit',function(e){
        e.preventDefault()
        var row = $("#datatable").DataTable().row($(this).closest('tr'));
        $(".btnCancel").show()
        $("input[name=id]").val(row.data()['id'])
        $("input[name=amount]").val(row.data()['lc_amount'])
        $("input[name=mt]").val(row.data()['lc_mt'])
        $("input[name=reference]").val(row.data()['lc_reference'])
    })


    $(document).on('click','button[name=removeInvoice]',function(){
       
        if (confirm("Are you about to remove this invoice?")) {
            $(this).closest("li.list-group-item").remove();
            $.ajax({
                url:`remove/${$(this).val()}`,
                type:'GET'
            }).done(function(data){
                tableOpenAmount.ajax.reload()
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
        }

        return false;
            
    })

    $(document).on('click','.btnAddInvoice',function(e){
        const obj = $(this).val();
        e.preventDefault();
        $("#searchInvoiceModal").modal('show')
        $("#searchInvoiceModalLabel").text('Reference: ' +obj.toString())
        $("input[name=id_lcopc]").val($(this).attr('id'))
        $(".showData").html(`<tr class="header text-center">  <td colspan="6">No data available</td> </tr>`)
    })

    $("#formSearchInvoice").on('submit',function(e){
        e.preventDefault()
       if($('input[name="search"]').val()!=""){
            $.ajax({
            url:`charge/invoice/search/item`,
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
                                        <button class="btnSave btn btn-sm btn-primary m-0" type="submit" value="${i}" style="font-size:9px"><i class="fas fa-plus"></i> Add</button>
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


$(document).on('click','.btnSave',function(){
        let iterate  =$(this).val()
        let pono = $("input[name=pono-"+iterate+"]").val()
        let itemcode = $("input[name=itemcode-"+iterate+"]").val()
        let cardname = $("input[name=cardname-"+iterate+"]").val()
        let cardcode = $("input[name=cardcode-"+iterate+"]").val()
        let vessel = $("input[name=vessel-"+iterate+"]").val()
        let description = $("input[name=description-"+iterate+"]").val()
        let invoiceno = $("input[name=invoiceno-"+iterate+"]").val()
        let broker = $("input[name=broker-"+iterate+"]").val()
        let createdate = $("input[name=createdate-"+iterate+"]").val()
        let docdate = $("input[name=docdate-"+iterate+"]").val()
        let weight = $("input[name=weight-"+iterate+"]").val()
        let quantity = $("input[name=quantity-"+iterate+"]").val()
        let qtykls = $("input[name=qtykls-"+iterate+"]").val()
        let qtymt = $("input[name=qtymt-"+iterate+"]").val()
        let fcl = $("input[name=fcl-"+iterate+"]").val()
        // $(this).closest("tr.header").remove();
    $.ajax({
            url:`charge/invoice/store/${openAmountId.val()}`,
            type: "POST",
            data:{
                _token:BaseModel._token,
                pono,itemcode,cardname,cardcode,vessel,description,invoiceno,
                broker,createdate,docdate,weight,quantity,qtykls,qtymt,fcl,
            },
        }).done(function(data){
            tableOpenAmount.ajax.reload()
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

$('input[name=transaction_date]').datepicker({
    toggleActive: true,
    autoclose: true,
    format:'yyyy-mm-dd'
})