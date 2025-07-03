function resolvePath(file) {
  // Adjusts path if current file is inside a subdirectory
  const depth = window.location.pathname.split("/").length - 2;
  return "../".repeat(depth) + file;
}

function loadComponent(id, file, callback) {
  const element = document.getElementById(id);
  if (!element) return;

  fetch(resolvePath(file))
    .then((res) => res.text())
    .then((html) => {
      element.innerHTML = html;
      if (typeof callback === "function") callback();
    })
    .catch((err) => console.error("Failed to load:", file, err));
}

function setupSidebarToggle() {
  const toggle = document.getElementById("menu-toggle");
  const sidebar = document.getElementById("sidebar");
  const overlay = document.getElementById("overlay");

  if (!toggle || !sidebar || !overlay) return;

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

document.addEventListener("DOMContentLoaded", () => {
  loadComponent("navbar-container", "navbar.php", setupSidebarToggle);
  loadComponent("footer-container", "footer.php");

  // Seller/admin components only load if container IDs exist
  loadComponent("seller-navbar-container", "seller/seller-navbar.php");
  loadComponent("seller-sidebar", "seller/seller-sidebar.php");
  loadComponent("admin-navbar-container", "admin/navbar.php");
  loadComponent("admin-sidebar", "admin/sidebar.php");
});
