

<?php $__env->startSection('title', 'Utilizatori - Admin DariaBeauty'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            <?php echo $__env->make('admin.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h3>Gestionare Utilizatori</h3>
                <a href="<?php echo e(route('admin.users-crud.create')); ?>" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Adaugă Utilizator
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

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <form method="GET" action="<?php echo e(route('admin.users-crud.index')); ?>" class="row g-2 mb-3">
                        <div class="col-md-4">
                            <input type="text" name="search" class="form-control" placeholder="Caută utilizator..." 
                                   value="<?php echo e(request('search')); ?>" id="searchUser">
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="role" id="filterRole">
                                <option value="">Toate rolurile</option>
                                <option value="client" <?php echo e(request('role') === 'client' ? 'selected' : ''); ?>>Client</option>
                                <option value="specialist" <?php echo e(request('role') === 'specialist' ? 'selected' : ''); ?>>Specialist</option>
                                <option value="admin" <?php echo e(request('role') === 'admin' ? 'selected' : ''); ?>>Admin</option>
                                <option value="superadmin" <?php echo e(request('role') === 'superadmin' ? 'selected' : ''); ?>>Super Admin</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <select class="form-select" name="is_active" id="filterStatus">
                                <option value="">Toate statusurile</option>
                                <option value="1" <?php echo e(request('is_active') === '1' ? 'selected' : ''); ?>>Activ</option>
                                <option value="0" <?php echo e(request('is_active') === '0' ? 'selected' : ''); ?>>Inactiv</option>
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
                                    <th>Nume</th>
                                    <th>Email</th>
                                    <th>Rol</th>
                                    <th>Status</th>
                                    <th>Înregistrat</th>
                                    <th>Acțiuni</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    $query = \App\Models\User::query();
                                    
                                    if(request('role')) {
                                        $query->where('role', request('role'));
                                    }
                                    
                                    if(request('is_active') !== null && request('is_active') !== '') {
                                        $query->where('is_active', request('is_active'));
                                    }
                                    
                                    if(request('search')) {
                                        $query->where(function($q) {
                                            $q->where('name', 'like', '%' . request('search') . '%')
                                              ->orWhere('email', 'like', '%' . request('search') . '%');
                                        });
                                    }
                                    
                                    $users = $query->latest()->paginate(15);
                                ?>
                                
                                <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <tr>
                                        <td><?php echo e($user->id); ?></td>
                                        <td><strong><?php echo e($user->name); ?></strong></td>
                                        <td><?php echo e($user->email); ?></td>
                                        <td>
                                            <span class="badge bg-<?php echo e($user->role === 'superadmin' ? 'danger' : ($user->role === 'admin' ? 'warning' : ($user->role === 'specialist' ? 'info' : 'secondary'))); ?>">
                                                <?php echo e(ucfirst($user->role)); ?>

                                            </span>
                                        </td>
                                        <td>
                                            <span class="badge bg-<?php echo e($user->is_active ? 'success' : 'secondary'); ?>">
                                                <?php echo e($user->is_active ? 'Activ' : 'Inactiv'); ?>

                                            </span>
                                        </td>
                                        <td><?php echo e($user->created_at->format('d M Y')); ?></td>
                                        <td>
                                            <a href="<?php echo e(route('admin.users-crud.edit', $user)); ?>" 
                                               class="btn btn-sm btn-outline-primary" title="Editează">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <?php if($user->role !== 'superadmin' && $user->id !== auth()->id()): ?>
                                                <form action="<?php echo e(route('admin.users-crud.destroy', $user)); ?>" 
                                                      method="POST" class="d-inline" 
                                                      onsubmit="return confirm('Sigur ștergi acest utilizator?')">
                                                    <?php echo csrf_field(); ?>
                                                    <?php echo method_field('DELETE'); ?>
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Șterge">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted py-4">
                                            Nu există utilizatori care să corespundă criteriilor de căutare.
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <?php if($users->hasPages()): ?>
                        <div class="mt-3">
                            <?php echo $users->appends(request()->query())->links(); ?>

                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/admin/users/index.blade.php ENDPATH**/ ?>