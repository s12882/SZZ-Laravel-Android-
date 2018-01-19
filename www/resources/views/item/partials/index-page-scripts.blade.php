<script>
    var dtInit = {
        processing: true,
        serverSide: true,
        buttons: [
            {
                extend: 'pdfHtml5',
                orientation: 'landscape',
                pageSize: 'LEGAL',
                exportOptions: {
                    columns: [ 0,1,2,3,4],
                    orthogonal: 'export',
                    stripNewlines: false
                },
                customize : function(doc) {
                    doc.content[0].text = "Raport wykazu przedmiotów",
                    doc.content[1].table.widths = [ '20%', '20%', '20%', '20%', '20%'];
                }
            }
        ],
        ajax: {
            url: '{!! route('item.datatables') !!}',
            type: "POST",
            data: function (filt) {
                filt.amount_from = $('input[name="amount_from"]').val();
                filt.amount_to = $('input[name="amount_to"]').val();
                filt.category_id = $('select[name="category_id"]').val();
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
        },
        columnDefs: [
            {"targets": [-1], "orderable": false, "searchable": false}
        ],
        columns: [
            {data: 'name'},
            {data: 'type'},
            {data: 'amount'},
            {data: 'ean'},
            {data: 'category.name', defaultContent: "Nie przypisano"},
            {data: 'actions'}
        ],
        language: dtLanguage,
        lengthMenu: dtLengthMenu,
        dom: "<'row'<'col-md-6 col-sm-12'l><'col-md-6 col-sm-12'f>r><'row'<'col-md-6 col-sm-12'B>><'table-scrollable't><'row'<'col-md-5 col-sm-12'i><'col-md-7 col-sm-12'p>>"
    };

    $(document).ready(function () {
        var table = $('#table').DataTable(dtInit);
        Tooltip.init();
        table.on('draw.dt', function () {
            Tooltip.init();
        });

        $('.filter').change(function () {
            table.draw();

        });
    
        $('#container').css( 'display', 'block' );
        table.columns.adjust().draw();
    });
</script>
