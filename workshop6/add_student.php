<?php
include "db.php";

if (isset($_POST['add'])) {
    $stmt = $conn->prepare(
        "INSERT INTO students (name, email, course) VALUES (?, ?, ?)"
    );
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['course']
    ]);

    echo "Student added successfully";
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Student</title>
</head>
<body>

<h2>Add Student</h2>

<a href="view.php">View Students</a>
<a href = "index.php">Home</a>

<form method="post">
    <input type="text" name="name" placeholder="Name" required><br><br>
    <input type="email" name="email" placeholder="Email" required><br><br>
    <input type="text" name="course" placeholder="Course" required><br><br>
    <button type="submit" name="add">Add Student</button>
</form>

<br>


</body>
</html>