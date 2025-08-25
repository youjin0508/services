<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        /* General Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Navigation Bar */
        .navbar {
            background-color: #003366;
            padding: 15px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0px 3px 10px rgba(0, 0, 0, 0.2);
        }

        .logo {
            font-size: 22px;
            font-weight: bold;
            color: gold;
        }

        .nav-container {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .nav-links {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            align-items: center;
        }

        .nav-links li {
            position: relative;
            margin: 0 15px;
        }

        .nav-links a {
            color: white;
            text-decoration: none;
            font-size: 16px;
            padding: 10px 15px;
            display: block;
            transition: 0.3s ease-in-out;
            border-radius: 5px;
        }

        .nav-links a:hover {
            background-color: gold;
            color: #003366;
        }

        /* User Profile Section */
        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            color: white;
        }

        .user-icon {
            width: 40px;
            height: 40px;
            background-color: gold;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #003366;
            font-size: 18px;
            cursor: pointer;
            transition: 0.3s ease-in-out;
        }

        .user-icon:hover {
            background-color: #ffd700;
            transform: scale(1.05);
        }

        .user-dropdown {
            position: relative;
        }

        .user-dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 200px;
            right: 0;
            top: 50px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            padding: 10px 0;
            z-index: 1000;
        }

        .user-dropdown-content a {
            display: block;
            color: #003366;
            padding: 12px 20px;
            text-decoration: none;
            font-size: 14px;
            transition: 0.3s ease-in-out;
            border-bottom: 1px solid #f0f0f0;
        }

        .user-dropdown-content a:last-child {
            border-bottom: none;
        }

        .user-dropdown-content a:hover {
            background-color: #003366;
            color: white;
        }

        .user-dropdown.active .user-dropdown-content {
            display: block;
        }

        /* Dropdown Menu */
        .dropdown {
            position: relative;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 220px;
            left: 0;
            top: 40px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            padding: 10px 0;
            z-index: 1000;
        }

        .dropdown-content a {
            display: block;
            color: black;
            padding: 10px 20px;
            text-decoration: none;
            font-size: 15px;
            transition: 0.3s ease-in-out;
        }

        .dropdown-content a:hover {
            background-color: #003366;
            color: white;
        }

        /* Show dropdown on click */
        .dropdown.active .dropdown-content {
            display: block;
        }

        /* Sub-dropdown (Second Level) */
        .sub-dropdown {
            position: relative;
        }

        .sub-dropdown-content {
            display: none;
            position: absolute;
            background-color: white;
            min-width: 220px;
            box-shadow: 0px 4px 8px rgba(0, 0, 0, 0.2);
            border-radius: 8px;
            padding: 10px 0;
            z-index: 1000;
            transition: transform 0.3s ease-in-out;
        }

        /* Smart positioning for sub-dropdowns */
        .sub-dropdown-content {
            left: 100%;
            top: 0;
        }

        /* Adjust if near right edge */
        .sub-dropdown:hover .sub-dropdown-content {
            left: auto;
            right: 100%;
        }

        .sub-dropdown.active > .sub-dropdown-content {
            display: block;
        }

        /* Dropdown Arrow */
        .nav-links li a i {
            margin-left: 5px;
        }

        /* Arrows for dropdowns */
        .dropdown > a::after,
        .sub-dropdown > a::after {
            content: ' ▼';
            font-size: 0.8em;
        }

        /* Active dropdown indicator */
        .dropdown.active > a::after,
        .sub-dropdown.active > a::after {
            content: ' ▲';
        }

        /* Responsive Menu */
        @media (max-width: 768px) {
            .nav-container {
                flex-direction: column;
                gap: 10px;
            }

            .nav-links {
                flex-direction: column;
                background: #003366;
                position: absolute;
                width: 100%;
                left: 0;
                top: 60px;
                display: none;
                padding: 15px;
            }

            .nav-links li {
                text-align: center;
                margin-bottom: 10px;
            }

            .nav-links.active {
                display: block;
            }

            .dropdown-content {
                width: 100%;
                position: relative;
                left: 0;
                top: 0;
            }

            .sub-dropdown-content {
                position: relative;
                left: 0;
                width: 100%;
            }

            .user-profile {
                margin-top: 10px;
            }
        }

    </style>
</head>
<body>

<!-- Navigation Bar -->
<nav class="navbar">
    <div class="logo">NEUST Gabaldon</div>
    
    <div class="nav-container">
        <ul class="nav-links">
            <li><a href="student_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a></li>
            <li><a href="student_announcement.php"><i class="fas fa-bullhorn"></i> Announcements</a></li>

            <!-- Services Dropdown -->
            <li class="dropdown">
                <a href="#"><i class="fas fa-list"></i> Services</a>
                <div class="dropdown-content">
                    <div class="sub-dropdown">
                        <a href="#">🏠 Dormitory</a>
                        <div class="sub-dropdown-content">
                            <a href="rooms.php">🏠 Apply</a>
                            <a href="check_applications_status.php">✅ Check Status</a>
                            <a href="student_payments.php">💳 Dormitory Payments</a>
                            <a href="dormitory_rules.php">📜 Rules</a>
                        </div>
                    </div>

                    <div class="sub-dropdown">
                        <a href="#">🎓 Scholarship</a>
                        <div class="sub-dropdown-content">
                            <a href="scholarships.php">📝 Apply</a>
                            <a href="track_applications.php">📊 Status</a>
                            <a href="scholarship_resources.php">📚 Resources</a>
                        </div>
                    </div>

                    <div class="sub-dropdown">
                        <a href="#">🗣️ Guidance</a>
                        <div class="sub-dropdown-content">
                            <a href="guidance_request.php">📅 Book Appointment</a>
                            <a href="student_status_appointments.php">📋 Appointment Status</a>
                            <a href="guidance_counseling.php">🗣️ Counseling</a>
                            <a href="guidance_resources.php">📖 Resources</a>
                        </div>
                    </div>

                    <div class="sub-dropdown">
                        <a href="#">📜 Registrar</a>
                        <div class="sub-dropdown-content">
                            <a href="create_student_tor_request.php">📄 TOR Request</a>
                            <a href="tor_list_student.php">📊 Track TOR Status</a>
                            <a href="student_profile.php">👨‍🎓 Student Profiling</a>
                        </div>
                    </div>

                    <div class="sub-dropdown">
                        <a href="#">⚖️ Grievance</a>
                        <div class="sub-dropdown-content">
                            <a href="grievance_filing.php">📢 File Complaint</a>
                            <a href="grievance_appointment.php">📅 Set Appointment</a>
                        </div>
                    </div>
                </div>
            </li>

           
        </ul>

        <!-- User Profile Section -->
        <div class="user-profile">
            <div class="user-dropdown">
                <div class="user-icon" onclick="toggleUserDropdown()">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-dropdown-content">
                    <a href="student_profile.php"><i class="fas fa-user-circle"></i> My Profile</a>
                    <a href="student_dashboard.php"><i class="fas fa-tachometer-alt"></i> Dashboard</a>
                    <a href="student_announcement.php"><i class="fas fa-bell"></i> Notifications</a>
                    <a href="login.php"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    // Mobile Menu Toggle
    document.addEventListener("DOMContentLoaded", function () {
        const menuToggle = document.querySelector(".navbar");
        const navLinks = document.querySelector(".nav-links");

        menuToggle.addEventListener("click", function () {
            navLinks.classList.toggle("active");
        });

        // Toggle Dropdown on Click for Desktop and Mobile
        const dropdowns = document.querySelectorAll(".dropdown > a");
        dropdowns.forEach(dropdown => {
            dropdown.addEventListener("click", function (event) {
                event.preventDefault();
                const dropdownContent = this.nextElementSibling;
                // Hide other dropdowns
                const allDropdowns = document.querySelectorAll('.dropdown');
                allDropdowns.forEach(d => {
                    if (d !== this.parentElement) {
                        d.classList.remove("active");
                        d.querySelector(".dropdown-content").style.display = "none";
                    }
                });
                // Toggle the current dropdown
                this.parentElement.classList.toggle("active");
                dropdownContent.style.display = dropdownContent.style.display === "block" ? "none" : "block";
            });
        });

        // Toggle Sub-dropdown on Click for Mobile
        const subDropdowns = document.querySelectorAll(".sub-dropdown > a");
        subDropdowns.forEach(subDropdown => {
            subDropdown.addEventListener("click", function (event) {
                event.preventDefault();
                const subDropdownContent = this.nextElementSibling;
                // Hide other sub-dropdowns
                const allSubDropdowns = document.querySelectorAll('.sub-dropdown');
                allSubDropdowns.forEach(sd => {
                    if (sd !== this.parentElement) {
                        sd.classList.remove("active");
                        sd.querySelector(".sub-dropdown-content").style.display = "none";
                    }
                });
                // Toggle the current sub-dropdown
                this.parentElement.classList.toggle("active");
                subDropdownContent.style.display = subDropdownContent.style.display === "block" ? "none" : "block";
            });
        });

        // Close dropdowns when clicking outside
        document.addEventListener("click", function(event) {
            if (!event.target.closest('.dropdown') && !event.target.closest('.user-dropdown')) {
                const allDropdowns = document.querySelectorAll('.dropdown, .user-dropdown');
                allDropdowns.forEach(d => {
                    d.classList.remove("active");
                    const content = d.querySelector('.dropdown-content, .user-dropdown-content');
                    if (content) {
                        content.style.display = "none";
                    }
                });
            }
        });
    });

    // Toggle User Dropdown
    function toggleUserDropdown() {
        const userDropdown = document.querySelector('.user-dropdown');
        userDropdown.classList.toggle('active');
    }
</script>

</body>
</html>