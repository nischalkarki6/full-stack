<?php
require_once __DIR__ . '/../../config/db.php';

class Student
{
    public function all()
    {
        global $conn;
        $stmt = $conn->query("SELECT * FROM students");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find($id)
    {
        global $conn;
        $stmt = $conn->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($name, $email, $course)
    {
        global $conn;
        $stmt = $conn->prepare(
            "INSERT INTO students (name, email, course) VALUES (?, ?, ?)"
        );
        $stmt->execute([$name, $email, $course]);
    }

    public function update($id, $name, $email, $course)
    {
        global $conn;
        $stmt = $conn->prepare(
            "UPDATE students SET name=?, email=?, course=? WHERE id=?"
        );
        $stmt->execute([$name, $email, $course, $id]);
    }

    public function delete($id)
    {
        global $conn;
        $stmt = $conn->prepare("DELETE FROM students WHERE id=?");
        $stmt->execute([$id]);
    }
}

?>