<script>
$(document).ready(function() {

    $.validator.addMethod('oneOrMore', function(value, element){
        return $('input[name="' + element.name +'"]:checked').length > 0;},
        "Przynajmniej jedno uprawnienie musi być wybrane");

    $('#permission-form').validate({
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        rules: {
            name: {
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
        submitHandler: function(form) {
            form.submit()
        }
    });

});
</script>
