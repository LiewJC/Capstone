

<?php $__env->startSection('title', 'Select Services'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto py-10">

    <h2 class="text-3xl font-bold text-primary-700 mb-8 text-center">Select Services</h2>

    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

    <?php if(session('message')): ?>
        <script>alert("<?php echo e(session('message')); ?>");</script>
    <?php endif; ?>

    <?php if($services->count()): ?>
        <div class="space-y-6">
            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex flex-col md:flex-row items-center bg-white shadow rounded-lg p-6 gap-6 hover:shadow-lg transition">

                    <img src="<?php echo e($service->image_url ?? 'https://via.placeholder.com/120x80?text=No+Image'); ?>"
                         alt="Service Image"
                         class="w-32 h-24 object-cover rounded-md border border-gray-200">

                    <div class="flex-grow text-center md:text-left">
                        <h3 class="text-lg font-semibold text-gray-800"><?php echo e($service->name); ?></h3>
                        <p class="text-sm text-gray-600 mt-1">
                            RM<?php echo e(number_format($service->price, 2)); ?> | 
                            Duration: <?php echo e($service->duration ?? 'N/A'); ?> mins
                        </p>
                        <p class="text-gray-700 mt-2"><?php echo e($service->description); ?></p>
                    </div>

                    <form method="POST" action="<?php echo e(route('cart.add')); ?>" class="flex-shrink-0 w-full md:w-auto">
                        <?php echo csrf_field(); ?>
                        <input type="hidden" name="service_id" value="<?php echo e($service->service_id); ?>">
                        <button type="submit"
                            class="w-full md:w-auto bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2 rounded transition">
                            Add to Cart
                        </button>
                    </form>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php else: ?>
        <p class="text-center text-gray-600 mt-10">No services available at this store.</p>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\CapStone\resources\views/select-service.blade.php ENDPATH**/ ?>