<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Official Stores - Rainbow Market</title>
  <link rel="stylesheet" href="/rainbow_market/styles/styles.css" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" />
  <style>
    .coming-soon {
      text-align: center;
      padding: 5rem 1rem;
      font-size: 1.8rem;
      font-weight: 600;
      color: #333;
    }
  </style>
</head>
<body>
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/navbar.php'; ?>

  <main>
    <div class="coming-soon">
      🚧 This page is under construction.<br><br>
      Official Stores coming soon!
    </div>
  </main>

  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/footer.php'; ?>
  <script src="/rainbow_market/scripts/rainbow-market.js"></script>
</body>
</html>
