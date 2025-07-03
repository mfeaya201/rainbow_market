<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$logged_in = isset($_SESSION['user_id']) && $_SESSION['role'] === 'buyer';
$current = basename($_SERVER['PHP_SELF'], ".php"); // for active category highlighting
?>

<div class="overlay" id="overlay"></div>
<div class="navbar">
  <div class="navbar-container">
    <!-- Sidebar / Categories -->
    <div class="sidebar" id="sidebar">
      <button
        class="sell-button"
        onclick="window.location.href='/rainbow_market/seller/dashboard.php'"
      >
        Start Selling for Free
      </button>
      <ul>
        <li>
          <a
            href="/rainbow_market/categories/fashion.php"
            class="<?= $current === 'fashion' ? 'active' : '' ?>"
            >Fashion</a
          >
        </li>
        <li>
          <a href="/rainbow_market/categories/tech.php" class="<?= $current === 'tech' ? 'active' : '' ?>"
            >Tech</a
          >
        </li>
        <li>
          <a
            href="/rainbow_market/categories/household.php"
            class="<?= $current === 'household' ? 'active' : '' ?>"
            >Household</a
          >
        </li>
        <li>
          <a
            href="/rainbow_market/categories/beauty.php"
            class="<?= $current === 'beauty' ? 'active' : '' ?>"
            >Beauty</a
          >
        </li>
        <li>
          <a
            href="/rainbow_market/categories/craft.php"
            class="<?= $current === 'craft' ? 'active' : '' ?>"
            >Craft</a
          >
        </li>
        <li>
          <a
            href="/rainbow_market/categories/influencers.php"
            class="<?= $current === 'influencers' ? 'active' : '' ?>"
            >Influencers</a
          >
        </li>
        <li>
          <a
            href="/rainbow_market/categories/books.php"
            class="<?= $current === 'books' ? 'active' : '' ?>"
            >Books</a
          >
        </li>
        <li>
          <a
            href="/rainbow_market/categories/other.php"
            class="<?= $current === 'other' ? 'active' : '' ?>"
            >Other</a
          >
        </li>
        <li>
          <a href="/rainbow_market/buyer/buyer_dashboard.php" class="<?= $current === 'dashboard' ? 'active' : '' ?>">
            Buyer Dashboard
          </a>
        </li>
      </ul>
    </div>

    <!-- Hamburger -->
    <nav>
      <div class="ham-menu" id="menu-toggle">
        <span></span>
        <span></span>
        <span></span>
      </div>
    </nav>

    <!-- Logo -->
    <a href="/rainbow_market/index.php" class="logo">Rainbow Market</a>

    <!-- Search -->
    <div class="search-bar">
      <button type="submit"><i class="fas fa-search"></i></button>
      <input type="text" placeholder="Search items or stores" />
    </div>

    <!-- Right Buttons -->
    <div class="navbar-icons">
      <a href="/rainbow_market/stores.php" title="Stores">
        <i class="fas fa-store"></i>
      </a>
      <a href="/rainbow_market/buyer/cart.php" title="Cart">
        <i class="fas fa-shopping-cart"></i>
      </a>

      <?php if ($logged_in): ?>
      <a href="/rainbow_market/buyer/buyer_dashboard.php" title="Dashboard">
        <i class="fas fa-user"></i>
      </a>
      <a href="/rainbow_market/logout.php" title="Logout">
        <i class="fas fa-sign-out-alt"></i>
      </a>
      <?php else: ?>
        <div class="signup">
          <button onclick="window.location.href='/rainbow_market/registration.html'">
            Sign Up / Login
          </button>
        </div>
      <?php endif; ?>
    </div>
  </div>
</div>
