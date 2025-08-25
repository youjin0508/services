<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { echo "Error: User not logged in."; exit(); }
$user_id = $_SESSION['user_id'];

$sql = "SELECT sa.id, s.name, sa.application_date, sa.status
        FROM scholarship_applications sa
        JOIN scholarships s ON sa.scholarship_id=s.id
        WHERE sa.user_id=?";
$st = $conn->prepare($sql);
$st->bind_param("s", $user_id);
$st->execute();
$res = $st->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>My Applications</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<?php include('student_header.php'); ?>
<div class="container my-4">
  <h4 class="mb-3">My Scholarship Applications</h4>
  <div class="table-responsive bg-white rounded shadow-sm">
    <table class="table table-bordered mb-0">
      <thead><tr><th>Scholarship</th><th>Application Date</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php while ($row = $res->fetch_assoc()): $stmap = strtolower($row['status']); ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td><?= date("F j, Y", strtotime($row['application_date'])) ?></td>
          <td><span class="badge bg-<?= $stmap==='approved'?'success':($stmap==='rejected'?'danger':($stmap==='under_review'?'info':'warning')) ?>"><?= htmlspecialchars(ucfirst(str_replace('_',' ',$row['status']))) ?></span></td>
          <td><a class="btn btn-sm btn-outline-primary" target="_blank" href="view_application_receipt.php?id=<?= (int)$row['id'] ?>">Receipt</a></td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
