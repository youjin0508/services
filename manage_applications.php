<?php
session_start();
require_once 'config.php';

// Restrict to Scholarship Admin or Admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Scholarship Admin', 'Admin'])) {
	header("Location: login.php");
	exit();
}

// Handle Approve/Reject/Pending (AJAX)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['application_id'])) {
	header('Content-Type: application/json');
	$action = $_POST['action'];
	$applicationId = (int)$_POST['application_id'];

	try {
		// Fetch application owner and scholarship for notifications/audit context
		$appStmt = $conn->prepare("SELECT sa.user_id, s.name AS scholarship_name FROM scholarship_applications sa JOIN scholarships s ON s.id = sa.scholarship_id WHERE sa.id = ?");
		$appStmt->bind_param("i", $applicationId);
		$appStmt->execute();
		$appInfo = $appStmt->get_result()->fetch_assoc();
		$appStmt->close();
		if (!$appInfo) throw new Exception('Application not found.');

		if ($action === 'approve') {
			$stmt = $conn->prepare("UPDATE scholarship_applications SET status='approved', approval_date=NOW(), rejection_reason=NULL, reviewed_by=?, reviewed_at=NOW() WHERE id=?");
			$reviewedBy = $_SESSION['user_id'];
			$stmt->bind_param("si", $reviewedBy, $applicationId);
			$ok = $stmt->execute();
			$stmt->close();
			if (!$ok) throw new Exception("Approve failed.");

			// Notify student
			$ns = $conn->prepare("INSERT INTO scholarship_notifications (user_id, title, message, type, is_read, created_at) VALUES (?, ?, ?, 'success', 0, NOW())");
			$title = 'Application Approved';
			$message = 'Your application for '.($appInfo['scholarship_name'] ?? 'the scholarship').' has been approved.';
			$ns->bind_param("sss", $appInfo['user_id'], $title, $message);
			$ns->execute();
			$ns->close();

			echo json_encode(['status'=>'success','message'=>'Application approved']);
			exit();
		} elseif ($action === 'reject') {
			$reason = trim($_POST['rejection_reason'] ?? '');
			if ($reason === '') throw new Exception('Rejection reason is required.');
			$stmt = $conn->prepare("UPDATE scholarship_applications SET status='rejected', approval_date=NULL, rejection_reason=?, reviewed_by=?, reviewed_at=NOW() WHERE id=?");
			$reviewedBy = $_SESSION['user_id'];
			$stmt->bind_param("ssi", $reason, $reviewedBy, $applicationId);
			$ok = $stmt->execute();
			$stmt->close();
			if (!$ok) throw new Exception("Reject failed.");

			// Notify student
			$ns = $conn->prepare("INSERT INTO scholarship_notifications (user_id, title, message, type, is_read, created_at) VALUES (?, ?, ?, 'error', 0, NOW())");
			$title = 'Application Rejected';
			$message = 'Your application for '.($appInfo['scholarship_name'] ?? 'the scholarship').' was rejected. Reason: '.$reason;
			$ns->bind_param("sss", $appInfo['user_id'], $title, $message);
			$ns->execute();
			$ns->close();

			echo json_encode(['status'=>'success','message'=>'Application rejected']);
			exit();
		} elseif ($action === 'set_pending') {
			$stmt = $conn->prepare("UPDATE scholarship_applications SET status='pending', approval_date=NULL, reviewed_by=?, reviewed_at=NOW() WHERE id=?");
			$reviewedBy = $_SESSION['user_id'];
			$stmt->bind_param("si", $reviewedBy, $applicationId);
			$ok = $stmt->execute();
			$stmt->close();
			if (!$ok) throw new Exception("Status update failed.");
			echo json_encode(['status'=>'success','message'=>'Application set to pending']);
			exit();
		}
		throw new Exception('Invalid action.');
	} catch (Exception $e) {
		echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
		exit();
	}
}

// Stats
$totals = ['total'=>0,'pending'=>0,'approved'=>0,'rejected'=>0];
if ($r=$conn->query("SELECT COUNT(*) c FROM scholarship_applications")) $totals['total']=(int)$r->fetch_assoc()['c'];
if ($r=$conn->query("SELECT COUNT(*) c FROM scholarship_applications WHERE status='pending'")) $totals['pending']=(int)$r->fetch_assoc()['c'];
if ($r=$conn->query("SELECT COUNT(*) c FROM scholarship_applications WHERE status='approved'")) $totals['approved']=(int)$r->fetch_assoc()['c'];
if ($r=$conn->query("SELECT COUNT(*) c FROM scholarship_applications WHERE status='rejected'")) $totals['rejected']=(int)$r->fetch_assoc()['c'];

// Fetch applications — matches schema (users.`year`)
$sql = "SELECT 
          sa.id, sa.scholarship_id, sa.user_id, sa.application_date, sa.status, sa.approval_date,
          s.name AS scholarship_name, s.type AS scholarship_type, s.amount AS scholarship_amount,
          u.first_name, u.middle_name, u.last_name, u.email, u.phone, u.course, u.`year` AS year_level, u.section
        FROM scholarship_applications sa
        JOIN scholarships s ON sa.scholarship_id = s.id
        JOIN users u ON sa.user_id = u.user_id
        ORDER BY sa.application_date DESC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<title>Manage Scholarship Applications</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet" />
<style>
:root { --blue:#003366; --light:#00509E; --gold:#FFD700; --gray:#F8F9FA; --sidebar-width:260px; }
body { background: var(--gray); }
/* Prevent overlap with fixed admin sidebar/header */
.main-content { margin-left: var(--sidebar-width); padding: 20px; }
@media (max-width: 991.98px){ .main-content{ margin-left:0; } }

.header { background: linear-gradient(135deg,var(--blue),var(--light)); color:#fff; padding:16px; border-radius:10px; margin:20px 0; }
.stats-card { background:#fff; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,.06); padding:20px; text-align:center; height:100%; }
.stats-number { font-size:1.8rem; color:var(--blue); font-weight:700; }
.controls .form-control, .controls .form-select, .controls .btn { min-height: 42px; }
.card-app { background:#fff; border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,.08); margin-bottom:16px; border:0; overflow:hidden; }
.card-app .card-header{ background:linear-gradient(135deg,var(--blue),var(--light)); color:#fff; }
.badge-status{ border-radius:20px; padding:6px 12px; text-transform:uppercase; font-size:.8rem; }
.badge-pending{ background:#fff3cd; color:#856404; }
.badge-approved{ background:#d4edda; color:#155724; }
.badge-rejected{ background:#f8d7da; color:#721c24; }
.btn-primary{ background:var(--blue); border:none; }
.btn-primary:hover{ background:var(--light); }
.btn-info{ background:#17a2b8; border:none; }
</style>
</head>
<body>

<?php if (file_exists(__DIR__ . '/admin_scholarship_header.php')) include 'admin_scholarship_header.php'; ?>

<div class="main-content">
  <div class="container-fluid">
    <div class="header d-flex flex-wrap justify-content-between align-items-center gap-2">
      <h4 class="mb-0"><i class="fa fa-file-alt me-2"></i> Manage Applications</h4>
      <div class="d-flex gap-2">
        <a href="scholarship_admin_dashboard.php" class="btn btn-sm btn-light">Dashboard</a>
        <a href="admin_manage_scholarships.php" class="btn btn-sm btn-light">Scholarships</a>
        <a href="approved_scholars.php" class="btn btn-sm btn-light">Approved Scholars</a>
      </div>
    </div>

    <div class="row g-3 mb-3">
      <div class="col-sm-6 col-lg-3"><div class="stats-card"><div class="stats-number"><?= $totals['total'] ?></div><div>Total Applications</div></div></div>
      <div class="col-sm-6 col-lg-3"><div class="stats-card"><div class="stats-number"><?= $totals['pending'] ?></div><div>Pending</div></div></div>
      <div class="col-sm-6 col-lg-3"><div class="stats-card"><div class="stats-number"><?= $totals['approved'] ?></div><div>Approved</div></div></div>
      <div class="col-sm-6 col-lg-3"><div class="stats-card"><div class="stats-number"><?= $totals['rejected'] ?></div><div>Rejected</div></div></div>
    </div>

    <div class="row g-2 mb-3 controls">
      <div class="col-12 col-md-6 col-lg-4">
        <input id="searchInput" type="text" class="form-control" placeholder="Search by student or scholarship..." />
      </div>
      <div class="col-12 col-md-4 col-lg-3">
        <select id="statusFilter" class="form-select">
          <option value="">All Status</option>
          <option value="pending">Pending</option>
          <option value="approved">Approved</option>
          <option value="rejected">Rejected</option>
        </select>
      </div>
      <div class="col-12 col-md-2 col-lg-2">
        <button id="resetFilters" class="btn btn-outline-secondary w-100">Reset</button>
      </div>
    </div>

    <div id="applicationsGrid">
      <?php if ($result && $result->num_rows > 0): while ($row = $result->fetch_assoc()):
        $fullName = trim(($row['first_name'] ?? '').' '.(($row['middle_name'] ?? '') ? $row['middle_name'].' ' : '').($row['last_name'] ?? ''));
        $status = strtolower($row['status'] ?? 'pending');
      ?>
      <div class="card card-app application-item"
           data-name="<?= strtolower(htmlspecialchars($fullName.' '.$row['scholarship_name'])) ?>"
           data-status="<?= htmlspecialchars($status) ?>">
        <div class="card-header">
          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
              <h6 class="mb-0"><?= htmlspecialchars($row['scholarship_name'] ?? '') ?></h6>
              <small><?= htmlspecialchars($row['scholarship_type'] ?? '') ?> • ₱<?= number_format((float)($row['scholarship_amount'] ?? 0), 2) ?></small>
            </div>
            <div>
              <span class="badge badge-status <?= $status==='approved'?'badge-approved':($status==='rejected'?'badge-rejected':'badge-pending') ?>">
                <?= strtoupper($status) ?>
              </span>
            </div>
          </div>
        </div>
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-6">
              <p class="mb-1"><strong>Student:</strong> <?= htmlspecialchars($fullName) ?> (<?= htmlspecialchars($row['user_id']) ?>)</p>
              <p class="mb-1"><strong>Email:</strong> <?= htmlspecialchars($row['email'] ?? '') ?></p>
              <p class="mb-1"><strong>Phone:</strong> <?= htmlspecialchars($row['phone'] ?? '') ?></p>
            </div>
            <div class="col-md-6">
              <p class="mb-1"><strong>Course:</strong> <?= htmlspecialchars($row['course'] ?? '') ?></p>
              <p class="mb-1"><strong>Year / Section:</strong> <?= htmlspecialchars((string)($row['year_level'] ?? '')) ?><?= !empty($row['section']) ? ' • '.htmlspecialchars($row['section']) : '' ?></p>
              <p class="mb-1"><strong>Applied:</strong> <?= date('M j, Y g:i A', strtotime($row['application_date'])) ?></p>
            </div>
          </div>

          <div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mt-3">
            <div class="d-flex gap-2">
              <button class="btn btn-info btn-sm view-documents" data-application-id="<?= (int)$row['id'] ?>" data-bs-toggle="modal" data-bs-target="#viewDocumentsModal">
                <i class="fa fa-file-alt"></i> View Documents
              </button>
              <a class="btn btn-secondary btn-sm" href="download_application_documents_zip.php?application_id=<?= (int)$row['id'] ?>" target="_blank">
                <i class="fa fa-download"></i> Download ZIP
              </a>
            </div>
            <div class="btn-group">
              <?php if ($status === 'pending'): ?>
                <button class="btn btn-success btn-sm do-approve" data-id="<?= (int)$row['id'] ?>"><i class="fa fa-check"></i> Approve</button>
                <button class="btn btn-danger btn-sm do-reject" data-id="<?= (int)$row['id'] ?>"><i class="fa fa-times"></i> Reject</button>
              <?php else: ?>
                <button class="btn btn-primary btn-sm do-pending" data-id="<?= (int)$row['id'] ?>"><i class="fa fa-rotate-left"></i> Set Pending</button>
              <?php endif; ?>
            </div>
          </div>
        </div>
      </div>
      <?php endwhile; else: ?>
        <div class="text-center text-muted py-5"><i class="fa fa-file-alt fa-2x mb-2"></i><div>No applications found.</div></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<!-- View Documents Modal -->
<div class="modal fade" id="viewDocumentsModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title"><i class="fa fa-file-alt me-2"></i> Application Documents</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body" id="documentsModalBody">Loading...</div>
  </div></div>
</div>

<!-- Reject Reason Modal -->
<div class="modal fade" id="rejectReasonModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog"><div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title"><i class="fa fa-comment-dots me-2"></i> Rejection Reason</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
    </div>
    <div class="modal-body">
      <input type="hidden" id="rejectApplicationId" value="">
      <div class="mb-3">
        <label class="form-label">Please provide a reason</label>
        <textarea id="rejectReasonText" class="form-control" rows="3" placeholder="Enter reason..." required></textarea>
      </div>
    </div>
    <div class="modal-footer">
      <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
      <button type="button" class="btn btn-danger" id="confirmRejectBtn"><i class="fa fa-times"></i> Reject</button>
    </div>
  </div></div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
  function filter() {
    const q = ($('#searchInput').val() || '').toLowerCase();
    const s = ($('#statusFilter').val() || '').toLowerCase();
    $('.application-item').each(function(){
      const name = $(this).data('name') || '';
      const st = $(this).data('status') || '';
      let show = true;
      if (q && name.indexOf(q) === -1) show = false;
      if (s && st !== s) show = false;
      $(this).toggle(show);
    });
  }
  $('#searchInput,#statusFilter').on('input change', filter);
  $('#resetFilters').on('click', function(){ $('#searchInput').val(''); $('#statusFilter').val(''); filter(); });

  $(document).on('click', '.view-documents', function(){
    const id = $(this).data('application-id');
    $('#documentsModalBody').html('Loading...');
    $.get('get_application_documents.php', { application_id: id })
     .done(html => $('#documentsModalBody').html(html))
     .fail(() => $('#documentsModalBody').html('<div class="alert alert-danger">Error loading documents.</div>'));
  });

  // Handle document delete inside modal
  $(document).on('click', '.delete-document', function(){
    if (!confirm('Delete this document?')) return;
    const docId = $(this).data('document-id');
    const appId = $('.view-documents[data-bs-target="#viewDocumentsModal"]').data('application-id') || $('#rejectApplicationId').val();
    $.post('delete_application_document.php', { document_id: docId })
      .done(resp => {
        if (resp && resp.status === 'success') {
          // Reload the documents list
          $('#documentsModalBody').html('Loading...');
          $.get('get_application_documents.php', { application_id: appId })
            .done(html => $('#documentsModalBody').html(html))
            .fail(() => $('#documentsModalBody').html('<div class="alert alert-danger">Error loading documents.</div>'));
        } else {
          alert((resp && resp.message) || 'Delete failed.');
        }
      })
      .fail(() => alert('Delete request failed.'));
  });

  function postAction(action, id, extraData) {
    const btn = $('[data-id="'+id+'"].do-'+action.replace('_','-') );
    const original = btn.html();
    btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i>');
    const payload = Object.assign({ action, application_id: id }, extraData || {});
    $.post('manage_applications.php', payload)
      .done(resp => { if (resp && resp.status === 'success') location.reload(); else alert((resp && resp.message) || 'Action failed.'); })
      .fail(() => alert('Request failed.'))
      .always(() => btn.prop('disabled', false).html(original));
  }

  $(document).on('click', '.do-approve', function(){ postAction('approve', $(this).data('id')); });
  $(document).on('click', '.do-pending', function(){ postAction('set_pending', $(this).data('id')); });

  // Reject with reason
  let rejectModal;
  $(document).on('click', '.do-reject', function(){
    $('#rejectApplicationId').val($(this).data('id'));
    $('#rejectReasonText').val('');
    rejectModal = new bootstrap.Modal(document.getElementById('rejectReasonModal'));
    rejectModal.show();
  });
  $('#confirmRejectBtn').on('click', function(){
    const id = $('#rejectApplicationId').val();
    const reason = ($('#rejectReasonText').val() || '').trim();
    if (!reason) { alert('Please provide a rejection reason.'); return; }
    postAction('reject', id, { rejection_reason: reason });
    if (rejectModal) rejectModal.hide();
  });

  filter();
})();
</script>
</body>
</html>
