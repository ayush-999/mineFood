$(document).ready(function () {
    var passwordInput = $('#password-field');
    var passwordIcon = $('#password-icon');

    // Toggle icons and input type on icon click
    passwordIcon.click(function () {
        // Check the current state of the input field
        if (passwordInput.attr('type') === 'password') {
            passwordInput.attr('type', 'text'); // Show password
            passwordIcon
                .removeClass('fas fa-lock fa-eye-slash')
                .addClass('fas fa-eye');
        } else {
            passwordInput.attr('type', 'password'); // Hide password
            passwordIcon
                .removeClass('fas fa-lock fa-eye')
                .addClass('fas fa-eye-slash');
        }
    });

    // Update icon based on input and focus
    passwordInput.on('input focus', function () {
        if (passwordInput.val().length > 0) {
            if (passwordInput.attr('type') === 'password') {
                passwordIcon
                    .removeClass('fas fa-lock fa-eye')
                    .addClass('fas fa-eye-slash');
            } else {
                passwordIcon
                    .removeClass('fas fa-lock fa-eye-slash')
                    .addClass('fas fa-eye');
            }
        } else {
            passwordIcon
                .removeClass('fas fa-eye fas fa-eye-slash')
                .addClass('fas fa-lock');
        }
    });

    // Revert to lock icon when input is empty and loses focus
    passwordInput.on('blur', function () {
        if (passwordInput.val().length === 0) {
            passwordIcon
                .removeClass('fas fa-eye fas fa-eye-slash')
                .addClass('fas fa-lock');
        }
    });
});
