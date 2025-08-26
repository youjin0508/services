<?php
session_start();
require_once 'config.php';
require_once 'csrf.php';
if (!isset($_SESSION['user_id'])) { echo "Error: User not logged in."; exit(); }
$user_id = $_SESSION['user_id'];

$sql = "SELECT sa.id, s.name, sa.application_date, sa.status, sa.approval_date FROM scholarship_applications sa JOIN scholarships s ON s.id=sa.scholarship_id WHERE sa.user_id=? ORDER BY sa.application_date DESC";
$st = $conn->prepare($sql);
$st->bind_param("s", $user_id);
$st->execute();
$res = $st->get_result();
$csrf = csrf_token();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>My Applications</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
<style>
.badge-needsinfo { background:#cff4fc; color:#055160; }
.badge-withdrawn { background:#e2e3e5; color:#41464b; }
</style>
</head>
<body>
<?php include('student_header.php'); ?>
<div class="container my-4">
  <h4 class="mb-3">My Scholarship Applications</h4>
  <div class="table-responsive bg-white rounded shadow-sm">
    <table class="table table-bordered mb-0 align-middle">
      <thead class="table-light"><tr><th>Scholarship</th><th>Applied</th><th>Status</th><th>Actions</th></tr></thead>
      <tbody>
      <?php while ($row = $res->fetch_assoc()): $stmap = strtolower($row['status']); $days = (int)floor((time()-strtotime($row['application_date']))/86400); $sla = ($stmap==='pending' && $days>7); ?>
        <tr>
          <td><?= htmlspecialchars($row['name']) ?></td>
          <td>
            <?= date("F j, Y", strtotime($row['application_date'])) ?>
            <div class="small text-muted">Applied <?= $days ?> day<?= $days===1?'':'s' ?> ago<?= $sla? ' • <span class=\'text-danger\'>Over SLA</span>':'' ?></div>
          </td>
          <td>
            <?php if ($stmap==='approved'): ?>
              <span class="badge bg-success">Approved</span>
            <?php elseif ($stmap==='rejected'): ?>
              <span class="badge bg-danger">Rejected</span>
            <?php elseif ($stmap==='needs_info'): ?>
              <span class="badge badge-needsinfo">Needs Info</span>
            <?php elseif ($stmap==='withdrawn'): ?>
              <span class="badge badge-withdrawn">Withdrawn</span>
            <?php else: ?>
              <span class="badge bg-warning text-dark">Pending</span>
            <?php endif; ?>
          </td>
          <td class="d-flex gap-2">
            <a class="btn btn-sm btn-outline-primary" target="_blank" href="view_application_receipt.php?id=<?= (int)$row['id'] ?>">Receipt</a>
            <?php if ($stmap==='pending'): ?>
            <button class="btn btn-sm btn-outline-danger btn-withdraw" data-id="<?= (int)$row['id'] ?>">Withdraw</button>
            <?php endif; ?>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
  </div>
</div>
<div class="modal fade" id="withdrawModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Withdraw Application</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
    <div class="modal-body">
      <input type="hidden" id="withdrawAppId" value="">
      <div class="mb-3">
        <label class="form-label">Reason (optional)</label>
        <textarea id="withdrawReason" class="form-control" rows="3" placeholder="Why are you withdrawing?"></textarea>
      </div>
      <div class="alert alert-warning">This action cannot be undone.</div>
    </div>
    <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button><button class="btn btn-danger" id="confirmWithdraw">Withdraw</button></div>
  </div></div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
(function(){
  let withdrawModal;
  $(document).on('click', '.btn-withdraw', function(){
    $('#withdrawAppId').val($(this).data('id'));
    $('#withdrawReason').val('');
    withdrawModal = new bootstrap.Modal(document.getElementById('withdrawModal'));
    withdrawModal.show();
  });
  $('#confirmWithdraw').on('click', function(){
    const id = $('#withdrawAppId').val();
    const reason = $('#withdrawReason').val();
    $.post('withdraw_application.php', { application_id: id, reason: reason, csrf_token: '<?= htmlspecialchars($csrf) ?>' })
      .done(resp => { if (resp && resp.status === 'success') location.reload(); else alert((resp && resp.message) || 'Withdraw failed.'); })
      .fail(() => alert('Request failed.'));
  });
})();
</script>
</body>
</html>
