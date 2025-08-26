<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scholarship Admin Panel</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="admin-container">
        <!-- Enhanced Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-header">
                <div class="logo-container">
                    <div class="logo-icon">
                        <i class="fas fa-graduation-cap"></i>
                    </div>
                    <div class="logo-text">
                        <h2>Scholarship</h2>
                        <span>Admin Panel</span>
                    </div>
                </div>
            </div>
            
            <nav class="sidebar-nav">
                <div class="nav-section">
                    <h3 class="nav-title">Main Dashboard</h3>
                    <a href="scholarship_admin_dashboard.php" class="nav-item" id="dashboard">
                        <div class="nav-icon">
                            <i class="fas fa-tachometer-alt"></i>
                        </div>
                        <span>Dashboard</span>
                        <div class="nav-indicator"></div>
                    </a>
                </div>
                
                <div class="nav-section">
                    <h3 class="nav-title">Scholarship Management</h3>
                    <a href="admin_manage_scholarships.php" class="nav-item" id="manage-scholars">
                        <div class="nav-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <span>Manage Scholarships</span>
                        <div class="nav-indicator"></div>
                    </a>
                    
                    <a href="manage_applications.php" class="nav-item" id="applications">
                        <div class="nav-icon">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <span>Applications</span>
                        <div class="nav-indicator"></div>
                    </a>
                    
                    <a href="approved_scholars.php" class="nav-item" id="approved-scholars">
                        <div class="nav-icon">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <span>Approved Scholars</span>
                        <div class="nav-indicator"></div>
                    </a>
                </div>
                
                <div class="nav-section">
                    <a href="login.php" class="nav-item logout-item" id="logout">
                        <div class="nav-icon">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <span>Logout</span>
                        <div class="nav-indicator"></div>
                    </a>
                </div>
            </nav>
            
            <div class="sidebar-footer">
                <div class="admin-info">
                    <div class="admin-avatar">
                        <i class="fas fa-user-shield"></i>
                    </div>
                    <div class="admin-details">
                        <span class="admin-name">Administrator</span>
                        <span class="admin-role">Scholarship Admin</span>
                    </div>
                </div>
            </div>
        </aside>
        
        <!-- Main Content Area -->
        <main class="main-content">
            <!-- Content will be included here -->
        </main>
    </div>

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
        overflow-x: hidden;
    }

    .admin-container {
        display: flex;
        min-height: 100vh;
    }

    /* Enhanced Sidebar Styles */
    .sidebar {
        width: 300px;
        background: linear-gradient(180deg, #1a1a2e 0%, #16213e 50%, #0f3460 100%);
        color: white;
        position: fixed;
        height: 100vh;
        overflow-y: auto;
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.3);
        z-index: 1000;
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .sidebar::-webkit-scrollbar {
        width: 6px;
    }

    .sidebar::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
        border-radius: 3px;
    }

    .sidebar::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 3px;
    }

    /* Enhanced Header */
    .sidebar-header {
        padding: 2rem 1.5rem;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        position: relative;
        overflow: hidden;
    }

    .sidebar-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><defs><pattern id="grain" width="100" height="100" patternUnits="userSpaceOnUse"><circle cx="50" cy="50" r="1" fill="white" opacity="0.1"/></pattern></defs><rect width="100" height="100" fill="url(%23grain)"/></svg>');
        opacity: 0.3;
    }

    .logo-container {
        position: relative;
        z-index: 2;
        text-align: center;
    }

    .logo-icon {
        width: 60px;
        height: 60px;
        background: rgba(255, 255, 255, 0.2);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        backdrop-filter: blur(10px);
        border: 2px solid rgba(255, 255, 255, 0.3);
    }

    .logo-icon i {
        font-size: 1.8rem;
        color: #FFD700;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }

    .logo-text h2 {
        font-size: 1.5rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: #FFD700;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
    }

    .logo-text span {
        font-size: 0.875rem;
        font-weight: 400;
        color: rgba(255, 255, 255, 0.9);
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    /* Navigation Styles */
    .sidebar-nav {
        padding: 2rem 1rem;
    }

    .nav-section {
        margin-bottom: 2rem;
    }

    .nav-title {
        font-size: 0.75rem;
        font-weight: 600;
        color: rgba(255, 255, 255, 0.6);
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 1rem;
        padding-left: 0.5rem;
    }

    .nav-item {
        display: flex;
        align-items: center;
        padding: 1rem 1rem;
        color: rgba(255, 255, 255, 0.8);
        text-decoration: none;
        border-radius: 12px;
        margin-bottom: 0.5rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .nav-item::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.1), transparent);
        transition: left 0.5s;
    }

    .nav-item:hover::before {
        left: 100%;
    }

    .nav-item:hover {
        background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%);
        color: white;
        transform: translateX(8px);
        border-color: rgba(102, 126, 234, 0.5);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.3);
    }

    .nav-item.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
        border-color: transparent;
    }

    .nav-icon {
        width: 40px;
        height: 40px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        transition: all 0.3s ease;
    }

    .nav-item:hover .nav-icon {
        background: rgba(255, 255, 255, 0.2);
        transform: scale(1.1);
    }

    .nav-item.active .nav-icon {
        background: rgba(255, 255, 255, 0.2);
    }

    .nav-icon i {
        font-size: 1.1rem;
        color: #FFD700;
    }

    .nav-item span {
        font-weight: 500;
        font-size: 0.95rem;
        flex: 1;
    }

    .nav-indicator {
        width: 6px;
        height: 6px;
        background: #FFD700;
        border-radius: 50%;
        opacity: 0;
        transition: all 0.3s ease;
    }

    .nav-item.active .nav-indicator {
        opacity: 1;
        transform: scale(1.5);
    }

    /* Logout Item Special Styling */
    .logout-item {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.2) 0%, rgba(220, 53, 69, 0.1) 100%);
        border-color: rgba(220, 53, 69, 0.3);
    }

    .logout-item:hover {
        background: linear-gradient(135deg, rgba(220, 53, 69, 0.3) 0%, rgba(220, 53, 69, 0.2) 100%);
        border-color: rgba(220, 53, 69, 0.6);
        box-shadow: 0 8px 25px rgba(220, 53, 69, 0.3);
    }

    .logout-item .nav-icon i {
        color: #ff6b6b;
    }

    /* Sidebar Footer */
    .sidebar-footer {
        padding: 1.5rem;
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: auto;
    }

    .admin-info {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: rgba(255, 255, 255, 0.05);
        border-radius: 12px;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .admin-avatar {
        width: 45px;
        height: 45px;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
        border: 2px solid rgba(255, 255, 255, 0.2);
    }

    .admin-avatar i {
        font-size: 1.2rem;
        color: white;
    }

    .admin-details {
        flex: 1;
    }

    .admin-name {
        display: block;
        font-weight: 600;
        font-size: 0.9rem;
        color: white;
        margin-bottom: 0.25rem;
    }

    .admin-role {
        display: block;
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.7);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    /* Main Content Area */
    .main-content {
        flex: 1;
        margin-left: 300px;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        min-height: 100vh;
    }

    /* Responsive Design */
    @media (max-width: 1024px) {
        .sidebar {
            width: 280px;
        }
        .main-content {
            margin-left: 280px;
        }
    }

    @media (max-width: 768px) {
        .sidebar {
            transform: translateX(-100%);
            width: 100%;
            max-width: 320px;
        }
        
        .sidebar.active {
            transform: translateX(0);
        }
        
        .main-content {
            margin-left: 0;
            padding: 1rem;
        }
        
        .sidebar-header {
            padding: 1.5rem 1rem;
        }
        
        .logo-icon {
            width: 50px;
            height: 50px;
        }
        
        .logo-text h2 {
            font-size: 1.25rem;
        }
    }

    /* Animation Classes */
    .fade-in {
        animation: fadeIn 0.6s ease-out;
    }

    .slide-in {
        animation: slideIn 0.6s ease-out;
    }

    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }

    @keyframes slideIn {
        from { transform: translateX(-20px); opacity: 0; }
        to { transform: translateX(0); opacity: 1; }
    }

    /* Enhanced Focus States for Accessibility */
    .nav-item:focus {
        outline: 2px solid #FFD700;
        outline-offset: 2px;
    }

    /* Smooth Transitions */
    .nav-item,
    .nav-icon,
    .nav-indicator {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
</style>

<script>
    // Enhanced JavaScript for better user experience
    document.addEventListener('DOMContentLoaded', function() {
        // Highlight active menu item
        const currentLocation = window.location.pathname.split('/').pop();
        const menuItems = document.querySelectorAll('.nav-item');
        
        menuItems.forEach(item => {
            const href = item.getAttribute('href');
            if (href && href === currentLocation) {
                item.classList.add('active');
            }
        });

        // Add smooth animations to nav items
        const navItems = document.querySelectorAll('.nav-item');
        navItems.forEach((item, index) => {
            item.style.animationDelay = `${index * 0.1}s`;
            item.classList.add('fade-in');
        });

        // Enhanced hover effects
        navItems.forEach(item => {
            item.addEventListener('mouseenter', function() {
                this.style.transform = 'translateX(8px) scale(1.02)';
            });
            
            item.addEventListener('mouseleave', function() {
                this.style.transform = 'translateX(0) scale(1)';
            });
        });

        // Mobile menu toggle (if needed)
        const createMobileToggle = () => {
            if (window.innerWidth <= 768) {
                const toggleBtn = document.createElement('button');
                toggleBtn.innerHTML = '<i class="fas fa-bars"></i>';
                toggleBtn.className = 'mobile-toggle';
                toggleBtn.style.cssText = `
                    position: fixed;
                    top: 1rem;
                    left: 1rem;
                    z-index: 1001;
                    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                    border: none;
                    color: white;
                    padding: 0.75rem;
                    border-radius: 8px;
                    cursor: pointer;
                    box-shadow: 0 4px 15px rgba(0,0,0,0.2);
                `;
                
                toggleBtn.addEventListener('click', () => {
                    document.querySelector('.sidebar').classList.toggle('active');
                });
                
                document.body.appendChild(toggleBtn);
            }
        };

        // Initialize mobile functionality
        createMobileToggle();
        window.addEventListener('resize', createMobileToggle);

        // Add loading states
        navItems.forEach(item => {
            item.addEventListener('click', function(e) {
                if (!this.classList.contains('logout-item')) {
                    this.style.pointerEvents = 'none';
                    this.style.opacity = '0.7';
                    
                    setTimeout(() => {
                        this.style.pointerEvents = 'auto';
                        this.style.opacity = '1';
                    }, 1000);
                }
            });
        });
    });
</script>
</body>
</html>