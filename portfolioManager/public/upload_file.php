<?php
// File upload function
function uploadPortfolioFile($file) {
    // Check upload error
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return "Upload error: " . $file['error'];
    }
    
    // Allowed types and size
    $allowed = ['pdf', 'jpg', 'jpeg', 'png'];
    $maxSize = 2 * 1024 * 1024; // 2MB
    
    // Get file info
    $fileName = $file['name'];
    $fileSize = $file['size'];
    $fileTmp = $file['tmp_name'];
    $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    
    // Validate type
    if (!in_array($fileExt, $allowed)) {
        return "Only PDF, JPG, PNG files allowed";
    }
    
    // Validate size
    if ($fileSize > $maxSize) {
        return "File too large (max 2MB)";
    }
    
    // Create uploads directory
    if (!is_dir('../uploads')) {
        mkdir('../uploads', 0755, true);
    }
    
    // Generate unique filename
    $newName = time() . '_' . uniqid() . '.' . $fileExt;
    $destination = '../uploads/' . $newName;
    
    // Move file
    if (move_uploaded_file($fileTmp, $destination)) {
        return "File uploaded: " . $newName;
    } else {
        return "Failed to upload file";
    }
}

// Handle form
$message = '';
$msgType = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    $result = uploadPortfolioFile($_FILES['file']);
    
    if (strpos($result, 'uploaded:') !== false) {
        $message = $result;
        $msgType = 'success';
    } else {
        $message = $result;
        $msgType = 'error';
    }
}
?>

<?php include '../includes/header.php'; ?>

<h2>Upload Portfolio File</h2>

<?php if ($message): ?>
    <div class="message <?php echo $msgType; ?>"><?php echo $message; ?></div>
<?php endif; ?>

<form method="POST" enctype="multipart/form-data">
    <div class="form-group">
        <label>Select file (PDF, JPG, PNG - max 2MB):</label>
        <input type="file" name="file" required accept=".pdf,.jpg,.jpeg,.png">
    </div>
    
    <button type="submit">Upload</button>
</form>

<h3>Uploaded Files:</h3>
<?php
$uploadDir = '../uploads/';
if (is_dir($uploadDir)) {
    $files = scandir($uploadDir);
    $files = array_diff($files, ['.', '..']);
    
    if (empty($files)) {
        echo '<p>No files uploaded yet.</p>';
    } else {
        echo '<ul>';
        foreach ($files as $file) {
            echo '<li>' . htmlspecialchars($file) . '</li>';
        }
        echo '</ul>';
    }
}
?>

<?php include '../includes/footer.php'; ?>