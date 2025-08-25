<?php
session_start();
require_once 'config.php';

// Restrict to Admins
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Scholarship Admin','Admin'])) {
    header("Location: login.php");
    exit();
}

// Handle CRUD
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $action = $_POST['action'];

    if ($action === 'create') {
        $name = trim($_POST['name'] ?? '');
        $type = trim($_POST['type'] ?? 'Academic');
        $description = trim($_POST['description'] ?? '');
        $eligibility = trim($_POST['eligibility'] ?? '');
        $amount = (float)($_POST['amount'] ?? 0);
        $requirements = trim($_POST['requirements'] ?? '');
        $deadline = $_POST['deadline'] ?? '';
        $max_applicants = (int)($_POST['max_applicants'] ?? 0);
        $documents_required = isset($_POST['documents_required']) ? json_encode($_POST['documents_required']) : json_encode([]);
        $created_by = $_SESSION['user_id'];
        $status = trim($_POST['status'] ?? 'pending');

        if ($name === '' || $description === '' || $eligibility === '' || $deadline === '') {
            $_SESSION['flash_error'] = 'Please fill all required fields.';
            header("Location: admin_manage_scholarships.php");
            exit();
        }

        $stmt = $conn->prepare("INSERT INTO scholarships (name, type, description, eligibility, amount, requirements, deadline, max_applicants, documents_required, created_by, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())");
        $stmt->bind_param("ssssdsissss", $name, $type, $description, $eligibility, $amount, $requirements, $deadline, $max_applicants, $documents_required, $created_by, $status);
        $stmt->execute();
        $stmt->close();

        header("Location: admin_manage_scholarships.php");
        exit();
    }

    if ($action === 'update') {
        $id = (int)($_POST['id'] ?? 0);
        $name = trim($_POST['name'] ?? '');
        $type = trim($_POST['type'] ?? 'Academic');
        $description = trim($_POST['description'] ?? '');
        $eligibility = trim($_POST['eligibility'] ?? '');
        $amount = (float)($_POST['amount'] ?? 0);
        $requirements = trim($_POST['requirements'] ?? '');
        $deadline = $_POST['deadline'] ?? '';
        $max_applicants = (int)($_POST['max_applicants'] ?? 0);
        $documents_required = isset($_POST['documents_required']) ? json_encode($_POST['documents_required']) : json_encode([]);
        $status = trim($_POST['status'] ?? 'pending');

        $stmt = $conn->prepare("UPDATE scholarships SET name=?, type=?, description=?, eligibility=?, amount=?, requirements=?, deadline=?, max_applicants=?, documents_required=?, status=?, updated_at=NOW() WHERE id=?");
        $stmt->bind_param("ssssdsisssi", $name, $type, $description, $eligibility, $amount, $requirements, $deadline, $max_applicants, $documents_required, $status, $id);
        $stmt->execute();
        $stmt->close();

        header("Location: admin_manage_scholarships.php");
        exit();
    }

    if ($action === 'delete') {
        $id = (int)($_POST['id'] ?? 0);

        $check = $conn->prepare("SELECT COUNT(*) c FROM scholarship_applications WHERE scholarship_id=?");
        $check->bind_param("i", $id);
        $check->execute();
        $count = $check->get_result()->fetch_assoc()['c'] ?? 0;
        $check->close();

        if ((int)$count > 0) {
            $_SESSION['flash_error'] = 'Cannot delete: scholarship has existing applications.';
        } else {
            $del = $conn->prepare("DELETE FROM scholarships WHERE id=?");
            $del->bind_param("i", $id);
            $del->execute();
            $del->close();
            $_SESSION['flash_success'] = 'Scholarship deleted.';
        }
        header("Location: admin_manage_scholarships.php");
        exit();
    }
}

// Fetch list
$q = $conn->query("SELECT s.*, u.first_name, u.last_name FROM scholarships s LEFT JOIN users u ON u.user_id = s.created_by ORDER BY s.created_at DESC");
$scholarships = [];
while ($row = $q->fetch_assoc()) $scholarships[] = $row;
$q->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Scholarships</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>
:root { --blue:#003366; --light:#00509E; --gold:#FFD700; --gray:#F8F9FA; --sidebar:260px; }
body { background: var(--gray); }
.main-content { margin-left: var(--sidebar); padding: 20px; }
@media (max-width: 991.98px){ .main-content{ margin-left:0; } }
.page-header { background:linear-gradient(135deg,var(--blue),var(--light)); color:#fff; padding:16px; border-radius:10px; margin:20px 0; }
.card { border-radius:12px; box-shadow:0 6px 18px rgba(0,0,0,.08); border:0; }
.card-header { background:linear-gradient(135deg,var(--blue),var(--light)); color:#fff; }
.btn-primary{ background:var(--blue); border:none; }
.btn-primary:hover{ background:var(--light); }
.badge-status { border-radius:8px; padding:6px 10px; }
</style>
</head>
<body>

<?php if (file_exists(__DIR__ . '/admin_scholarship_header.php')) include 'admin_scholarship_header.php'; ?>

<div class="main-content">
  <div class="container-fluid">
    <div class="page-header d-flex flex-wrap justify-content-between align-items-center gap-2">
      <h4 class="mb-0"><i class="bi bi-mortarboard me-2"></i> Manage Scholarships</h4>
      <div class="d-flex gap-2 flex-wrap">
        <input type="text" id="searchInput" class="form-control" placeholder="Search name/type/description..." style="min-width:260px">
        <select id="typeFilter" class="form-select">
          <option value="">All Types</option>
          <option>Academic</option><option>Athletic</option><option>Need-based</option><option>Merit-based</option><option>Other</option>
        </select>
        <select id="statusFilter" class="form-select">
          <option value="">All Status</option>
          <option value="pending">Pending</option><option value="active">Active</option><option value="inactive">Inactive</option>
        </select>
        <button id="resetFilters" class="btn btn-outline-secondary">Reset</button>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createModal"><i class="bi bi-plus-circle me-1"></i> Add Scholarship</button>
      </div>
    </div>

    <?php if (!empty($_SESSION['flash_error'])): ?>
      <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['flash_error']) ?></div>
      <?php unset($_SESSION['flash_error']); endif; ?>
    <?php if (!empty($_SESSION['flash_success'])): ?>
      <div class="alert alert-success"><?= htmlspecialchars($_SESSION['flash_success']) ?></div>
      <?php unset($_SESSION['flash_success']); endif; ?>

    <div class="row" id="scholarshipGrid">
      <?php foreach ($scholarships as $s): ?>
      <div class="col-lg-6 col-xl-4 mb-3 scholarship-item"
           data-name="<?= strtolower(htmlspecialchars($s['name'])) ?>"
           data-type="<?= strtolower(htmlspecialchars($s['type'])) ?>"
           data-status="<?= strtolower(htmlspecialchars($s['status'])) ?>">
        <div class="card h-100">
          <div class="card-header">
            <h6 class="mb-0"><?= htmlspecialchars($s['name']) ?></h6>
          </div>
          <div class="card-body">
            <p class="text-muted mb-2"><?= htmlspecialchars(mb_strimwidth($s['description'],0,120,'...')) ?></p>
            <div class="row mb-2">
              <div class="col-6"><small class="text-muted">Type:</small><div><strong><?= htmlspecialchars($s['type']) ?></strong></div></div>
              <div class="col-6"><small class="text-muted">Amount:</small><div><strong class="text-success">₱<?= number_format((float)$s['amount'],2) ?></strong></div></div>
            </div>
            <div class="row mb-2">
              <div class="col-6"><small class="text-muted">Deadline:</small><div><strong><?= htmlspecialchars($s['deadline']) ?></strong></div></div>
              <div class="col-6"><small class="text-muted">Status:</small><div>
                <span class="badge badge-status <?= $s['status']==='active'?'bg-success':($s['status']==='pending'?'bg-warning text-dark':'bg-secondary') ?>">
                  <?= htmlspecialchars(ucfirst($s['status'])) ?>
                </span></div>
              </div>
            </div>
            <div class="row mb-2">
              <div class="col-6"><small class="text-muted">Max Applicants:</small><div><strong><?= (int)$s['max_applicants'] ?></strong></div></div>
              <div class="col-6"><small class="text-muted">Current:</small><div><strong><?= (int)$s['current_applicants'] ?></strong></div></div>
            </div>
            <?php if (!empty($s['first_name']) || !empty($s['last_name'])): ?>
              <small class="text-muted d-block mb-2">Created by: <strong><?= htmlspecialchars(trim(($s['first_name'] ?? '').' '.($s['last_name'] ?? ''))) ?></strong></small>
            <?php endif; ?>
            <div class="d-flex justify-content-end gap-2">
              <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editModal<?= (int)$s['id'] ?>"><i class="bi bi-pencil-square"></i> Edit</button>
              <form method="post" onsubmit="return confirm('Delete this scholarship?')">
                <input type="hidden" name="action" value="delete">
                <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
                <button class="btn btn-sm btn-danger"><i class="bi bi-trash"></i> Delete</button>
              </form>
            </div>
          </div>
        </div>
      </div>

      <!-- Edit Modal -->
      <div class="modal fade" id="editModal<?= (int)$s['id'] ?>" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg"><div class="modal-content">
          <form method="post">
            <input type="hidden" name="action" value="update">
            <input type="hidden" name="id" value="<?= (int)$s['id'] ?>">
            <div class="modal-header"><h5 class="modal-title">Edit Scholarship</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
              <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="name" value="<?= htmlspecialchars($s['name']) ?>" required></div>
                <div class="col-md-6"><label class="form-label">Type</label>
                  <select class="form-select" name="type" required>
                    <?php foreach (['Academic','Athletic','Need-based','Merit-based','Other'] as $t): ?>
                      <option <?= $s['type']===$t?'selected':'' ?>><?= $t ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3" required><?= htmlspecialchars($s['description']) ?></textarea></div>
                <div class="col-12"><label class="form-label">Eligibility</label><textarea class="form-control" name="eligibility" rows="3" required><?= htmlspecialchars($s['eligibility']) ?></textarea></div>
                <div class="col-md-6"><label class="form-label">Amount (₱)</label><input type="number" step="0.01" class="form-control" name="amount" value="<?= (float)$s['amount'] ?>" required></div>
                <div class="col-md-6"><label class="form-label">Max Applicants</label><input type="number" class="form-control" name="max_applicants" value="<?= (int)$s['max_applicants'] ?>" required></div>
                <div class="col-12"><label class="form-label">Requirements</label><textarea class="form-control" name="requirements" rows="2"><?= htmlspecialchars($s['requirements'] ?? '') ?></textarea></div>
                <div class="col-md-6"><label class="form-label">Deadline</label><input type="date" class="form-control" name="deadline" value="<?= htmlspecialchars($s['deadline']) ?>" required></div>
                <div class="col-md-6"><label class="form-label">Status</label>
                  <select class="form-select" name="status" required>
                    <?php foreach (['pending','active','inactive'] as $st): ?>
                      <option value="<?= $st ?>" <?= $s['status']===$st?'selected':'' ?>><?= ucfirst($st) ?></option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-12">
                  <label class="form-label">Required Documents</label>
                  <?php
                    $docOptions = ['Birth Certificate','Report Card','Good Moral','Recommendation Letter'];
                    $docs = json_decode($s['documents_required'] ?? '[]', true) ?: [];
                    foreach ($docOptions as $doc) {
                      $checked = in_array($doc, $docs) ? 'checked' : '';
                      echo '<div class="form-check form-check-inline">
                              <input class="form-check-input" type="checkbox" name="documents_required[]" value="'.htmlspecialchars($doc).'" '.$checked.'>
                              <label class="form-check-label">'.htmlspecialchars($doc).'</label>
                            </div>';
                    }
                  ?>
                </div>
              </div>
            </div>
            <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal">Close</button><button class="btn btn-primary">Save</button></div>
          </form>
        </div></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- Create Modal -->
<div class="modal fade" id="createModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg"><div class="modal-content">
    <form method="post">
      <input type="hidden" name="action" value="create">
      <div class="modal-header"><h5 class="modal-title">Add Scholarship</h5><button class="btn-close" data-bs-dismiss="modal" type="button"></button></div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6"><label class="form-label">Name</label><input class="form-control" name="name" required></div>
          <div class="col-md-6"><label class="form-label">Type</label>
            <select class="form-select" name="type" required>
              <option>Academic</option><option>Athletic</option><option>Need-based</option><option>Merit-based</option><option>Other</option>
            </select>
          </div>
          <div class="col-12"><label class="form-label">Description</label><textarea class="form-control" name="description" rows="3" required></textarea></div>
          <div class="col-12"><label class="form-label">Eligibility</label><textarea class="form-control" name="eligibility" rows="3" required></textarea></div>
          <div class="col-md-6"><label class="form-label">Amount (₱)</label><input type="number" step="0.01" class="form-control" name="amount" value="0.00" required></div>
          <div class="col-md-6"><label class="form-label">Max Applicants</label><input type="number" class="form-control" name="max_applicants" value="0" required></div>
          <div class="col-12"><label class="form-label">Requirements</label><textarea class="form-control" name="requirements" rows="2"></textarea></div>
          <div class="col-md-6"><label class="form-label">Deadline</label><input type="date" class="form-control" name="deadline" required></div>
          <div class="col-md-6"><label class="form-label">Status</label>
            <select class="form-select" name="status" required>
              <option value="pending">Pending</option><option value="active">Active</option><option value="inactive">Inactive</option>
            </select>
          </div>
          <div class="col-12">
            <label class="form-label">Required Documents</label>
            <?php foreach (['Birth Certificate','Report Card','Good Moral','Recommendation Letter'] as $doc): ?>
              <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="documents_required[]" value="<?= htmlspecialchars($doc) ?>">
                <label class="form-check-label"><?= htmlspecialchars($doc) ?></label>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>
      <div class="modal-footer"><button class="btn btn-secondary" data-bs-dismiss="modal" type="button">Close</button><button class="btn btn-primary">Create</button></div>
    </form>
  </div></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
(function(){
  const searchInput = document.getElementById('searchInput');
  const typeFilter = document.getElementById('typeFilter');
  const statusFilter = document.getElementById('statusFilter');
  const resetBtn = document.getElementById('resetFilters');
  function applyFilters(){
    const q=(searchInput?.value||'').toLowerCase(), t=(typeFilter?.value||'').toLowerCase(), s=(statusFilter?.value||'').toLowerCase();
    document.querySelectorAll('.scholarship-item').forEach(el=>{
      const name=el.dataset.name||'', type=el.dataset.type||'', st=el.dataset.status||'';
      let show=true; if(q && !name.includes(q)) show=false; if(t && type!==t) show=false; if(s && st!==s) show=false;
      el.style.display = show ? '' : 'none';
    });
  }
  searchInput?.addEventListener('input', applyFilters);
  typeFilter?.addEventListener('change', applyFilters);
  statusFilter?.addEventListener('change', applyFilters);
  resetBtn?.addEventListener('click', ()=>{ if(searchInput) searchInput.value=''; if(typeFilter) typeFilter.value=''; if(statusFilter) statusFilter.value=''; applyFilters(); });
  applyFilters();
})();
</script>
</body>
</html>