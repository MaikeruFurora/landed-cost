$("button[name='btnCancel']").hide()
$(function() {
      $("#sortable").sortable({
          axis: 'y',
          update: function (event, ui) {
              var data = $(this).sortable('serialize');

              // POST to server using $.post or $.ajax
              $.ajax({
                  data: data,
                  type: 'GET',
                  url: 'particular/update/sort'
              });
          }
          // stop: function() {
          //     $.map($(this).find('li'), function(el) {
          //         var id = el.id;
          //         var sorting = parseInt($(el).index())+1;
          //         $.ajax({
          //             url: 'particular/update/sort',
          //             type: 'GET',
          //             data: {
          //                 id: id,
          //                 sorting: sorting
          //             },
          //         }).done(function(data){
          //             console.log(data);
          //         });
          //     });
          // }
      });
  });

  $("button[name='edit']").on('click',function(){
      $.ajax({
          url:'particular/edit/'+$(this).val(),
          type:'GET',
      }).done(function(data){
          console.log(data);
          $("input[name='name']").val(data.p_name)
          $("input[name='id']").val(data.id)
          $("input[name='sort']").val(data.p_sort)
          $("input[name='code']").val(data.p_code).prop('readonly',data.action)
          $("select[name='action']").val((data.action)?1:0).prop('disabled',data.action)
          $("input[name='strOrpdt']").val('update')
          $("button[name='btnCancel']").show()
      }).fail(function(a,b,c){
          console.log(a,b,c);
      })
  })

  $("button[name='btnCancel']").on('click',function(){
      $("input[name='name']").val('')
      $("input[name='id']").val('')
      $("input[name='code']").val('').prop('disabled',false).prop('readonly',false)
      $("input[name='sort']").val('')
      $("select[name='action']").prop('selectedIndex',0)
      $("input[name='strOrpdt']").val('store')
      $(this).hide()
  })