<div class="list-group">
    <a href="<?php echo e(route('specialist.dashboard')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('specialist.dashboard') ? 'active' : ''); ?>">
        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
    </a>
    <a href="<?php echo e(route('specialist.appointments.index')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('specialist.appointments.*') ? 'active' : ''); ?>">
        <i class="fas fa-calendar-check me-2"></i>Programări
        <?php
            $pendingApps = Auth::user()->appointments()->where('status', 'pending')->count();
        ?>
        <?php if($pendingApps > 0): ?>
            <span class="badge bg-warning text-dark float-end"><?php echo e($pendingApps); ?></span>
        <?php endif; ?>
    </a>
    <a href="<?php echo e(route('specialist.services.index')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('specialist.services.*') ? 'active' : ''); ?>">
        <i class="fas fa-concierge-bell me-2"></i>Serviciile Mele
    </a>
    <a href="<?php echo e(route('specialist.reviews.index')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('specialist.reviews.*') ? 'active' : ''); ?>">
        <i class="fas fa-star me-2"></i>Reviews
    </a>
    <a href="<?php echo e(route('specialist.gallery.index')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('specialist.gallery.*') ? 'active' : ''); ?>">
        <i class="fas fa-images me-2"></i>Galerie
    </a>
    <a href="<?php echo e(route('specialist.profile')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('specialist.profile') ? 'active' : ''); ?>">
        <i class="fas fa-user me-2"></i>Profilul Meu
    </a>
    <a href="<?php echo e(route('specialist.social')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('specialist.social') ? 'active' : ''); ?>">
        <i class="fas fa-share-alt me-2"></i>Social Media
    </a>
    <form action="<?php echo e(route('logout')); ?>" method="POST" class="d-inline">
        <?php echo csrf_field(); ?>
        <button type="submit" class="list-group-item list-group-item-action text-danger border-0 w-100 text-start">
            <i class="fas fa-sign-out-alt me-2"></i>Deconectare
        </button>
    </form>
</div>
<?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/specialist/partials/sidebar.blade.php ENDPATH**/ ?>