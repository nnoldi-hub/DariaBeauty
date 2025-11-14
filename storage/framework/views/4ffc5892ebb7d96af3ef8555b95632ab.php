

<?php $__env->startSection('title', 'Servicii - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            <?php echo $__env->make('admin.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Gestionare Servicii</h3>
                <a href="<?php echo e(route('admin.services-crud.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Adaugă Serviciu
                </a>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm mb-3">
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('admin.services-crud.index')); ?>" class="row g-2">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Caută serviciu..." value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-3">
                            <select name="sub_brand" class="form-select">
                                <option value="">Toate brandurile</option>
                                <option value="dariaNails" <?php echo e(request('sub_brand') === 'dariaNails' ? 'selected' : ''); ?>>dariaNails</option>
                                <option value="dariaHair" <?php echo e(request('sub_brand') === 'dariaHair' ? 'selected' : ''); ?>>dariaHair</option>
                                <option value="dariaGlow" <?php echo e(request('sub_brand') === 'dariaGlow' ? 'selected' : ''); ?>>dariaGlow</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select name="category" class="form-select">
                                <option value="">Toate categoriile</option>
                                <?php $__currentLoopData = \App\Models\Service::select('category')->distinct()->pluck('category'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($cat); ?>" <?php echo e(request('category') === $cat ? 'selected' : ''); ?>><?php echo e($cat); ?></option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="fas fa-search"></i> Filtrează
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Serviciu</th>
                                    <th>Brand</th>
                                    <th>Categorie</th>
                                    <th>Specialist</th>
                                    <th>Preț</th>
                                    <th>Durată</th>
                                    <th>Status</th>
                                    <th>Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $services = \App\Models\Service::with('specialist')->latest()->paginate(15);
                                ?>
                                <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($service->id); ?></td>
                                        <td><strong><?php echo e($service->name); ?></strong></td>
                                        <td>
                                            <span class="badge" style="background:<?php echo e($service->sub_brand === 'dariaNails' ? '#E91E63' : ($service->sub_brand === 'dariaHair' ? '#9C27B0' : '#FF9800')); ?>">
                                                <?php echo e($service->sub_brand); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($service->category); ?></td>
                                        <td><?php echo e($service->specialist->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($service->price); ?> RON</td>
                                        <td><?php echo e($service->duration); ?> min</td>
                                        <td>
                                            <span class="badge bg-<?php echo e($service->is_active ? 'success' : 'secondary'); ?>">
                                                <?php echo e($service->is_active ? 'Activ' : 'Inactiv'); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <a href="<?php echo e(route('admin.services-crud.edit', $service)); ?>" class="btn btn-sm btn-outline-primary" title="Editează">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="<?php echo e(route('admin.services-crud.destroy', $service)); ?>" method="POST" class="d-inline" onsubmit="return confirm('Sigur ștergi acest serviciu?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Șterge">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="9" class="text-center text-muted py-4">Nu există servicii în baza de date.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php if($services->hasPages()): ?>
                        <div class="mt-3">
                            <?php echo e($services->appends(request()->query())->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/admin/services/index.blade.php ENDPATH**/ ?>