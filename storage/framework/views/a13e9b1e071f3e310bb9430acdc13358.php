

<?php $__env->startSection('title', 'Manage Discounts'); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="text-3xl font-bold text-primary-700 mb-6">Manage Discounts</h1>

    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <div class="mb-6">
        <a href="<?php echo e(route('admin.discounts.create')); ?>">
            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                Add New Discount
            </button>
        </a>
    </div>

    <form method="GET" action="<?php echo e(route('admin.manage-discount')); ?>" class="mb-6 flex space-x-3">
        <input type="text" name="search" placeholder="Search by code" value="<?php echo e(request('search')); ?>"
            class="border border-gray-300 rounded px-4 py-2 w-64">
        <button type="submit" class="bg-primary-500 text-white px-4 py-2 rounded hover:bg-primary-700">
            Search
        </button>
        <?php if(request('search')): ?>
            <a href="<?php echo e(route('admin.manage-discount')); ?>" class="text-sm text-blue-500 hover:underline self-center">
                Clear
            </a>
        <?php endif; ?>
    </form>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-primary-100 text-primary-700">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">ID</th>
                    <th class="px-6 py-3 text-left font-semibold">Code</th>
                    <th class="px-6 py-3 text-left font-semibold">Percentage (%)</th>
                    <th class="px-6 py-3 text-left font-semibold">Valid Until</th>
                    <th class="px-6 py-3 text-left font-semibold">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $discounts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $discount): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4"><?php echo e($discount->discount_id); ?></td>
                        <td class="px-6 py-4"><?php echo e($discount->code); ?></td>
                        <td class="px-6 py-4"><?php echo e($discount->percentage); ?></td>
                        <td class="px-6 py-4"><?php echo e($discount->valid_until); ?></td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="<?php echo e(route('admin.discounts.edit', $discount->discount_id)); ?>"
                                class="text-blue-600 hover:underline text-sm"><button type="button"
                                    class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded">
                                    Edit
                                </button></a>

                            <form action="<?php echo e(route('admin.discounts.delete', $discount->discount_id)); ?>" method="POST"
                                class="inline" onsubmit="return confirm('Are you sure you want to delete this discount?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center text-gray-500 px-6 py-4">No discounts found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CapStone\resources\views/admin/manage-discounts.blade.php ENDPATH**/ ?>