<?php
// Custom functions for this page
function formatName($name) {
    return ucwords(strtolower(trim($name)));
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function cleanSkills($string) {
    $cleaned = preg_replace('/[^a-zA-Z0-9,\s]/', '', $string);
    $cleaned = preg_replace('/\s*,\s*/', ',', $cleaned);
    return trim($cleaned);
}

function saveStudent($name, $email, $skillsArray) {
    $file = '../data/students.txt';
    
    // Create data directory if needed
    if (!is_dir('../data')) {
        mkdir('../data', 0755, true);
    }
    
    // Create file with header if doesn't exist
    if (!file_exists($file)) {
        file_put_contents($file, "Name|Email|Skills|Date\n");
    }
    
    // Prepare data
    $skillsString = implode(', ', $skillsArray);
    $date = date('Y-m-d H:i:s');
    $line = "$name|$email|$skillsString|$date\n";
    
    // Save to file
    file_put_contents($file, $line, FILE_APPEND);
    return true;
}

// Handle form submission
$name = $email = $skills = '';
$message = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $skillsInput = trim($_POST['skills'] ?? '');
    
    // Validation
    $errors = [];
    
    if (empty($name)) $errors[] = 'Name is required';
    if (empty($email)) $errors[] = 'Email is required';
    if (empty($skillsInput)) $errors[] = 'Skills are required';
    
    if (!empty($email) && !validateEmail($email)) {
        $errors[] = 'Invalid email format';
    }
    
    if (empty($errors)) {
        try {
            $formattedName = formatName($name);
            $cleanedSkills = cleanSkills($skillsInput);
            $skillsArray = explode(',', $cleanedSkills);
            $skillsArray = array_map('trim', $skillsArray);
            $skillsArray = array_filter($skillsArray);
            
            saveStudent($formattedName, $email, $skillsArray);
            
            $message = "Student added successfully!";
            $msgType = 'success';
            $name = $email = $skills = ''; // Clear form
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
            $msgType = 'error';
        }
    } else {
        $message = implode('<br>', $errors);
        $msgType = 'error';
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Add Student</h2>

<?php if ($message): ?>
    <div class="message <?php echo $msgType; ?>"><?php echo $message; ?></div>
<?php endif; ?>

<form method="POST">
    <div class="form-group">
        <label>Name:</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>" required>
    </div>
    
    <div class="form-group">
        <label>Email:</label>
        <input type="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
    </div>
    
    <div class="form-group">
        <label>Skills (comma separated):</label>
        <textarea name="skills" rows="3" required><?php echo htmlspecialchars($skillsInput ?? $skills); ?></textarea>
    </div>
    
    <button type="submit">Save Student</button>
</form>

<?php include '../includes/footer.php'; ?>