<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !in_array($_SESSION['role'], ['Scholarship Admin','Admin'])) { echo '<div class="alert alert-danger">Unauthorized.</div>'; exit(); }
if (!isset($_GET['application_id'])) { echo '<div class="alert alert-danger">Missing application ID.</div>'; exit(); }
$applicationId = (int)$_GET['application_id'];

$stmt = $conn->prepare("SELECT id, document_type, file_name, file_path, file_size, mime_type, uploaded_at FROM scholarship_documents WHERE application_id=? ORDER BY uploaded_at DESC");
$stmt->bind_param("i", $applicationId);
$stmt->execute();
$res = $stmt->get_result();
if (!$res->num_rows) { echo '<div class="alert alert-info">No documents uploaded.</div>'; exit(); }

echo '<div class="list-group">';
while ($row = $res->fetch_assoc()) {
  $viewUrl = 'view_scholarship_document.php?id='.(int)$row['id'];
  $sizeKb = round(((int)$row['file_size']) / 1024, 2);
  echo '<a href="'.htmlspecialchars($viewUrl).'" target="_blank" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">'
     . '<div><strong>'.htmlspecialchars($row['document_type']?:'Document').'</strong><br><small class="text-muted">'
     . htmlspecialchars($row['file_name']).' • '.date("M j, Y g:i A", strtotime($row['uploaded_at'])).'</small></div>'
     . '<span class="badge bg-primary rounded-pill">'.$sizeKb.' KB</span></a>';
}
echo '</div>';
