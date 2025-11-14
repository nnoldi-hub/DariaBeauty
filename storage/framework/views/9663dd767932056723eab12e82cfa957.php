

<?php $__env->startSection('title','Servicii - DariaBeauty'); ?>

<?php $__env->startSection('content'); ?>
<style>
  .services-hero {
    background: linear-gradient(135deg, #FFFBF7 0%, #F7F3EF 100%);
    padding: 120px 0 60px;
  }
  
  .brand-section {
    padding: 60px 0;
  }
  
  .brand-header {
    margin-bottom: 40px;
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
  
  .brand-title {
    display: flex;
    align-items: center;
    gap: 15px;
  }
  
  .brand-icon {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
  }
  
  .brand-icon.nails {
    background: linear-gradient(135deg, #E91E63, #F06292);
  }
  
  .brand-icon.hair {
    background: linear-gradient(135deg, #9C27B0, #BA68C8);
  }
  
  .brand-icon.glow {
    background: linear-gradient(135deg, #FF9800, #FFB74D);
  }
  
  .category-title {
    color: #6B7280;
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 20px;
    text-transform: uppercase;
    letter-spacing: 1px;
  }
  
  .service-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: all 0.3s ease;
    height: 100%;
    border-top: 4px solid transparent;
  }
  
  .service-card.nails {
    border-top-color: #E91E63;
  }
  
  .service-card.hair {
    border-top-color: #9C27B0;
  }
  
  .service-card.glow {
    border-top-color: #FF9800;
  }
  
  .service-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0,0,0,0.15);
  }
  
  .service-card-img {
    height: 200px;
    object-fit: cover;
    width: 100%;
  }
  
  .service-card-body {
    padding: 20px;
  }
  
  .service-name {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 10px;
    color: #2C1810;
  }
  
  .service-specialist {
    color: #6B7280;
    font-size: 0.9rem;
    margin-bottom: 15px;
  }
  
  .service-price {
    font-size: 1.5rem;
    font-weight: 700;
    color: #2C1810;
  }
  
  .btn-explore {
    padding: 10px 25px;
    border-radius: 25px;
    font-weight: 600;
    border: none;
    color: white;
    transition: all 0.3s ease;
  }
  
  .btn-explore.nails {
    background: #E91E63;
  }
  
  .btn-explore.hair {
    background: #9C27B0;
  }
  
  .btn-explore.glow {
    background: #FF9800;
  }
  
  .btn-explore:hover {
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.3);
  }
</style>

<div class="services-hero">
  <div class="container text-center">
    <h1 class="display-4 fw-bold mb-4">Toate Serviciile Noastre</h1>
    <p class="lead text-muted">Descoperă gama completă de servicii premium de frumusețe</p>
  </div>
</div>

<div class="container py-5">
  <?php $__currentLoopData = $servicesByBrand; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand => $groups): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
    <?php
      $brandClass = strtolower(str_replace('daria', '', $brand));
      $brandIcons = [
        'nails' => 'fas fa-hand-sparkles',
        'hair' => 'fas fa-cut',
        'glow' => 'fas fa-spa'
      ];
      $brandTitles = [
        'nails' => 'dariaNails - Manichiură & Pedichiură',
        'hair' => 'dariaHair - Coafură & Styling',
        'glow' => 'dariaGlow - Skincare & Makeup'
      ];
      $brandRoutes = [
        'nails' => 'darianails',
        'hair' => 'dariahair',
        'glow' => 'dariaglow'
      ];
    ?>
    
    <section class="brand-section">
      <div class="brand-header <?php echo e($brandClass); ?>">
        <div class="brand-title">
          <div class="brand-icon <?php echo e($brandClass); ?>">
            <i class="<?php echo e($brandIcons[$brandClass] ?? 'fas fa-star'); ?>"></i>
          </div>
          <div>
            <h2 class="mb-1"><?php echo e($brandTitles[$brandClass] ?? $brand); ?></h2>
            <a href="<?php echo e(route($brandRoutes[$brandClass] ?? 'services')); ?>" class="btn btn-explore <?php echo e($brandClass); ?> btn-sm">
              <i class="fas fa-arrow-right me-2"></i>Explorează <?php echo e($brand); ?>

            </a>
          </div>
        </div>
      </div>
      
      <?php $__empty_1 = true; $__currentLoopData = $groups; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $services): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
        <div class="mb-5">
          <h5 class="category-title"><?php echo e($category); ?></h5>
          <div class="row g-4">
            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-md-4 col-lg-3">
              <div class="service-card <?php echo e($brandClass); ?>">
                <?php if($service->image): ?>
                  <img src="<?php echo e(asset('storage/'.$service->image)); ?>" class="service-card-img" alt="<?php echo e($service->name); ?>">
                <?php else: ?>
                  <div class="service-card-img bg-light d-flex align-items-center justify-content-center">
                    <i class="<?php echo e($brandIcons[$brandClass] ?? 'fas fa-star'); ?> fa-3x text-muted" style="opacity: 0.3;"></i>
                  </div>
                <?php endif; ?>
                <div class="service-card-body">
                  <h6 class="service-name"><?php echo e($service->name); ?></h6>
                  <div class="service-specialist">
                    <i class="fas fa-user me-1"></i><?php echo e($service->specialist->name ?? 'Specialist'); ?>

                  </div>
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="service-price"><?php echo e($service->formatted_price); ?></div>
                    <?php if($service->duration): ?>
                    <div class="text-muted small">
                      <i class="far fa-clock me-1"></i><?php echo e($service->formatted_duration); ?>

                    </div>
                    <?php endif; ?>
                  </div>
                </div>
              </div>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
          </div>
        </div>
      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
        <div class="text-center py-5">
          <i class="<?php echo e($brandIcons[$brandClass] ?? 'fas fa-star'); ?> fa-3x text-muted mb-3"></i>
          <p class="text-muted">Nu sunt servicii disponibile momentan pentru <?php echo e($brand); ?>.</p>
        </div>
      <?php endif; ?>
    </section>
  <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
</div>

<section class="py-5 bg-light">
  <div class="container text-center">
    <h2 class="mb-4">Gata să te programezi?</h2>
    <p class="lead text-muted mb-4">Alege serviciul perfect pentru tine și rezervă acum</p>
    <a href="<?php echo e(route('booking.landing')); ?>" class="btn btn-primary btn-lg">
      <i class="fas fa-calendar-check me-2"></i>Programează-te acum
    </a>
  </div>
</section>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/services.blade.php ENDPATH**/ ?>