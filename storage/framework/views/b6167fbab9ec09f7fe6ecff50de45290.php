

<?php $__env->startSection('title','Galerie - DariaBeauty'); ?>

<?php $__env->startSection('content'); ?>
<style>
  .gallery-hero {
    background: linear-gradient(135deg, #FFFBF7 0%, #F7F3EF 100%);
    padding: 120px 0 60px;
  }
  
  .brand-section {
    padding: 60px 0;
  }
  
  .brand-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 30px;
    padding-bottom: 20px;
    border-bottom: 3px solid;
  }
  
  .brand-header.nails {
    border-color: #E91E63;
  }
  
  .brand-header.hair {
    border-color: #9C27B0;
  }
  
  .brand-header.glow {
    border-color: #FF9800;
  }
  
  .brand-title-group {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  
  .brand-icon-small {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.25rem;
  }
  
  .brand-icon-small.nails {
    background: linear-gradient(135deg, #E91E63, #F06292);
  }
  
  .brand-icon-small.hair {
    background: linear-gradient(135deg, #9C27B0, #BA68C8);
  }
  
  .brand-icon-small.glow {
    background: linear-gradient(135deg, #FF9800, #FFB74D);
  }
  
  .gallery-item {
    position: relative;
    overflow: hidden;
    border-radius: 15px;
    transition: transform 0.3s ease;
  }
  
  .gallery-item:hover {
    transform: scale(1.05);
  }
  
  .gallery-item img {
    width: 100%;
    height: 100%;
    object-fit: cover;
  }
  
  .btn-view-brand {
    padding: 8px 20px;
    border-radius: 25px;
    font-weight: 600;
    color: white;
    border: none;
    transition: all 0.3s ease;
  }
  
  .btn-view-brand.nails {
    background: #E91E63;
  }
  
  .btn-view-brand.hair {
    background: #9C27B0;
  }
  
  .btn-view-brand.glow {
    background: #FF9800;
  }
  
  .btn-view-brand:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    color: white;
  }
</style>

<div class="gallery-hero">
  <div class="container text-center">
    <h1 class="display-5 fw-bold mb-3">Galeria DariaBeauty</h1>
    <p class="text-muted">Descoperă transformările noastre spectaculoase</p>
  </div>
</div>

<div class="container py-5">
  <?php
    $brandData = [
      'nails' => [
        'label' => 'dariaNails',
        'title' => 'Manichiură & Pedichiură',
        'icon' => 'fas fa-hand-sparkles',
        'route' => 'darianails',
        'class' => 'nails'
      ],
      'hair' => [
        'label' => 'dariaHair',
        'title' => 'Coafură & Styling',
        'icon' => 'fas fa-cut',
        'route' => 'dariahair',
        'class' => 'hair'
      ],
      'glow' => [
        'label' => 'dariaGlow',
        'title' => 'Skincare & Makeup',
        'icon' => 'fas fa-spa',
        'route' => 'dariaglow',
        'class' => 'glow'
      ]
    ];
  ?>

  <?php $__currentLoopData = $brandData; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <section class="brand-section">
      <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom" 
           style="border-color: <?php echo e(['nails' => '#E91E63', 'hair' => '#9C27B0', 'glow' => '#FF9800'][$brand['class']]); ?>;">
        <div class="d-flex align-items-center gap-3">
          <div class="brand-icon-small <?php echo e($brand['class']); ?>">
            <i class="<?php echo e($brand['icon']); ?>"></i>
          </div>
          <div>
            <h4 class="mb-0 fw-bold"><?php echo e($brand['label']); ?></h4>
            <small class="text-muted"><?php echo e($brand['title']); ?></small>
          </div>
        </div>
        <a href="<?php echo e(route($brand['route'])); ?>" class="btn btn-view-brand <?php echo e($brand['class']); ?> btn-sm rounded-pill px-4">
          Vezi serviciile →
        </a>
      </div>
      
      <div class="row g-2">
        <?php $__empty_1 = true; $__currentLoopData = $gallery[$key]; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
          <div class="col-6 col-md-4 col-lg-3">
            <div class="gallery-item">
              <div class="ratio ratio-1x1">
                <img src="<?php echo e(asset('storage/'.$item->image_path)); ?>" 
                     class="rounded-3" 
                     alt="<?php echo e($item->caption ?? 'Gallery image'); ?>"
                     loading="lazy"
                     style="transition: transform 0.3s ease;">
              </div>
            </div>
          </div>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
          <div class="col-12">
            <div class="text-center py-4">
              <i class="<?php echo e($brand['icon']); ?> fa-2x text-muted mb-3" style="opacity: 0.3;"></i>
              <p class="text-muted mb-3">Imaginile vor fi disponibile în curând.</p>
              <a href="<?php echo e(route($brand['route'])); ?>" class="btn btn-view-brand <?php echo e($brand['class']); ?> btn-sm rounded-pill px-4">
                Vezi serviciile <?php echo e($brand['label']); ?>

              </a>
            </div>
          </div>
        <?php endif; ?>
      </div>
    </section>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<section class="py-5 bg-light">
  <div class="container text-center">
    <h3 class="fw-bold mb-3">Inspirată de ceea ce ai văzut?</h3>
    <p class="text-muted mb-4">Alege serviciul perfect și rezervă acum</p>
    <div class="d-flex justify-content-center gap-3 flex-wrap">
      <a href="<?php echo e(route('booking.landing')); ?>" class="btn btn-primary btn-lg px-5 rounded-pill">
        <i class="fas fa-calendar-check me-2"></i>Programează-te
      </a>
      <a href="<?php echo e(route('services')); ?>" class="btn btn-outline-secondary btn-lg px-5 rounded-pill">
        <i class="fas fa-list me-2"></i>Toate serviciile
      </a>
    </div>
  </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/gallery.blade.php ENDPATH**/ ?>