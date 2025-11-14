<div class="list-group">
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('admin.dashboard') ? 'active' : ''); ?>">
        <i class="fas fa-tachometer-alt me-2"></i>Dashboard
    </a>
    <a href="<?php echo e(route('admin.specialists.pending')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('admin.specialists.*') ? 'active' : ''); ?>">
        <i class="fas fa-user-check me-2"></i>Specialiști
        <?php
            $pendingCount = \App\Models\User::where('role', 'specialist')->where('is_active', false)->count();
        ?>
        <?php if($pendingCount > 0): ?>
            <span class="badge bg-warning float-end"><?php echo e($pendingCount); ?></span>
        <?php endif; ?>
    </a>
    <a href="<?php echo e(route('admin.users-crud.index')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('admin.users-crud.*') ? 'active' : ''); ?>">
        <i class="fas fa-users me-2"></i>Utilizatori
    </a>
    <a href="<?php echo e(route('admin.services-crud.index')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('admin.services-crud.*') || request()->routeIs('admin.services') ? 'active' : ''); ?>">
        <i class="fas fa-concierge-bell me-2"></i>Servicii
    </a>
    <a href="<?php echo e(route('admin.appointments')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('admin.appointments') ? 'active' : ''); ?>">
        <i class="fas fa-calendar-check me-2"></i>Programări
    </a>
    <a href="<?php echo e(route('admin.reviews.index')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('admin.reviews.*') ? 'active' : ''); ?>">
        <i class="fas fa-star me-2"></i>Reviews
        <?php
            $pendingReviews = \App\Models\Review::where('is_approved', false)->count();
        ?>
        <?php if($pendingReviews > 0): ?>
            <span class="badge bg-warning float-end"><?php echo e($pendingReviews); ?></span>
        <?php endif; ?>
    </a>
    <a href="<?php echo e(route('admin.reports')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('admin.reports') ? 'active' : ''); ?>">
        <i class="fas fa-chart-line me-2"></i>Rapoarte
    </a>
    <a href="<?php echo e(route('admin.settings')); ?>" class="list-group-item list-group-item-action <?php echo e(request()->routeIs('admin.settings') ? 'active' : ''); ?>">
        <i class="fas fa-cog me-2"></i>Setări
    </a>
</div>
<?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/admin/partials/sidebar.blade.php ENDPATH**/ ?>