

<?php $__env->startSection('title','Programează-te - DariaBeauty'); ?>

<?php $__env->startSection('content'); ?>
<div class="container py-5">
  <h1 class="mb-4">Programează-te</h1>
  <p class="text-muted">Alege un specialist sau un serviciu și continuă la programare.</p>

  <h3 class="mt-4">Servicii populare</h3>
  <div class="row g-3">
    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <h6 class="card-title"><?php echo e($service->name); ?></h6>
          <div class="small text-muted"><?php echo e($service->specialist->name ?? 'Specialist'); ?></div>
          <div class="fw-bold"><?php echo e($service->formatted_price); ?></div>
          <?php if($service->specialist): ?>
          <a class="btn btn-sm btn-outline-primary mt-2" href="<?php echo e(route('specialists.booking',['slug'=>$service->specialist->slug ?? 'specialist','service_id'=>$service->id])); ?>">Programează-te</a>
          <?php endif; ?>
        </div>
      </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>

  <h3 class="mt-5">Specialiști disponibili</h3>
  <div class="row g-3">
    <?php $__currentLoopData = $specialists; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $spec): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <div class="col-md-3">
      <div class="card h-100">
        <div class="card-body">
          <h6 class="card-title"><?php echo e($spec->name); ?></h6>
          <div class="small text-muted"><?php echo e($spec->sub_brand); ?></div>
          <a class="btn btn-sm btn-outline-primary mt-2" href="<?php echo e(route('specialists.booking',['slug'=>$spec->slug ?? 'specialist'])); ?>">Programează-te</a>
        </div>
      </div>
    </div>
    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
  </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/booking.blade.php ENDPATH**/ ?>