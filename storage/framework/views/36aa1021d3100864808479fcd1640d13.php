
<?php $__env->startSection('content'); ?>
            <h1 class="text-3xl font-bold text-primary-700 mb-6">User Management</h1>

            <?php if(session('success')): ?>
                <script>alert("<?php echo e(session('success')); ?>");</script>
            <?php endif; ?>

            <form method="GET" action="<?php echo e(route('admin.dashboard')); ?>" class="mb-6 flex space-x-3">
                <input
                    type="text"
                    name="search"
                    placeholder="Search by name or email"
                    value="<?php echo e($search ?? ''); ?>"
                    class="border border-gray-300 rounded px-4 py-2 w-64"
                >
                <button type="submit" class="bg-primary-500 text-white px-4 py-2 rounded hover:bg-primary-700">
                    Search
                </button>
                <?php if(!empty($search)): ?>
                    <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-sm text-blue-500 hover:underline self-center">Clear</a>
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
                            <th class="px-6 py-3 text-left text-sm font-semibold">Vehicle Info</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Status</th>
                            <th class="px-6 py-3 text-left text-sm font-semibold">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4"><?php echo e($user->user_id); ?></td>
                                <td class="px-6 py-4"><?php echo e($user->user_name); ?></td>
                                <td class="px-6 py-4"><?php echo e($user->email); ?></td>
                                <td class="px-6 py-4"><?php echo e($user->phone); ?></td>
                                <td class="px-6 py-4"><?php echo e($user->vehicle_info); ?></td>
                                <td class="px-6 py-4">
                                    <?php if($user->active_status): ?>
                                        <span class="text-green-600 font-medium">Active</span>
                                    <?php else: ?>
                                        <span class="text-red-600 font-medium">Inactive</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 space-x-2">
                                    <form action="<?php echo e(route('admin.users.toggleStatus', $user->user_id ?? $user->id)); ?>" method="POST" class="inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit" class="bg-yellow-500 text-white px-3 py-1 rounded hover:bg-yellow-600 text-sm">
                                            <?php echo e($user->active_status ? 'Deactivate' : 'Activate'); ?>

                                        </button>
                                    </form>

                                    <form action="<?php echo e(route('admin.users.destroy', $user->user_id ?? $user->id)); ?>" method="POST" class="inline" onsubmit="return confirm('Delete this user?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600 text-sm">
                                            Delete
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if($users->isEmpty()): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CapStone\resources\views/admin/dashboard.blade.php ENDPATH**/ ?>