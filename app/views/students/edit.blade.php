@extends('layouts.master')

@section('content')

<h2>Edit Student</h2>

<form method="post" action="index.php?page=update&id={{ $student['id'] }}">
    Name: <input type="text" name="name" value="{{ $student['name'] }}"><br><br>
    Email: <input type="email" name="email" value="{{ $student['email'] }}"><br><br>
    Course: <input type="text" name="course" value="{{ $student['course'] }}"><br><br>
    <button type="submit">Update</button>
</form>

@endsection
