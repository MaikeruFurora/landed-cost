btnMinusCom.hide()

let getCompanyName = () =>{

    $.ajax({

        url:`company/list`,

        type:'GET',

    }).done(function(data){

        extractDropdownCompanyList(data)

        extractTableCompanyList(data)

    }).fail(function (jqxHR, textStatus, errorThrown) {

        var errors = $.parseJSON(jqxHR.responseText);
        $.each(errors, function (key, val) {
            console.log(key,val);
            // $("#" + key + "_error").text(val[0]);
        });

    })
}


const extractDropdownCompanyList = (data)=>{

    let _h=''
    if (data.length<0) {
        _h=`<option selected disabled>Select Company</option><option value="addOption"><b>&plus;</b>&nbsp;Add a selection</option>`
    } else {
        _h+=`<option selected disabled>Select Company</option>`
        data.forEach(val => {      
                _h+=`<option value="${val.id}" >&bull; ${val.companyname}</option>`
        }); 
        _h+=`<option value="addOption"><b>&plus;</b>&nbsp;Add a selection</option>`
    }
    selectCompany.html(_h)
}


const extractTableCompanyList = (data)=>{
        let _t=''
        if (data.length==0) {
            _t=`<tr><td colspan="2" class="text-center">No data available</td></tr>`
        }else{
            data.forEach(element => {
                _t+=`
                <tr>
                    <td class="companyname">${element.companyname}</td>
                    <td class="text-center"><i id="${element.id}" class="fas fa-edit edit-company"></i></td>
                </tr>
                `
            });
        }
    $("#companyModal tbody").html(_t)
}


selectCompany.on('change',function(){

    if ($(this).val()=="addOption") {

        getCompanyName()

        $("#companyModal").modal("show")

        // selectCompany.prop('selectedIndex',0)

    }
    
    // else{
    //     let id = $(this).attr("id");
    //     requestToSave(id,
    //         $("input[name=amount-"+id+"]").val(),
    //         $("input[name=referenceno-"+id+"]").val(),
    //         $("input[name=transaction-date-"+id+"]").val(),
    //         $(this).val()
    //     )
    // }
})

btnAddCom.on('click',function(){

    btnMinusCom.hide()

    $.ajax({

        url:`company/store`,

        type:'POST',

        data:{

            _token:BaseModel._token,

            companyname:$("input[name=companyname]").val(),

            id:$("input[name=comID]").val()

        }

    }).done(function(data){
        
        $(".err-companyname").text('')

        getCompanyName()

        $("input[name=companyname]").val('')

    }).fail(function (jqxHR, textStatus, errorThrown) {

        var errors = $.parseJSON(jqxHR.responseText);
        $.each(errors, function (key, val) {
            // console.log(Object.keys(val)[0]);
           if (Object.keys(val)[0]=='companyname') {
                $(".err-companyname").text(val.companyname)
           }
        });

    })
})

$(document).on('click',".edit-company",function(){

    btnMinusCom.show()

    $("input[name=companyname]").val($(this).closest('tr').find(".companyname").text())

    $("input[name='comID']").val($(this).attr('id'))

})

btnMinusCom.on('click',function(){

    btnMinusCom.hide()

    $("input[name=companyname]").val('')

    $("input[name='comID']").val('')

})

