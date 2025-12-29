
<?php
include "db.php";

/* INSERT */
if (isset($_POST['add'])) {
    $stmt = $conn->prepare(
        "INSERT INTO students (name, email, course) VALUES (?, ?, ?)"
    );
    $stmt->execute([
        $_POST['name'],
        $_POST['email'],
        $_POST['course']
    ]);
    header("Location: index.php");
}

/* DELETE */
if (isset($_GET['delete'])) {
    $stmt = $conn->prepare("DELETE FROM students WHERE id = ?");
    $stmt->execute([$_GET['delete']]);
    header("Location: index.php");
}

/* READ */
$students = $conn->query("SELECT * FROM students")->fetchAll();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel= "stylesheet" href = "style.css">
    <title>Student List</title>

</head>
<body>
    <a href = "add_student.php">Home</a>
    <a href="view.php">View Students</a><br><br>
    

<h2>Add Student</h2>
<form method="post">
    <input type="text" name="name" placeholder="Name" required>
    <input type="email" name="email" placeholder="Email" required>
    <input type="text" name="course" placeholder="Course" required>
    <button type="submit" name="add">Add</button>
</form>

<h2>Student List</h2>
<table id = "table">
<tr>
    <th>ID</th><th>Name</th><th>Email</th><th>Course</th><th>Actions</th>
</tr>

<?php foreach ($students as $student): ?>
<tr>
    <td><?= $student['id'] ?></td>
    <td><?= $student['name'] ?></td>
    <td><?= $student['email'] ?></td>
    <td><?= $student['course'] ?></td>
    <td>
        <a href="update.php?id=<?= $student['id'] ?>">Edit</a> |
        <a href="index.php?delete=<?= $student['id'] ?>"
           onclick="return confirm('Delete this student?')">Delete</a>
    </td>
</tr>
<?php endforeach; ?>

</table>

</body>
</html>
