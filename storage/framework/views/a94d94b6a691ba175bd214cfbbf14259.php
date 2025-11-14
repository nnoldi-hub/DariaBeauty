

<?php $__env->startSection('content'); ?>
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            <?php echo $__env->make('specialist.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                <h1 class="h2">Galeria Mea</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">
                    <i class="fas fa-plus"></i> Adaugă Imagine
                </button>
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

            <!-- Gallery Grid -->
            <div class="row">
                <?php $__empty_1 = true; $__currentLoopData = $gallery; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-md-4 col-sm-6 mb-4">
                        <div class="card h-100">
                            <img src="<?php echo e(asset('storage/' . $image->image_path)); ?>" class="card-img-top" 
                                 alt="<?php echo e($image->caption); ?>" style="height: 250px; object-fit: cover;">
                            <div class="card-body">
                                <?php if($image->caption): ?>
                                    <p class="card-text"><?php echo e($image->caption); ?></p>
                                <?php endif; ?>
                                
                                <?php if($image->service): ?>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-tags"></i> <?php echo e($image->service->name); ?>

                                        </small>
                                    </p>
                                <?php endif; ?>

                                <p class="card-text">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar"></i> <?php echo e($image->created_at->format('d.m.Y')); ?>

                                    </small>
                                </p>

                                <?php if($image->before_after && $image->before_after !== 'single'): ?>
                                    <span class="badge bg-info"><?php echo e(ucfirst($image->before_after)); ?></span>
                                <?php endif; ?>

                                <?php if($image->is_featured): ?>
                                    <span class="badge bg-warning text-dark">Evidențiat</span>
                                <?php endif; ?>
                            </div>
                            <div class="card-footer">
                                <div class="d-flex justify-content-between">
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal" 
                                            data-bs-target="#editModal<?php echo e($image->id); ?>">
                                        <i class="fas fa-edit"></i> Editează
                                    </button>
                                    <form method="POST" action="<?php echo e(route('specialist.gallery.destroy', $image->id)); ?>" 
                                          class="d-inline" onsubmit="return confirm('Sigur doriți să ștergeți această imagine?');">
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

                    <!-- Edit Modal for each image -->
                    <div class="modal fade" id="editModal<?php echo e($image->id); ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Editează Imagine</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST" action="<?php echo e(route('specialist.gallery.update', $image->id)); ?>">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PUT'); ?>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label for="caption<?php echo e($image->id); ?>" class="form-label">Descriere</label>
                                            <textarea class="form-control" id="caption<?php echo e($image->id); ?>" 
                                                      name="caption" rows="2"><?php echo e($image->caption); ?></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="service_id<?php echo e($image->id); ?>" class="form-label">Serviciu Asociat</label>
                                            <select class="form-select" id="service_id<?php echo e($image->id); ?>" name="service_id">
                                                <option value="">Fără serviciu asociat</option>
                                                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <option value="<?php echo e($service->id); ?>" 
                                                            <?php echo e($image->service_id == $service->id ? 'selected' : ''); ?>>
                                                        <?php echo e($service->name); ?>

                                                    </option>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label for="before_after<?php echo e($image->id); ?>" class="form-label">Tip Imagine</label>
                                            <select class="form-select" id="before_after<?php echo e($image->id); ?>" name="before_after">
                                                <option value="single" <?php echo e($image->before_after == 'single' ? 'selected' : ''); ?>>Imagine Simplă</option>
                                                <option value="before" <?php echo e($image->before_after == 'before' ? 'selected' : ''); ?>>Înainte</option>
                                                <option value="after" <?php echo e($image->before_after == 'after' ? 'selected' : ''); ?>>După</option>
                                            </select>
                                        </div>

                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" 
                                                   id="is_featured<?php echo e($image->id); ?>" name="is_featured" value="1"
                                                   <?php echo e($image->is_featured ? 'checked' : ''); ?>>
                                            <label class="form-check-label" for="is_featured<?php echo e($image->id); ?>">
                                                Evidențiază în galeria principală
                                            </label>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                                        <button type="submit" class="btn btn-primary">Salvează Modificările</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <div class="col-12">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i> Nu aveți imagini în galerie încă. 
                            <button class="btn btn-link p-0 align-baseline" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                Adăugați prima imagine
                            </button>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <?php if(isset($gallery) && method_exists($gallery, 'links')): ?>
                <div class="mt-3">
                    <?php echo e($gallery->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Adaugă Imagine Nouă</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" action="<?php echo e(route('specialist.gallery.store')); ?>" enctype="multipart/form-data">
                <?php echo csrf_field(); ?>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="image" class="form-label">Selectează Imaginea <span class="text-danger">*</span></label>
                        <input type="file" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="image" name="image" accept="image/*" required>
                        <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Format acceptat: JPG, PNG, WebP. Dimensiune maximă: 5MB</small>
                    </div>

                    <div class="mb-3">
                        <label for="caption" class="form-label">Descriere</label>
                        <textarea class="form-control <?php $__errorArgs = ['caption'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                                  id="caption" name="caption" rows="2" 
                                  placeholder="Adaugă o descriere pentru această imagine..."><?php echo e(old('caption')); ?></textarea>
                        <?php $__errorArgs = ['caption'];
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

                    <div class="mb-3">
                        <label for="service_id" class="form-label">Serviciu Asociat</label>
                        <select class="form-select <?php $__errorArgs = ['service_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="service_id" name="service_id">
                            <option value="">Fără serviciu asociat</option>
                            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($service->id); ?>" <?php echo e(old('service_id') == $service->id ? 'selected' : ''); ?>>
                                    <?php echo e($service->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                        <?php $__errorArgs = ['service_id'];
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

                    <div class="mb-3">
                        <label for="before_after" class="form-label">Tip Imagine</label>
                        <select class="form-select <?php $__errorArgs = ['before_after'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="before_after" name="before_after" required>
                            <option value="single" <?php echo e(old('before_after') == 'single' ? 'selected' : ''); ?>>Imagine Simplă</option>
                            <option value="before" <?php echo e(old('before_after') == 'before' ? 'selected' : ''); ?>>Înainte</option>
                            <option value="after" <?php echo e(old('before_after') == 'after' ? 'selected' : ''); ?>>După</option>
                        </select>
                        <?php $__errorArgs = ['before_after'];
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

                    <div class="mb-3">
                        <label for="tags" class="form-label">Tag-uri (opțional)</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['tags'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" 
                               id="tags" name="tags" value="<?php echo e(old('tags')); ?>"
                               placeholder="De ex: manichiură, gel, french, etc. (separate prin virgulă)">
                        <?php $__errorArgs = ['tags'];
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

                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" <?php echo e(old('is_featured') ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="is_featured">
                            Evidențiază în galeria principală
                        </label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Anulează</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-upload"></i> Încarcă Imagine
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/specialist/gallery/index.blade.php ENDPATH**/ ?>