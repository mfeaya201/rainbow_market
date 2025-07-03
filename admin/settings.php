<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//start session and check if admin is logged in
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/auth.php';
//connect to the database
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Settings</title>
    <link rel="stylesheet" href="/rainbow_market/styles/admin.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css"
    />
  </head>
  <body>
  <!--Navbar-->
  <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/navbar.php'; ?>

  <main class="admin-dashboard">
    <!--Sidebar-->
    <?php include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/sidebar.php'; ?>

      <section class="admin-main">
        <h1>Admin Settings</h1>

        <form id="settings-form" class="admin-settings-form">
          <label for="site-name">Site Name:</label>
          <input
            type="text"
            id="site-name"
            name="site-name"
            placeholder="Enter site name"
          />

          <label for="support-email">Support Email:</label>
          <input
            type="email"
            id="support-email"
            name="support-email"
            placeholder="Enter support email"
          />

          <label for="maintenance-mode">Maintenance Mode:</label>
          <select id="maintenance-mode" name="maintenance-mode">
            <option value="off">Off</option>
            <option value="on">On</option>
          </select>

          <button type="submit">Save Settings</button>
        </form>
      </section>
    </main>

    <script src="/rainbow_market/scripts/components.js"></script>
    <!--Backend logic will be added here-->
  </body>
</html>
