

<?php $__env->startSection('title', 'Manage Services'); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="text-3xl font-bold text-primary-700 mb-6">Manage Services</h1>

    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <div class="mb-6">
        <a href="<?php echo e(route('admin.services.create')); ?>">
            <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
                Add New Service
            </button>
        </a>
    </div>

    <form method="GET" action="<?php echo e(route('admin.service')); ?>" class="mb-6 flex space-x-3">
        <input type="text" name="search" placeholder="Search by name" value="<?php echo e(request('search')); ?>"
            class="border border-gray-300 rounded px-4 py-2 w-64">
        <button type="submit" class="bg-primary-500 text-white px-4 py-2 rounded hover:bg-primary-700">
            Search
        </button>
        <?php if(request('search')): ?>
            <a href="<?php echo e(route('admin.service')); ?>" class="text-sm text-blue-500 hover:underline self-center">
                Clear
            </a>
        <?php endif; ?>
    </form>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-primary-100 text-primary-700">
                <tr>
                    <th class="px-6 py-3 text-left font-semibold">Image</th>
                    <th class="px-6 py-3 text-left font-semibold">Name</th>
                    <th class="px-6 py-3 text-left font-semibold">Description</th>
                    <th class="px-6 py-3 text-left font-semibold">Price (RM)</th>
                    <th class="px-6 py-3 text-left font-semibold">Duration</th>
                    <th class="px-6 py-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">
                            <img src="<?php echo e(asset($service->image_url)); ?>" alt="Service Image" class="w-24 h-auto rounded border">
                        </td>
                        <td class="px-6 py-4"><?php echo e($service->name); ?></td>
                        <td class="px-6 py-4"><?php echo e($service->description); ?></td>
                        <td class="px-6 py-4">RM <?php echo e(number_format($service->price, 2)); ?></td>
                        <td class="px-6 py-4"><?php echo e($service->duration); ?></td>
                        <td class="px-6 py-4 space-x-2">
                            <a href="<?php echo e(route('admin.services.edit', $service->service_id)); ?>"
                                class="text-blue-600 hover:underline text-sm">
                                <button type="button"
                                    class="bg-blue-500 hover:bg-blue-600 text-white text-sm px-3 py-1 rounded">
                                    Edit
                                </button></a>
                            <form action="<?php echo e(route('admin.services.destroy', $service->service_id)); ?>" method="POST"
                                class="inline" onsubmit="return confirm('Delete this service?');">
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
                        <td colspan="6" class="text-center text-gray-500 px-6 py-4">No services found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\CapStone\resources\views/admin/manage-services.blade.php ENDPATH**/ ?>