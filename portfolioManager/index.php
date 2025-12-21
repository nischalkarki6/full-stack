<?php

function getStudentCount() {
    $file = 'data/students.txt';
    if (!file_exists($file)) return 0;
    $lines = file($file);
    return max(0, count($lines) - 1); // Subtract header
}

function getUploadCount() {
    $dir = 'uploads/';
    if (!is_dir($dir)) return 0;
    $files = scandir($dir);
    return count($files) - 2; // Subtract . and ..
}
?>

<?php include 'includes/header.php'; ?>

<h2>Welcome to Student Portfolio Manager</h2>
<p>Manage student information and portfolio files.</p>

<div style="display: flex; gap: 20px; margin-top: 30px;">
    <div style="flex: 1; border: 1px solid #ccc; padding: 20px; text-align: center;">
        <h3>Students</h3>
        <p style="font-size: 24px; font-weight: bold;"><?php echo getStudentCount(); ?></p>
        <p>Total registered students</p>
        <a href="public/add_student.php" style="display: inline-block; padding: 10px; background: #333; color: white; text-decoration: none;">Add Student</a>
    </div>
    
    <div style="flex: 1; border: 1px solid #ccc; padding: 20px; text-align: center;">
        <h3>Uploads</h3>
        <p style="font-size: 24px; font-weight: bold;"><?php echo getUploadCount(); ?></p>
        <p>Portfolio files uploaded</p>
        <a href="public/upload_file.php" style="display: inline-block; padding: 10px; background: #333; color: white; text-decoration: none;">Upload File</a>
    </div>
    
    <div style="flex: 1; border: 1px solid #ccc; padding: 20px; text-align: center;">
        <h3>View</h3>
        <p>View all student information</p>
        <a href="public/students.php" style="display: inline-block; padding: 10px; background: #333; color: white; text-decoration: none;">View Students</a>
    </div>
</div>

<?php include 'includes/footer.php'; ?>