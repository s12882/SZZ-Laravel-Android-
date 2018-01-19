<script>
var Edit = function() {

    var handleEdit = function(){

$('.user-form').validate({
    errorClass: 'help-block',
            focusInvalid: false,
            rules: {
                login: {
                    required: true,
                    minlength: 4
                },
                first_name: {
                    required: true
                },
                surname: {
                    required: true
                },
                phoneNumber: {
                    required: true,
                    minlength: 9,
                    maxlength: 14,
                },
                email: {
                    required:true, 
                    email: true
                },
                section_id: {
                    required: true
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
                $(form).ajaxSubmit() // form validation success, call ajax form submit
            }
        });
            $('.edit-form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('.edit-form').validate().form()) {
                    $('.edit-form').submit(); //form validation success, call ajax form submit
                }
                return false;
            }
        });
    };

    return {
        //main function to initiate the module
        init: function() {
            handleEdit();
        }
    };
} ();

jQuery(document).ready(function() {
    {{--  passwordInputHideShow();  --}}
    Edit.init();

    $('#generatePassword').change(function() {  
        passwordInputHideShow();
    });

    function passwordInputHideShow()
    {
        $('#assignPassword').fadeToggle('slow');
    }

    
});
</script>
