

<?php $__env->startSection('title', 'Detalii Review - Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            <?php echo $__env->make('admin.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Detalii Review</h3>
                <a href="<?php echo e(route('admin.reviews.index')); ?>" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Înapoi
                </a>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-md-8">
                    <div class="card shadow-sm mb-4">
                        <div class="card-header bg-white">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">Review-ul clientului</h5>
                                <?php if($review->is_approved): ?>
                                    <span class="badge bg-success">Aprobat</span>
                                <?php else: ?>
                                    <span class="badge bg-warning text-dark">În așteptare</span>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <strong>Rating:</strong>
                                <div style="color: #FFD700; font-size: 24px;">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if($i <= $review->rating): ?>
                                            ⭐
                                        <?php else: ?>
                                            ☆
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <span class="text-muted" style="font-size: 16px;">(<?php echo e($review->rating); ?>/5)</span>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <strong>Comentariu:</strong>
                                <p class="mt-2 border rounded p-3 bg-light"><?php echo e($review->comment); ?></p>
                            </div>
                            
                            <div class="mb-3">
                                <small class="text-muted">
                                    <i class="fas fa-calendar me-1"></i>Postat la: <?php echo e($review->created_at->format('d M Y, H:i')); ?>

                                </small>
                            </div>
                            
                            <?php if($review->specialist_response): ?>
                                <div class="alert alert-info">
                                    <strong><i class="fas fa-reply me-2"></i>Răspunsul specialistului:</strong>
                                    <p class="mb-0 mt-2"><?php echo e($review->specialist_response); ?></p>
                                </div>
                            <?php endif; ?>
                            
                            <form action="<?php echo e(route('admin.reviews.respond', $review)); ?>" method="POST" class="mt-4">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <div class="mb-3">
                                    <label class="form-label"><strong><?php echo e($review->specialist_response ? 'Actualizează' : 'Adaugă'); ?> răspuns specialist:</strong></label>
                                    <textarea name="specialist_response" class="form-control <?php $__errorArgs = ['specialist_response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                              rows="4" placeholder="Scrie răspunsul aici..."><?php echo e(old('specialist_response', $review->specialist_response)); ?></textarea>
                                    <?php $__errorArgs = ['specialist_response'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>Salvează răspuns
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-4">
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Client</h6>
                        </div>
                        <div class="card-body">
                            <p class="mb-1"><strong><?php echo e($review->client_name ?? 'Client necunoscut'); ?></strong></p>
                        </div>
                    </div>
                    
                    <div class="card shadow-sm mb-3">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Specialist</h6>
                        </div>
                        <div class="card-body">
                            <?php if($review->specialist): ?>
                                <p class="mb-2"><strong><?php echo e($review->specialist->name); ?></strong></p>
                                <span class="badge mb-2" style="background-color: <?php echo e($review->specialist->sub_brand === 'dariaNails' ? '#E91E63' : ($review->specialist->sub_brand === 'dariaHair' ? '#9C27B0' : '#FF9800')); ?>">
                                    <?php echo e(ucfirst(str_replace('daria', '', $review->specialist->sub_brand))); ?>

                                </span>
                                <p class="mb-1"><small class="text-muted"><?php echo e($review->specialist->email); ?></small></p>
                                <?php if($review->specialist->phone): ?>
                                    <p class="mb-0"><small><i class="fas fa-phone me-1"></i><?php echo e($review->specialist->phone); ?></small></p>
                                <?php endif; ?>
                            <?php else: ?>
                                <p class="text-muted">Specialist șters</p>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="card shadow-sm">
                        <div class="card-header bg-white">
                            <h6 class="mb-0">Acțiuni</h6>
                        </div>
                        <div class="card-body">
                            <?php if(!$review->is_approved): ?>
                                <form action="<?php echo e(route('admin.reviews.approve', $review)); ?>" method="POST" class="mb-2">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-success w-100">
                                        <i class="fas fa-check me-2"></i>Aprobă Review
                                    </button>
                                </form>
                            <?php else: ?>
                                <form action="<?php echo e(route('admin.reviews.reject', $review)); ?>" method="POST" class="mb-2">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit" class="btn btn-warning w-100">
                                        <i class="fas fa-times me-2"></i>Respinge Review
                                    </button>
                                </form>
                            <?php endif; ?>
                            
                            <form action="<?php echo e(route('admin.reviews.destroy', $review)); ?>" method="POST" 
                                  onsubmit="return confirm('Sigur ștergi acest review? Acțiunea este ireversibilă!')">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-danger w-100">
                                    <i class="fas fa-trash me-2"></i>Șterge Review
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/admin/reviews/show.blade.php ENDPATH**/ ?>