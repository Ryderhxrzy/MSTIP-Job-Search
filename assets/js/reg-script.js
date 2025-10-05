// ================= Password Toggle =================
    function togglePassword() {
        const password = document.getElementById("password");
        const toggleIcon = document.getElementById("toggleIcon");
        password.type = password.type === "password" ? "text" : "password";
        toggleIcon.classList.toggle("fa-eye");
        toggleIcon.classList.toggle("fa-eye-slash");
    }

    function toggleConfirmPassword() {
        const confirmPassword = document.getElementById("confirmPassword");
        const icon = confirmPassword.nextElementSibling;
        confirmPassword.type = confirmPassword.type === "password" ? "text" : "password";
        icon.classList.toggle("fa-eye");
        icon.classList.toggle("fa-eye-slash");
    }

    // ================= Validation =================
    function validateEmail(emailField, errorField) {
        const email = emailField.value.trim();
        const pattern = /^[a-zA-Z0-9._%+-]+@mstip\.edu\.ph$/;

        if (!pattern.test(email)) {
            errorField.textContent = "Only @mstip.edu.ph email addresses are accepted.";
            errorField.className = "error-message error-text";
            return false;
        }
        errorField.textContent = "Valid email address.";
        errorField.className = "error-message success-text";
        return true;
    }

    function validatePassword(passwordField, errorField) {
        const password = passwordField.value;
        if (password.length < 7) {
            errorField.textContent = "Password must be at least 7 characters long.";
            errorField.className = "error-message error-text";
            return false;
        }
        errorField.textContent = "Password looks good.";
        errorField.className = "error-message success-text";
        return true;
    }

    function validateConfirmPassword(passwordField, confirmField, errorField) {
        if (confirmField.value !== passwordField.value || confirmField.value === "") {
            errorField.textContent = "Passwords do not match.";
            errorField.className = "error-message error-text";
            return false;
        }
        errorField.textContent = "Passwords match!";
        errorField.className = "error-message success-text";
        return true;
    }

    // ================= Form Submit =================
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
                        window.location.href = 'login.php';
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

    // Live validation
    document.getElementById("email").addEventListener("input", () => validateEmail(email, emailError));
    document.getElementById("password").addEventListener("input", () => validatePassword(password, passwordError));
    document.getElementById("confirmPassword").addEventListener("input", () => 
        validateConfirmPassword(password, confirmPassword, confirmPasswordError)
    );