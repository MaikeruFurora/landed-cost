let tableOpenAmount = $('#datatable').DataTable({
    "serverSide": true,
    // pageLength: 5,
    paging:true,
    "ajax": {
        url: "audit-log/list", 
        method: "get"
    },
    order: [[0, 'desc']],
    columns:[
        {
            data: "id",
            target: 0,
            visible: false,
            searchable: false
        },
        { 
        orderable:false,
        data:'auditable_id'
    },
    { 
        orderable:false,
        data:'name'
    },
    { 
        orderable:false,
        data:'event'
    },
    { 
        orderable:false,
        data:'created_at'
    },
    { 
        orderable:false,
        data:'ip_address'
    },
    { 
        orderable:false,
        data:'old_values'
    },
    { 
        orderable:false,
        data:'new_values'
    },
    { 
        orderable:false,
        data:'url'
    },
    { 
        orderable:false,
        data:'user_agent'
    },
    ]
});
$("button[name=refreshTable]").on('click',function(){
    tableOpenAmount.ajax.reload()
})