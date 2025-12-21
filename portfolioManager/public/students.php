<?php
// Function to get students
function getStudents() {
    $file = '../data/students.txt';
    $students = [];
    
    if (!file_exists($file)) {
        return $students;
    }
    
    $lines = file($file);
    
    // Skip header
    if (count($lines) > 0) {
        array_shift($lines);
    }
    
    foreach ($lines as $line) {
        $parts = explode('|', $line);
        if (count($parts) >= 4) {
            $students[] = [
                'name' => $parts[0],
                'email' => $parts[1],
                'skills' => $parts[2],
                'date' => $parts[3]
            ];
        }
    }
    
    return $students;
}

$students = getStudents();
?>

<?php include '../includes/header.php'; ?>

<h2>All Students</h2>

<?php if (empty($students)): ?>
    <p>No students found. <a href="add_student.php">Add a student</a> first.</p>
<?php else: ?>
    <p>Total students: <?php echo count($students); ?></p>
    
    <?php foreach ($students as $student): ?>
        <div class="student-card">
            <h3><?php echo htmlspecialchars($student['name']); ?></h3>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($student['email']); ?></p>
            <p><strong>Date Added:</strong> <?php echo htmlspecialchars($student['date']); ?></p>
            <p><strong>Skills:</strong> 
                <?php 
                $skills = explode(', ', $student['skills']);
                foreach ($skills as $skill):
                    if (!empty(trim($skill))):
                ?>
                    <span class="skill"><?php echo htmlspecialchars($skill); ?></span>
                <?php 
                    endif;
                endforeach; 
                ?>
            </p>
        </div>
    <?php endforeach; ?>
<?php endif; ?>

<?php include '../includes/footer.php'; ?>