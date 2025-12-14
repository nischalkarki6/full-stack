<?php
// Initialize variables
$errors = [];
$success = false;

// Define password requirements
define('MIN_PASSWORD_LENGTH', 8);
$usersFile = 'users.json';

// Function to load users from JSON file
function loadUsers($file) {
    if (!file_exists($file)) {
        // Create empty users array if file doesn't exist
        file_put_contents($file, json_encode([]));
        return [];
    }
    
    $content = file_get_contents($file);
    if ($content === false) {
        throw new Exception("Unable to read users file.");
    }
    
    $users = json_decode($content, true);
    if ($users === null) {
        return [];
    }
    
    return $users;
}

// Function to save users to JSON file
function saveUsers($file, $users) {
    $jsonData = json_encode($users, JSON_PRETTY_PRINT);
    if ($jsonData === false) {
        throw new Exception("Unable to encode user data.");
    }
    
    if (file_put_contents($file, $jsonData) === false) {
        throw new Exception("Unable to save user data.");
    }
    
    return true;
}

// Function to validate email
function isValidEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Function to validate password strength
function isPasswordStrong($password) {
    // At least 8 characters
    if (strlen($password) < MIN_PASSWORD_LENGTH) {
        return "Password must be at least " . MIN_PASSWORD_LENGTH . " characters long.";
    }
    
    // At least one uppercase letter
    if (!preg_match('/[A-Z]/', $password)) {
        return "Password must contain at least one uppercase letter.";
    }
    
    // At least one lowercase letter
    if (!preg_match('/[a-z]/', $password)) {
        return "Password must contain at least one lowercase letter.";
    }
    
    // At least one number
    if (!preg_match('/[0-9]/', $password)) {
        return "Password must contain at least one number.";
    }
    
    // At least one special character
    if (!preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) {
        return "Password must contain at least one special character.";
    }
    
    return true;
}

// Function to check if email already exists
function emailExists($email, $users) {
    foreach ($users as $user) {
        if ($user['email'] === $email) {
            return true;
        }
    }
    return false;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';
    
    // Validate name
    if (empty($name)) {
        $errors['name'] = "Name is required.";
    } elseif (strlen($name) < 2) {
        $errors['name'] = "Name must be at least 2 characters long.";
    }
    
    // Validate email
    if (empty($email)) {
        $errors['email'] = "Email is required.";
    } elseif (!isValidEmail($email)) {
        $errors['email'] = "Please enter a valid email address.";
    }
    
    // Validate password
    if (empty($password)) {
        $errors['password'] = "Password is required.";
    } else {
        $passwordCheck = isPasswordStrong($password);
        if ($passwordCheck !== true) {
            $errors['password'] = $passwordCheck;
        }
    }
    
    // Validate confirm password
    if (empty($confirmPassword)) {
        $errors['confirm_password'] = "Please confirm your password.";
    } elseif ($password !== $confirmPassword) {
        $errors['confirm_password'] = "Passwords do not match.";
    }
    
    // If no validation errors, process registration
    if (empty($errors)) {
        try {
            // Load existing users
            $users = loadUsers($usersFile);
            
            // Check if email already exists
            if (emailExists($email, $users)) {
                $errors['email'] = "This email is already registered.";
            } else {
                // Hash the password
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                
                // Create new user array
                $newUser = [
                    'id' => uniqid(),
                    'name' => $name,
                    'email' => $email,
                    'password' => $hashedPassword,
                    'registered_at' => date('Y-m-d H:i:s')
                ];
                
                // Add new user to users array
                $users[] = $newUser;
                
                // Save users back to file
                saveUsers($usersFile, $users);
                
                // Set success flag
                $success = true;
                
                // Clear form fields
                $name = $email = '';
            }
        } catch (Exception $e) {
            $errors['system'] = "Registration failed: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Registration</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
            font-family: Arial, sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        
        .container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 30px;
            width: 100%;
            max-width: 450px;
        }
        
        h1 {
            color: #2c3e50;
            text-align: center;
            margin-bottom: 25px;
            font-size: 1.8rem;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 6px;
            color: #34495e;
            font-weight: 500;
            font-size: 0.9rem;
        }
        
        input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: #3498db;
            box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
        }
        
        .error {
            color: #e74c3c;
            font-size: 0.85rem;
            margin-top: 5px;
            display: block;
        }
        
        .success {
            background-color: #27ae60;
            color: white;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
            font-weight: 500;
        }
        
        .system-error {
            background-color: #e74c3c;
            color: white;
            padding: 12px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .password-requirements {
            background-color: #ecf0f1;
            border: 1px solid #bdc3c7;
            padding: 12px;
            margin-bottom: 20px;
            font-size: 0.85rem;
            color: #2c3e50;
            border-radius: 4px;
        }
        
        .password-requirements h4 {
            margin-bottom: 8px;
            color: #2c3e50;
            font-size: 0.95rem;
        }
        
        .password-requirements ul {
            padding-left: 20px;
        }
        
        .password-requirements li {
            margin-bottom: 4px;
            list-style-type: none;
            position: relative;
            padding-left: 20px;
        }
        
        .password-requirements li:before {
            content: "•";
            position: absolute;
            left: 0;
            color: #7f8c8d;
        }
        
        .password-requirements li.valid {
            color: #27ae60;
        }
        
        .password-requirements li.valid:before {
            content: "✓";
            color: #27ae60;
        }
        
        .password-requirements li.invalid {
            color: #e74c3c;
        }
        
        .password-requirements li.invalid:before {
            content: "✗";
            color: #e74c3c;
        }
        
        button {
            background-color: #3498db;
            color: white;
            border: none;
            padding: 14px;
            border-radius: 4px;
            font-size: 1rem;
            font-weight: 600;
            cursor: pointer;
            width: 100%;
            transition: background-color 0.3s;
        }
        
        button:hover {
            background-color: #2980b9;
        }
        
        button:active {
            background-color: #21618c;
        }
        
        .login-link {
            text-align: center;
            margin-top: 20px;
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        
        .login-link a {
            color: #3498db;
            text-decoration: none;
            font-weight: 500;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        /* Responsive adjustments */
        @media (max-width: 480px) {
            .container {
                padding: 20px;
            }
            
            h1 {
                font-size: 1.5rem;
            }
            
            input, button {
                padding: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>User Registration</h1>
        
        <?php if ($success): ?>
            <div class="success">
                ✅ Registration successful! You can now log in.
            </div>
        <?php endif; ?>
        
        <?php if (isset($errors['system'])): ?>
            <div class="system-error">
                ❌ <?php echo htmlspecialchars($errors['system']); ?>
            </div>
        <?php endif; ?>
        
        <div class="password-requirements">
            <h4>Password Requirements:</h4>
            <ul>
                <li class="<?php echo (isset($password) && strlen($password) >= MIN_PASSWORD_LENGTH) ? 'valid' : 'invalid'; ?>">
                    At least <?php echo MIN_PASSWORD_LENGTH; ?> characters
                </li>
                <li class="<?php echo (isset($password) && preg_match('/[A-Z]/', $password)) ? 'valid' : 'invalid'; ?>">
                    At least one uppercase letter
                </li>
                <li class="<?php echo (isset($password) && preg_match('/[a-z]/', $password)) ? 'valid' : 'invalid'; ?>">
                    At least one lowercase letter
                </li>
                <li class="<?php echo (isset($password) && preg_match('/[0-9]/', $password)) ? 'valid' : 'invalid'; ?>">
                    At least one number
                </li>
                <li class="<?php echo (isset($password) && preg_match('/[!@#$%^&*(),.?":{}|<>]/', $password)) ? 'valid' : 'invalid'; ?>">
                    At least one special character
                </li>
            </ul>
        </div>
        
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Full Name</label>
                <input type="text" id="name" name="name" 
                       value="<?php echo htmlspecialchars($name ?? ''); ?>"
                       placeholder="Enter your full name">
                <?php if (isset($errors['name'])): ?>
                    <span class="error"><?php echo htmlspecialchars($errors['name']); ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" 
                       value="<?php echo htmlspecialchars($email ?? ''); ?>"
                       placeholder="Enter your email">
                <?php if (isset($errors['email'])): ?>
                    <span class="error"><?php echo htmlspecialchars($errors['email']); ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" 
                       placeholder="Create a strong password">
                <?php if (isset($errors['password'])): ?>
                    <span class="error"><?php echo htmlspecialchars($errors['password']); ?></span>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" 
                       placeholder="Re-enter your password">
                <?php if (isset($errors['confirm_password'])): ?>
                    <span class="error"><?php echo htmlspecialchars($errors['confirm_password']); ?></span>
                <?php endif; ?>
            </div>
            
            <button type="submit">Register</button>
        </form>
        
        <div class="login-link">
            Already have an account? <a href="#">Log in here</a>
        </div>
    </div>
    
    <script>
        // Live password validation feedback
        const passwordInput = document.getElementById('password');
        const requirements = document.querySelectorAll('.password-requirements li');
        
        if (passwordInput) {
            passwordInput.addEventListener('input', function() {
                const password = this.value;
                
                // Check each requirement
                requirements[0].className = password.length >= <?php echo MIN_PASSWORD_LENGTH; ?> ? 'valid' : 'invalid';
                requirements[1].className = /[A-Z]/.test(password) ? 'valid' : 'invalid';
                requirements[2].className = /[a-z]/.test(password) ? 'valid' : 'invalid';
                requirements[3].className = /[0-9]/.test(password) ? 'valid' : 'invalid';
                requirements[4].className = /[!@#$%^&*(),.?":{}|<>]/.test(password) ? 'valid' : 'invalid';
            });
        }
        
        // Clear success message after 5 seconds
        setTimeout(() => {
            const successMsg = document.querySelector('.success');
            if (successMsg) {
                successMsg.style.opacity = '0';
                successMsg.style.transition = 'opacity 0.5s';
                setTimeout(() => successMsg.remove(), 500);
            }
        }, 5000);
    </script>
</body>
</html>