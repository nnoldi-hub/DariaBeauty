

<?php $__env->startSection('title', 'Panou Admin - DariaBeauty'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <h3 class="mb-4">Panou de administrare</h3>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Specialisti in asteptare</h5>
                    <p class="card-text text-muted">Gestioneaza cererile de inregistrare.</p>
                    <a href="<?php echo e(route('admin.specialists.pending')); ?>" class="btn btn-primary btn-sm">Vezi lista</a>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title">Setari</h5>
                    <p class="card-text text-muted">Configureaza aplicatia.</p>
                    <a href="<?php echo e(route('admin.settings')); ?>" class="btn btn-outline-secondary btn-sm">Deschide</a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\salon\dariabeauty\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>