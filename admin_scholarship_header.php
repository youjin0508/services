<div class="sidebar">
    <div class="sidebar-header">
        <i class="fa fa-graduation-cap"></i>
        <span>Admin Panel</span>
    </div>
    <div class="sidebar-menu">
        <a href="scholarship_admin_dashboard.php" class="menu-item" id="dashboard">
            <i class="fas fa-tachometer-alt"></i> Dashboard
        </a>
        <a href="admin_manage_scholarships.php" class="menu-item" id="manage-scholars">
            <i class="fas fa-graduation-cap"></i> Manage Scholarships
        </a>
        <a href="manage_applications.php" class="menu-item" id="applications">
            <i class="fas fa-file-alt"></i> Applications
        </a>
        <a href="approved_scholars.php" class="menu-item" id="approved-scholars">
            <i class="fas fa-check-circle"></i> Approved Scholars
        </a>
        <a href="login.php" class="logout-btn">
            <i class="fas fa-sign-out-alt"></i> Logout
        </a>
    </div>
</div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Roboto', sans-serif;
    }

    .sidebar {
        width: 260px;
        height: 100vh;
        background-color: #003366;
        color: white;
        position: fixed;
        padding-top: 20px;
        transition: all 0.3s ease;
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 20px;
        background-color: #002855;
        font-size: 1.5rem;
        font-weight: 700;
        text-transform: uppercase;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        color: #FFD700;
    }

    .sidebar-header i {
        margin-right: 10px;
        color: #FFD700;
    }

    .sidebar-menu {
        margin-top: 20px;
    }

    .menu-item {
        text-decoration: none;
        color: white;
        display: block;
        padding: 15px 20px;
        font-size: 1.1rem;
        margin: 8px 15px;
        background-color: #004080;
        transition: all 0.3s ease;
        border-radius: 8px;
    }

    .menu-item:hover,
    .menu-item.active {
        background-color: #FFD700;
        color: #003366;
        transform: scale(1.05);
        font-weight: bold;
    }

    .logout-btn {
        text-decoration: none;
        color: white;
        display: block;
        padding: 15px;
        font-size: 1.1rem;
        background-color: #d9534f;
        margin: 30px 15px;
        border-radius: 8px;
        text-align: center;
        transition: all 0.3s ease;
    }

    .logout-btn:hover {
        background-color: #c9302c;
        transform: scale(1.05);
    }

    @media (max-width: 768px) {
        .sidebar {
            width: 100%;
            height: auto;
            position: relative;
        }
        .sidebar-header {
            justify-content: flex-start;
            padding-left: 15px;
        }
        .sidebar-menu {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .menu-item {
            width: 100%;
            text-align: left;
        }
        .logout-btn {
            width: 100%;
            text-align: left;
        }
    }
</style>

<script>
    // Highlight the active menu item
    document.addEventListener('DOMContentLoaded', function() {
        const currentLocation = window.location.pathname.split('/').pop();
        const menuItems = document.querySelectorAll('.menu-item');
        menuItems.forEach(item => {
            if (item.getAttribute('href') === currentLocation) {
                item.classList.add('active');
            }
        });
    });
</script>