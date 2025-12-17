

<?php $__env->startSection('content'); ?>
        <h1 class="text-3xl font-bold text-primary-700 mb-6">Admin Management</h1>

        <?php if(session('success')): ?>
            <script>alert("<?php echo e(session('success')); ?>");</script>
        <?php endif; ?>

        <div class="mb-6">
            <a href="<?php echo e(route('admin.add')); ?>">
    <button class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded">
        Add New Admin
    </button>
            </a>
        </div>

        <form method="GET" action="<?php echo e(route('admin.manage-admin')); ?>" class="mb-6 flex space-x-3">
            <input
                type="text"
                name="search"
                placeholder="Search by name or email"
                value="<?php echo e(request('search')); ?>"
                class="border border-gray-300 rounded px-4 py-2 w-64"
            >
            <button type="submit" class="bg-primary-500 text-white px-4 py-2 rounded hover:bg-primary-700">
                Search
            </button>
            <?php if(request('search')): ?>
                <a href="<?php echo e(route('admin.manage-admin')); ?>" class="text-sm text-blue-500 hover:underline self-center">
                    Clear
                </a>
            <?php endif; ?>
        </form>

        <div class="overflow-x-auto bg-white rounded shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-primary-100 text-primary-700">
                    <tr>
                        <th class="px-6 py-3 text-left text-sm font-semibold">ID</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Name</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Email</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Phone</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Role</th>
                        <th class="px-6 py-3 text-left text-sm font-semibold">Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php $__empty_1 = true; $__currentLoopData = $admins; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $admin): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php if($admin->admin_id !== session('admin_id')): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4"><?php echo e($admin->admin_id); ?></td>
                                <td class="px-6 py-4"><?php echo e($admin->admin_name); ?></td>
                                <td class="px-6 py-4"><?php echo e($admin->email); ?></td>
                                <td class="px-6 py-4"><?php echo e($admin->phone); ?></td>
                                <td class="px-6 py-4">
                                    <form action="<?php echo e(route('admin.update.role', $admin->admin_id)); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <select name="role"
                                                onchange="this.form.submit()"
                                                class="border border-gray-300 rounded px-2 py-1 text-sm">
                                            <option value="admin" <?php echo e($admin->role == 'admin' ? 'selected' : ''); ?>>Admin</option>
                                            <option value="superadmin" <?php echo e($admin->role == 'superadmin' ? 'selected' : ''); ?>>Superadmin</option>
                                        </select>
                                    </form>
                                </td>
                                <td class="px-6 py-4">
                                    <form action="<?php echo e(route('admin.delete', $admin->admin_id)); ?>"
                                          method="POST"
                                          onsubmit="return confirm('Are you sure you want to delete this admin?');"
                                          class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit"
                                                class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endif; ?>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 text-center text-gray-500">No admins found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CapStone\resources\views/admin/manage-admins.blade.php ENDPATH**/ ?>