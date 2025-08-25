<?php
session_start();
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Fetch user details for application form
$user_id = $_SESSION['user_id'];
$user_sql = "SELECT * FROM users WHERE user_id = ?";
$user_stmt = $conn->prepare($user_sql);
$user_stmt->bind_param("s", $user_id);
$user_stmt->execute();
$user_result = $user_stmt->get_result();
$user_data = $user_result->fetch_assoc();
$user_stmt->close();

// Fetch scholarships (tolerant to minor data issues)
$sql = "SELECT s.*, 
        (SELECT COUNT(*) FROM scholarship_applications sa WHERE sa.scholarship_id = s.id) AS applicant_count
        FROM scholarships s
        WHERE LOWER(TRIM(s.status)) = 'active'
          AND (s.deadline IS NULL OR s.deadline >= CURDATE())
        ORDER BY s.deadline ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enhanced Scholarships - NEUST Gabaldon</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        :root {
            --neust-blue: #003366;
            --neust-light-blue: #00509E;
            --neust-gold: #FFD700;
            --neust-white: #FFFFFF;
            --neust-gray: #F8F9FA;
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
            background: var(--neust-gray); 
        }
        
        .container { margin-top: 20px; }
        
        .page-header {
            background: linear-gradient(135deg, var(--neust-blue), var(--neust-light-blue));
            color: var(--neust-white);
            padding: 30px;
            border-radius: 15px;
            margin-bottom: 30px;
            text-align: center;
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        
        .page-header h1 {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: 10px;
        }
        
        .page-header p {
            font-size: 1.1rem;
            opacity: 0.9;
        }
        
        .filters-section {
            background: var(--neust-white);
            padding: 25px;
            border-radius: 15px;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
        }
        
        .filter-group { margin-bottom: 15px; }
        
        .filter-group label {
            font-weight: 600;
            color: var(--neust-blue);
            margin-bottom: 8px;
        }
        
        .scholarship-card {
            background: var(--neust-white);
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0,0,0,0.1);
            margin-bottom: 25px;
            transition: all 0.3s ease;
            border: none;
            overflow: hidden;
        }
        
        .scholarship-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.15);
        }
        
        .card-header {
            background: linear-gradient(135deg, var(--neust-blue), var(--neust-light-blue));
            color: var(--neust-white);
            padding: 20px;
            border: none;
        }
        
        .card-header h5 {
            font-size: 1.3rem;
            font-weight: 600;
            margin: 0;
        }
        
        .scholarship-type {
            background: var(--neust-gold);
            color: var(--neust-blue);
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 600;
            display: inline-block;
            margin-bottom: 10px;
        }
        
        .card-body { padding: 25px; }
        
        .scholarship-info {
            display: flex;
            align-items: center;
            margin-bottom: 15px;
            color: #666;
        }
        
        .scholarship-info i {
            width: 20px;
            margin-right: 10px;
            color: var(--neust-blue);
        }
        
        .amount-badge {
            background: linear-gradient(135deg, #28a745, #20c997);
            color: white;
            padding: 8px 16px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1.1rem;
            display: inline-block;
            margin-bottom: 15px;
        }
        
        .deadline-warning {
            background: #ffc107;
            color: #856404;
            padding: 8px 16px;
            border-radius: 8px;
            font-size: 0.9rem;
            margin-bottom: 15px;
        }
        
        .btn-apply {
            background: linear-gradient(135deg, var(--neust-gold), #FFA500);
            border: none;
            color: var(--neust-blue);
            padding: 12px 30px;
            border-radius: 25px;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            width: 100%;
            margin-bottom: 10px;
        }
        
        .btn-apply:hover {
            transform: scale(1.05);
            box-shadow: 0 5px 15px rgba(255, 215, 0, 0.4);
        }
        
        .btn-details {
            background: var(--neust-light-blue);
            border: none;
            color: var(--neust-white);
            padding: 10px 25px;
            border-radius: 20px;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
        }
        
        .btn-details:hover {
            background: var(--neust-blue);
            transform: scale(1.02);
        }
        
        .search-box {
            border: 2px solid #e9ecef;
            border-radius: 25px;
            padding: 12px 20px;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        
        .search-box:focus {
            border-color: var(--neust-blue);
            box-shadow: 0 0 0 0.2rem rgba(0, 83, 158, 0.25);
        }
        
        .stats-card {
            background: var(--neust-white);
            padding: 20px;
            border-radius: 15px;
            text-align: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            margin-bottom: 20px;
        }
        
        .stats-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--neust-blue);
        }
        
        .stats-label { color: #666; font-size: 0.9rem; }
        
        .no-scholarships {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .no-scholarships i {
            font-size: 4rem;
            color: #ddd;
            margin-bottom: 20px;
        }
        
        @media (max-width: 768px) {
            .page-header h1 { font-size: 2rem; }
            .filters-section { padding: 20px; }
            .scholarship-card { margin-bottom: 20px; }
        }
    </style>
</head>
<body>

<?php include('student_header.php'); ?>

<div class="container">
    <div class="page-header">
        <h1><i class="fas fa-graduation-cap"></i> Available Scholarships</h1>
        <p>Discover and apply for scholarships that match your academic excellence and financial needs</p>
    </div>
    
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number"><?= $result ? $result->num_rows : 0 ?></div>
                <div class="stats-label">Available Scholarships</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">
                    <?php
                    $pending_sql = "SELECT COUNT(*) as count FROM scholarship_applications WHERE user_id = ? AND status = 'pending'";
                    $pending_stmt = $conn->prepare($pending_sql);
                    $pending_stmt->bind_param("s", $user_id);
                    $pending_stmt->execute();
                    $pending_result = $pending_stmt->get_result();
                    $pending_count = $pending_result->fetch_assoc()['count'] ?? 0;
                    $pending_stmt->close();
                    echo (int)$pending_count;
                    ?>
                </div>
                <div class="stats-label">Pending Applications</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">
                    <?php
                    $approved_sql = "SELECT COUNT(*) as count FROM scholarship_applications WHERE user_id = ? AND status = 'approved'";
                    $approved_stmt = $conn->prepare($approved_sql);
                    $approved_stmt->bind_param("s", $user_id);
                    $approved_stmt->execute();
                    $approved_result = $approved_stmt->get_result();
                    $approved_count = $approved_result->fetch_assoc()['count'] ?? 0;
                    $approved_stmt->close();
                    echo (int)$approved_count;
                    ?>
                </div>
                <div class="stats-label">Approved Scholarships</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="stats-card">
                <div class="stats-number">
                    <?php
                    $total_sql = "SELECT COUNT(*) as count FROM scholarship_applications WHERE user_id = ?";
                    $total_stmt = $conn->prepare($total_sql);
                    $total_stmt->bind_param("s", $user_id);
                    $total_stmt->execute();
                    $total_result = $total_stmt->get_result();
                    $total_count = $total_result->fetch_assoc()['count'] ?? 0;
                    $total_stmt->close();
                    echo (int)$total_count;
                    ?>
                </div>
                <div class="stats-label">Total Applications</div>
            </div>
        </div>
    </div>
    
    <div class="filters-section">
        <div class="row">
            <div class="col-md-4">
                <div class="filter-group">
                    <label for="searchInput"><i class="fas fa-search"></i> Search Scholarships</label>
                    <input type="text" id="searchInput" class="form-control search-box" placeholder="Search by name, type, or description...">
                </div>
            </div>
            <div class="col-md-3">
                <div class="filter-group">
                    <label for="typeFilter"><i class="fas fa-filter"></i> Scholarship Type</label>
                    <select id="typeFilter" class="form-control">
                        <option value="">All Types</option>
                        <option value="Academic">Academic</option>
                        <option value="Leadership">Leadership</option>
                        <option value="Need-based">Need-based</option>
                        <option value="Sports">Sports</option>
                        <option value="Arts">Arts</option>
                    </select>
                </div>
            </div>
            <div class="col-md-3">
                <div class="filter-group">
                    <label for="sortFilter"><i class="fas fa-sort"></i> Sort By</label>
                    <select id="sortFilter" class="form-control">
                        <option value="deadline">Deadline (Earliest)</option>
                        <option value="deadline_desc">Deadline (Latest)</option>
                        <option value="amount">Amount (Highest)</option>
                        <option value="amount_asc">Amount (Lowest)</option>
                        <option value="popularity">Popularity</option>
                    </select>
                </div>
            </div>
            <div class="col-md-2">
                <div class="filter-group">
                    <label>&nbsp;</label>
                    <button id="resetFilters" class="btn btn-outline-secondary w-100">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div id="scholarshipsGrid" class="row">
        <?php if ($result && $result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-lg-4 col-md-6 scholarship-item" 
                     data-name="<?= strtolower($row['name']) ?>"
                     data-type="<?= strtolower($row['type']) ?>"
                     data-amount="<?= (float)$row['amount'] ?>"
                     data-deadline="<?= htmlspecialchars($row['deadline'] ?? '') ?>"
                     data-popularity="<?= (int)$row['applicant_count'] ?>">
                    
                    <div class="scholarship-card">
                        <div class="card-header">
                            <div class="scholarship-type"><?= htmlspecialchars($row['type']) ?></div>
                            <h5 class="mb-0"><?= htmlspecialchars($row['name']) ?></h5>
                        </div>
                        
                        <div class="card-body">
                            <?php if ((float)$row['amount'] > 0): ?>
                                <div class="amount-badge mb-2">
                                    <i class="fas fa-peso-sign"></i> <?= number_format((float)$row['amount'], 2) ?>
                                </div>
                            <?php endif; ?>
                            
                            <p class="text-muted mb-3"><?= htmlspecialchars(mb_strimwidth($row['description'], 0, 100, '...')) ?></p>
                            
                            <div class="scholarship-info">
                                <i class="fas fa-calendar-alt"></i>
                                <span>Deadline: <?= $row['deadline'] ? date("M j, Y", strtotime($row['deadline'])) : '—' ?></span>
                            </div>
                            
                            <div class="scholarship-info">
                                <i class="fas fa-users"></i>
                                <span><?= (int)$row['applicant_count'] ?> applicants</span>
                            </div>
                            
                            <?php
                            if (!empty($row['deadline'])) {
                                $days_until_deadline = (strtotime($row['deadline']) - time()) / (60 * 60 * 24);
                                if ($days_until_deadline <= 7 && $days_until_deadline > 0):
                            ?>
                                <div class="deadline-warning">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    Only <?= ceil($days_until_deadline) ?> days left!
                                </div>
                            <?php 
                                endif;
                            } 
                            ?>
                            
                            <button class="btn btn-apply apply-btn" 
                                    data-id="<?= (int)$row['id'] ?>" 
                                    data-name="<?= htmlspecialchars($row['name']) ?>">
                                <i class="fas fa-paper-plane"></i> Apply Now
                            </button>
                            
                            <button class="btn btn-details view-details" 
                                    data-id="<?= (int)$row['id'] ?>">
                                <i class="fas fa-info-circle"></i> View Details
                            </button>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="col-12">
                <div class="no-scholarships">
                    <i class="fas fa-graduation-cap"></i>
                    <h4>No scholarships available at the moment</h4>
                    <p>Please check back later for new scholarship opportunities.</p>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<div class="modal fade" id="scholarshipModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--neust-blue); color: var(--neust-white);">
                <h5 class="modal-title" id="scholarshipTitle"></h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="scholarshipDetails"></div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="applyFromModal">
                    <i class="fas fa-paper-plane"></i> Apply Now
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="applicationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header" style="background: var(--neust-blue); color: var(--neust-white);">
                <h5 class="modal-title">Scholarship Application Form</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="applicationForm"></div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
$(document).ready(function(){
    let currentScholarshipId = null;
    
    function filterScholarships() {
        const searchTerm = ($('#searchInput').val() || '').toLowerCase();
        const typeFilter = ($('#typeFilter').val() || '').toLowerCase();
        const sortBy = $('#sortFilter').val();
        
        $('.scholarship-item').each(function() {
            const $item = $(this);
            const name = ($item.data('name') || '').toString();
            const type = ($item.data('type') || '').toString();
            
            let show = true;
            if (searchTerm && !name.includes(searchTerm)) show = false;
            if (typeFilter && type !== typeFilter) show = false;
            $item.toggle(show);
        });
        
        const $items = $('.scholarship-item:visible').get();
        $items.sort(function(a, b) {
            const $a = $(a), $b = $(b);
            switch(sortBy) {
                case 'deadline':      return new Date($a.data('deadline')) - new Date($b.data('deadline'));
                case 'deadline_desc': return new Date($b.data('deadline')) - new Date($a.data('deadline'));
                case 'amount':        return ($b.data('amount')||0) - ($a.data('amount')||0);
                case 'amount_asc':    return ($a.data('amount')||0) - ($b.data('amount')||0);
                case 'popularity':    return ($b.data('popularity')||0) - ($a.data('popularity')||0);
                default: return 0;
            }
        });
        $('#scholarshipsGrid').append($items);
    }
    
    $('#searchInput, #typeFilter, #sortFilter').on('change keyup', filterScholarships);
    $('#resetFilters').click(function() {
        $('#searchInput').val('');
        $('#typeFilter').val('');
        $('#sortFilter').val('deadline');
        filterScholarships();
    });
    
    $('.view-details').click(function() {
        const id = $(this).data('id');
        currentScholarshipId = id;
        
        $.ajax({
            url: 'get_scholarship.php',
            type: 'GET',
            data: { id: id },
            success: function(response) {
                try {
                    const data = (typeof response === 'string') ? JSON.parse(response) : response;
                    if (data.error) { alert('Error: ' + data.error); return; }
                    
                    $('#scholarshipTitle').text(data.name);
                    $('#scholarshipDetails').html(`
                        <div class="row">
                            <div class="col-md-8">
                                <h6 class="text-primary">Description</h6>
                                <p>${(data.description||'').toString()}</p>
                                
                                <h6 class="text-primary">Eligibility Requirements</h6>
                                <p>${(data.eligibility||'').toString()}</p>
                                
                                <h6 class="text-primary">Scholarship Requirements</h6>
                                <p>${(data.requirements || 'No specific requirements listed.').toString()}</p>
                            </div>
                            <div class="col-md-4">
                                <div class="card">
                                    <div class="card-body">
                                        <h6 class="text-primary">Quick Info</h6>
                                        <p><strong>Type:</strong> ${data.type}</p>
                                        <p><strong>Amount:</strong> ₱${parseFloat(data.amount||0).toLocaleString()}</p>
                                        <p><strong>Deadline:</strong> ${data.deadline ? new Date(data.deadline).toLocaleDateString() : '—'}</p>
                                        <p><strong>Max Applicants:</strong> ${data.max_applicants || 'Unlimited'}</p>
                                        <p><strong>Current Applicants:</strong> ${data.current_applicants || 0}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                    
                    $('#scholarshipModal').modal('show');
                } catch (e) {
                    alert('Error parsing scholarship data');
                }
            },
            error: function() {
                alert('Error loading scholarship details');
            }
        });
    });
    
    // Apply functionality (GET via querystring so load_application_form.php receives scholarship_id correctly)
    $('.apply-btn, #applyFromModal').off('click').on('click', function() {
        const scholarshipId = currentScholarshipId || $(this).data('id');
        currentScholarshipId = scholarshipId;

        $('#applicationForm').load('load_application_form.php?scholarship_id=' + encodeURIComponent(scholarshipId), function() {
            $('#applicationModal').modal('show');
            $('#scholarshipModal').modal('hide');
        });
    });
});
</script>

</body>
</html>
