<script>
    $(document).ready(function() {
    var assignedItems = {!!$assignedItems!!};
    
            $('#availableItems').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('item.datatables') !!}",
                    method: "POST",
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                columnDefs: [{
                        "targets": [-1],
                        "orderable": false,
                        "searchable": false
                    },
                    {
                        "targets": [2],
                        "visible": false
                    }
                ],
                columns: [{
                        data: "name"
                    },
                    {
                        data: "type"
                    },
                    {
                        data: "ean"
                    },
                    {
                        data: null,
                        className: "text-center",
                        defaultContent: '<a class="add_item"><span class="glyphicon glyphicon-plus"></span></a>',
                        width: "2%"
                    }
                ],
                bLengthChange: false,
                info: false,
                language: dtLanguage,
            });
    
    
            $('#assignedItems').DataTable({
                data: assignedItems,
                columnDefs: [
                    {"targets": [-1],"orderable": false,"searchable": false},
                    {"targets": [3],"visible": false}
                ],
                columns: [{
                        data: "name"
                    },
                    {
                        data: "type"
                    },
                    {
                        data: "pivot.amount",
                        render: function(data, type, row) {
                            return '<input type="number" value=' + (data == undefined ? 1 : data) + ' class="amount w-100"></input>';
                        },
                        width: "20%"
                    },
                    { data: 'ean'},
                    {
                        data: null,
                        className: "text-center",
                        defaultContent: '<a class="remove_item"><span class="glyphicon glyphicon-trash"></span></a>',
                        width: "2%"
                    }
                ],
                bLengthChange: false,
                info: false,
                language: dtLanguage,
            });
    
            $('#warehouse_document').submit(function(event){
                var items = assignedItems.map(function(item){
                    return {item_id:item.id, amount: item.pivot.amount}
                });;
                var input = $("<input>")
                            .attr("type", "hidden")
                            .attr("name", "items")
                            .val(JSON.stringify(items));
                $('#warehouse_document').append($(input));
            });

            $(document).on('change', '.amount', function() {
                console.log("SFS");
                var newValue = this.value
                var data = $('#assignedItems').DataTable().row($(this).parents('tr')).data();
                var index = assignedItems.indexOf(data);
                var a = assignedItems[index].pivot;
                var selector = $("select[name=warehouse_document_category] option:selected" ).val();
                if (selector != {!!\App\Enums\WarehouseDocumentCategory::Przyjęcie!!}  
                    && selector != {!!\App\Enums\WarehouseDocumentCategory::Zwrot!!}
                    {{--  && {!! preg_match('/edit/', Route::currentRouteName())!!}  == 1 ? false : true    --}}
                    && parseInt(data.amount) < parseInt(newValue)) {
                    swal({
                        title: "Przekroczono dostępną ilość elementów",
                        type: "error"
                    });
                    this.value = a.amount;
                } else
                    assignedItems[index].pivot.amount = parseInt(newValue);
            });
    
    
            $('#availableItems tbody').on('click', '.add_item', function() {
                var table = $('#availableItems').DataTable();
                var data = table.row($(this).parents('tr')).data();
                var id = data.id;
                var inArray = $.grep(assignedItems, function(item) {
                    return item.id == id;
                });
                if (inArray.length == 0) {
                    if(data.pivot == undefined)
                        data['pivot'] = {amount: 1}
                    assignedItems.push(data);
                    $('#assignedItems').DataTable().clear().rows.add(assignedItems).draw();
                } else
                    swal({
                        position: 'top-end',
                        type: 'warning',
                        title: 'Ten przedmiot został już dodany.'
                    })
            });
    
            $('#assignedItems tbody').on('click', '.remove_item', function() {
                var table = $('#assignedItems').DataTable();
                var data = table.row($(this).parents('tr')).data();
                assignedItems.splice(assignedItems.indexOf(data), 1);
                $('#assignedItems').DataTable().row(data).remove().draw();
            });

            $('#document_type').change(function(){
                var type = $(this).val();
                if(type == {!! \App\Enums\WarehouseDocumentCategory::Przyjęcie !!} || type == {!! \App\Enums\WarehouseDocumentCategory::Likwidacja !!})
                    console.log("PRZYJĘCIE");
            })
        });
</script>