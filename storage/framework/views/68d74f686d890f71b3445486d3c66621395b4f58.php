<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="<?php echo e(route('dashboard')); ?>">UTS PWEB</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navMenu">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('dashboard')); ?>">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('pengelolaan')); ?>">Pengelolaan</a></li>
        <li class="nav-item"><a class="nav-link" href="<?php echo e(route('profile')); ?>">Profile</a></li>
      </ul>
    </div>
  </div>
</nav>
<?php /**PATH C:\laragon\www\template_uts\resources\views/components/navbar.blade.php ENDPATH**/ ?>