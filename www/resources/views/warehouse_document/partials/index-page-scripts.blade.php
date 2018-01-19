<script>
    var dtInit = {
        processing: true,
        serverSide: true,
        "order": [
            [0, 'asc']
        ],
        ajax: {
            url: "{!! route('warehouse_document.datatables') !!}",
            type: "POST",
            data: function(filt){
                filt.warehouse_document_category = $('select[name="warehouse_document_category"]').val();
                filt.date_from = $('input[name="date_from"]').val();
                filt.date_to = $('input[name="date_to"]').val();
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        },
        columnDefs: [
            {"targets": [-1], "orderable": false, "searchable": false}
        ],
        columns: [
            {data: 'created_at'},
            {data: 'created_by'},
            {data: 'warehouse_document_category'},
            {data: 'actions', defaultContent:""}
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
        DatePicker.init();
        $('.filter').change(function () {
            table.draw();
        });
    });
</script>