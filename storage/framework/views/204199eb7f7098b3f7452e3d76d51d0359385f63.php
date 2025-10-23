<?php $__env->startSection('title', 'Login'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow">
      <div class="card-body">
        <h4 class="mb-3 text-center">Form Login</h4>
        <form action="<?php echo e(route('login.process')); ?>" method="POST">
          <?php echo csrf_field(); ?>
          <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
          </div>
          <?php if($errors->has('login')): ?>
          <div class="alert alert-danger mb-3">
            <?php echo e($errors->first('login')); ?>

          </div>
          <?php endif; ?>
          <button class="btn btn-primary w-100" type="submit">Login</button>
        </form>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\template_uts\resources\views/login.blade.php ENDPATH**/ ?>