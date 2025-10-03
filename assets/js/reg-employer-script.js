// Toggle main password visibility
        function togglePassword() {
            const password = document.getElementById("password");
            const toggleIcon = document.getElementById("toggleIcon");

            if (password.type === "password") {
                password.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                password.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }

        // Toggle confirm password visibility
        function toggleConfirmPassword() {
            const confirmPassword = document.getElementById("confirmPassword");
            const toggleIcon = document.getElementById("toggleConfirmIcon");

            if (confirmPassword.type === "password") {
                confirmPassword.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                confirmPassword.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }

        // Validate email (specific domain)
        function validateEmail(emailField, errorField) {
            if (!emailField.value.endsWith("@gmail.com")) {
                errorField.textContent = "Only @gmail.com email addresses are accepted.";
                errorField.className = "error-message error-text";
                emailField.classList.add("error");
                emailField.classList.remove("success");
                return false;
            } else {
                errorField.textContent = "Valid email address.";
                errorField.className = "error-message success-text";
                emailField.classList.add("success");
                emailField.classList.remove("error");
                return true;
            }
        }

        // Validate password
        function validatePassword(passwordField, errorField) {
            if (passwordField.value.length <= 6) {
                errorField.textContent = "Password must be at least 7 characters long.";
                errorField.className = "error-message error-text";
                passwordField.classList.add("error");
                passwordField.classList.remove("success");
                return false;
            } else {
                errorField.textContent = "Password looks good.";
                errorField.className = "error-message success-text";
                passwordField.classList.add("success");
                passwordField.classList.remove("error");
                return true;
            }
        }

        // Validate confirm password
        function validateConfirmPassword(passwordField, confirmField, errorField) {
            if (confirmField.value !== passwordField.value || confirmField.value === "") {
                errorField.textContent = "Passwords do not match.";
                errorField.className = "error-message error-text";
                confirmField.classList.add("error");
                confirmField.classList.remove("success");
                return false;
            } else {
                errorField.textContent = "Passwords match!";
                errorField.className = "error-message success-text";
                confirmField.classList.add("success");
                confirmField.classList.remove("error");
                return true;
            }
        }

        // Live validation
        document.getElementById("email").addEventListener("input", function () {
            validateEmail(this, document.getElementById("emailError"));
        });

        document.getElementById("password").addEventListener("input", function () {
            validatePassword(this, document.getElementById("passwordError"));
        });

        document.getElementById("confirmPassword").addEventListener("input", function () {
            validateConfirmPassword(document.getElementById("password"), this, document.getElementById("confirmPasswordError"));
        });

        // Form submission with validation
        document.getElementById('registerForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById("email");
            const password = document.getElementById("password");
            const confirmPassword = document.getElementById("confirmPassword");

            const emailError = document.getElementById("emailError");
            const passwordError = document.getElementById("passwordError");
            const confirmPasswordError = document.getElementById("confirmPasswordError");

            const isEmailValid = validateEmail(email, emailError);
            const isPasswordValid = validatePassword(password, passwordError);
            const isConfirmValid = validateConfirmPassword(password, confirmPassword, confirmPasswordError);

            if (!isEmailValid || !isPasswordValid || !isConfirmValid) {
                return false;
            }
            
            const formData = new FormData(this);
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    }).then(() => {
                        window.location.href = 'employer-login.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error!',
                        text: data.message,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred. Please try again.',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
            });
        });