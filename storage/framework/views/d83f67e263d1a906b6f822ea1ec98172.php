

<?php $__env->startSection('title', 'Pagina nu a fost găsită - 404'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="padding-top:140px; padding-bottom:100px;">
    <div class="row justify-content-center">
        <div class="col-lg-8 text-center">
            <h1 class="display-4 mb-3">404</h1>
            <h2 class="h4 mb-4">Pagina nu a fost găsită</h2>
            <p class="text-muted mb-4">Ne pare rău, pagina pe care o cauți nu există sau a fost mutată.</p>
            <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">Înapoi la acasă</a>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/errors/404.blade.php ENDPATH**/ ?>