

<?php $__env->startSection('title', 'Add Admin'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col items-center justify-center min-h-full py-12 px-4">

    <h1 class="text-3xl font-bold text-primary-700 mb-8 text-center">Create New Admin</h1>

    <?php if($errors->any()): ?>
        <div class="mb-6 max-w-lg w-full bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('admin.add.post')); ?>" class="max-w-lg w-full bg-white rounded shadow p-6 space-y-6">
        <?php echo csrf_field(); ?>

        <div>
            <label for="admin_name" class="block font-semibold text-gray-700 mb-1">Admin Name:</label>
            <input type="text" id="admin_name" name="admin_name" value="<?php echo e(old('admin_name')); ?>" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <div>
            <label for="email" class="block font-semibold text-gray-700 mb-1">Email:</label>
            <input type="email" id="email" name="email" value="<?php echo e(old('email')); ?>" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <div>
            <label for="admin_password" class="block font-semibold text-gray-700 mb-1">Password:</label>
            <input type="password" id="admin_password" name="admin_password" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <div>
            <label for="admin_password_confirmation" class="block font-semibold text-gray-700 mb-1">Confirm Password:</label>
            <input type="password" id="admin_password_confirmation" name="admin_password_confirmation" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <div>
            <label for="role" class="block font-semibold text-gray-700 mb-1">Role:</label>
            <select id="role" name="role" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
                <option value="">-- Select Role --</option>
                <option value="superadmin" <?php echo e(old('role') == 'superadmin' ? 'selected' : ''); ?>>Super Admin</option>
                <option value="admin" <?php echo e(old('role') == 'admin' ? 'selected' : ''); ?>>Admin</option>
            </select>
        </div>

        <div>
            <label for="phone" class="block font-semibold text-gray-700 mb-1">Phone:</label>
            <input type="text" id="phone" name="phone" value="<?php echo e(old('phone')); ?>"
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <button type="submit"
            class="bg-primary-500 hover:bg-primary-700 text-white font-semibold px-6 py-2 rounded w-full transition">
            Create Admin
        </button>
    </form>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CapStone\resources\views/admin/add-admin.blade.php ENDPATH**/ ?>