<?php
include "db.php";

$students = $conn->query("SELECT * FROM students")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel ="stylesheet" href= "style.css">
    <title>Student List</title>

</head>
<body>
    <br>
<a href="add_student.php">Add New Student</a>

<h2>Student List</h2>

<table class ="table" >
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Course</th>
    </tr>

    <?php foreach ($students as $student): ?>
    <tr>
        <td><?= $student['id']; ?></td>
        <td><?= $student['name']; ?></td>
        <td><?= $student['email']; ?></td>
        <td><?= $student['course']; ?></td>
    </tr>
    <?php endforeach; ?>

</table>



</body>
</html>