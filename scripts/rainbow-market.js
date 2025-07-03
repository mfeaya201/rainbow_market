const toggle = document.getElementById("menu-toggle");
const sidebar = document.getElementById("sidebar");
const overlay = document.getElementById("overlay");
const signupBtn = document.getElementById("signupBtn");

if (toggle && sidebar && overlay) {
  toggle.addEventListener("click", () => {
    sidebar.classList.toggle("active");
    overlay.classList.toggle("active");
    toggle.classList.toggle("active");
  });

  overlay.addEventListener("click", () => {
    sidebar.classList.remove("active");
    overlay.classList.remove("active");
    toggle.classList.remove("active");
  });
}

if (signupBtn) {
  signupBtn.addEventListener("click", () => {
    window.location.href = "registration.html";
  });
}
