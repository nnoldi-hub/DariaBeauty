

<?php $__env->startSection('title', 'Dashboard Specialist - DariaBeauty'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            <?php echo $__env->make('specialist.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h3>Bun venit, <?php echo e($specialist->name); ?>!</h3>
                    <p class="text-muted mb-0">
                        <span class="badge" style="background-color: <?php echo e($specialist->sub_brand === 'dariaNails' ? '#E91E63' : ($specialist->sub_brand === 'dariaHair' ? '#9C27B0' : '#FF9800')); ?>">
                            <?php echo e(ucfirst(str_replace('daria', '', $specialist->sub_brand))); ?>

                        </span>
                    </p>
                </div>
            </div>

            <!-- Statistici -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card text-white" style="background-color: #06D6A0;">
                        <div class="card-body">
                            <h6 class="card-title">Programări Astăzi</h6>
                            <h2 class="mb-0"><?php echo e($stats['today_appointments']); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background-color: #118AB2;">
                        <div class="card-body">
                            <h6 class="card-title">Programări Viitoare</h6>
                            <h2 class="mb-0"><?php echo e($stats['upcoming_appointments']); ?></h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background-color: #FFD60A;">
                        <div class="card-body">
                            <h6 class="card-title">Rating Mediu</h6>
                            <h2 class="mb-0"><?php echo e(number_format($stats['average_rating'], 1)); ?> ⭐</h2>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card text-white" style="background-color: #9C27B0;">
                        <div class="card-body">
                            <h6 class="card-title">Total Reviews</h6>
                            <h2 class="mb-0"><?php echo e($stats['total_reviews']); ?></h2>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Programări Recente -->
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Programări Recente</h5>
                        <a href="<?php echo e(route('specialist.appointments.index')); ?>" class="btn btn-sm btn-outline-primary">
                            Vezi toate
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($recentAppointments->count() > 0): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Data & Ora</th>
                                        <th>Client</th>
                                        <th>Serviciu</th>
                                        <th>Status</th>
                                        <th>Preț</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $__currentLoopData = $recentAppointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td>
                                                <?php echo e($appointment->appointment_date->format('d M Y')); ?><br>
                                                <small class="text-muted"><?php echo e($appointment->appointment_time); ?></small>
                                            </td>
                                            <td>
                                                <strong><?php echo e($appointment->client_name); ?></strong><br>
                                                <small class="text-muted"><?php echo e($appointment->client_phone); ?></small>
                                            </td>
                                            <td><?php echo e($appointment->service ? $appointment->service->name : 'N/A'); ?></td>
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
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Nu ai programări recente.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Reviews Recente -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Reviews Recente</h5>
                        <a href="<?php echo e(route('specialist.reviews.index')); ?>" class="btn btn-sm btn-outline-primary">
                            Vezi toate
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <?php if($recentReviews->count() > 0): ?>
                        <?php $__currentLoopData = $recentReviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="border-bottom pb-3 mb-3">
                                <div class="d-flex justify-content-between mb-2">
                                    <div>
                                        <strong><?php echo e($review->client_name); ?></strong>
                                        <div style="color: #FFD700;">
                                            <?php for($i = 1; $i <= 5; $i++): ?>
                                                <?php if($i <= $review->rating): ?>
                                                    ⭐
                                                <?php else: ?>
                                                    ☆
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                        </div>
                                    </div>
                                    <small class="text-muted"><?php echo e($review->created_at->diffForHumans()); ?></small>
                                </div>
                                <p class="mb-0"><?php echo e(Str::limit($review->comment, 150)); ?></p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    <?php else: ?>
                        <p class="text-muted text-center py-4">Nu ai reviews încă.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/specialist/dashboard.blade.php ENDPATH**/ ?>