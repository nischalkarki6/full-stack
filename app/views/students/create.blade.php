@extends('layouts.master')

@section('content')

<h2>Add Student</h2>

<form method="post" action="index.php?page=store">
    Name: <input type="text" name="name"><br><br>
    Email: <input type="email" name="email"><br><br>
    Course: <input type="text" name="course"><br><br>
    <button type="submit">Save</button>
</form>

@endsection
