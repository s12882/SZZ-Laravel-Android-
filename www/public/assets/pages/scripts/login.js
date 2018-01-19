var Login = function() {

    var handleLogin = function() {

        $('.login-form').validate({
            errorClass: 'help-block', // default input error message class
            focusInvalid: false, // do not focus the last invalid input
            rules: {
                login: {
                    required: true
                },
                password: {
                    required: true
                },
                remember: {
                    required: false
                }
            },

            messages: {
                login: {
                    required: "Username is required.",
                },
                password: {
                    required: "Password is required."
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

        $('.login-form input').keypress(function(e) {
            if (e.which == 13) {
                if ($('.login-form').validate().form()) {
                    $('.login-form').submit();
                }
                return false;
            }
        });
    };

    return {
        //main function to initiate the module
        init: function() {
            handleLogin();
        }
    };
} ();

jQuery(document).ready(function() {
    Login.init();
});
