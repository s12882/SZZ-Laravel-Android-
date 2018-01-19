<script>
    $(document).ready(function() {

        var end = "<p>${content}</p></div>";
        if ("{!!Auth::user()->hasPermissionTo('delete comment')!!}" == true)
            end = "<span class='pull-right'>" +
            "<button id='deleteButton' class='btn btn-xs red' value='${id}' data-title=Usuń obiekt><i class='fa fa-close'></i></button>" +
            "</span>" + end;

        var template = "<div class='chat-body clearfix'>" +
            "<div class='header'>" +
            "<strong class='primary-font'>${author.first_name} ${author.surname}</strong>" +
            "<span class='pull-right text-muted'>" +
            "<span class='glyphicon glyphicon-time'></span>${created_at}" +
            "</span>" +
            "</div>" + end;

        $.template("commentTemplate", template);

        var last;

        function loadComments() {
            $.ajax({
                type: "POST",
                url: "{!! route('comment.all') !!}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('.chat')
                },
                data: {
                    task_id: {!!$task->id!!},
                },
                success: function(data) {
                    last = last_item($.map(data, function(o) {
                        return o["id"];
                    }).pop())
                    $.tmpl("commentTemplate", data).appendTo('.chat');
                    $('.panel-body').scrollTop($('.panel-body')[0].scrollHeight);
                }
            });
        }



        loadComments();

        function loadNewComments() {
            $.ajax({
                type: "POST",
                url: "{!! route('comment.new') !!}",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('.chat')
                },
                data: {
                    task_id: {!!$task->id!!},
                    last: last.value()
                },
                success: function(data) {
                    if (data.length > 0) {
                        last = last_item($.map(data, function(o) {
                            return o["id"];
                        }).pop());
                        $.tmpl("commentTemplate", data).appendTo('.chat');
                    }
                },
                error: function(err) {
                    console.log(err);
                }
            });
        };
        
        setInterval(loadNewComments, 1000);

        var form = $('form[id="comment-form"]');
        form.on('submit', function(event) {
            event.preventDefault();
            $.ajax({
                type: 'POST',
                url: "{!!$postAction!!}",
                data: form.serialize(),
                success: function(data) {
                    last = last_item(data["id"]);
                    data['author'] = {
                        first_name: "{!!Auth::user()->first_name!!}",
                        surname: "{!!Auth::user()->surname!!}"
                    };
                    $.tmpl("commentTemplate", data).appendTo('.chat');
                    form[0].reset();
                    $('.panel-body').scrollTop($('.panel-body')[0].scrollHeight + 500);
                }
            })
        });

        $(document).on("click", "#deleteButton", function(e) {
            var button = $(this)

            var url = '{{ route("comment.destroy", ":id") }}'.replace(':id', button.val());
            $.ajax({
                type: 'DELETE',
                url: url,
                success: function(resp) {
                    button.closest('.chat .clearfix').remove();
                }
            });
        });

        $('#reserveButton').click(function() {
            $.ajax({
                type: 'POST',
                url: '{!!$reserveURL!!}',
                data: {
                    'task_id': "{!!$task->id!!}"
                },
                success: function(resp) {
                    $('#status').text("Zarezerwowane");
                    swal({
                            title: "Zostałeś przypisany do zadania",
                            type: "success",
                        },
                        function() {
                            $('#reserveButton').slideUp("slow", function() {
                                $(this).remove();
                                $('#button_placeholder').append($('<button data-url="{!!route("task.forwardToCheck", ["id" => $task->id])!!}" id="finishButton" class="btn btn-primary btn-block">Zakończ zadanie</button>'));
                            });

                            {{--    --}}
                        });
                },
                error: function(resp) {
                    if (resp.responseJSON.hasOwnProperty('task_id'))
                        swal({
                            title: "Zostałeś już dodany",
                            type: "info"
                        })
                    else
                        swal({
                            title: "Nie przypisano do działu!",
                            type: "error",
                        });
                }
            });
        });

        $(document).on("click","#finishButton", function () {
            var url = $(this).data('url');
            makePOSTRequest(url);
        });
    });

    var last_item = function(item) {
        var i;
        if (item == undefined)
            i = 1;
        else
            i = item;
        return {
            value: function() {
                return i;
            }
        };
    };
</script>