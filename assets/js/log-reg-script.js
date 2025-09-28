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

  function validateEmail(emailField, errorField) {
    if (!emailField.value.endsWith("@mstip.edu.ph")) {
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

document.getElementById("loginForm").addEventListener("submit", function (e) {
    e.preventDefault();

    let email = document.getElementById("email");
    let password = document.getElementById("password");
    let emailError = document.getElementById("emailError");
    let passwordError = document.getElementById("passwordError");

    let isEmailValid = validateEmail(email, emailError);
    let isPasswordValid = validatePassword(password, passwordError);

    if (isEmailValid && isPasswordValid) {
        this.submit(); // allow submit if both valid
    }
});

// ✅ Live validation while typing
document.getElementById("email").addEventListener("input", function () {
    validateEmail(this, document.getElementById("emailError"));
});

document.getElementById("password").addEventListener("input", function () {
    validatePassword(this, document.getElementById("passwordError"));
});
