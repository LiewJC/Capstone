

<?php $__env->startSection('title', 'Edit Discount'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col items-center justify-center min-h-full py-12 px-4">

    <h2 class="text-3xl font-bold text-primary-700 mb-8 text-center">Edit Discount: <?php echo e($discount->code); ?></h2>

    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="mb-6 max-w-lg w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.discounts.update', $discount->discount_id)); ?>" class="max-w-lg w-full bg-white rounded shadow p-6 space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div>
            <label for="code" class="block font-semibold text-gray-700 mb-1">Discount Code:</label>
            <input type="text" id="code" name="code" value="<?php echo e(old('code', $discount->code)); ?>" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <div>
            <label for="percentage" class="block font-semibold text-gray-700 mb-1">Discount Percentage (%):</label>
            <input type="number" id="percentage" name="percentage" value="<?php echo e(old('percentage', $discount->percentage)); ?>" min="1" max="100" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <div>
            <label for="valid_until" class="block font-semibold text-gray-700 mb-1">Valid Until:</label>
            <input type="date" id="valid_until" name="valid_until" value="<?php echo e(old('valid_until', $discount->valid_until->format('Y-m-d'))); ?>" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <div class="flex space-x-4 pt-2">
            <button type="submit"
                class="flex-1 bg-primary-500 hover:bg-primary-700 text-white font-semibold px-6 py-2 rounded transition">
                Update Discount
            </button>
            
            <a href="<?php echo e(route('admin.manage-discount')); ?>" class="flex-1">
                <button type="button"
                    class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded transition">
                    Cancel
                </button>
            </a>
        </div>
    </form>

</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\CapStone\resources\views/admin/edit-discount.blade.php ENDPATH**/ ?>