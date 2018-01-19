<script>
$(document).ready(function() {
    $('#section-form').validate({
        errorClass: 'help-block', // default input error message class
        focusInvalid: false, // do not focus the last invalid input
        rules: {
        name: {
            required: true,
            },
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
