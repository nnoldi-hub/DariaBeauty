

<?php $__env->startSection('content'); ?>
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            <?php echo $__env->make('specialist.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Reviews</h1>
            </div>

            <?php if(session('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?php echo e(session('success')); ?>

                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!-- Statistics -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-primary">
                        <div class="card-body text-center">
                            <h5 class="card-title text-primary">Rating Mediu</h5>
                            <p class="card-text display-4">
                                <?php echo e(number_format($averageRating ?? 0, 1)); ?>

                                <small class="text-muted">/5</small>
                            </p>
                            <div class="text-warning">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= floor($averageRating ?? 0)): ?>
                                        <i class="fas fa-star"></i>
                                    <?php elseif($i - 0.5 <= ($averageRating ?? 0)): ?>
                                        <i class="fas fa-star-half-alt"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-success">
                        <div class="card-body">
                            <h5 class="card-title text-success">Total Reviews</h5>
                            <p class="card-text display-6"><?php echo e($totalReviews ?? 0); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-warning">
                        <div class="card-body">
                            <h5 class="card-title text-warning">În Așteptare</h5>
                            <p class="card-text display-6"><?php echo e($pendingReviews ?? 0); ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-info">
                        <div class="card-body">
                            <h5 class="card-title text-info">Aprobate</h5>
                            <p class="card-text display-6"><?php echo e($approvedReviews ?? 0); ?></p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Reviews List -->
            <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card mb-3">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?php echo e($review->client_name); ?></strong>
                                <span class="text-muted">• <?php echo e($review->created_at->format('d.m.Y H:i')); ?></span>
                                <?php if($review->service): ?>
                                    <span class="text-muted">• <?php echo e($review->service->name); ?></span>
                                <?php endif; ?>
                            </div>
                            <div>
                                <?php if($review->is_approved): ?>
                                    <span class="badge bg-success">Aprobat</span>
                                <?php else: ?>
                                    <span class="badge bg-warning">În Așteptare</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <!-- Rating -->
                        <div class="mb-2">
                            <div class="text-warning">
                                <?php for($i = 1; $i <= 5; $i++): ?>
                                    <?php if($i <= $review->rating): ?>
                                        <i class="fas fa-star"></i>
                                    <?php else: ?>
                                        <i class="far fa-star"></i>
                                    <?php endif; ?>
                                <?php endfor; ?>
                                <span class="text-muted ms-2"><?php echo e($review->rating); ?>/5</span>
                            </div>
                        </div>

                        <!-- Review Text -->
                        <p class="card-text"><?php echo e($review->review); ?></p>

                        <!-- Specialist Response -->
                        <?php if($review->specialist_response): ?>
                            <div class="alert alert-info mt-3">
                                <strong><i class="fas fa-reply"></i> Răspunsul Tău:</strong>
                                <p class="mb-0 mt-2"><?php echo e($review->specialist_response); ?></p>
                                <small class="text-muted">
                                    Răspuns dat la: <?php echo e($review->specialist_response_date ? \Carbon\Carbon::parse($review->specialist_response_date)->format('d.m.Y H:i') : 'N/A'); ?>

                                </small>
                            </div>
                        <?php else: ?>
                            <!-- Response Form -->
                            <div class="mt-3">
                                <button class="btn btn-sm btn-primary" type="button" data-bs-toggle="collapse" 
                                        data-bs-target="#responseForm<?php echo e($review->id); ?>">
                                    <i class="fas fa-reply"></i> Răspunde la Review
                                </button>
                                
                                <div class="collapse mt-3" id="responseForm<?php echo e($review->id); ?>">
                                    <form method="POST" action="<?php echo e(route('specialist.reviews.respond', $review->id)); ?>">
                                        <?php echo csrf_field(); ?>
                                        <div class="mb-3">
                                            <label for="response<?php echo e($review->id); ?>" class="form-label">Răspunsul Tău</label>
                                            <textarea class="form-control" id="response<?php echo e($review->id); ?>" 
                                                      name="specialist_response" rows="3" required 
                                                      placeholder="Scrie un răspuns profesional și prietenos..."></textarea>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="button" class="btn btn-secondary btn-sm me-2" 
                                                    data-bs-toggle="collapse" data-bs-target="#responseForm<?php echo e($review->id); ?>">
                                                Anulează
                                            </button>
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                <i class="fas fa-paper-plane"></i> Trimite Răspuns
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Nu aveți reviews încă.
                </div>
            <?php endif; ?>

            <?php if(isset($reviews) && method_exists($reviews, 'links')): ?>
                <div class="mt-3">
                    <?php echo e($reviews->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/specialist/reviews/index.blade.php ENDPATH**/ ?>