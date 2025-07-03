/*document
  .getElementById("admin-login-form")
  .addEventListener("submit", function (event) {
    event.preventDefault();

    var email = document.getElementById("admin-email").value.trim();
    var password = document.getElementById("admin-password").value;

    if (email === "" || password === "") {
      alert("Please enter both email and password.");
      return;
    }

    if (password.length < 6) {
      alert("Password must be at least 6 characters long.");
      return;
    }

    // This part will later send data to PHP backend to verify
    alert("Admin login attempt...");
  });
*/
