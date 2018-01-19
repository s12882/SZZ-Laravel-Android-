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
                    columns: [ 0,1,2,3,4,6,7,5],
                    orthogonal: 'export',
                    stripNewlines: false
                },
                customize : function(doc) {
                    doc.content[0].text = "Raport wykazu zadań",
                    doc.content[1].table.widths = [ '16%', '30%', '10%', '10%', '7%','6%','6%', '15%'];
                }
            }
        ],
        ajax: {
            url: '{!! route('task.datatables') !!}',
            type: "POST",
            data: function (filt) {
                filt.status = $('select[name="status"] option:selected').val();
                filt.section_id = $('select[name="section_id"] option:selected').val();
                filt.date_from = $('input[name="date_from"]').val();
                filt.date_to = $('input[name="date_to"]').val();
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        },
        columnDefs: [
            {"targets": [-1], "orderable": false, "searchable": false},
            {"targets": [5], "visible": false, "searchable": false},
            {"targets": [6], "width": "110px"},
            {"targets": [7], "visible": false},
            {"targets": [8], "width": "130px"}
        ],
        columns: [
            {data: 'name'},
            {data: 'description', 
                render: function(data, type){
                    return type === 'export' ? data : data.length > 60 ? data.substr(0, 50)+"..." : data;
                }
            },
            {data: 'location'},
            {data: 'section.name', defaultContent: "Nie przypisano"},
            {data: 'status'},
            {data: 'users', render: function(data){
                return data.split(",").join("\n<br/>");
            }},
            {data: 'created_at',
                render: function(data, type){
                    return type === 'export' ? data.substr(0, 10) : data;
                }  
            },
            {data: 'finished_at', render: function(data, type){
                    return data.substr(0, 10);
                }  
            },
            {data: 'actions', className:"text-center", defaultContent: "--------"}
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
        DatePicker.init();

        $('.filter').change(function () {
            table.draw();
        });

        $('#container').css( 'display', 'block' );
        table.columns.adjust().draw();
});
</script>
