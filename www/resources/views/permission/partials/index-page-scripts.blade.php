<script>
    var dtInit = {
        processing: true,
        serverSide: true,
        "order": [
            [0, 'asc']
        ],
        ajax: {
            url: '{!! route('role.datatables') !!}',
            type: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        },
        columnDefs: [
            {"targets": [-1], "orderable": false, "searchable": false}
        ],
        columns: [
            {data: 'name'},
            {data: 'actions', defaultContent: "--------"}
        ],
        language: dtLanguage,
        lengthMenu: dtLengthMenu,
        "dom": "<'row'><'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>"
    };

    $(document).ready(function () {
        var table = $('#table').DataTable(dtInit);
        Tooltip.init();
        table.on('draw.dt', function () {
            Tooltip.init();
        });
    });
</script>
