

<?php $__env->startSection('title', 'User Profile'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col items-center justify-center min-h-full py-12">

    <h2 class="text-3xl font-bold text-primary-700 mb-6 text-center">User Profile</h2>

    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

    <div class="max-w-lg w-full bg-white rounded shadow p-6 mb-8">
        <form method="POST" action="<?php echo e(route('profile.update')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>
            <?php echo method_field('PUT'); ?>

            <div>
                <label for="user_name" class="block font-semibold text-gray-700 mb-1 text-left">Name:</label>
                <input type="text" id="user_name" name="user_name"
                    value="<?php echo e(old('user_name', $user->user_name)); ?>" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div>
                <label for="phone" class="block font-semibold text-gray-700 mb-1 text-left">Phone:</label>
                <input type="text" id="phone" name="phone"
                    value="<?php echo e(old('phone', $user->phone)); ?>" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div>
                <label for="email" class="block font-semibold text-gray-700 mb-1 text-left">Email:</label>
                <input type="text" id="email"
                    value="<?php echo e($user->email); ?>" disabled
                    class="w-full bg-gray-100 border border-gray-300 rounded px-3 py-2 cursor-not-allowed">
            </div>

            <div>
                <label for="vehicle_info" class="block font-semibold text-gray-700 mb-1 text-left">Vehicle Info:</label>
                <input type="text" id="vehicle_info" name="vehicle_info"
                    value="<?php echo e(old('vehicle_info', $user->vehicle_info)); ?>" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <button type="submit"
                class="bg-primary-500 hover:bg-primary-700 text-white font-semibold px-4 py-2 rounded transition w-full">
                Update Profile
            </button>
        </form>
    </div>

    <hr class="my-10 border-gray-300 w-full max-w-lg">

    <div class="max-w-lg w-full bg-white rounded shadow p-6">
        <h3 class="text-xl font-bold text-primary-700 mb-6 text-center">Change Password</h3>

        <form method="POST" action="<?php echo e(route('profile.update-password')); ?>" class="space-y-4">
            <?php echo csrf_field(); ?>

            <div>
                <label for="current_password" class="block font-semibold text-gray-700 mb-1 text-left">Current Password:</label>
                <input type="password" id="current_password" name="current_password" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div>
                <label for="new_password" class="block font-semibold text-gray-700 mb-1 text-left">New Password:</label>
                <input type="password" id="new_password" name="new_password" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div>
                <label for="new_password_confirmation" class="block font-semibold text-gray-700 mb-1 text-left">Confirm New Password:</label>
                <input type="password" id="new_password_confirmation" name="new_password_confirmation" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <button type="submit"
                class="bg-primary-500 hover:bg-primary-700 text-white font-semibold px-4 py-2 rounded transition w-full">
                Change Password
            </button>
        </form>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\CapStone\resources\views/profile.blade.php ENDPATH**/ ?>