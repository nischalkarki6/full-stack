<?php
require 'db.php';

$theme = isset($_COOKIE['theme']) ? $_COOKIE['theme'] : 'red';

// Handle theme toggle
if(isset($_POST['toggle_theme'])) {
    $new_theme = ($theme == 'red') ? 'blue' : 'red';
    setcookie('theme', $new_theme, time() + (86400 * 30), "/");
    header("Location: login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD']==="POST" && isset($_POST['login'])){
    $student_id = $_POST['student_id'];
    $password = $_POST['password'];
    $table_name = "students";

    $sql = "SELECT * FROM $table_name WHERE student_id = ?";
    
    try{
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$student_id]);
        $student = $stmt->fetch();

        if ($student) {
            $hashedPassword = $student['password_hash'];
            $isPasswordValid = password_verify($password, $hashedPassword);
            
            if($isPasswordValid){
                $_SESSION['logged_in'] = true;
                $_SESSION['student_id'] = $student_id;
                $_SESSION['username'] = $student['full_name'];
                
                echo "<div class='success-message'>Welcome " . htmlspecialchars($student['full_name']) . "! Redirecting...</div>";
                header("Refresh:2; url=dashboard.php");
                exit();
            } else {
                $error = "Invalid password";
            }
        } else {
            $error = "Invalid student ID";
        }
    } catch(PDOException $e){
        $error = "Database Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login</title>
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
            <h2>Student Login</h2>
            <p style="text-align: center; margin-bottom: 20px; color: var(--text-muted);">
                Current Theme: <strong><?php echo ucfirst($theme); ?> Theme</strong>
            </p>
            
            <?php if(isset($error)): ?>
                <div class="error-message"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="login-form">
                <div class="form-group">
                    <label for="student_id">Student ID</label>
                    <input type="text" id="student_id" name="student_id" required placeholder="Enter your student ID">
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required placeholder="Enter your password">
                </div>
                
                <button type="submit" name="login" class="submit-btn">Login</button>
            </form>
            
            <div class="register-link">
                Don't have an account? <a href="register.php">Register here</a>
            </div>
        </div>
    </div>
</body>
</html>