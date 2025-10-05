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

// Validate email (mstip domain only)
function validateEmail(emailField, errorField) {
    if (emailField.value.trim() === "") {
        errorField.textContent = "Email is required.";
        errorField.className = "error-message error-text";
        emailField.classList.add("error");
        emailField.classList.remove("success");
        return false;
    } else if (!emailField.value.endsWith("@mstip.edu.ph")) {
        errorField.textContent = "Only @mstip.edu.ph email addresses are accepted.";
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

// Validate password (minimum 7 characters)
function validatePassword(passwordField, errorField) {
    if (passwordField.value.trim() === "") {
        errorField.textContent = "Password is required.";
        errorField.className = "error-message error-text";
        passwordField.classList.add("error");
        passwordField.classList.remove("success");
        return false;
    } else if (passwordField.value.length <= 6) {
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

// Form submit validation
document.getElementById("loginForm").addEventListener("submit", function (e) {
    const emailField = document.getElementById("email");
    const passwordField = document.getElementById("password");
    const emailError = document.getElementById("emailError");
    const passwordError = document.getElementById("passwordError");

    const isEmailValid = validateEmail(emailField, emailError);
    const isPasswordValid = validatePassword(passwordField, passwordError);

    if (!isEmailValid || !isPasswordValid) {
        e.preventDefault(); // prevent submit if invalid
    }
});

// Live validation while typing
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
                        window.location.href = 'index.php';
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
