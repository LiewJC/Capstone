

<?php $__env->startSection('title', 'Verify OTP'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-md mx-auto py-10">

    <h1 class="text-3xl font-bold text-primary-700 mb-8 text-center">Email Verification</h1>

    <?php if(session('message')): ?>
        <script>
            alert("<?php echo e(session('message')); ?>");
        </script>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
            <ul class="list-disc list-inside space-y-1">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="POST" action="<?php echo e(route('verify.otp.post')); ?>" class="bg-white shadow rounded-lg p-6 space-y-6">
        <?php echo csrf_field(); ?>

        <div>
            <label for="otp" class="block text-sm font-medium text-gray-700">
                Enter the 6-digit OTP sent to your email:
            </label>
            <input type="text" name="otp" id="otp" required maxlength="6" minlength="6"
                class="mt-1 block w-full border border-gray-300 rounded px-3 py-2 text-center text-lg tracking-widest focus:outline-none focus:ring-2 focus:ring-primary-500 focus:border-primary-500"
                pattern="\d{6}" title="Please enter exactly 6 digits">
        </div>

        <div>
            <button type="submit"
                class="w-full bg-primary-500 hover:bg-primary-600 text-white font-semibold py-2 rounded transition">
                Verify
            </button>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CapStone\resources\views/verify-otp.blade.php ENDPATH**/ ?>