<?php
require 'db.php';
require 'session.php';

$message = '';

try {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $email = $_POST['email'];
        $password = $_POST['password'];

        $isEmailValid = filter_var($email, FILTER_VALIDATE_EMAIL);
        $isPasswordValid = !empty($password) && strlen($password) > 6;

        if (!$isEmailValid) {
            $message = "Please enter a valid email";
        } elseif (!$isPasswordValid) {
            $message = "Password must be more than 6 characters"; #&& preg_match('^A-Za-z0-9', $password);
        } else {
            $hashPassword = password_hash($password, PASSWORD_BCRYPT);

            $sql = "INSERT INTO users (email, password) VALUES (?, ?)";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$email, $hashPassword]);

            $message = "User signed up successfully";
            header("refresh:2; url=login.php");
        }
    }
} catch (Exception $e) {
    $message = "Something went wrong.";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Signup</title>
</head>
<body>

<h2>Signup</h2>

<?php if ($message): ?>
    <p><?php echo htmlspecialchars($message); ?></p>
<?php endif; ?>

<form method="POST">
    <label>Email:</label><br>
    <input type="text" name="email"><br><br>

    <label>Password:</label><br>
    <input type="password" name="password"><br><br>

    <button type="submit">Signup</button>
</form>

<br>
<a href="login.php">Go to Login</a>

</body>
</html>
