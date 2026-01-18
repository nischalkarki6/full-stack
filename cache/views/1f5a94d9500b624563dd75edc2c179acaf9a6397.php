

<?php $__env->startSection('content'); ?>

<h2>Edit Student</h2>

<form method="post" action="index.php?page=update&id=<?php echo e($student['id']); ?>">
    Name: <input type="text" name="name" value="<?php echo e($student['name']); ?>"><br><br>
    Email: <input type="email" name="email" value="<?php echo e($student['email']); ?>"><br><br>
    Course: <input type="text" name="course" value="<?php echo e($student['course']); ?>"><br><br>
    <button type="submit">Update</button>
</form>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\workshop8\app\views/students/edit.blade.php ENDPATH**/ ?>