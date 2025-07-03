<?php

if (session_status() === PHP_SESSION_NONE) {
  session_start();
}
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Rainbow Market</title>
  <link rel="stylesheet" href="/rainbow_market/styles/styles.css" />
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
  />
</head>
<body>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/navbar.php'; ?>

  <section class="hero">
    <nav class="nav-links">
      <li><a href="/rainbow_market/categories/fashion.php">Fashion</a></li>
      <li><a href="/rainbow_market/categories/tech.php">Tech</a></li>
      <li><a href="/rainbow_market/categories/household.php">Household</a></li>
      <li><a href="/rainbow_market/categories/beauty.php">Beauty</a></li>
      <li><a href="/rainbow_market/categories/craft.php">Craft</a></li>
      <li><a href="/rainbow_market/categories/influencers.php">Influencers</a></li>
      <li><a href="/rainbow_market/categories/books.php">Books</a></li>
      <li><a href="/rainbow_market/categories/other.php">Other</a></li>
    </nav>

    <div class="hero-overlay">
      <img src="/rainbow_market/images/Landing.png" alt="Rainbow Market" />
      <h1>Your Gateway to Endless Choices</h1>
    </div>
  </section>

  <section class="category-selection">
    <h2>Explore By Category</h2>
    <div class="category-grid">
      <a href="/rainbow_market/categories/fashion.php" class="category-card">
        <div class="card-image">
          <img src="/rainbow_market/images/Fashion Category.jpeg" alt="fashion" />
          <div class="card-title">Fashion</div>
        </div>
      </a>
      <a href="/rainbow_market/categories/tech.php" class="category-card">
        <div class="card-image">
          <img src="/rainbow_market/images/Tech Category.png" alt="tech" />
          <div class="card-title">Tech</div>
        </div>
      </a>
      <a href="/rainbow_market/categories/household.php" class="category-card">
        <div class="card-image">
          <img src="/rainbow_market/images/Household Category.png" alt="Household" />
          <div class="card-title">Household</div>
        </div>
      </a>
      <a href="/rainbow_market/categories/beauty.php" class="category-card">
        <div class="card-image">
          <img src="/rainbow_market/images/Beauty Category.jfif" alt="Beauty" />
          <div class="card-title">Beauty</div>
        </div>
      </a>
      <a href="/rainbow_market/categories/craft.php" class="category-card">
        <div class="card-image">
          <img src="/rainbow_market/images/Craft Category.png" alt="Craft" />
          <div class="card-title">Craft</div>
        </div>
      </a>
      <a href="/rainbow_market/categories/influencers.php" class="category-card">
        <div class="card-image">
          <img src="/rainbow_market/images/Influencers Category.jpg" alt="Influencers" />
          <div class="card-title">Influencers</div>
        </div>
      </a>
      <a href="/rainbow_market/categories/books.php" class="category-card">
        <div class="card-image">
          <img src="/rainbow_market/images/Books Category.png" alt="Books" />
          <div class="card-title">Books</div>
        </div>
      </a>
      <a href="/rainbow_market/categories/other.php" class="category-card">
        <div class="card-image">
          <img src="/rainbow_market/images/Other Category.jpeg" alt="other" />
          <div class="card-title">Other</div>
        </div>
      </a>
    </div>
  </section>

  <section class="safe-selling-banner">
    <div class="banner-container">
      <div class="banner-item">
        <i class="fas fa-shield-alt"></i>
        <div>
          <strong>Safe selling & buying</strong>
          <div class="icon-row payment-icons">
            <a href="#">How it works</a>
          </div>
        </div>
      </div>
      <div class="banner-item">
        <strong>Secure payments by</strong>
        <div class="icon-row payment-icons">
          <img src="/rainbow_market/images/Ozow.png" alt="ozow" />
          <img src="/rainbow_market/images/Visa.png" alt="visa" />
          <img src="/rainbow_market/images/mastercard-logo-black-and-white.png" alt="mastercard" />
        </div>
      </div>
      <div class="banner-item">
        <strong>Fast delivery by</strong>
        <div class="icon-row courier-icons">
          <img src="/rainbow_market/images/PAXI Full Colur 1.png" alt="paxi" />
          <img src="/rainbow_market/images/postnet-logo-black-and-white.png" alt="postnet" />
          <img src="/rainbow_market/images/[CITYPNG.COM]HD Aramex Black Logo Transparent PNG - 1000x1000.png" alt="aramex" />
        </div>
      </div>
    </div>
  </section>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/footer.php'; ?>

  <script src="/rainbow_market/scripts/rainbow-market.js"></script>
</body>
</html>
