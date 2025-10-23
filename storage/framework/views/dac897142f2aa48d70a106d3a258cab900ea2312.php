<?php $__env->startSection('title', 'Profile'); ?>

<?php $__env->startSection('content'); ?>
<h3>User Profile</h3>
<div class="card">
  <div class="card-body">
    <p><strong>Username:</strong> <?php echo e($profile['username']); ?></p>
    <p><strong>Full Name:</strong> <?php echo e($profile['nama_lengkap']); ?></p>
    <p><strong>Email:</strong> <?php echo e($profile['email']); ?></p>
    <p><strong>Role:</strong> <?php echo e($profile['role']); ?></p>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\template_uts\resources\views/profile.blade.php ENDPATH**/ ?>