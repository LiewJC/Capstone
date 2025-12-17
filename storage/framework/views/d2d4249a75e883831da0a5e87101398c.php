

<?php $__env->startSection('title', 'Add Service'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col items-center justify-center min-h-full py-12 px-4">

    <h2 class="text-3xl font-bold text-primary-700 mb-8 text-center">Add New Service</h2>

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

    <form action="<?php echo e(route('admin.services.store')); ?>" method="POST" enctype="multipart/form-data" 
          class="max-w-lg w-full bg-white rounded shadow p-6 space-y-6">
        <?php echo csrf_field(); ?>

        <div>
            <label for="name" class="block font-semibold text-gray-700 mb-1">Service Name:</label>
            <input type="text" id="name" name="name" placeholder="Service Name" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                value="<?php echo e(old('name')); ?>">
        </div>

        <div>
            <label for="description" class="block font-semibold text-gray-700 mb-1">Description:</label>
            <textarea id="description" name="description" placeholder="Description" required rows="4"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"><?php echo e(old('description')); ?></textarea>
        </div>

        <div>
            <label for="price" class="block font-semibold text-gray-700 mb-1">Price (RM):</label>
            <input type="number" id="price" name="price" placeholder="Price" step="0.01" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                value="<?php echo e(old('price')); ?>">
        </div>

        <div>
            <label for="duration" class="block font-semibold text-gray-700 mb-1">Duration (in mins):</label>
            <input type="text" id="duration" name="duration" placeholder="Duration (e.g. 30 mins)" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                value="<?php echo e(old('duration')); ?>">
        </div>

        <div>
            <label for="image" class="block font-semibold text-gray-700 mb-1">Service Image:</label>
            <input type="file" id="image" name="image" accept="image/*" required
                class="w-full">
        </div>

        <button type="submit"
            class="bg-primary-500 hover:bg-primary-700 text-white font-semibold px-6 py-2 rounded w-full transition">
            Add Service
        </button>
    </form>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\CapStone\resources\views/admin/add-service.blade.php ENDPATH**/ ?>