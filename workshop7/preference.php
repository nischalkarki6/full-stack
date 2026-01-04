<?php
require 'db.php';

// Check if user is logged in
if(!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true){
    header("Location: login.php");
    exit();
}

$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'red';

// Handle theme selection
if (isset($_POST["theme"])) {
    $theme = $_POST["theme"];
    setcookie("theme", $theme, time() + (86400 * 30), "/");
    header("Location: preference.php");
    exit();
}

// Handle quick toggle
if(isset($_POST['toggle_theme'])) {
    $new_theme = ($theme == 'red') ? 'blue' : 'red';
    setcookie('theme', $new_theme, time() + (86400 * 30), "/");
    header("Location: preference.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Theme Preference</title>
    <link rel="stylesheet" href="css/<?php echo $theme; ?>.css">
    <link rel="stylesheet" href="css/style.css">
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
            <a href="dashboard.php">Dashboard</a>
            <a href="preference.php" class="active">Theme Preference</a>
            <a href="logout.php" class="logout-btn">Logout</a>
        </nav>
        
        <div class="preference-container">
            <h2>Theme Preference</h2>
            <p class="current-theme">Current Theme: <span><?php echo ucfirst($theme); ?> Theme</span></p>
            
            <div class="theme-preview" style="margin: 30px 0; padding: 20px; border-radius: 10px; background: var(--card-bg);">
                <h3>Theme Preview</h3>
                <div style="display: flex; gap: 20px; margin-top: 15px; justify-content: center;">
                    <div style="width: 50px; height: 50px; background: var(--primary); border-radius: 8px;" title="Primary Color"></div>
                    <div style="width: 50px; height: 50px; background: var(--bg); border: 2px solid var(--border); border-radius: 8px;" title="Background"></div>
                    <div style="width: 50px; height: 50px; background: var(--nav-bg); border-radius: 8px;" title="Navigation"></div>
                </div>
            </div>
            
            <form method="post" class="theme-form">
                <div class="theme-options">
                    <button type="submit" name="theme" value="red" class="theme-btn red-btn <?php echo $theme == 'red' ? 'active' : ''; ?>">
                        🔴 Red Theme
                    </button>
                    
                    <button type="submit" name="theme" value="blue" class="theme-btn blue-btn <?php echo $theme == 'blue' ? 'active' : ''; ?>">
                        🔵 Blue Theme
                    </button>
                </div>
            </form>
            
            <div style="margin-top: 30px; padding: 20px; background: var(--card-bg); border-radius: 10px;">
                <h3>Quick Toggle</h3>
                <p style="margin: 10px 0; color: var(--text-muted);">Switch between themes instantly:</p>
                <form method="POST" style="margin-top: 15px;">
                    <button type="submit" name="toggle_theme" class="action-btn">
                        Switch to <?php echo ($theme == 'red') ? 'Blue' : 'Red'; ?> Theme
                    </button>
                </form>
            </div>
            
            <div class="back-link">
                <a href="dashboard.php">← Back to Dashboard</a>
            </div>
        </div>
    </div>
</body>
</html>