

<?php $__env->startSection('title', 'Edit Service'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col items-center justify-center min-h-full py-12 px-4">

    <h2 class="text-3xl font-bold text-primary-700 mb-8 text-center">Edit Service: <?php echo e($service->name); ?></h2>

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

    <form action="<?php echo e(route('admin.services.update', $service->service_id)); ?>" method="POST" enctype="multipart/form-data" class="max-w-lg w-full bg-white rounded shadow p-6 space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div>
            <label for="name" class="block font-semibold text-gray-700 mb-1">Service Name:</label>
            <input type="text" id="name" name="name" value="<?php echo e(old('name', $service->name)); ?>" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <div>
            <label for="description" class="block font-semibold text-gray-700 mb-1">Description:</label>
            <textarea id="description" name="description" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 h-24"
            ><?php echo e(old('description', $service->description)); ?></textarea>
        </div>

        <div>
            <label for="price" class="block font-semibold text-gray-700 mb-1">Price (RM):</label>
            <input type="number" id="price" name="price" step="0.01" value="<?php echo e(old('price', $service->price)); ?>" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div> 

        <div>
            <label for="duration" class="block font-semibold text-gray-700 mb-1">Duration (in mins e.g., "60 minutes"):</label>
            <input type="text" id="duration" name="duration" value="<?php echo e(old('duration', $service->duration)); ?>" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <div class="py-2 border-t border-gray-200">
            <p class="block font-semibold text-gray-700 mb-2">Current Image:</p>
            <div class="p-2 border border-gray-300 rounded inline-block bg-gray-50">
                <img src="<?php echo e(asset($service->image_url)); ?>" alt="Current Service Image" class="max-w-xs h-auto rounded shadow-md">
            </div>
        </div>

        <div>
            <label for="image" class="block font-semibold text-gray-700 mb-1">Replace Image (optional):</label>
            <input type="file" id="image" name="image" accept="image/*"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 bg-gray-50"
            >
            <p class="text-sm text-gray-500 mt-1">Leave blank to keep the current image.</p>
        </div>

                <div class="flex space-x-4 pt-6">
    <button type="submit"
        class="flex-1 bg-primary-500 hover:bg-primary-700 text-white font-semibold px-6 py-2 rounded transition">
        Update Service
    </button>

    <a href="<?php echo e(route('admin.service')); ?>" class="flex-1">
        <button type="button"
            class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded transition">
            Cancel
        </button>
    </a>
</div>


    </form>
</div>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CapStone\resources\views/admin/edit-service.blade.php ENDPATH**/ ?>