<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Scholarship Admin','Admin'])) {
    header("Location: login.php"); exit();
}
$ts = $conn->query("SELECT COUNT(*) c FROM scholarships")->fetch_assoc()['c'] ?? 0;
$tp = $conn->query("SELECT COUNT(*) c FROM scholarship_applications WHERE status='pending'")->fetch_assoc()['c'] ?? 0;
$ta = $conn->query("SELECT COUNT(*) c FROM scholarship_applications WHERE status='approved'")->fetch_assoc()['c'] ?? 0;
$tr = $conn->query("SELECT COUNT(*) c FROM scholarship_applications WHERE status='rejected'")->fetch_assoc()['c'] ?? 0;
$g = $conn->query("SELECT DATE_FORMAT(application_date,'%Y-%m') m, COUNT(*) c FROM scholarship_applications GROUP BY 1 ORDER BY 1");
$graph=[]; while($r=$g->fetch_assoc()) $graph[]=$r;
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"><title>Admin Dashboard</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<style>
:root { --blue:#003366; --light:#00509E; --gold:#FFD700; --sidebar:260px; }
body { background:#f4f4f4; }
.main-content { margin-left: var(--sidebar); padding:20px; }
@media (max-width:991.98px){ .main-content{ margin-left:0; } }
.header { background:linear-gradient(90deg,#00509E,#002855); color:#fff; padding:16px; border-radius:10px; margin-bottom:16px; }
.card { border:0; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,.08); cursor:pointer; }
.card-header { background:#004080; color:#fff; }
</style>
</head>
<body>
<?php if (file_exists(__DIR__ . '/admin_scholarship_header.php')) include 'admin_scholarship_header.php'; ?>
<div class="main-content">
  <div class="header"><h4 class="mb-0">Admin Dashboard</h4></div>
  <div class="row g-3">
    <div class="col-md-4"><div class="card" onclick="location.href='admin_manage_scholarships.php'"><div class="card-header">Total Scholarships</div><div class="card-body text-center"><h3><?= (int)$ts ?></h3></div></div></div>
    <div class="col-md-4"><div class="card" onclick="location.href='manage_applications.php'"><div class="card-header">Pending Applications</div><div class="card-body text-center"><h3><?= (int)$tp ?></h3></div></div></div>
    <div class="col-md-4"><div class="card" onclick="location.href='approved_scholars.php'"><div class="card-header">Approved Scholars</div><div class="card-body text-center"><h3><?= (int)$ta ?></h3></div></div></div>
    <div class="col-md-4"><div class="card" onclick="location.href='manage_applications.php'"><div class="card-header">Rejected Applications</div><div class="card-body text-center"><h3><?= (int)$tr ?></h3></div></div></div>
  </div>
  <div class="mt-4 bg-white rounded p-3 shadow-sm">
    <h6>Applications Over Time</h6>
    <div id="chart"></div>
  </div>
</div>
<script>
const data = <?= json_encode($graph) ?>;
const labels = data.map(d=>d.m), series = data.map(d=>parseInt(d.c||0));
new ApexCharts(document.querySelector("#chart"), {
  series:[{name:'Applications', data:series}],
  chart:{ type:'line', height:350, zoom:{enabled:true}},
  dataLabels:{enabled:false}, stroke:{curve:'smooth'},
  xaxis:{ categories: labels }
}).render();
</script>
</body>
</html>
/