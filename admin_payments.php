<?php
session_start();
require_once 'config.php';
include 'admin_dormitory_header.php';

if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'Dormitory Admin') {
    http_response_code(403);
    echo 'Access denied';
    exit;
}

$status = isset($_GET['status']) ? $_GET['status'] : 'All';
$search = isset($_GET['q']) ? trim($_GET['q']) : '';

// Accounting-backed: dormitory_payments
$whereA = [];
$paramsA = [];
$typesA = '';
if ($status !== 'All') { $whereA[] = 'p.status = ?'; $paramsA[] = $status; $typesA .= 's'; }
if ($search !== '') {
    $whereA[] = "(p.user_id LIKE CONCAT('%', ?, '%') OR u.first_name LIKE CONCAT('%', ?, '%') OR u.last_name LIKE CONCAT('%', ?, '%') OR p.receipt_number LIKE CONCAT('%', ?, '%'))";
    $paramsA[] = $search; $paramsA[] = $search; $paramsA[] = $search; $paramsA[] = $search; $typesA .= 'ssss';
}
$whereSqlA = count($whereA) ? ('WHERE ' . implode(' AND ', $whereA)) : '';
$sqlA = "SELECT
    'p_accounting' AS source,
    p.id,
    p.user_id AS student_id,
    TRIM(CONCAT(u.first_name,' ',COALESCE(NULLIF(u.middle_name,''),''),' ',u.last_name)) AS full_name,
    NULL AS room_name,
    p.amount,
    p.receipt_number,
    p.date_paid,
    p.receipt_file AS receipt_ref,
    p.created_at AS submitted_at,
    p.status,
    p.verified_by,
    p.verified_at,
    p.remarks
    FROM dormitory_payments p JOIN users u ON p.user_id = u.user_id
    $whereSqlA
    ORDER BY p.created_at DESC";
$stmtA = $conn->prepare($sqlA);
if ($typesA !== '') { $stmtA->bind_param($typesA, ...$paramsA); }
$stmtA->execute();
$resA = $stmtA->get_result();
$accRows = [];
while ($r = $resA->fetch_assoc()) { $accRows[] = $r; }

// Student uploads: payments
$whereS = [];
$paramsS = [];
$typesS = '';
if ($status !== 'All') { $whereS[] = 'p.status = ?'; $paramsS[] = $status; $typesS .= 's'; }
if ($search !== '') {
    $whereS[] = "(p.student_id LIKE CONCAT('%', ?, '%') OR u.first_name LIKE CONCAT('%', ?, '%') OR u.last_name LIKE CONCAT('%', ?, '%'))";
    $paramsS[] = $search; $paramsS[] = $search; $paramsS[] = $search; $typesS .= 'sss';
}
$whereSqlS = count($whereS) ? ('WHERE ' . implode(' AND ', $whereS)) : '';
$sqlS = "SELECT
    'p_student' AS source,
    p.id,
    p.student_id,
    TRIM(CONCAT(u.first_name,' ',COALESCE(NULLIF(u.middle_name,''),''),' ',u.last_name)) AS full_name,
    COALESCE(r.name, CONCAT('Room #', p.room_id)) AS room_name,
    p.amount,
    NULL AS receipt_number,
    NULL AS date_paid,
    p.receipt_path AS receipt_ref,
    p.submitted_at,
    p.status,
    p.verified_by,
    p.verified_at,
    p.remarks
    FROM payments p JOIN users u ON p.student_id = u.user_id
    LEFT JOIN rooms r ON p.room_id = r.id
    $whereSqlS
    ORDER BY p.submitted_at DESC";
$stmtS = $conn->prepare($sqlS);
if ($typesS !== '') { $stmtS->bind_param($typesS, ...$paramsS); }
$stmtS->execute();
$resS = $stmtS->get_result();
$stuRows = [];
while ($r = $resS->fetch_assoc()) { $stuRows[] = $r; }

// Merge and sort by submitted_at desc
$all = array_merge($accRows, $stuRows);
usort($all, function($a,$b){ return strcmp($b['submitted_at'], $a['submitted_at']); });
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dormitory Payments (Unified)</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-3">
    <h2>Dormitory Payments</h2>
    <form class="row g-2 mb-3" method="get">
        <div class="col-md-3">
            <select name="status" class="form-select">
                <?php $statuses = ['All','Pending','Verified','Rejected']; foreach ($statuses as $s): ?>
                <option value="<?= $s ?>" <?= $status===$s?'selected':'' ?>><?= $s ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" name="q" value="<?= htmlspecialchars($search) ?>" class="form-control" placeholder="Search student, user id, or receipt #">
        </div>
        <div class="col-md-3">
            <button class="btn btn-primary w-100" type="submit">Filter</button>
        </div>
    </form>

    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>Source</th>
                    <th>Student</th>
                    <th>Room</th>
                    <th>Amount</th>
                    <th>Receipt #</th>
                    <th>Date Paid</th>
                    <th>Submitted</th>
                    <th>Receipt</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($all as $row): ?>
                <tr>
                    <td><?= $row['source']==='p_student'?'Student':'Accounting' ?></td>
                    <td><?= htmlspecialchars($row['full_name']) ?><br><small><?= htmlspecialchars($row['student_id']) ?></small></td>
                    <td><?= htmlspecialchars($row['room_name'] ?? '-') ?></td>
                    <td>₱<?= htmlspecialchars(number_format((float)$row['amount'],2)) ?></td>
                    <td><?= htmlspecialchars($row['receipt_number'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['date_paid'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($row['submitted_at']) ?></td>
                    <td>
                        <?php if ($row['source']==='p_student'): ?>
                            <a class="btn btn-sm btn-outline-secondary" href="view_payment_file.php?id=<?= (int)$row['id'] ?>" target="_blank">View</a>
                        <?php else: ?>
                            <a class="btn btn-sm btn-outline-secondary" href="view_payment_receipt.php?id=<?= (int)$row['id'] ?>" target="_blank">View</a>
                        <?php endif; ?>
                    </td>
                    <td><span class="badge bg-<?= $row['status']==='Verified'?'success':($row['status']==='Rejected'?'danger':'warning') ?>"><?= htmlspecialchars($row['status']) ?></span></td>
                    <td>
                        <?php if ($row['status'] === 'Pending'): ?>
                            <?php if ($row['source']==='p_student'): ?>
                                <button class="btn btn-sm btn-success me-1" data-action="verify-student" data-id="<?= (int)$row['id'] ?>">Verify</button>
                                <button class="btn btn-sm btn-danger" data-action="reject-student" data-id="<?= (int)$row['id'] ?>">Reject</button>
                            <?php else: ?>
                                <button class="btn btn-sm btn-success me-1" data-action="verify-acc" data-id="<?= (int)$row['id'] ?>">Verify</button>
                                <button class="btn btn-sm btn-danger" data-action="reject-acc" data-id="<?= (int)$row['id'] ?>">Reject</button>
                            <?php endif; ?>
                        <?php else: ?>
                            <small>Updated by: <?= htmlspecialchars($row['verified_by'] ?? '-') ?> on <?= htmlspecialchars($row['verified_at'] ?? '-') ?></small>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal for setting receipt info (student payments) -->
<div class="modal fade" id="verifyStudentModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header"><h5 class="modal-title">Verify Payment</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label">Receipt Number</label>
                    <input type="text" id="vs_receipt" class="form-control" placeholder="e.g., OR-2025-001" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Date Paid</label>
                    <input type="date" id="vs_date" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Remarks (optional)</label>
                    <textarea id="vs_remarks" class="form-control" rows="2"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button class="btn btn-primary" id="vs_confirm">Confirm Verify</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function(){
    let verifyId = null;
    const vsModal = new bootstrap.Modal(document.getElementById('verifyStudentModal'));
    const vsReceipt = document.getElementById('vs_receipt');
    const vsDate = document.getElementById('vs_date');
    const vsRemarks = document.getElementById('vs_remarks');

    document.querySelectorAll('button[data-action]').forEach(btn => {
        btn.addEventListener('click', function(){
            const id = this.getAttribute('data-id');
            const action = this.getAttribute('data-action');
            if (action === 'verify-student') {
                verifyId = id;
                vsReceipt.value = '';
                vsDate.value = '';
                vsRemarks.value = '';
                vsModal.show();
                return;
            }
            if (action === 'reject-student') {
                const reason = prompt('Reason for rejection:');
                if (reason === null) return;
                const body = new URLSearchParams({id, action: 'reject', remarks: reason});
                fetch('process_student_payment.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body })
                .then(r=>r.json()).then(d=>{ alert(d.message||'Updated'); if(d.success) location.reload(); });
                return;
            }
            if (action === 'verify-acc' || action === 'reject-acc') {
                let body = new URLSearchParams({id, action: action==='verify-acc'?'verify':'reject'});
                if (action === 'reject-acc') {
                    const reason = prompt('Reason for rejection:');
                    if (reason === null) return;
                    body.append('remarks', reason);
                }
                fetch('process_dorm_payment.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body })
                .then(r=>r.json()).then(d=>{ alert(d.message||'Updated'); if(d.success) location.reload(); });
            }
        });
    });

    document.getElementById('vs_confirm').addEventListener('click', function(){
        if (!verifyId) return;
        if (!vsReceipt.value.trim() || !vsDate.value) { alert('Receipt # and Date Paid are required.'); return; }
        const body = new URLSearchParams({
            id: verifyId,
            action: 'verify',
            receipt_number: vsReceipt.value.trim(),
            date_paid: vsDate.value,
            remarks: vsRemarks.value.trim()
        });
        fetch('process_student_payment.php', { method: 'POST', headers: { 'Content-Type': 'application/x-www-form-urlencoded' }, body })
        .then(r=>r.json()).then(d=>{ alert(d.message||'Updated'); if(d.success) location.reload(); });
    });
});
</script>
</body>
</html>
