

<?php $__env->startSection('title', 'Rapoarte - Admin DariaBeauty'); ?>

<?php $__env->startSection('content'); ?>
<div class="container" style="padding-top:120px; padding-bottom:60px;">
    <div class="row">
        <div class="col-md-3">
            <?php echo $__env->make('admin.partials.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <div class="col-md-9">
            <h3 class="mb-4">Rapoarte & Analiză</h3>

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Venituri Lunare</h6>
                            <h2 class="mb-0">12,450 RON</h2>
                            <small class="text-success"><i class="fas fa-arrow-up"></i> +15% vs luna trecută</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Programări Luna</h6>
                            <h2 class="mb-0">156</h2>
                            <small class="text-success"><i class="fas fa-arrow-up"></i> +8% vs luna trecută</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-body">
                            <h6 class="text-muted">Rating Mediu</h6>
                            <h2 class="mb-0">4.7 <i class="fas fa-star text-warning"></i></h2>
                            <small class="text-muted">din 89 review-uri</small>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <h5 class="card-title mb-3">Top Specialiști (Luna aceasta)</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Specialist</th>
                                    <th>Brand</th>
                                    <th>Programări</th>
                                    <th>Venit</th>
                                    <th>Rating</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Maria Ionescu</td>
                                    <td><span class="badge" style="background:#E91E63;">Nails</span></td>
                                    <td>45</td>
                                    <td>3,600 RON</td>
                                    <td>4.9 ⭐</td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Gabriela Stan</td>
                                    <td><span class="badge" style="background:#9C27B0;">Hair</span></td>
                                    <td>38</td>
                                    <td>5,700 RON</td>
                                    <td>4.8 ⭐</td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Elena Popa</td>
                                    <td><span class="badge" style="background:#FF9800;">Glow</span></td>
                                    <td>32</td>
                                    <td>2,880 RON</td>
                                    <td>4.7 ⭐</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">Servicii Populare</h5>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Serviciu</th>
                                    <th>Brand</th>
                                    <th>Rezervări</th>
                                    <th>Venit Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Manichiură Gel</td>
                                    <td>dariaNails</td>
                                    <td>67</td>
                                    <td>5,360 RON</td>
                                </tr>
                                <tr>
                                    <td>Vopsit Păr</td>
                                    <td>dariaHair</td>
                                    <td>54</td>
                                    <td>8,100 RON</td>
                                </tr>
                                <tr>
                                    <td>Tratament Facial</td>
                                    <td>dariaGlow</td>
                                    <td>42</td>
                                    <td>4,200 RON</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layout', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\wamp64\www\Daria-Beauty\dariabeauty\resources\views/admin/reports.blade.php ENDPATH**/ ?>