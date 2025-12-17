

<?php $__env->startSection('title', 'Manage Bookings'); ?>

<?php $__env->startSection('content'); ?>
    <h1 class="text-3xl font-bold text-primary-700 mb-6">Manage Bookings</h1>

    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script> 
    <?php endif; ?>

    <form method="GET" action="<?php echo e(route('admin.manage-booking')); ?>" class="mb-6 flex space-x-3">
        <input
            type="text"
            name="search"
            placeholder="Search by user or store"
            value="<?php echo e(request('search')); ?>"
            class="border border-gray-300 rounded px-4 py-2 w-64"
        >
        <button type="submit" class="bg-primary-500 text-white px-4 py-2 rounded hover:bg-primary-700">
            Search
        </button>
        <?php if(request('search')): ?>
            <a href="<?php echo e(route('admin.manage-booking')); ?>" class="text-sm text-blue-500 hover:underline self-center">
                Clear
            </a>
        <?php endif; ?>
    </form>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="min-w-full divide-y divide-gray-200 text-sm">
            <thead class="bg-primary-100 text-primary-700">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold">ID</th>
                    <th class="px-4 py-3 text-left font-semibold">User</th>
                    <th class="px-4 py-3 text-left font-semibold">Store</th>
                    <th class="px-4 py-3 text-left font-semibold">Date & Time</th>
                    <th class="px-4 py-3 text-left font-semibold">Status</th>
                    <th class="px-4 py-3 text-left font-semibold">Feedback</th>
                    <th class="px-4 py-3 text-left font-semibold">Services</th>
                    <th class="px-4 py-3 text-left font-semibold">Payment</th>
                    <th class="px-4 py-3 text-left font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50 align-top">
                        <td class="px-4 py-3"><?php echo e($booking->booking_id); ?></td>
                        <td class="px-4 py-3"><?php echo e($booking->user->user_name ?? 'N/A'); ?></td>
                        <td class="px-4 py-3"><?php echo e($booking->store->name ?? 'N/A'); ?></td>
                        <td class="px-4 py-3">
                            <?php echo e(\Carbon\Carbon::parse($booking->datetime)->format('Y-m-d')); ?><br>
                            <span class="text-xs text-gray-500">(<?php echo e($booking->timeStart); ?> to <?php echo e($booking->timeEnd); ?>)</span>
                        </td>
                        <td class="px-4 py-3">
                            <form action="<?php echo e(route('admin.bookings.updateStatus', $booking->booking_id)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <select name="status" onchange="this.form.submit()" class="border rounded px-2 py-1">
                                    <option value="pending" <?php echo e($booking->status === 'pending' ? 'selected' : ''); ?>>Pending</option>
                                    <option value="confirmed" <?php echo e($booking->status === 'confirmed' ? 'selected' : ''); ?>>Confirmed</option>
                                    <option value="completed" <?php echo e($booking->status === 'completed' ? 'selected' : ''); ?>>Completed</option>
                                    <option value="cancelled" <?php echo e($booking->status === 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                                </select>
                            </form>
                        </td>
                        <td class="px-4 py-3">
                            <?php if($booking->feedback): ?>
                                <strong>Rating:</strong> <?php echo e($booking->feedback->rating); ?><br>
                                <strong>Comment:</strong> <?php echo e($booking->feedback->comment); ?><br>
                                <form action="<?php echo e(route('admin.feedbacks.destroy', $booking->feedback->feedback_id)); ?>" method="POST" onsubmit="return confirm('Delete this feedback?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="text-red-600 text-sm mt-1 hover:underline">Delete Feedback</button>
                                </form>
                            <?php else: ?>
                                <em class="text-gray-500">No feedback</em>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <?php $__empty_2 = true; $__currentLoopData = $booking->bookingServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_2 = false; ?>
                                <div>
                                    • <?php echo e($bs->service->name ?? 'Service deleted'); ?> - RM <?php echo e(number_format($bs->price, 2)); ?>

                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_2): ?>
                                <em class="text-gray-500">No services</em>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <?php if($booking->payment): ?>
                                <strong>Method:</strong> <?php echo e($booking->payment->payment_method); ?><br>
                                <strong>Status:</strong>
                                <form action="<?php echo e(route('admin.payments.updateStatus', $booking->payment->payment_id)); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <select name="payment_status" onchange="this.form.submit()" class="border rounded px-2 py-1 mt-1">
                                        <option value="pending" <?php echo e($booking->payment->payment_status == 'Pending' ? 'selected' : ''); ?>>Pending</option>
                                        <option value="completed" <?php echo e($booking->payment->payment_status == 'Completed' ? 'selected' : ''); ?>>Completed</option>
                                        <option value="refund" <?php echo e($booking->payment->payment_status == 'refund' ? 'selected' : ''); ?>>Refund</option>
                                    </select>
                                </form>
                                <strong>Amount:</strong> RM <?php echo e(number_format($booking->payment->amount, 2)); ?>

                            <?php else: ?>
                                <em class="text-gray-500">No payment info</em>
                            <?php endif; ?>
                        </td>
                        <td class="px-4 py-3">
                            <form action="<?php echo e(route('admin.bookings.destroy', $booking->booking_id)); ?>" method="POST" onsubmit="return confirm('Delete this booking?');">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm mt-1">
                                    Delete Booking
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="9" class="text-center text-gray-500 px-4 py-4">No bookings found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CapStone\resources\views/admin/manage-bookings.blade.php ENDPATH**/ ?>