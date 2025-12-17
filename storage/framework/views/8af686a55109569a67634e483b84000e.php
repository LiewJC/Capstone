

<?php $__env->startSection('title', 'Manage Stores'); ?>

<?php $__env->startSection('content'); ?>

    <h1 class="text-3xl font-bold text-primary-700 mb-6">Manage Stores</h1>

    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <!-- Add New Store Button -->
    <div class="mb-6">
        <a href="<?php echo e(route('admin.stores.create')); ?>">
            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                Add New Store
            </button>
        </a>
    </div>

    <!-- Search Form -->
    <form method="GET" action="<?php echo e(route('admin.manage-store')); ?>" class="mb-6 flex space-x-3">
        <input type="text" name="search" placeholder="Search by name or address" value="<?php echo e($search ?? ''); ?>"
            class="border border-gray-300 rounded px-4 py-2 w-64">
        <button type="submit" class="bg-primary-500 text-white px-4 py-2 rounded hover:bg-primary-700">
            Search
        </button>
        <?php if(!empty($search)): ?>
            <a href="<?php echo e(route('admin.manage-store')); ?>" class="text-sm text-blue-500 hover:underline self-center">
                Clear
            </a>
        <?php endif; ?>
    </form>

    <!-- Stores Table -->
    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-primary-100 text-primary-700">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">ID</th>
                    <th class="px-6 py-3 text-left font-semibold">Name</th>
                    <th class="px-6 py-3 text-left font-semibold w-64">Address</th> <!-- set width -->
                    <th class="px-6 py-3 text-left font-semibold">Contact</th>
                    <th class="px-6 py-3 text-left font-semibold">Status</th>
                    <th class="px-6 py-3 text-left font-semibold">Operation Hours</th>
                    <th class="px-6 py-3 text-left font-semibold">Schedules</th>
                    <th class="px-6 py-3 text-left font-semibold w-40">Actions</th> <!-- set fixed width -->
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $stores; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $store): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4"><?php echo e($store->store_id); ?></td>
                        <td class="px-6 py-4"><?php echo e($store->name); ?></td>
                        <td class="px-6 py-4 w-64 whitespace-normal break-words">
                            <?php echo e($store->address); ?>

                        </td>
                        <td class="px-6 py-4"><?php echo e($store->contact_number); ?></td>
                        <td class="px-6 py-4"><?php echo e(ucfirst($store->status)); ?></td>
                        <td class="px-6 py-4"><?php echo e($store->operation_hours); ?></td>
                        <td class="px-6 py-4 space-y-1">
                            <?php $__currentLoopData = \App\Models\Schedule::where('store_id', $store->store_id)->get(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $schedule): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div><?php echo e($schedule->day_of_week); ?> (<?php echo e($schedule->start_time); ?> - <?php echo e($schedule->end_time); ?>)</div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </td>
                        <td class="px-6 py-4 w-40 space-y-2">
                            <a href="<?php echo e(route('admin.stores.edit', $store->store_id)); ?>">
                                <button type="button"
                                    class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded w-full">
                                    Edit
                                </button>
                            </a>
                            <form action="<?php echo e(route('admin.stores.destroy', $store->store_id)); ?>" method="POST"
                                onsubmit="return confirm('Delete this store?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit"
                                    class="bg-red-500 hover:bg-red-600 text-white text-sm px-3 py-1 rounded w-full">
                                    Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="8" class="text-center text-gray-500 px-6 py-4">No stores found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\CapStone\resources\views/admin/manage-stores.blade.php ENDPATH**/ ?>