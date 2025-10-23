
<?php $__env->startSection('title', 'Dashboard'); ?>

<?php $__env->startSection('content'); ?>
<h3>Dashboard</h3>
<div class="alert alert-success">Selamat datang, <strong><?php echo e($username); ?></strong>!</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\template_uts\resources\views/dashboard.blade.php ENDPATH**/ ?>