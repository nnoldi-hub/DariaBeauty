

<?php $__env->startSection('content'); ?>
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            <?php echo $__env->make('specialist.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Serviciile Mele</h1>
                <a href="<?php echo e(route('specialist.services.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Adaugă Serviciu Nou
                </a>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if(session('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?php echo e(session('error')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-6 mb-4">
                        <div class="card h-100">
                            <?php if($service->image): ?>
                                <img src="<?php echo e(asset('storage/' . $service->image)); ?>" class="card-img-top" alt="<?php echo e($service->name); ?>" style="height: 200px; object-fit: cover;">
                            <?php else: ?>
                                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 200px;">
                                    <i class="fas fa-image fa-3x text-white"></i>
                                </div>
                            <?php endif; ?>
                            <div class="card-body">
                                <h5 class="card-title"><?php echo e($service->name); ?></h5>
                                <p class="card-text"><?php echo e(Str::limit($service->description, 120)); ?></p>
                                
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <strong>Preț:</strong> <?php echo e($service->price); ?> RON
                                    </div>
                                    <div class="col-6">
                                        <strong>Durată:</strong> <?php echo e($service->duration); ?> min
                                    </div>
                                </div>

                                <?php if($service->category): ?>
                                    <p class="mb-2">
                                        <span class="badge bg-secondary"><?php echo e(ucfirst($service->category)); ?></span>
                                    </p>
                                <?php endif; ?>

                                <p class="mb-2">
                                    <strong>Status:</strong>
                                    <?php if($service->is_active): ?>
                                        <span class="badge bg-success">Activ</span>
                                    <?php else: ?>
                                        <span class="badge bg-danger">Inactiv</span>
                                    <?php endif; ?>
                                </p>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <a href="<?php echo e(route('specialist.services.edit', $service->id)); ?>" class="btn btn-sm btn-warning">
                                        <i class="fas fa-edit"></i> Editează
                                    </a>
                                    <form method="POST" action="<?php echo e(route('specialist.services.destroy', $service->id)); ?>" class="d-inline" onsubmit="return confirm('Sigur doriți să ștergeți acest serviciu?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="fas fa-trash"></i> Șterge
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Nu aveți servicii adăugate încă. 
                            <a href="<?php echo e(route('specialist.services.create')); ?>" class="alert-link">Adăugați primul serviciu</a>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if(isset($services) && method_exists($services, 'links')): ?>
                <div class="mt-3">
                    <?php echo e($services->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/specialist/services/index.blade.php ENDPATH**/ ?>