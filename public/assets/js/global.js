const BaseModel = {

    loadToPrint:(url) =>{
        $("<iframe>")             // create a new iframe element
            .hide()               // make it invisible
            .attr("src", url)     // point the iframe to the page you want to print
            .appendTo("body");    // add iframe to the DOM to cause it to load the page
    },

    numberWithCommas:(amount) => {
        amount.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",")
    },

    disabledProperties:(target,bool=true) =>{
        $("#"+target+" *").prop("readonly", bool);
    },

    commaSeparateNumber:(val) => {
        // remove sign if negative
        var sign = 1;
        if (val < 0) {
          sign = -1;
          val = -val;
        }
      
        // trim the number decimal point if it exists
        let num = val.toString().includes('.') ? val.toString().split('.')[0] : val.toString();
      
        while (/(\d+)(\d{3})/.test(num.toString())) {
          // insert comma to 4th last position to the match number
          num = num.toString().replace(/(\d+)(\d{3})/, '$1' + ',' + '$2');
        }
      
        // add number after decimal point
        if (val.toString().includes('.')) {
          num = num + '.' + val.toString().split('.')[1];
        }
      
        // return result with - sign if negative
        return sign < 0 ? '-' + num : num;

    },

    myStorage: window['localStorage'],

    _token: $("meta[name='_token']").attr("content"),
   
    myRights: $("meta[name='myRights']").attr("content"),

    otherPrev: JSON.parse($("meta[name='otherPrev']").attr("content")),

    initialUserControl: JSON.parse(JSON.stringify($("meta[name='initialUserControl']").attr("content"))),

    urlUserControl: $("meta[name='checkUserControl']").attr("content"),
    
    userType: $("meta[name='userType']").attr("content"),

    arrControl:[],

    checkControl:() => {
    //   setTimeout(() => {
        $.ajax({
            url: BaseModel.urlUserControl,
            type:'GET'
        }).done(function(data){
            
            if(localStorage.getItem("data") === null){
                BaseModel.myStorage.setItem('data',data)
            }else{

                const arx = data.filter(x => JSON.parse(BaseModel.myStorage.getItem('data')).indexOf(x) === -1);
                const ary = JSON.parse(BaseModel.myStorage.getItem('data')).filter(x => data.indexOf(x) === -1);
    
                // console.log(arx,ary);
    
                if (arx.length!=0 || ary!=0) {
                    BaseModel.myStorage.setItem('data',JSON.stringify(data))
                    swal({
                        title: "Information",
                        text: "Your access has been modified by admin. Please press 'OK' to reload the page",
                        type: "warning",
                        buttons: false,
                        allowOutsideClick: false,
                        // confirmButtonClass: 'btn-danger',
                        // confirmButtonText: 'Yes, delete it!',
                        // closeOnConfirm: false,
                        // closeOnCancel: true
                      }).then((value) => {
                        window.location.reload(true)
                      });
                    
                }
            }


        })
    //   }, 1500);
    },


    findPrev: (val)=>{
        if (JSON.parse(BaseModel.myStorage.getItem('data'))!==null && JSON.parse(BaseModel.myStorage.getItem('data')).length>0) {
            let res = JSON.parse(BaseModel.myStorage.getItem('data')).some(elem=>{
                return (elem.toLowerCase()===val.toLowerCase())
            })
    
            return res
        }
    },


    dropdown: function(buttons){
        // {name:null,text:null,value:null,icon:null,color:null,id:null}
        let btnHTML=`
        <div class="btn-group btn-group-sm" role="group text-center">
            <button type="button" class="btn dropdown-toggle" data-toggle="dropdown" aria-expanded="false" style="font-size:11px;background:#bbdaee;color:black">Action</button>
             <div class="dropdown-menu" style="font-size:11px">`
                buttons.forEach(val => {
                        val.elementType=='button' || !val.hasOwnProperty('elementType')?
                        btnHTML+=`<button 
                                        ${ ((val.disabled)?'disabled':'') }      
                                        name="${val.name}"
                                        type="button"
                                        value="${val.value}"
                                        class="dropdown-item border  ${val.hasOwnProperty('color')?val.color:'primary'}" id="${val.id}">
                                        ${val.icon} ${val.text}
                                    </button>`
                        :
                        btnHTML+=`<a class="dropdown-item border" href="${ (val.hasOwnProperty('disabled'))?((val.disabled)?'':val.url):val.url }">${val.icon} ${val.text}</a>`
                });
        btnHTML+=`</div>
        </div>`

        return btnHTML;
        
    },

    handleAjaxError:(jqxHR) =>{
        if (jqxHR.status === 419 || jqxHR.status === 401) {
            alert('Your session has expired. You will be redirected to the login page.');
            window.location.href = '/'; // Replace with your login page URL
        } else {
            toasMessage("Information", jqxHR.responseJSON.msg,"warning");
        }
    },

}

const toasMessage = (heading,text,icon) =>{
    $.toast({
        heading,text,icon,
        loader: true,        // Change it to false to disable loader
        loaderBg: '#9EC600'  // To change the background
    })
}

(function ($) {
    var timeout;
    $(document).on('mousemove', function (event) {
        if (timeout !== undefined) {
            window.clearTimeout(timeout);
        }
        timeout = window.setTimeout(function () {
            // trigger the new event on event.target, so that it can bubble appropriately
            $(event.target).trigger('mousemoveend');
        }, 5000);
    });
}(jQuery));


$("body").on('mousemoveend',function(){
        BaseModel.checkControl()
})

window.onload = () =>{

    BaseModel.myStorage.setItem('data',BaseModel.initialUserControl)

}

$('input').on('click',function(){
    $(this).select();
})


$(".datepciker").datepicker()