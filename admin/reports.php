<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
//start session and check if admin is logged in
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/auth.php';
//connect to the database
include $_SERVER['DOCUMENT_ROOT'] . '/rainbow_market/admin/db.php';

//Fetch report data with users names
$reports = $conn->query("
SELECT
r.id,
reporter.name AS reporter_name,
reported.name AS reported_name,
r.reason,
r.status
FROM reports r
JOIN users reporter ON r.reporter_id = reporter.id
JOIN users reported ON r.reported_user_id = reported.id
ORDER BY r.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin - Reports</title>
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
        <h1>Reports & Complaints</h1>
        <table class="product-table">
          <thead>
            <tr>
              <th>Report ID</th>
              <th>Reporter</th>
              <th>Reported User</th>
              <th>Reason</th>
              <th>Status</th>
            </tr>
          </thead>
          <tbody id="reports-table-body">
            <?php if ($reports && $reports->num_rows > 0): ?>
              <?php while ($row = $reports->fetch_assoc()): ?>
                <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['reporter_name']) ?></td>
                <td><?= htmlspecialchars($row['reported_name']) ?></td>
                <td><?= htmlspecialchars($row['reason']) ?></td>
                <td>
                  <?= ucfirst($row['status']) ?>
                  <?php if ($row['status'] === 'pending'): ?>
                    <form method="POST" action="resolve_report.php" style="display:inline;">
                      <input type="hidden" name="report_id" value="<?= $row['id'] ?>">
                      <button type="submit">Mark as Resolved</button>
                  </form>
                  <?php endif; ?>
              </td>
                </tr>
                <?php endwhile; ?>
                <?php else: ?>
                  <tr><td colspan="5">No reports found</td></tr>
                  <?php endif; ?>
          </tbody>
        </table>
      </section>
    </main>

    <script src="/rainbow_market/scripts/components.js"></script>
    
  </body>
</html>
