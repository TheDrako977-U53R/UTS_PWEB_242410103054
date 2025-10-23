<?php $__env->startSection('title', 'Edit Room'); ?>

<?php $__env->startSection('content'); ?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card">
      <div class="card-body">
        <h4 class="mb-3">Edit Room</h4>
  <form action="<?php echo e(route('pengelolaan.update', ['code' => $code])); ?>" method="POST">
          <?php echo csrf_field(); ?>
          <div class="mb-3">
            <label class="form-label">Code (immutable)</label>
            <input type="number" class="form-control" value="<?php echo e($item['Code']); ?>" disabled>
            
            <input type="hidden" name="Code" value="<?php echo e($item['Code']); ?>">
          </div>
          <div class="mb-3">
            <label class="form-label">Booker(s) Name</label>
            <input type="text" name="Booker(s) Name" class="form-control" value="<?php echo e($item['Booker(s) Name']); ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Room Type</label>
            <input type="text" name="Room Type" class="form-control" value="<?php echo e($item['Room Type']); ?>" required>
          </div>
          <div class="mb-3">
            <label class="form-label">Status</label>
            <select name="status" class="form-select" required>
              <option value="Available" <?php echo e($item['status'] === 'Available' ? 'selected' : ''); ?>>Available</option>
              <option value="Booked" <?php echo e($item['status'] === 'Booked' ? 'selected' : ''); ?>>Booked</option>
            </select>
          </div>
          <div class="d-flex justify-content-between">
            <a href="<?php echo e(route('pengelolaan')); ?>" class="btn btn-secondary">Cancel</a>
            <button class="btn btn-primary" type="submit">Update</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\template_uts\resources\views/pengelolaan_edit.blade.php ENDPATH**/ ?>