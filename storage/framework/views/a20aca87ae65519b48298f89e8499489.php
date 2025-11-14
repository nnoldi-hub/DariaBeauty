

<?php $__env->startSection('content'); ?>
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            <?php echo $__env->make('specialist.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Programările Mele</h1>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-primary">
                        <div class="card-body">
                            <h5 class="card-title text-primary">Astăzi</h5>
                            <p class="card-text display-6"><?php echo e($todayCount ?? 0); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-warning">
                        <div class="card-body">
                            <h5 class="card-title text-warning">În Așteptare</h5>
                            <p class="card-text display-6"><?php echo e($pendingCount ?? 0); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-success">
                        <div class="card-body">
                            <h5 class="card-title text-success">Confirmate</h5>
                            <p class="card-text display-6"><?php echo e($confirmedCount ?? 0); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-info">
                        <div class="card-body">
                            <h5 class="card-title text-info">Finalizate</h5>
                            <p class="card-text display-6"><?php echo e($completedCount ?? 0); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filters -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('specialist.appointments.index')); ?>">
                        <div class="row">
                            <div class="col-md-3">
                                <label for="status" class="form-label">Status</label>
                                <select name="status" id="status" class="form-select">
                                    <option value="">Toate</option>
                                    <option value="pending" <?php echo e(request('status') == 'pending' ? 'selected' : ''); ?>>În Așteptare</option>
                                    <option value="confirmed" <?php echo e(request('status') == 'confirmed' ? 'selected' : ''); ?>>Confirmat</option>
                                    <option value="completed" <?php echo e(request('status') == 'completed' ? 'selected' : ''); ?>>Finalizat</option>
                                    <option value="cancelled" <?php echo e(request('status') == 'cancelled' ? 'selected' : ''); ?>>Anulat</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="date_from" class="form-label">Data Început</label>
                                <input type="date" name="date_from" id="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
                            </div>
                            <div class="col-md-3">
                                <label for="date_to" class="form-label">Data Sfârșit</label>
                                <input type="date" name="date_to" id="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
                            </div>
                            <div class="col-md-3 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary me-2">
                                    <i class="fas fa-filter"></i> Filtrează
                                </button>
                                <a href="<?php echo e(route('specialist.appointments.index')); ?>" class="btn btn-secondary">
                                    <i class="fas fa-redo"></i> Resetează
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Appointments Table -->
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Lista Programări</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Data și Ora</th>
                                    <th>Serviciu</th>
                                    <th>Client</th>
                                    <th>Telefon</th>
                                    <th>Locație</th>
                                    <th>Status</th>
                                    <th>Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $appointments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $appointment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($appointment->id); ?></td>
                                        <td>
                                            <strong><?php echo e(\Carbon\Carbon::parse($appointment->appointment_date)->format('d.m.Y')); ?></strong><br>
                                            <small class="text-muted"><?php echo e(\Carbon\Carbon::parse($appointment->appointment_time)->format('H:i')); ?></small>
                                        </td>
                                        <td><?php echo e($appointment->service->name ?? 'N/A'); ?></td>
                                        <td><?php echo e($appointment->client_name); ?></td>
                                        <td><?php echo e($appointment->client_phone); ?></td>
                                        <td>
                                            <?php if($appointment->location === 'salon'): ?>
                                                <span class="badge bg-info">Salon</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning">Deplasare</span><br>
                                                <small class="text-muted"><?php echo e($appointment->client_address); ?></small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($appointment->status === 'pending'): ?>
                                                <span class="badge bg-warning">În Așteptare</span>
                                            <?php elseif($appointment->status === 'confirmed'): ?>
                                                <span class="badge bg-success">Confirmat</span>
                                            <?php elseif($appointment->status === 'completed'): ?>
                                                <span class="badge bg-info">Finalizat</span>
                                            <?php elseif($appointment->status === 'cancelled'): ?>
                                                <span class="badge bg-danger">Anulat</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <div class="btn-group btn-group-sm" role="group">
                                                <?php if($appointment->status === 'pending'): ?>
                                                    <form method="POST" action="<?php echo e(route('specialist.appointments.confirm', $appointment->id)); ?>" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-success btn-sm" title="Confirmă">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <?php if($appointment->status === 'confirmed'): ?>
                                                    <form method="POST" action="<?php echo e(route('specialist.appointments.complete', $appointment->id)); ?>" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <button type="submit" class="btn btn-info btn-sm" title="Marchează ca Finalizat">
                                                            <i class="fas fa-check-double"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#detailsModal<?php echo e($appointment->id); ?>" title="Detalii">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>

                                    <!-- Details Modal -->
                                    <div class="modal fade" id="detailsModal<?php echo e($appointment->id); ?>" tabindex="-1">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Detalii Programare #<?php echo e($appointment->id); ?></h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <dl class="row">
                                                        <dt class="col-sm-4">Data și Ora:</dt>
                                                        <dd class="col-sm-8"><?php echo e(\Carbon\Carbon::parse($appointment->appointment_date)->format('d.m.Y')); ?> la <?php echo e(\Carbon\Carbon::parse($appointment->appointment_time)->format('H:i')); ?></dd>

                                                        <dt class="col-sm-4">Serviciu:</dt>
                                                        <dd class="col-sm-8"><?php echo e($appointment->service->name ?? 'N/A'); ?></dd>

                                                        <dt class="col-sm-4">Client:</dt>
                                                        <dd class="col-sm-8"><?php echo e($appointment->client_name); ?></dd>

                                                        <dt class="col-sm-4">Email:</dt>
                                                        <dd class="col-sm-8"><?php echo e($appointment->client_email); ?></dd>

                                                        <dt class="col-sm-4">Telefon:</dt>
                                                        <dd class="col-sm-8"><?php echo e($appointment->client_phone); ?></dd>

                                                        <dt class="col-sm-4">Locație:</dt>
                                                        <dd class="col-sm-8">
                                                            <?php if($appointment->location === 'salon'): ?>
                                                                Salon
                                                            <?php else: ?>
                                                                Deplasare la: <?php echo e($appointment->client_address); ?>

                                                            <?php endif; ?>
                                                        </dd>

                                                        <dt class="col-sm-4">Status:</dt>
                                                        <dd class="col-sm-8">
                                                            <?php if($appointment->status === 'pending'): ?>
                                                                <span class="badge bg-warning">În Așteptare</span>
                                                            <?php elseif($appointment->status === 'confirmed'): ?>
                                                                <span class="badge bg-success">Confirmat</span>
                                                            <?php elseif($appointment->status === 'completed'): ?>
                                                                <span class="badge bg-info">Finalizat</span>
                                                            <?php elseif($appointment->status === 'cancelled'): ?>
                                                                <span class="badge bg-danger">Anulat</span>
                                                            <?php endif; ?>
                                                        </dd>

                                                        <?php if($appointment->notes): ?>
                                                            <dt class="col-sm-4">Observații:</dt>
                                                            <dd class="col-sm-8"><?php echo e($appointment->notes); ?></dd>
                                                        <?php endif; ?>

                                                        <dt class="col-sm-4">Creat la:</dt>
                                                        <dd class="col-sm-8"><?php echo e($appointment->created_at->format('d.m.Y H:i')); ?></dd>
                                                    </dl>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Închide</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Nu există programări</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if(isset($appointments) && method_exists($appointments, 'links')): ?>
                        <div class="mt-3">
                            <?php echo e($appointments->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/specialist/appointments/index.blade.php ENDPATH**/ ?>