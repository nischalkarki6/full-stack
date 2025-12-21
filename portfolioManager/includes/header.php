<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Portfolio Manager</title>
    <style>
        body { font-family: Arial; margin: 0; padding: 20px; background: #f0f0f0; }
        .container { max-width: 1000px; margin: 0 auto; background: white; padding: 20px; border-radius: 5px; }
        header { background: #333; color: white; padding: 15px; margin-bottom: 20px; }
        nav a { color: white; margin: 0 10px; text-decoration: none; }
        nav a:hover { text-decoration: underline; }
        .form-group { margin-bottom: 15px; }
        label { display: block; font-weight: bold; }
        input, textarea { width: 100%; padding: 8px; margin-top: 5px; }
        button { background: #333; color: white; padding: 10px 20px; border: none; cursor: pointer; }
        .message { padding: 10px; margin: 10px 0; border-radius: 3px; }
        .success { background: #d4edda; color: #155724; }
        .error { background: #f8d7da; color: #721c24; }
        .student-card { border: 1px solid #ddd; padding: 15px; margin: 10px 0; }
        .skill { background: #e3e3e3; padding: 3px 8px; margin: 2px; display: inline-block; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Student Portfolio Manager</h1>
            <nav>
                <a href="../index.php">Home</a>
                <a href="add_student.php">Add Student</a>
                <a href="upload_portfolio.php">Upload File</a>
                <a href="students.php">View Students</a>
            </nav>
        </header>
        <main>