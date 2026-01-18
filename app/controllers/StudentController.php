<?php
require_once __DIR__ . '/../models/Student.php';

class StudentController
{
    private $student;
    private $blade;

    public function __construct($blade)
    {
        $this->student = new Student();
        $this->blade = $blade;
    }

    public function index()
    {
        $students = $this->student->all();
        echo $this->blade->render('students.index', compact('students'));
    }

    public function create()
    {
        echo $this->blade->render('students.create');
    }

    public function store()
    {
        $this->student->create(
            $_POST['name'],
            $_POST['email'],
            $_POST['course']
        );
        header("Location: index.php");
    }

    public function edit($id)
    {
        $student = $this->student->find($id);
        echo $this->blade->render('students.edit', compact('student'));
    }

    public function update($id)
    {
        $this->student->update(
            $id,
            $_POST['name'],
            $_POST['email'],
            $_POST['course']
        );
        header("Location: index.php");
    }

    public function delete($id)
    {
        $this->student->delete($id);
        header("Location: index.php");
    }
}
?> 