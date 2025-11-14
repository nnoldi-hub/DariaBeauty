

<?php $__env->startSection('title', 'Programări - Admin DariaBeauty'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            <?php echo $__env->make('admin.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Monitorizare Programări</h3>
                <a href="<?php echo e(route('admin.appointments.export', request()->query())); ?>" class="btn btn-success">
                    <i class="fas fa-download me-2"></i>Export Excel
                </a>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card bg-primary text-white">
                        <div class="card-body">
                            <h6 class="card-title">Astăzi</h6>
                            <h3 class="mb-0"><?php echo e($stats['today']); ?></h3>
                            <small>programări</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-success text-white">
                        <div class="card-body">
                            <h6 class="card-title">Confirmate</h6>
                            <h3 class="mb-0"><?php echo e($stats['confirmed']); ?></h3>
                            <small>finalizate</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-warning text-white">
                        <div class="card-body">
                            <h6 class="card-title">În așteptare</h6>
                            <h3 class="mb-0"><?php echo e($stats['pending']); ?></h3>
                            <small>neprocesate</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card bg-danger text-white">
                        <div class="card-body">
                            <h6 class="card-title">Anulate</h6>
                            <h3 class="mb-0"><?php echo e($stats['cancelled']); ?></h3>
                            <small>azi</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('admin.appointments')); ?>" class="row mb-3">
                        <div class="col-md-3">
                            <input type="date" name="date" class="form-control" value="<?php echo e(request('date')); ?>">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="status">
                                <option value="">Toate statusurile</option>
                                <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>În așteptare</option>
                                <option value="confirmed" <?php echo e(request('status') == 'confirmed' ? 'selected' : ''); ?>>Confirmată</option>
                                <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Finalizată</option>
                                <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Anulată</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="brand">
                                <option value="">Toate brandurile</option>
                                <option value="dariaNails" <?php echo e(request('brand') == 'dariaNails' ? 'selected' : ''); ?>>dariaNails</option>
                                <option value="dariaHair" <?php echo e(request('brand') == 'dariaHair' ? 'selected' : ''); ?>>dariaHair</option>
                                <option value="dariaGlow" <?php echo e(request('brand') == 'dariaGlow' ? 'selected' : ''); ?>>dariaGlow</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <input type="text" name="search" class="form-control" placeholder="Caută specialist..." value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-1">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Dată & Oră</th>
                                    <th>Client</th>
                                    <th>Specialist</th>
                                    <th>Serviciu</th>
                                    <th>Brand</th>
                                    <th>Status</th>
                                    <th>Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td>#<?php echo e($appointment->id); ?></td>
                                        <td>
                                            <?php echo e($appointment->appointment_date ? $appointment->appointment_date->format('d M Y') : 'N/A'); ?>, 
                                            <?php echo e($appointment->appointment_time ?? 'N/A'); ?>

                                        </td>
                                        <td><?php echo e($appointment->client_name ?? 'N/A'); ?></td>
                                        <td><?php echo e($appointment->specialist ? $appointment->specialist->name : 'N/A'); ?></td>
                                        <td><?php echo e($appointment->service ? $appointment->service->name : 'N/A'); ?></td>
                                        <td>
                                            <?php if($appointment->specialist): ?>
                                                <span class="badge" style="background-color: <?php echo e($appointment->specialist->sub_brand === 'dariaNails' ? '#E91E63' : ($appointment->specialist->sub_brand === 'dariaHair' ? '#9C27B0' : '#FF9800')); ?>">
                                                    <?php echo e(ucfirst(str_replace('daria', '', $appointment->specialist->sub_brand))); ?>

                                                </span>
                                            <?php else: ?>
                                                N/A
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($appointment->status === 'pending'): ?>
                                                <span class="badge bg-warning text-dark">În așteptare</span>
                                            <?php elseif($appointment->status === 'confirmed'): ?>
                                                <span class="badge bg-success">Confirmată</span>
                                            <?php elseif($appointment->status === 'completed'): ?>
                                                <span class="badge bg-info">Finalizată</span>
                                            <?php else: ?>
                                                <span class="badge bg-danger">Anulată</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($appointment->service ? $appointment->service->price : '0'); ?> RON</td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            Nu există programări care să corespundă criteriilor de căutare.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if($appointments->hasPages()): ?>
                        <div class="mt-3">
                            <?php echo e($appointments->appends(request()->query())->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/admin/appointments.blade.php ENDPATH**/ ?>