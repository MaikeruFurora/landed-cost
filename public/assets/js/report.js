const printVar = $("#print")
printVar.hide()

$('#date-range').datepicker({
toggleActive: true,
});

// $("#searchForm").on('submit',function(e){

//     e.preventDefault()
//     $.ajax({
//         url:`report/filter`,
//         type:'POST',
//         data: new FormData(this),
//         processData: false,
//         contentType: false,
//         cache: false,
//         beforeSend:function(){
//             $("tbody.showData").html(`
//                 <tr class="header text-center">
//                     <td colspan="5">
//                         <div class="spinner-border spinner-border-sm" role="status">
//                             <span class="sr-only">Loading...</span>
//                         </div> Getting data...
//                     </td>
//                 </tr>
      
//             `)
//             // $("#searchForm *").prop("disabled", true);
//         }
//         }).done(function(data){
//             let html=``;
     
//                 if(data.length>0){
//                     printVar.show()
//                     data.forEach(element => {
//                         html+=` <tr style="font-size: 11px;">
//                                     <td>${element.exchangeRateDate}</td>
//                                     <td>${element.invoiceno}</td>
//                                     <td>${element.qtymt}</td>
//                                     <td>&#36; ${element.priceMetricTon}</td>
//                                     <td>&#8369; ${element.avg}</td>
//                                 </tr>`
//                     })
//                     let avgPriceMetricTon = data.reduce(function(prev,current){
//                         return prev+=parseFloat(current.priceMetricTon)/data.length
//                     },0)
//                     let avgExchangeRate = data.reduce(function(prev,current){
//                         return prev+=parseFloat(current.avg)/data.length
//                     },0)
//                     html+=` <tr style="font-size: 15px;">
//                                 <th colspan="3" class="text-right">Average</th>
//                                 <th>&#36; ${parseFloat(avgPriceMetricTon)}</th>
//                                 <th>&#8369; ${parseFloat(avgExchangeRate)}</th>
//                             </tr>`
//                 }else{
//                     printVar.hide()
//                     html=` <tr><td colspan="5" class="text-center">No data available</td></tr>`
//                 }
//                 $(".showData").html(html)

//             $("#searchForm *").prop("disabled", false)
//         }).fail(function (jqxHR, textStatus, errorThrown) {
//             console.log();(textStatus)
//         })         
// })


$("#searchForm").on('submit',function(e){
e.preventDefault()
$.ajax({
  url:$(this).attr("action"),
  type:'POST',
  data: new FormData(this),
  processData: false,
  contentType: false,
  cache: false,
  beforeSend:function(){
      // $("#searchForm *").prop("disabled", true);
  }
  }).done(function(data){
      negoTable(data[0])
      freightTable(data[1])
      allDollarExpenes(data[2])
      $("#searchForm *").prop("disabled", false)
  }).fail(function (jqxHR, textStatus, errorThrown) {
      console.log(textStatus)
  })         
})

const select2Source = () =>{

return   {
placeholder: 'Select an item',
ajax: {
  url: 'report/filter/description',
  dataType: 'json',
  delay: 250,
  processResults: function (data) {
      return {
          results:  $.map(data, function (item) {
              return {
                  text: item.description,
                  id: item.description,
              }
          })
      };
  },
  cache: true
}
}

}

$('select[name="item"]').select2({
tags:true,
allowClear:true,
placeholder: 'Select an item',
ajax: {
  url: $('select[name="item"]').attr("id"),
  dataType: 'json',
  delay: 250,
  processResults: function (data) {
      return {
          results:  $.map(data, function (item) {
              return {
                  text: item.description,
                  id: item.description,
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
});

$('select[name="supplier"]').select2({
tags:true,
allowClear:true,
placeholder: 'Select supplier',
ajax: {
  url: $('select[name="supplier"]').attr("id"),
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

});


$('select[name="itemName"]').select2({
// minimumResultsForSearch: -1,
placeholder: function(){
  $(this).data('placeholder');
},
placeholder: 'Select an item',
dropdownParent: $('#dutiesModal'),
closeOnSelect: true,
allowClear: true,
ajax: {
  url: 'report/filter/description',
  dataType: 'json',
  delay: 250,
  data: function (params) {
        return { 
            term:params.term,
            all: 'YES'
        };
    },
  processResults: function (data) {
      return {
          results:  $.map(data, function (item) {
              return {
                  text: item.description,
                  id: item.description,
              }
          })
      };
  },
  cache: true
}
})

$('#print').on('click',function(){

let url_string = "report/print";
let start      = $("input[name='start']").val()
let end        = $("input[name='end']").val()
let item       = $("select[name='item']").val()
let _token     = BaseModel._token
let adsURL     = url_string+"?_token="+_token+"&start="+start+"&end="+end+"&item="+item;

BaseModel.loadToPrint(adsURL)

})


$('#report-range-modal').datepicker({
toggleActive: true,
autoclose: true
});

// $("#exportForm").on('submit',function(){
//     setTimeout(() => {
//         $("input[type=search]").val('')
//     }, 3000);
// })

$(".itemName_details").hide()
$("select[name=type]").on('change',function(){

switch ($(this).val()) {
  case "projectedCostReport":
          $(".company_details").hide()
          $(".itemName_details").show()
      break;
  case "dollarBook":
          $(".company_details").hide()
          $(".itemName_details").hide()
      break;

  default:
      $(".company_details").show()
      $(".itemName_details").hide()
      break;
}
})

$("table").DataTable({
ordering:false,
paging:false,
})

const dataTableSetting = (data) => {
return {
  responsive:true,
  ordering:false,
  paging:false,
  destroy:true,
  data:data,
  footerCallback: function ( row, data, start, end, display ) {
      var api = this.api(), data;
          console.log(api);
      // converting to interger to find total
      let intVal = function ( i ) {
          return typeof i === 'string' ?
              i.replace(/[\$,]/g, '')*1 :
              typeof i === 'number' ?
                  i : 0;
      };

      let  countF = api.column(5,{ page:'current' }).data().filter(function (value, index) {
          return intVal(value.exchangeRate) !=  0 || value.exchangeRate !=  null ? true : false;
      }).length;

      // computing column Total of the complete result 
      let monTotal = api.column(3,{page:'current'}).data().reduce( function (a, b) {
          return intVal(a) + intVal(b.qtymt);
          }, 0 );
          
      let tueTotal = api.column(4,{ page:'current' }).data().reduce( function (a, b) {
              return intVal(a) + intVal(b.priceMetricTon);
          }, 0 );
          
      let wedTotal = api.column(5,{ page:'current' }).data().reduce( function (a, b) {
              return intVal(a) + intVal(b.amountUSD);
          }, 0 ) /  countF;
          
      let thuTotal = api.column(4,{ page:'current' }).data().reduce( function (a, b) {
              return intVal(a) + intVal(b.exchangeRate);
          }, 0 ) /  countF;

      let friTotal = api.column(5,{ page:'current' }).data().reduce( function (a, b) {
              return intVal(a) + intVal(b.amountPHP);
          }, 0 );
      
      // Update footer by showing the total with the reference of the column index 
      $( api.column( 0 ).footer() ).html('Total');
      $( api.column( 3 ).footer() ).html($.number(monTotal,4));
      $( api.column( 4 ).footer() ).html($.number(tueTotal,4));
      $( api.column( 5 ).footer() ).html('Avg. '+$.number(wedTotal,4));
      $( api.column( 6 ).footer() ).html('Avg. '+$.number(thuTotal));
      $( api.column( 7 ).footer() ).html($.number(friTotal,2));
},
  columns:[
      { data:'exchangeRateDate' },
      { data:'description' },
      { data:'suppliername' },
      { 
          data:null,
          render:function(data){
              return $.number(data.qtymt,2)
          }
      },
      { 
          data:null,
          render:function(data){
              return $.number(data.priceMetricTon,2)
          }
      },
      { 
          data:null,
          render:function(data){
              return $.number(data.amountUSD,2)
          }
      },
      { 
          data:null,
          render:function(data){
              return $.number(data.exchangeRate,2)
          }
      },
      { 
          data:null,
          render:function(data){
              return $.number(data.amountPHP,2)
          }
      },
      
  ],

 
}
}

const negoTable = (data)=>{
$("#negoTable").DataTable(dataTableSetting(data))
}

const freightTable = (data)=>{
$("#freightTable").DataTable(dataTableSetting(data))
}

const allDollarExpenes = (data) =>{
$("#allDollarExpenes").DataTable({
  responsive:true,
  ordering:false,
  paging:false,
  destroy:true,
  data:data,
  footerCallback: function ( row, data, start, end, display ) {
      var api = this.api(), data;
          console.log(api);
      // converting to interger to find total
      let intVal = function ( i ) {
          return typeof i === 'string' ?
              i.replace(/[\$,]/g, '')*1 :
              typeof i === 'number' ?
                  i : 0;
      };

      let  countF = api.column(5,{ page:'current' }).data().filter(function (value, index) {
          return intVal(value.exchangeRate) !=  0 || value.exchangeRate !=  null ? true : false;
      }).length;

      // computing column Total of the complete result 
      let monTotal = api.column(4,{page:'current'}).data().reduce( function (a, b) {
          return intVal(a) + intVal(b.qtymt);
          }, 0 );
          
      let tueTotal = api.column(5,{ page:'current' }).data().reduce( function (a, b) {
              return intVal(a) + intVal(b.priceMetricTon);
          }, 0 );
          
      let wedTotal = api.column(6,{ page:'current' }).data().reduce( function (a, b) {
              return intVal(a) + intVal(b.amountUSD);
          }, 0 ) /  countF;

      let thuTotal = api.column(5,{ page:'current' }).data().reduce( function (a, b) {
              return intVal(a) + intVal(b.exchangeRate);
          }, 0 ) /  countF;

      // console.log(api.column(5,{ page:'current' }).data()[5]);

      let friTotal = api.column(6,{ page:'current' }).data().reduce( function (a, b) {
              return intVal(a) + intVal(b.amountPHP);
          }, 0 );
      
      // Update footer by showing the total with the reference of the column index 
      $( api.column( 0 ).footer() ).html('Total');
      $( api.column( 4 ).footer() ).html($.number(monTotal,4));
      $( api.column( 5 ).footer() ).html($.number(tueTotal,4));
      $( api.column( 6 ).footer() ).html('Avg. '+$.number(wedTotal,4));
      $( api.column( 7 ).footer() ).html('Avg. '+$.number(thuTotal));
      $( api.column( 8 ).footer() ).html($.number(friTotal,2));
},
  columns:[
    
        { 
            data:null,
            render:function(data){
                return data.invoiceID!=null?`<a target="_blank" href="${$("#searchForm").attr("data-cost").replace("invoice",data.invoiceID)}">${data.particular}</a>`:data.particular;
            }
        },
      { data:'exchangeRateDate' },
      { data:'description' },
      { data:'suppliername' },
      { 
          data:null,
          render:function(data){
              return $.number(data.qtymt,2)
          }
      },
      { 
          data:null,
          render:function(data){
              return $.number(data.priceMetricTon,2)
          }
      },
      { 
          data:null,
          render:function(data){
              return $.number(data.amountUSD,2)
          }
      },
      { 
          data:null,
          render:function(data){
              return $.number(data.exchangeRate,2)
          }
      },
      { 
          data:null,
          render:function(data){
              return $.number(data.amountPHP,2)
          }
      },
      
  ],

 
})
}


$("#exportForm").on('submit',function(e){
    // e.preventDefault()
    let name     = $("select[name=itemName]").val() 
    let from     = $("input[name=from]").val() 
    let to       = $("input[name=to]").val()
    // $.ajax({
    //     url: $('select[name=type] option:checked').attr('id'),
    //     type:'GET',
    //     data: {
    //         name,from,to
    //     },
    //     processData: false,
    //     contentType: false,
    //     cache: false,
    //     beforeSend:function(){
            
    //     }
    //     }).done(function(data){
           
    //     }).fail(function (jqxHR, textStatus, errorThrown) {
    //         console.log(textStatus)
    //     })     
        
    let url = $('select[name=type] option:checked').attr('id');
                moment.suppressDeprecationWarnings = true;
    let momentDatefrom = moment(moment(from), "YYYY-MM-DD");
    let momentDateto = moment(moment(to), "YYYY-MM-DD");
    let newUrl   = url.replace(":name",name.replace("%","_")).replace(":from", momentDatefrom.format("DD-MM-YYYY")).replace(":to",momentDateto.format("DD-MM-YYYY"));
        return window.open(newUrl,'_blank')

})