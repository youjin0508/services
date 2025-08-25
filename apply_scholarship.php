<?php
session_start();
require_once 'config.php';
if (!isset($_SESSION['user_id'])) { header('Content-Type: application/json'); echo json_encode(['status'=>'error','message'=>'User not authenticated']); exit(); }

try {
    if (!isset($_POST['scholarship_id'], $_POST['gpa'], $_POST['family_income'])) throw new Exception('Missing required application information.');
    $scholarship_id = (int)$_POST['scholarship_id'];
    $user_id = $_SESSION['user_id'];
    $gpa = (float)$_POST['gpa'];
    $family_income = (float)$_POST['family_income'];
    if ($gpa < 1.0 || $gpa > 4.0) throw new Exception('Invalid GPA value. Must be between 1.0 and 4.0.');

    $ss = $conn->prepare("SELECT * FROM scholarships WHERE id=? AND status='active'");
    $ss->bind_param("i", $scholarship_id);
    $ss->execute(); $res = $ss->get_result();
    if (!$res->num_rows) throw new Exception('Scholarship not found or inactive.');
    $sch = $res->fetch_assoc();
    $ss->close();

    if (strtotime($sch['deadline']) < time()) throw new Exception('Application deadline has passed.');

    $chk = $conn->prepare("SELECT id FROM scholarship_applications WHERE scholarship_id=? AND user_id=?");
    $chk->bind_param("is", $scholarship_id, $user_id);
    $chk->execute(); if ($chk->get_result()->num_rows) throw new Exception('You have already applied for this scholarship.'); $chk->close();

    if ((int)$sch['max_applicants'] > 0) {
        $cc = $conn->prepare("SELECT COUNT(*) c FROM scholarship_applications WHERE scholarship_id=?");
        $cc->bind_param("i", $scholarship_id);
        $cc->execute(); $cur = (int)($cc->get_result()->fetch_assoc()['c'] ?? 0); $cc->close();
        if ($cur >= (int)$sch['max_applicants']) throw new Exception('Maximum number of applicants reached for this scholarship.');
    }

    $uu = $conn->prepare("SELECT * FROM users WHERE user_id=?");
    $uu->bind_param("s", $user_id);
    $uu->execute(); $user = $uu->get_result()->fetch_assoc(); $uu->close();
    if (!$user) throw new Exception('User not found.');

    $conn->begin_transaction();

    $ins = $conn->prepare("INSERT INTO scholarship_applications (scholarship_id, user_id, application_date, status, gpa, course, year_level, documents_submitted, created_at, updated_at) VALUES (?, ?, NOW(), 'pending', ?, ?, ?, ?, NOW(), NOW())");
    $course = $user['course'] ?? '';
    $year_level_value = isset($user['year']) ? (string)$user['year'] : '';
    $empty_docs_json = '[]';
    $ins->bind_param("isdsss", $scholarship_id, $user_id, $gpa, $course, $year_level_value, $empty_docs_json);
    if (!$ins->execute()) throw new Exception('Failed to create application.');
    $application_id = $conn->insert_id;
    $ins->close();

    // Uploads
    $uploaded_documents = [];
    $dir = __DIR__.'/uploads/scholarship_documents/';
    if (!is_dir($dir)) mkdir($dir, 0755, true);
    if (isset($_FILES['documents']) && is_array($_FILES['documents']['name'])) {
        $doc_types = $_POST['document_types'] ?? [];
        foreach ($_FILES['documents']['name'] as $i=>$filename) {
            if ($_FILES['documents']['error'][$i] === UPLOAD_ERR_OK) {
                $tmp = $_FILES['documents']['tmp_name'][$i];
                $size = (int)$_FILES['documents']['size'][$i];
                $mime = $_FILES['documents']['type'][$i];
                $dtype = $doc_types[$i] ?? 'Unknown';
                $allowed = ['application/pdf','image/jpeg','image/jpg','image/png','application/msword','application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                if (!in_array($mime, $allowed)) throw new Exception('Invalid file type for document: '.$dtype);
                if ($size > 5*1024*1024) throw new Exception('File too large: '.$dtype);
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                $newname = $application_id.'_'.$i.'_'.time().'.'.$ext;
                $path = $dir.$newname;
                if (!move_uploaded_file($tmp, $path)) throw new Exception('Failed to save document: '.$dtype);

                $relPath = 'uploads/scholarship_documents/'.$newname;
                $di = $conn->prepare("INSERT INTO scholarship_documents (application_id, document_type, file_name, file_path, file_size, mime_type, uploaded_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
                $di->bind_param("isssis", $application_id, $dtype, $filename, $relPath, $size, $mime);
                if (!$di->execute()) throw new Exception('Failed to save document record.');
                $di->close();

                $uploaded_documents[] = ['type'=>$dtype,'filename'=>$filename,'path'=>$relPath];
            }
        }
    }

    $docs_json = json_encode($uploaded_documents);
    $up = $conn->prepare("UPDATE scholarship_applications SET documents_submitted=? WHERE id=?");
    $up->bind_param("si", $docs_json, $application_id); $up->execute(); $up->close();

    $inc = $conn->prepare("UPDATE scholarships SET current_applicants=current_applicants+1 WHERE id=?");
    $inc->bind_param("i", $scholarship_id); $inc->execute(); $inc->close();

    // Notification
    $nn = $conn->prepare("INSERT INTO scholarship_notifications (user_id, title, message, type, is_read, created_at) VALUES (?, ?, ?, 'success', 0, NOW())");
    $title = 'Application Submitted Successfully';
    $message = 'Your application for '.$sch['name'].' has been submitted successfully. Application ID: '.$application_id;
    $nn->bind_param("sss", $user_id, $title, $message); $nn->execute(); $nn->close();

    // Audit log (new_values only)
    $al = $conn->prepare("INSERT INTO scholarship_audit_log (action, table_name, record_id, old_values, new_values, user_id, ip_address, user_agent, created_at) VALUES ('CREATE','scholarship_applications', ?, NULL, ?, ?, ?, ?, NOW())");
    $new_values = json_encode(['scholarship_id'=>$scholarship_id,'user_id'=>$user_id,'gpa'=>$gpa,'documents_count'=>count($uploaded_documents)]);
    $ip = $_SERVER['REMOTE_ADDR'] ?? null; $ua = $_SERVER['HTTP_USER_AGENT'] ?? null;
    $al->bind_param("issss", $application_id, $new_values, $user_id, $ip, $ua);
    $al->execute(); $al->close();

    $conn->commit();
    header('Content-Type: application/json');
    echo json_encode(['status'=>'success','message'=>'Your scholarship application has been submitted successfully! Application ID: '.$application_id,'application_id'=>$application_id,'documents_uploaded'=>count($uploaded_documents)]);
} catch (Exception $e) {
    if ($conn->errno) $conn->rollback();
    header('Content-Type: application/json');
    echo json_encode(['status'=>'error','message'=>$e->getMessage()]);
}
