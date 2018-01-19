<script>
	$(document).ready(function() {

            var assignedUsers = {!! $taskUsers !!};

            $('#available').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{!! route('user.datatables') !!}",
                    method: "POST",
                    data: function (filt) {
                        filt.section_id = $('select[name="section_id"] option:selected').val();
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                },
                columnDefs: [
                    {"targets": [-1], "orderable": false, "searchable": false}
                ],
                columns: [
                    { data: "first_name" },
                    { data: "surname" },
                    {
                        data: null,
                        className: "text-center",
                        defaultContent: '<a class="add_user"><span class="glyphicon glyphicon-plus"></span></a>',
                        width: "2%"
                    }
                ],
                bLengthChange: false,
                info: false,
                language: dtLanguage,
            } );

            $('.filter').change(function () {
                $('#available').DataTable().draw();
                assignedUsers = [];
                $('#assigned').DataTable().clear().draw();
            });

            $('#assigned').DataTable( {
                data: assignedUsers,
                columns: [
                    { data: "first_name" },
                    { data: "surname" },
                    {
                        data: null,
                        className: "text-center",
                        defaultContent: '<a class="remove_user"><span class="glyphicon glyphicon-trash"></span></a>',
                        "width": "2%"
                    }
                ],
                bLengthChange: false,
                info: false,
                language: dtLanguage,
            } );

            $('#available tbody').on( 'click', '.add_user', function () {
                var table = $('#available').DataTable();
                var data = table.row( $(this).parents('tr') ).data();
                var id = data.id;
                var inArray = $.grep(assignedUsers, function(user) { return user.id == id; });
                if(inArray.length == 0){
                    assignedUsers.push(data);
                    $('#assigned').DataTable().clear().rows.add(assignedUsers).draw();
                }else
                swal({
                    position: 'top-end',
                    type: 'warning',
                    title: 'Ten pracownik został już dodany.'
                  })
            } );

            $('#assigned tbody').on( 'click', '.remove_user', function () {
                var table = $('#assigned').DataTable();
                var data = table.row( $(this).parents('tr') ).data();
                assignedUsers.splice(assignedUsers.indexOf(data), 1);
                $('#assigned').DataTable().clear().rows.add(assignedUsers).draw();
            });

        $('#task_form').validate({
            errorClass: 'help-block',
                    focusInvalid: false,
                    rules: {
                        name: {
                            required: true,
                        },
                        location: {
                            required: true,
                        },
                        description: {
                            required: true,
                        }               
                    },
                    highlight: function(element) {
                        $(element).closest('.form-group').addClass('has-error');
                    },
        
                    success: function(label) {
                        label.closest('.form-group').removeClass('has-error');
                        label.remove();
                    },
        
                    errorPlacement: function(error, element) {
                        error.appendTo( element.parents('div.form-group'));
                    },
                    invalidHandler: function(form, validator) {
                        
                                if (!validator.numberOfInvalids())
                                    return;
                        
                                $('html, body').animate({
                                    scrollTop: $(validator.errorList[0].element).offset().top - 100
                                }, 1000);
                        
                            },
                    submitHandler: function(form) {
                        var result = assignedUsers.map(a => a.id);
                        $('#assignedUsers').val(JSON.stringify(result));
                        form.submit();
                    }
                });

            $('#showAssignForm').click(function(event) {
                event.preventDefault();
                $('#userAssign').fadeToggle('slow');
              });
              
              
            $('#datepicker').datepicker({
                language: 'pl',
                autoclose: true,
                immediateUpdates: true,
                todayBtn: true,
                todayHighlight: true,
                pickTime: false,
                format: 'yyyy-mm-dd'
            });

            if("{!!Route::currentRouteName()!!}".includes("create"))
                $('#datepicker').datepicker('setDate', new Date());
        
                $(".remove-image").click(function () {
                    var url = $(this).data('url');
                    makeDeleteRequest(url);
                });
    });

</script>