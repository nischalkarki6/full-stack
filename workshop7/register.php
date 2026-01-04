<?php
require 'db.php';

$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'red';

// Handle theme toggle
if(isset($_POST['toggle_theme'])) {
    $new_theme = ($theme == 'red') ? 'blue' : 'red';
    setcookie('theme', $new_theme, time() + (86400 * 30), "/");
    header("Location: register.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === "POST" && isset($_POST['add_students'])){
    $student_id = $_POST['student_id'] ?? "";
    $name = $_POST['name']  ?? "";
    $password = $_POST['password']  ?? "";
    $tableName = "students";

    $hashedpassword = password_hash($password, PASSWORD_BCRYPT);

    $sql = "INSERT INTO $tableName (student_id,full_name,password_hash) VALUES (?,?,?)";
    try{
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$student_id,$name,$hashedpassword]);
        echo "<div class='success-message'>Student Added Successfully! Redirecting to login...</div>";
        header("Refresh:2; url=login.php");
    } catch(PDOException $e){
        echo "<div class='error-message'>Unable to add student: " . $e->getMessage() . "</div>";
    }   
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>User Registration</title>
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
        <div class="form-container">
            <h2>Student Registration</h2>
            <p style="text-align: center; margin-bottom: 20px; color: var(--text-muted);">
                Current Theme: <strong><?php echo ucfirst($theme); ?> Theme</strong>
            </p>
            
            <form method="POST" class="registration-form">
                <div class="form-group">
                    <label for="student_id">Student ID</label>
                    <input type="text" id="student_id" name="student_id" required placeholder="Enter student ID">
                </div>
                
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required placeholder="Enter your full name">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter password">
                </div>
                
                <button type="submit" name="add_students" class="submit-btn">Register</button>
            </form>
            
            <div class="login-link">
                Already have an account? <a href="login.php">Login here</a>
            </div>
        </div>
    </div>
</body>
</html>