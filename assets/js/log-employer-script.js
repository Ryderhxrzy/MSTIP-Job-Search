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
    } else if (!emailField.value.endsWith("@gmail.com")) {
        errorField.textContent = "Only@gmail.com email addresses are accepted.";
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
    const passwordField = document.getElementById("password");
    const passwordError = document.getElementById("passwordError");

    const isEmailValid = validateEmail(emailField, emailError);
    const isPasswordValid = validatePassword(passwordField, passwordError);

    if (!isEmailValid || !isPasswordValid) {
        e.preventDefault(); // prevent submit if invalid
    }
});

document.getElementById("password").addEventListener("input", function () {
    validatePassword(this, document.getElementById("passwordError"));
});
