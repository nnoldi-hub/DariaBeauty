

<?php $__env->startSection('title', 'Reviews - Admin DariaBeauty'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            <?php echo $__env->make('admin.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Gestionare Reviews</h3>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('admin.reviews.index')); ?>" class="row g-2 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Caută în reviews..." 
                                   value="<?php echo e(request('search')); ?>">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="is_approved">
                                <option value="">Toate statusurile</option>
                                <option value="1" <?php echo e(request('is_approved') === '1' ? 'selected' : ''); ?>>Aprobat</option>
                                <option value="0" <?php echo e(request('is_approved') === '0' ? 'selected' : ''); ?>>În așteptare</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="rating">
                                <option value="">Toate rating-urile</option>
                                <option value="5" <?php echo e(request('rating') == '5' ? 'selected' : ''); ?>>⭐⭐⭐⭐⭐ (5)</option>
                                <option value="4" <?php echo e(request('rating') == '4' ? 'selected' : ''); ?>>⭐⭐⭐⭐ (4)</option>
                                <option value="3" <?php echo e(request('rating') == '3' ? 'selected' : ''); ?>>⭐⭐⭐ (3)</option>
                                <option value="2" <?php echo e(request('rating') == '2' ? 'selected' : ''); ?>>⭐⭐ (2)</option>
                                <option value="1" <?php echo e(request('rating') == '1' ? 'selected' : ''); ?>>⭐ (1)</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <button type="submit" class="btn btn-secondary w-100">
                                <i class="fas fa-search"></i> Filtrează
                            </button>
                        </div>
                    </form>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Client</th>
                                    <th>Specialist</th>
                                    <th>Rating</th>
                                    <th>Comentariu</th>
                                    <th>Status</th>
                                    <th>Data</th>
                                    <th>Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($review->id); ?></td>
                                        <td>
                                            <?php if($review->user): ?>
                                                <strong><?php echo e($review->user->name); ?></strong><br>
                                                <small class="text-muted"><?php echo e($review->user->email); ?></small>
                                            <?php else: ?>
                                                <span class="text-muted">Utilizator șters</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($review->specialist): ?>
                                                <strong><?php echo e($review->specialist->name); ?></strong><br>
                                                <span class="badge" style="background-color: <?php echo e($review->specialist->sub_brand === 'dariaNails' ? '#E91E63' : ($review->specialist->sub_brand === 'dariaHair' ? '#9C27B0' : '#FF9800')); ?>">
                                                    <?php echo e(ucfirst(str_replace('daria', '', $review->specialist->sub_brand))); ?>

                                                </span>
                                            <?php else: ?>
                                                <span class="text-muted">Specialist șters</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span style="color: #FFD700;">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <?php if($i <= $review->rating): ?>
                                                        ⭐
                                                    <?php else: ?>
                                                        ☆
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                            </span>
                                            <br>
                                            <small class="text-muted">(<?php echo e($review->rating); ?>/5)</small>
                                        </td>
                                        <td style="max-width: 250px;">
                                            <p class="mb-0 text-truncate" title="<?php echo e($review->comment); ?>">
                                                <?php echo e(Str::limit($review->comment, 60)); ?>

                                            </p>
                                            <?php if($review->specialist_response): ?>
                                                <small class="text-primary">
                                                    <i class="fas fa-reply me-1"></i>Răspuns: <?php echo e(Str::limit($review->specialist_response, 40)); ?>

                                                </small>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($review->is_approved): ?>
                                                <span class="badge bg-success">Aprobat</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning text-dark">În așteptare</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($review->created_at->format('d M Y')); ?></td>
                                        <td>
                                            <div class="btn-group">
                                                <a href="<?php echo e(route('admin.reviews.show', $review)); ?>" 
                                                   class="btn btn-sm btn-outline-info" title="Detalii">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                                <?php if(!$review->is_approved): ?>
                                                    <form action="<?php echo e(route('admin.reviews.approve', $review)); ?>" 
                                                          method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-success" title="Aprobă">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                <?php else: ?>
                                                    <form action="<?php echo e(route('admin.reviews.reject', $review)); ?>" 
                                                          method="POST" class="d-inline">
                                                        <?php echo csrf_field(); ?>
                                                        <?php echo method_field('PATCH'); ?>
                                                        <button type="submit" class="btn btn-sm btn-outline-warning" title="Respinge">
                                                            <i class="fas fa-times"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <form action="<?php echo e(route('admin.reviews.destroy', $review)); ?>" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Sigur ștergi acest review?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Șterge">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="8" class="text-center text-muted py-4">
                                            Nu există reviews care să corespundă criteriilor de căutare.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if($reviews->hasPages()): ?>
                        <div class="mt-3">
                            <?php echo e($reviews->appends(request()->query())->links()); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/admin/reviews/index.blade.php ENDPATH**/ ?>