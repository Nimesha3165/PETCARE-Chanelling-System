<?php require_once 'db.php'; require_once 'functions.php'; ?>
<!DOCTYPE html>
<html lang="si">
<head>
<meta charset="UTF-8">
<title>Pet Care Channeling System</title>
<style>
    body {
        font-family: system-ui, Segoe UI, Roboto, Ubuntu, sans-serif;
        background: #f6f9fc;
        color: #222;
        margin: 0;
    }
    header {
        background: #4f46e5;
        color: #fff;
        padding: 20px 40px;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    header svg {
        width: 34px;
        height: 34px;
        fill: #fff;
    }
    nav a {
        color: #fff;
        margin-right: 15px;
        text-decoration: none;
        font-weight: 600;
    }
    .hero {
        padding: 80px 20px;
        text-align: center;
        background: linear-gradient(135deg, #6366f1cc, #4f46e5cc),
                    url('images/pet-bg.jpg') no-repeat center center;
        background-size: cover;
        color: #fff;
    }
    .hero h1 {
        font-size: 42px;
        margin: 0 0 10px;
    }
    .hero p {
        font-size: 18px;
        opacity: .95;
    }
    .cards {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
        gap: 20px;
        padding: 30px;
        max-width: 1100px;
        margin: -50px auto 40px;
    }
    .card {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 8px 20px rgba(0, 0, 0, .06);
        padding: 24px;
    }
    .card h3 {
        margin: 0 0 10px;
        color: #4f46e5;
    }
    .btn {
        display: inline-block;
        padding: 10px 16px;
        border-radius: 8px;
        background: #4f46e5;
        color: #fff;
        text-decoration: none;
        font-weight: 600;
        margin-top: 12px;
    }
    footer {
        padding: 20px;
        text-align: center;
        background: #fff;
        border-top: 1px solid #eee;
    }
</style>
</head>
<body>
<header>
    <svg viewBox="0 0 24 24"><path d="M4.5 9.5a2.5 2.5 0 115 0 2.5 2.5 0 01-5 0zm10-2a2.5 2.5 0 115 0 2.5 2.5 0 01-5 0zM3 15.5a3.5 3.5 0 107 0 3.5 3.5 0 00-7 0zm11.5 2a3.5 3.5 0 107 0 3.5 3.5 0 00-7 0z"/></svg>
    <h2>Pet Care Channeling System</h2>
    <nav style="margin-left:auto;">
        <?php if(function_exists('is_logged_in') && is_logged_in()): ?>
            <a href="logout.php">Logout</a>
        <?php else: ?>
            <a href="login.php">Login</a>
            <a href="register.php">Register</a>
        <?php endif; ?>
    </nav>
</header>

<section class="hero">
    <h1>ඔබේ සුරතලා සලකා බලමු</h1>
    <p>Veterinarian appointment (channeling) online — ඉස්සරහාම දවස, වේලාව තෝරා ගන්න.</p>
    <p style="margin-top:24px;">
        <a href="register.php" class="btn">Get Started</a>
    </p>
</section>

<section class="cards">
    <div class="card">
        <h3>Owner Flow</h3>
        <ol>
            <li>Register / Login</li>
            <li>ඔබේ සුරතලා (Pet) එකතු කරන්න</li>
            <li>Vet සෙවීම & Schedule බලන්න</li>
            <li>Channel / Book Appointment</li>
            <li>Payment & Feedback</li>
        </ol>
        <a class="btn" href="login.php">Owner Login</a>
    </div>
    <div class="card">
        <h3>Vet Flow</h3>
        <ol>
            <li>Register as Vet</li>
            <li>Admin Approve</li>
            <li>Schedule set කරන්න</li>
            <li>Appointments Confirm/Reject</li>
            <li>Treat & Complete</li>
        </ol>
        <a class="btn" href="login.php">Vet Login</a>
    </div>
    <div class="card">
        <h3>Admin</h3>
        <ul>
            <li>Manage Users & Vets</li>
            <li>Approve Vet Accounts</li>
            <li>View All Appointments</li>
        </ul>
        <a class="btn" href="login.php">Admin Login</a>
    </div>
</section>

<footer>© <?php echo date('Y'); ?> Pet Care Channeling System</footer>
</body>
</html>
