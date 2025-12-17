

<?php $__env->startSection('title', 'Your Cart'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-5xl mx-auto py-10">

    <h2 class="text-3xl font-bold text-primary-700 mb-8 text-center">Your Cart</h2>

    <?php if(session('message')): ?>
        <script>alert("<?php echo e(session('message')); ?>");</script>
    <?php endif; ?>

    <?php if($cartItems->count()): ?>
        <div class="space-y-6">
            <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="flex items-center bg-white shadow rounded-lg p-4 gap-6 hover:shadow-lg transition">
                    <img src="<?php echo e($item->service->image_url ?? 'https://via.placeholder.com/100x80?text=No+Image'); ?>"
                         alt="Service Image"
                         class="w-24 h-20 object-cover rounded-md border border-gray-200">

                    <div class="flex-grow">
                        <h3 class="text-lg font-semibold text-gray-800"><?php echo e($item->service->name); ?></h3>
                        <p class="text-gray-700 mt-1 font-semibold">RM<?php echo e(number_format($item->total_price, 2)); ?></p>
                    </div>

                    <form action="<?php echo e(route('cart.remove', $item->cart_id)); ?>" method="POST"
                          onsubmit="return confirm('Remove this item?');" class="flex-shrink-0">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('DELETE'); ?>
                        <button type="submit"
                                class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded transition">
                            Remove
                        </button>
                    </form>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>

        <div class="mt-8 text-right text-xl font-bold text-gray-900">
            Total: RM<?php echo e(number_format($total, 2)); ?>

        </div>

        <div class="mt-6 text-center">
            <a href="<?php echo e(route('booking.select-date')); ?>">
                <button type="button"
                        class="bg-primary-700 hover:bg-primary-800 text-white font-semibold px-6 py-3 rounded transition">
                    Select Date & Time
                </button>
            </a>
        </div>
    <?php else: ?>
        <p class="text-center text-gray-600 mt-10">Your cart is empty.</p>
    <?php endif; ?>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CapStone\resources\views/booking-cart.blade.php ENDPATH**/ ?>