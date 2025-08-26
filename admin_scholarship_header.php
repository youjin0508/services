<div class="sidebar" role="navigation" aria-label="Scholarship admin navigation">
    <div class="sidebar-header d-flex align-items-center justify-content-between">
        <div class="d-flex align-items-center">
            <i class="fa fa-graduation-cap"></i>
            <span>Admin Panel</span>
        </div>
        <button class="btn-toggle d-lg-none" id="sidebarClose" aria-label="Close sidebar">
            <i class="fas fa-times"></i>
        </button>
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
    <div class="sidebar-footer d-none d-lg-flex">
        <button id="themeToggleSidebar" class="btn-theme" aria-label="Toggle theme">
            <i class="fa fa-moon"></i>
        </button>
    </div>
</div>
<div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div>

<style>
    :root {
        --sidebar-width: 260px;
        --sidebar-bg: #003366;
        --sidebar-bg-dark: #002855;
        --accent: #FFD700;
        --text: #ffffff;
        --hover: #004080;
    }

    * { box-sizing: border-box; }
    body { margin: 0; }

    .sidebar {
        width: var(--sidebar-width);
        height: 100vh;
        background: linear-gradient(180deg, var(--sidebar-bg) 0%, #002d5c 100%);
        color: var(--text);
        position: fixed;
        left: 0; top: 0;
        padding-top: 12px;
        transition: transform .3s ease, box-shadow .3s ease;
        z-index: 1040;
        box-shadow: 4px 0 18px rgba(0,0,0,.15);
    }

    .sidebar-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 14px 16px;
        background-color: var(--sidebar-bg-dark);
        font-size: 1.1rem;
        font-weight: 700;
        color: var(--accent);
        text-transform: uppercase;
        letter-spacing: .5px;
    }
    .sidebar-header i { margin-right: 10px; color: var(--accent); }

    .btn-toggle { background: transparent; border: 1px solid rgba(255,255,255,.35); color: #fff; border-radius: 8px; padding: 6px 10px; }

    .sidebar-menu { margin-top: 10px; padding: 0 10px; }

    .menu-item {
        text-decoration: none;
        color: #f8f9fb;
        display: block;
        padding: 12px 14px;
        font-size: 1rem;
        margin: 8px 5px;
        background-color: rgba(255,255,255,.06);
        transition: all .2s ease;
        border-radius: 10px;
        border: 1px solid rgba(255,255,255,.08);
    }

    .menu-item i { width: 20px; margin-right: 10px; }

    .menu-item:hover,
    .menu-item.active {
        background-color: var(--accent);
        color: #003366;
        transform: translateX(2px);
        box-shadow: 0 6px 16px rgba(0,0,0,.15);
    }

    .logout-btn {
        text-decoration: none;
        color: white;
        display: block;
        padding: 12px 14px;
        font-size: 1rem;
        background-color: #d9534f;
        margin: 20px 15px;
        border-radius: 10px;
        text-align: center;
        transition: all .2s ease;
        border: 1px solid rgba(255,255,255,.08);
    }

    .logout-btn:hover { background-color: #c9302c; transform: translateY(-1px); }

    .sidebar-footer { padding: 10px 16px; margin-top: auto; }
    .btn-theme { background: transparent; color: var(--text); border: 1px solid rgba(255,255,255,.35); border-radius: 10px; padding: 8px 10px; }
    .btn-theme:hover { background: rgba(255,255,255,.1); }

    .sidebar-overlay { position: fixed; inset: 0; background: rgba(0,0,0,.4); display: none; z-index: 1039; }

    @media (max-width: 991.98px) {
        .sidebar { transform: translateX(-100%); }
        body.sidebar-open .sidebar { transform: translateX(0); }
        body.sidebar-open #sidebarOverlay { display: block; }
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
        // Theme toggle (aligns with dashboard pages using data-theme)
        const themeBtn = document.getElementById('themeToggleSidebar');
        if (themeBtn) {
            const saved = localStorage.getItem('theme') || 'dark';
            document.body.setAttribute('data-theme', saved);
            themeBtn.innerHTML = saved === 'light' ? '<i class="fa fa-moon"></i>' : '<i class="fa fa-sun"></i>';
            themeBtn.addEventListener('click', function(){
                const next = document.body.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                document.body.setAttribute('data-theme', next);
                localStorage.setItem('theme', next);
                themeBtn.innerHTML = next === 'light' ? '<i class="fa fa-moon"></i>' : '<i class="fa fa-sun"></i>';
            });
        }
        // Mobile: open/close handlers
        const overlay = document.getElementById('sidebarOverlay');
        const closeBtn = document.getElementById('sidebarClose');
        // Open when any .menu toggle elsewhere triggers body.sidebar-open
        if (overlay) overlay.addEventListener('click', ()=> document.body.classList.remove('sidebar-open'));
        if (closeBtn) closeBtn.addEventListener('click', ()=> document.body.classList.remove('sidebar-open'));
    });
</script>