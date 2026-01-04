<?php
require 'db.php';

// Check if user is logged in
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true){
    header("Location: login.php");
    exit();
}

// Check theme from cookie
$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'red';

// Handle theme toggle
if(isset($_POST['toggle_theme'])) {
    $new_theme = ($theme == 'red') ? 'blue' : 'red';
    setcookie('theme', $new_theme, time() + (86400 * 30), "/");
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?php echo $theme; ?>.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <!-- Theme Toggle Button -->
    <div class="theme-toggle">
        <form method="POST">
            <button type="submit" name="toggle_theme" class="theme-toggle-btn">
                <?php if($theme == 'red'): ?>
                    🔵 Switch to Blue Theme
                <?php else: ?>
                    🔴 Switch to Red Theme
                <?php endif; ?>
            </button>
        </form>
    </div>
    
    <div class="container">
        <nav class="navbar">
            <a href="dashboard.php" class="active">Dashboard</a>
            <a href="preference.php">Theme Preference</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </nav>
        
        <div class="welcome-section">
            <h1>Welcome <?php echo htmlspecialchars($_SESSION['username']); ?></h1>
            <p class="welcome-message">You have successfully logged into the Student Grade Portal.</p>
            <p class="current-theme">Current Theme: <span><?php echo ucfirst($theme); ?> Theme</span></p>
        </div>
        
        <div class="dashboard-content">
            <div class="card">
                <h3>Student Information</h3>
                <p><strong>Student ID:</strong> <?php echo htmlspecialchars($_SESSION['student_id']); ?></p>
                <p><strong>Name:</strong> <?php echo htmlspecialchars($_SESSION['username']); ?></p>
                <p><strong>Current Theme:</strong> <?php echo ucfirst($theme); ?> Theme</p>
                <p><strong>Login Time:</strong> <?php echo date('Y-m-d H:i:s'); ?></p>
            </div>
            
            <div class="card">
                <h3>Quick Actions</h3>
                <a href="preference.php" class="action-btn">Change Theme</a>
                <a href="logout.php" class="action-btn logout">Logout</a>
                
                <h3 style="margin-top: 30px;">Theme Options</h3>
                <form method="POST" style="margin-top: 15px;">
                    <button type="submit" name="toggle_theme" class="action-btn">
                        Switch to <?php echo ($theme == 'red') ? 'Blue' : 'Red'; ?> Theme
                    </button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>