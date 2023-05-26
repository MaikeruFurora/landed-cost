    // $(".exploder").click(function(){
    
    //     $(this).toggleClass("btn-success btn-danger");
        
    //     $(this).children("span").toggleClass("glyphicon-search glyphicon-zoom-out");  
        
    //     $(this).closest("tr").next("tr").toggleClass("hide");
        
    //     if($(this).closest("tr").next("tr").hasClass("hide")){
    //         $(this).closest("tr").next("tr").children("td").slideUp();
    //     }
    //     else{
    //         $(this).closest("tr").next("tr").children("td").slideDown(350);
    //     }
    // });

    $('input[name="search"]').on('input', function() {
        let res = /^[^'"`]*$/.test($(this).val());
        res?$(this).val($(this).val()): $(this).val('')
    });

    const dataTable = (data)=>{
       

        let hold=``
        if(data.length>0){
            data.forEach((val,i) => {
                hold+=`
                        <tr class="header">
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
                            <td>${ val['itemcode'] }</td>
                            <td>${ val['suppliername'] }</td>
                            <td>${ val['vessel'] } </td>
                            <td>${ val['description'] } </td>
                            <td>${ val['invoiceno'] } </td>
                            <td>${ val['broker'] ?? 'N/A'} </td>
                            <td>${ val['quantity'] } </td>
                            <td>${ val['qtykls'] } </td>
                            <td>${ val['qtymt'] } </td>
                            <td>${ val['fcl'] } </td>
                            <td style="font-size: 6;">
                                <button class="btnSave btn btn-sm btn-primary m-0" value="${i}" type="submit" >Save <i class="fas fa-sign-in-alt"></i></button>
                            </td>
                            </form>
                        </tr>
                    `
                    val['data'].forEach((dataSub) => {
                        hold+=`
                            <tr class="table-warning">
                                <td class="text-center">${ dataSub.ContainerNo ?? 'N/A'}</td>
                                <td>${ dataSub.ItemCode }</td>
                                <td>${ dataSub.suppliername }</td>
                                <td>${ dataSub.vessel }</td>
                                <td>${ dataSub.Dscription }</td>
                                <td>${ dataSub.InvoiceNo }</td>
                                <td>${ dataSub.Broker ?? 'N/A' }</td>
                                <td>${ dataSub.quantity }</td>
                                <td>${ dataSub.QtyInKls }</td>
                                <td>${ dataSub.QtyInMT }</td>
                                <td colspan="2" class="text-left">1</td>
                            </tr>
                      `
                    });
                   
            });
        }else{
            hold=` <tr class="header text-center">
                      <td colspan="12">No data available</td>
                  </tr>`
        }
       
        $(".showData").html(hold)

         $(document).on('click','tbody tr.header',function(){
            $(this).nextUntil('tbody tr.header').css('display', function(i,v){
                return this.style.display === 'table-row' ? 'none' : 'table-row';
            });
        });
        
    }


    $(document).on('click','.btnSave',function(){
                $(this).nextUntil('tbody tr.header').css('display', function(i,v){
                    return this.style.display === 'table-row' ? 'none' : 'table-row';
                });
                let iterate     = $(this).val()
                let pono        = $("input[name=pono-"+iterate+"]").val()
                let suppliername= $("input[name=suppliername-"+iterate+"]").val()
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
                let doc_date    = $("input[name=doc_date-"+iterate+"]").val()
                $.ajax({
                    url:`po/store`,
                    type: "POST",
                    data:{
                        _token:BaseModel._token,suppliername,
                        pono,itemcode,cardname,cardcode,vessel,description,invoiceno,
                        broker,createdate,docdate,weight,quantity,qtykls,qtymt,fcl,doc_date
                    },
                    beforeSend:function(){
                        $("body").html(`
                        <div id="preloader">
                            <div id="status">
                                <div class="spinner">
                                    <div class="rect1"></div><div class="rect2"></div>
                                    <div class="rect3"></div><div class="rect4"></div>
                                    <div class="rect5"></div>
                                </div>
                            </div>
                        </div>`)
                    }
                }).done(function(data){
                    window.location.href =`details/cost/${data.id}`
                }).fail(function (jqxHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                })
                $(this).closest("tr.header").remove();

        });


    $(document).on('submit','#poForm',function(e){
        e.preventDefault()
        console.log("okey");
    })
        

    $("#searchForm").on("submit",function(e){
        e.preventDefault()
        // alert("Dasad")
        $.ajax({
                url:`po/search`,
                type: "GET",
                data:{
                    whse:$('select[name="whse"]').val(),
                    search:$('input[name="search"]').val(),
                },
                beforeSend:function(){
                    $("tbody.showData").html(`
                        <tr class="header text-center">
                            <td colspan="12">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div> Getting data...
                            </td>
                        </tr>
                    
                    `)
                    $("#searchForm *").prop("disabled", true);
                }
            }).done(function(data){
                dataTable(data)
                $("#searchForm *").prop("disabled", false);
            }).fail(function (jqxHR, textStatus, errorThrown) {
                $("#searchForm *").prop("disabled", false);
                console.log(errorThrown);
            })
    })