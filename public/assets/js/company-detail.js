     
        //clear bank details
        let clearBankDetails = $(".clearBankDetails")
        let bdCompanyName    = $(".bdCompanyName")
        let addBank          = $(".addBank")
        
        // get bank form
        let bankForm         = $("#bankForm")
        let bankId           = $("input[name=bankId]")
        let company_id       = $("input[name=company_id]")
        let bankName         = $("input[name=bankName]")
        let acronym          = $("input[name=acronym]")
        let bankCancel       = $("button[name=bankCancel]")
        let tableBank        = $(".tableBank")
            bankCancel.hide()
            bankForm.hide()
        
        // get branch
        let branchForm       = $("#branchForm")
        let branchName       = $("input[name=branchName]")
        let branchId         = $("input[name=branchId]")
        let branchCancel     = $("button[name=branchCancel]")
        let badgeInfo        = $(".badge-info")
            branchForm.hide()

        // get account
        let accountForm      = $("#accountForm")
        let accountName      = $("input[name=accountName]")
        let accountId        = $("input[name=accountId]")
        let accountNo        = $("input[name=accountNo]")
        let currencyType     = $("select[name=currencyType]")
        let accountCancel    = $("button[name=accountCancel]")
            accountForm.hide()    
            accountCancel.hide()

        let tblCompany       = $("#datatable").DataTable({
            lengthMenu: [ [5, 10, 25, 50, -1], [5, 10, 25, 50, "All"] ],
            columnDefs: [
                { visible: false, targets: [0,1,2,3] }
            ]
        })

        $('input[name=registrationDate]').datepicker({
            toggleActive: true,
            format: "yyyy-mm-dd",
        });

         // clear bank details

         clearBankDetails.on('click',function(){
            clearForm(bankForm,bankId,bankCancel)
            clearForm(branchForm,branchId,branchCancel)
            clearForm(accountForm,accountId,accountCancel)
            bankForm.hide()
            branchForm.hide()
            accountForm.hide()
            tableBank.find('tbody').html(`<tr class="text-center font-weight-bold"><td colspan="3">No data available</td></tr>`)
            bdCompanyName.text('')
            badgeInfo.html(``)
        })

        addBank.on('click',function(){
            clearForm(bankForm,bankId,bankCancel)
            clearForm(branchForm,branchId,branchCancel)
            clearForm(accountForm,accountId,accountCancel)
            bankForm.fadeIn()
            branchForm.hide()
            accountForm.hide()
            badgeInfo.html(``)
        })


        const bankInfo = (id) => {
            let _hold='';
            $.ajax({
                url:`company/bank/${id}`,
                type: "GET",
                beforeSend:function(){
                    tableBank.find('tbody').html(
                        `<tr class="header text-center">
                            <td colspan="12">
                                <div class="spinner-border spinner-border-sm" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div> Getting data...
                            </td>
                        </tr>`
                    )
                }
            }).done(function(data){
                if (data.banks.length!=0) {
                    data.banks.forEach(element => {
                    _hold+=`
                            <tr>
                                <td class="bankName" id="${element.bankName}">
                                    <b>${element.bankName}<b>${branchList(element.branches,element.bankName)}
                                </td>
                                <td class="acronym text-center"><b>${element.acronym}<b></td>
                                <td class="text-center">
                                        <button class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Edit Bank Details" name="editBank" value="${element.id}"><i class="fas fa-edit"></i></button>
                                        <button class="btn btn-sm btn-default" data-toggle="tooltip" data-placement="top" title="Add Branch" name="addbank" value="${element.id}"><i class="fas fa-university"></i></button>
                                </td>
                            </tr>
                        `
                    });
                } else {
                    _hold=`<tr class="text-center font-weight-bold"><td colspan="3">No data available</td></tr>`
                }
                tableBank.find('tbody').html(_hold)
            }).fail(function (jqxHR, textStatus, errorThrown) {
                tableBank.find('tbody').html(`<tr class="text-center font-weight-bold"><td colspan="3">No data available</td></tr>`)
            })
            
        }

        const branchList = (data,bankName) =>{
            let _hold = ''
            if (data.length!=0) {
                _hold+='<table border="1" style="font-size:10px" width="100%">'
                    data.forEach(brch=>{
                    _hold+=`<tr>
                                <td class="branch-data" id="${brch.branchName+'_'+brch.bank_id+'_'+bankName}">${brch.branchName}
                                    ${accountList(brch.accounts,brch.branchName)}
                                </td>
                                <td class="text-center">
                                    <button class="btn btn-sm btn-default p-0" data-toggle="tooltip" data-placement="top" title="Edit Branch Details" name="editBranch" value="${brch.id}"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-default p-1" data-toggle="tooltip" data-placement="top" title="Add Account" name="addAccount" value="${brch.id}"><i class="fas fa-user"></i></button>
                                </td>
                            </tr>`
                })
                _hold+=`</table>`
            }

            return _hold;

        }
        const accountList = (data,branch) =>{
            let _hold = ''
            if (data.length!=0) {
                _hold+='<table border="1" style="font-size:10px" width="100%">'
                    data.forEach(accnt=>{
                    _hold+=`<tr>
                                <td class="account-data" id="${accnt.accountNo+'_'+accnt.branch_id+'_'+branch+'_'+accnt.currencyType}">${accnt.accountNo}</td>
                                <td class="account-data" >${accnt.currencyType}</td>
                                <td class="text-center"><button class="btn btn-sm btn-default p-0" data-toggle="tooltip" data-placement="top" title="Edit Account Details" name="editAccount" value="${accnt.id}"><i class="fas fa-edit"></i></button></td>
                            </tr>`
                })
                _hold+=`</table>`
            }

            return _hold;
        }

        const clearForm = (forms,id,btncancel)=>{
            btncancel.fadeOut()
            id.val('')
            forms[0].reset()
        }
        
        $(document).on('click','.btnEdit',function(){
            let data = tblCompany.row($(this).closest('tr')).data();
            $("input[name='id']").val($(this).attr("id"))
            $("input[name='companystatus']").prop("checked",data[Object.keys(data)[0]]==1)
            $("textarea[name='companyAddress']").val(data[Object.keys(data)[1]])
            $("input[name='tinNo']").val(data[Object.keys(data)[2]])
            $("input[name='registrationDate']").val(data[Object.keys(data)[3]])
            $("input[name='companyname']").val(data[Object.keys(data)[5]])
            $("input[name='companyacronym']").val(data[Object.keys(data)[6]])
            $(".cancel-btn").html(`<button type="button" class="btn btn-warning btn-block btn-sm cBtnCancel">Cancel</button>`)
        })

        $(document).on('click','.cBtnCancel',function(){
            $(this).hide()
            $("input[name='id']").val(null)
            $("input[name='companystatus']").prop("checked",false)
            $("input[name='companyname']").val(null)
            $("input[name='companyacronym']").val(null)
            $("textarea[name='companyAddress']").val(null)
            $("input[name='tinNo']").val(null)
            $("input[name='registrationDate']").val(null)
        })

        /**
         * BANK INFORMATION
         * **/

        //form save bank details
        bankForm.on("submit",function(e){
            const formData = new FormData(this)
            formData.append('_token',BaseModel._token)
            formData.append('bank_id',bankId.val())
            e.preventDefault()
            $.ajax({
                url:`company/bank/store`,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
            }).done(function(data){
                clearForm(bankForm,bankId,bankCancel)
                bankInfo(company_id.val())
            }).fail(function (jqxHR, textStatus, errorThrown) {
                // var errors = $.parseJSON(jqxHR.responseText);
                // $.each(errors, function (key, val) {
                //     if (key.bankName) {
                //         alert(val)
                //     }
                // });
            })
        })
        
        // dsiplay bank details
        $(document).on('click','.bankInfo',function(){
            let tdata = tblCompany.row($(this).closest('tr')).data();
            //display info
            bdCompanyName.text(`[ ${tdata[Object.keys(tdata)[5]]} ]`)
            // UI MANIPULATE
            bankForm.fadeIn()
            branchForm.hide()
            accountForm.hide()
            badgeInfo.html('')
            company_id.val($(this).attr("id"))
            clearForm(bankForm,bankId,bankCancel)
            clearForm(branchForm,branchId,branchCancel)
            //get data
            bankInfo($(this).attr("id"))
        })


       

        $(document).on('click','button[name=editBank]',function(){
            bankCancel.show()
            bankForm.fadeIn()
            branchForm.hide()
            bankId.val($(this).val())
            bankName.val($.trim($(this).closest('tr').find(".bankName").attr('id')))
            acronym.val($.trim($(this).closest('tr').find(".acronym").text()))
        })

        

        bankCancel.on('click',function(){
            clearForm(bankForm,bankId,bankCancel)
        })

        branchCancel.on('click',function(){
            clearForm(branchForm,branchId,branchCancel)
        })

        accountCancel.on('click',function(){
            clearForm(accountForm,accountId,accountCancel)
        })

        //form save bank details [BRANCH]
        branchForm.on("submit",function(e){
            const formData = new FormData(this)
            formData.append('_token',BaseModel._token)
            formData.append('bank_id',bankId.val())
            e.preventDefault()
            $.ajax({
                url:`company/branch/store`,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
            }).done(function(data){
                clearForm(branchForm,branchId,branchCancel)
                bankInfo(company_id.val())
            }).fail(function (jqxHR, textStatus, errorThrown) {
               console.log(jqxHR, textStatus, errorThrown);
            })
        })

        $(document).on('click','button[name=editBranch]',function(){
            let branchData = ($(this).closest('tr').find(".branch-data").attr('id')).split("_")
            branchCancel.show()
            branchForm.fadeIn()
            bankForm.hide()
            accountForm.hide()
            branchId.val($(this).val())
            branchName.val($.trim(branchData[0]))
            bankId.val(branchData[1])
            badgeInfo.html(`<i class="fas fa-university"></i>&nbsp;&nbsp;${$.trim(branchData[2]).toUpperCase()} `)
        })


         // branch
         $(document).on('click',"button[name=addbank]",function(){
            badgeInfo.html(`<i class="fas fa-university"></i>&nbsp;&nbsp;${$.trim($(this).closest('tr').find(".bankName").attr('id')).toUpperCase()} `)
            bankForm.hide()
            accountForm.hide()
            branchForm.fadeIn()
            bankId.val($(this).val())
            clearForm(branchForm,branchId,branchCancel)
            clearForm(accountForm,accountId,accountCancel)
        })

         // Account
         $(document).on('click',"button[name=addAccount]",function(){
            let branchData = ($(this).closest('tr').find(".branch-data").attr('id')).split("_")
            badgeInfo.html(`<i class="fas fa-university"></i>&nbsp;&nbsp;${$.trim(branchData[0])}`)
            bankForm.hide()
            branchForm.hide()
            accountForm.fadeIn()
            clearForm(bankForm,bankId,bankCancel)
            clearForm(accountForm,accountId,accountCancel)
            branchId.val($(this).val())
        })

          //form save bank details [BRANCH]
          accountForm.on("submit",function(e){
            const formData = new FormData(this)
            formData.append('_token',BaseModel._token)
            formData.append('branch_id',branchId.val())
            e.preventDefault()
            $.ajax({
                url:`company/account/store`,
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                cache: false,
            }).done(function(data){
                clearForm(accountForm,accountId,accountCancel)
                bankInfo(company_id.val())
            }).fail(function (jqxHR, textStatus, errorThrown) {
               console.log(jqxHR, textStatus, errorThrown);
            })
        })

        $(document).on('click','button[name=editAccount]',function(){

            let accountData = ($(this).closest('tr').find(".account-data").attr('id')).split("_")
          
            accountId.val($(this).val())
            accountNo.val($.trim(accountData[0]))
            currencyType.val($.trim(accountData[3]))
            branchId.val(accountData[1])
            badgeInfo.html(`<i class="fas fa-university"></i>&nbsp;&nbsp;${$.trim(accountData[2]).toUpperCase()} `)


            // UI manipulate

            accountCancel.show()
            branchForm.hide()
            bankForm.hide()
            accountForm.fadeIn()

            //
        })