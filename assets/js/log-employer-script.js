// Toggle password visibility
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

        // Validate email
        function validateEmail(emailField, errorField) {
            const emailValue = emailField.value.trim();
            
            if (emailValue === '') {
                errorField.textContent = "Email is required.";
                errorField.className = "error-message error-text";
                emailField.classList.add("error");
                emailField.classList.remove("success");
                return false;
            }
            
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(emailValue)) {
                errorField.textContent = "Please enter a valid email address.";
                errorField.className = "error-message error-text";
                emailField.classList.add("error");
                emailField.classList.remove("success");
                return false;
            }
            
            errorField.textContent = "";
            emailField.classList.remove("error");
            emailField.classList.add("success");
            return true;
        }

        // Validate password
        function validatePassword(passwordField, errorField) {
            const passwordValue = passwordField.value;
            
            if (passwordValue === '') {
                errorField.textContent = "Password is required.";
                errorField.className = "error-message error-text";
                passwordField.classList.add("error");
                passwordField.classList.remove("success");
                return false;
            }
            
            if (passwordValue.length < 6) {
                errorField.textContent = "Password must be at least 6 characters.";
                errorField.className = "error-message error-text";
                passwordField.classList.add("error");
                passwordField.classList.remove("success");
                return false;
            }
            
            errorField.textContent = "";
            passwordField.classList.remove("error");
            passwordField.classList.add("success");
            return true;
        }

        // Live validation
        document.getElementById("email").addEventListener("input", function () {
    validateEmail(this, document.getElementById("emailError"));
});

document.getElementById("password").addEventListener("input", function () {
    validatePassword(this, document.getElementById("passwordError"));
});

        // Form submission with validation
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const email = document.getElementById("email");
            const password = document.getElementById("password");
            const emailError = document.getElementById("emailError");
            const passwordError = document.getElementById("passwordError");

            const isEmailValid = validateEmail(email, emailError);
            const isPasswordValid = validatePassword(password, passwordError);

            if (!isEmailValid || !isPasswordValid) {
                return false;
            }
            
            const formData = new FormData(this);
            
            // Show loading state
            const submitBtn = this.querySelector('.btn-login');
            const originalText = submitBtn.innerHTML;
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin" style="margin-right: 0.5rem;"></i>Signing in...';
            
            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                
                if (data.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: data.message,
                        showConfirmButton: false,
                        timer: 1500
                    }).then(() => {
                        window.location.href = 'employer-dashboard.php';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Login Failed',
                        text: data.message,
                        showConfirmButton: true,
                        confirmButtonText: 'OK'
                    });
                }
            })
            .catch(error => {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: 'An error occurred. Please try again.',
                    showConfirmButton: true,
                    confirmButtonText: 'OK'
                });
            });
        });