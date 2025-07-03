// Get page elements
var showSignupBtn = document.getElementById("show-signup");
var showLoginBtn = document.getElementById("show-login");
var signupForm = document.getElementById("signup-form");
var loginForm = document.getElementById("login-form");

// Switch between signup and login forms
showSignupBtn.addEventListener("click", function () {
  signupForm.classList.remove("hidden");
  loginForm.classList.add("hidden");
  showSignupBtn.classList.add("active");
  showLoginBtn.classList.remove("active");
});

showLoginBtn.addEventListener("click", function () {
  loginForm.classList.remove("hidden");
  signupForm.classList.add("hidden");
  showLoginBtn.classList.add("active");
  showSignupBtn.classList.remove("active");
});

// Signup form validation
signupForm.addEventListener("submit", function (event) {
  /*event.preventDefault(); */

  var name = document.getElementById("signup-name").value.trim();
  var email = document.getElementById("signup-email").value.trim();
  var password = document.getElementById("signup-password").value;
  var confirmPassword = document.getElementById(
    "signup-confirm-password"
  ).value;
  var role = document.getElementById("signup-role").value;

  if (
    name === "" ||
    email === "" ||
    password === "" ||
    confirmPassword === "" ||
    role === ""
  ) {
    alert("Please fill in all fields including your role.");
    event.preventDefault();
    return;
  }

  if (password !== confirmPassword) {
    alert("Passwords do not match.");
    event.preventDefault();
    return;
  }

  if (password.length < 6) {
    alert("Password must be at least 6 characters long.");
    event.preventDefault();
    return;
  }

  /*alert("Signup successful as a " + role + "!");*/
});

// Login form validation
loginForm.addEventListener("submit", function (event) {
  /*event.preventDefault(); */

  var email = document.getElementById("login-email").value.trim();
  var password = document.getElementById("login-password").value;
  var role = document.getElementById("login-role").value;

  if (email === "" || password === "" || role === "") {
    alert("Please enter all fields and choose your role.");
    return;
  }

  if (password.length < 6) {
    alert("Password must be at least 6 characters.");
    return;
  }

  /*alert("Login successful as " + role + "!");*/
});
