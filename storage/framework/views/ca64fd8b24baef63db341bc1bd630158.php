

<?php $__env->startSection('title','Contact - DariaBeauty'); ?>

<?php $__env->startSection('content'); ?>
<!-- Hero Section -->
<section class="py-5" style="background: linear-gradient(135deg, #D4AF37 0%, #8B6914 100%);">
  <div class="container text-center text-white">
    <h1 class="display-5 fw-bold mb-3">Contactează-ne</h1>
    <p class="mb-0">Suntem aici să răspundem la toate întrebările tale</p>
  </div>
</section>

<!-- Contact Form Section -->
<section class="py-5">
  <div class="container">
    <div class="row g-4">
      <!-- Contact Form -->
      <div class="col-lg-7">
        <div class="bg-white rounded-4 shadow-sm p-4 border">
          <h3 class="fw-bold mb-4">Trimite-ne un mesaj</h3>
          
          <?php if(session('success')): ?>
            <div class="alert alert-success rounded-3 d-flex align-items-center">
              <i class="fas fa-check-circle me-2"></i>
              <?php echo e(session('success')); ?>

            </div>
          <?php endif; ?>
          
          <form method="POST" action="<?php echo e(route('contact.store')); ?>" class="row g-3">
            <?php echo csrf_field(); ?>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Nume <span class="text-danger">*</span></label>
              <input type="text" name="name" class="form-control rounded-3" required value="<?php echo e(old('name')); ?>" 
                     placeholder="Nume complet">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Email <span class="text-danger">*</span></label>
              <input type="email" name="email" class="form-control rounded-3" required value="<?php echo e(old('email')); ?>"
                     placeholder="email@exemplu.ro">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Telefon</label>
              <input type="text" name="phone" class="form-control rounded-3" value="<?php echo e(old('phone')); ?>"
                     placeholder="07XX XXX XXX">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Subiect <span class="text-danger">*</span></label>
              <input type="text" name="subject" class="form-control rounded-3" required value="<?php echo e(old('subject')); ?>"
                     placeholder="Subiectul mesajului">
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Preferi un sub-brand?</label>
              <select name="preferred_sub_brand" class="form-select rounded-3">
                <option value="">Indiferent</option>
                <option value="dariaNails">💅 dariaNails - Manichiură & Pedichiură</option>
                <option value="dariaHair">✂️ dariaHair - Coafură & Styling</option>
                <option value="dariaGlow">✨ dariaGlow - Skincare & Makeup</option>
              </select>
            </div>
            <div class="col-12">
              <label class="form-label fw-semibold">Mesaj <span class="text-danger">*</span></label>
              <textarea name="message" class="form-control rounded-3" rows="5" required 
                        placeholder="Scrie aici mesajul tău..."><?php echo e(old('message')); ?></textarea>
            </div>
            <div class="col-12">
              <button type="submit" class="btn btn-lg w-100 rounded-pill text-white" 
                      style="background: linear-gradient(135deg, #D4AF37 0%, #FFD700 100%); border: none; font-weight: 600;">
                <i class="fas fa-paper-plane me-2"></i>Trimite Mesaj
              </button>
            </div>
          </form>
        </div>
      </div>

      <!-- Contact Info -->
      <div class="col-lg-5">
        <div class="bg-white rounded-4 shadow-sm p-4 border h-100">
          <h4 class="fw-bold mb-4">Informații Contact</h4>
          
          <div class="mb-4">
            <div class="d-flex align-items-start mb-3">
              <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                   style="width: 50px; height: 50px; background: rgba(212, 175, 55, 0.1);">
                <i class="fas fa-phone" style="color: #D4AF37; font-size: 1.2rem;"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-1">Telefon</h6>
                <p class="text-muted mb-0">+40 XXX XXX XXX</p>
              </div>
            </div>

            <div class="d-flex align-items-start mb-3">
              <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                   style="width: 50px; height: 50px; background: rgba(212, 175, 55, 0.1);">
                <i class="fas fa-envelope" style="color: #D4AF37; font-size: 1.2rem;"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-1">Email</h6>
                <p class="text-muted mb-0">contact@dariabeauty.ro</p>
              </div>
            </div>

            <div class="d-flex align-items-start mb-3">
              <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                   style="width: 50px; height: 50px; background: rgba(212, 175, 55, 0.1);">
                <i class="fas fa-clock" style="color: #D4AF37; font-size: 1.2rem;"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-1">Program</h6>
                <p class="text-muted mb-0">Luni - Duminică<br>09:00 - 21:00</p>
              </div>
            </div>

            <div class="d-flex align-items-start">
              <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                   style="width: 50px; height: 50px; background: rgba(212, 175, 55, 0.1);">
                <i class="fas fa-map-marker-alt" style="color: #D4AF37; font-size: 1.2rem;"></i>
              </div>
              <div>
                <h6 class="fw-bold mb-1">Zone Acoperite</h6>
                <p class="text-muted mb-0">Toate sectoarele din București</p>
              </div>
            </div>
          </div>

          <hr class="my-4">

          <h6 class="fw-bold mb-3">Urmărește-ne</h6>
          <div class="d-flex gap-3">
            <a href="#" class="btn btn-outline-secondary rounded-circle" style="width: 45px; height: 45px; padding: 0;">
              <i class="fab fa-facebook-f"></i>
            </a>
            <a href="#" class="btn btn-outline-secondary rounded-circle" style="width: 45px; height: 45px; padding: 0;">
              <i class="fab fa-instagram"></i>
            </a>
            <a href="#" class="btn btn-outline-secondary rounded-circle" style="width: 45px; height: 45px; padding: 0;">
              <i class="fab fa-tiktok"></i>
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/contact.blade.php ENDPATH**/ ?>