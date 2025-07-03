<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//start session and check if admin is logged in
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/auth.php';
//connect to the database
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

// Fetch all users from the database
$users = $conn->query("SELECT id, name, email, role, created_at FROM users");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Manage Users</title>
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
        <h1>Manage Users</h1>
        <table class="product-table">
          <thead>
            <tr>
              <th>User ID</th>
              <th>Name</th>
              <th>Email</th>
              <th>Role</th>
              <th>Joined</th>
              <th>Actions</th>
            </tr>
          </thead>
          <tbody id="user-table-body">
          <?php while ($row = $users->fetch_assoc()): ?>
              <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= ucfirst($row['role']) ?></td>
                <td><?= date('Y-m-d', strtotime($row['created_at'])) ?></td>
                <td>
                <form action="ban_user.php" method="POST" style="display:inline;">
                   <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                    <button type="submit">Ban</button>
                </form>

                 <form action="delete_user.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this user?');">
                   <input type="hidden" name="user_id" value="<?= $row['id'] ?>">
                    <button type="submit">Delete</button>
                  </form>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </section>
    </main>

    <script src="/rainbow_market/scripts/components.js"></script>
    
  </body>
</html>
