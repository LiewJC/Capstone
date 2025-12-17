

<?php $__env->startSection('title', 'Booking History'); ?>

<?php $__env->startSection('content'); ?>
<div class="max-w-6xl mx-auto py-10">

    <h2 class="text-3xl font-bold text-primary-700 mb-8 text-center">Booking History</h2>

    <?php if(session('success')): ?>
        <script>alert("<?php echo e(session('success')); ?>");</script>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <script>alert("<?php echo e(session('error')); ?>");</script>
    <?php endif; ?>

    <div class="overflow-x-auto bg-white shadow rounded-lg">
        <table class="min-w-full border border-gray-200 divide-y divide-gray-200">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Booking Info</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Services</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Status</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Feedback</th>
                    <th class="px-4 py-3 text-left text-sm font-semibold text-gray-700">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <?php $__empty_1 = true; $__currentLoopData = $bookings; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $booking): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr class="hover:bg-gray-50">
                        <!-- Booking Info -->
                        <td class="px-4 py-4 text-sm text-gray-700 align-top">
                            <p><strong>Booking ID:</strong> <?php echo e($booking->booking_id); ?></p>
                            <p><strong>Store:</strong> <?php echo e($booking->store->name ?? 'N/A'); ?></p>
                            <p><strong>Date:</strong> <?php echo e(\Carbon\Carbon::parse($booking->datetime)->format('d M Y')); ?></p>
                            <p><strong>Time:</strong> <?php echo e($booking->timeStart); ?> - <?php echo e($booking->timeEnd); ?></p>
                        </td>

                        <!-- Services -->
                        <td class="px-4 py-4 text-sm text-gray-700 align-top">
                            <ul class="list-disc list-inside space-y-1">
                                <?php $__currentLoopData = $booking->bookingServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $bs): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <li><?php echo e($bs->service->name ?? 'Service Deleted'); ?> - RM<?php echo e(number_format($bs->price, 2)); ?></li>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </ul>
                        </td>

                        <!-- Status -->
                        <td class="px-4 py-4 text-sm text-gray-700 align-top">
                            <span class="inline-block px-3 py-1 rounded-full text-xs font-semibold
                                <?php if($booking->status === 'completed'): ?> bg-green-100 text-green-700
                                <?php elseif($booking->status === 'pending'): ?> bg-yellow-100 text-yellow-700
                                <?php elseif($booking->status === 'confirmed'): ?> bg-blue-100 text-blue-700
                                <?php elseif($booking->status === 'cancelled'): ?> bg-red-100 text-red-700
                                <?php else: ?> bg-gray-100 text-gray-700
                                <?php endif; ?>">
                                <?php echo e(ucfirst($booking->status)); ?>

                            </span>
                        </td>

                        <!-- Feedback -->
                        <td class="px-4 py-4 text-sm text-gray-700 align-top">
                            <?php if($booking->status === 'completed'): ?>
                                <?php if($booking->feedback): ?>
                                    <div class="feedback-section">
                                        <div class="text-yellow-500 font-bold">⭐ <?php echo e($booking->feedback->rating); ?>/5</div>
                                        <p class="mt-1 text-gray-600"><?php echo e($booking->feedback->comment); ?></p>
                                    </div>
                                <?php else: ?>
                                    <form action="<?php echo e(route('feedback.store')); ?>" method="POST" class="space-y-2">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="booking_id" value="<?php echo e($booking->booking_id); ?>">

                                        <div>
                                            <label for="rating-<?php echo e($booking->booking_id); ?>" class="text-sm font-medium text-gray-700">Rating</label>
                                            <select name="rating" id="rating-<?php echo e($booking->booking_id); ?>" required
                                                class="w-full border border-gray-300 rounded px-2 py-1 focus:ring-primary-500 focus:border-primary-500">
                                                <option value="">-- Select Rating --</option>
                                                <?php for($i = 5; $i >= 1; $i--): ?>
                                                    <option value="<?php echo e($i); ?>"><?php echo e($i); ?></option>
                                                <?php endfor; ?>
                                            </select>
                                        </div>

                                        <div>
                                            <label for="comment-<?php echo e($booking->booking_id); ?>" class="text-sm font-medium text-gray-700">Comment</label>
                                            <textarea name="comment" id="comment-<?php echo e($booking->booking_id); ?>" rows="3" required
                                                class="w-full border border-gray-300 rounded px-2 py-1 focus:ring-primary-500 focus:border-primary-500"></textarea>
                                        </div>

                                        <button type="submit"
                                            class="bg-primary-500 hover:bg-primary-600 text-white font-medium px-3 py-1.5 rounded transition">
                                            Submit
                                        </button>
                                    </form>
                                <?php endif; ?>
                            <?php else: ?>
                                <span class="text-gray-400">N/A</span>
                            <?php endif; ?>
                        </td>

                        <!-- Action -->
                        <td class="px-4 py-4 text-sm text-gray-700 align-top">
                            <?php if(in_array($booking->status, ['pending', 'confirmed'])): ?>
                                <form action="<?php echo e(route('booking.cancel', $booking->booking_id)); ?>" method="POST"
                                    onsubmit="return confirm('Are you sure you want to cancel this booking?');">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('PATCH'); ?>
                                    <button type="submit"
                                        class="bg-red-500 hover:bg-red-600 text-white font-medium px-3 py-1.5 rounded transition">
                                        Cancel
                                    </button>
                                </form>
                            <?php else: ?>
                                <span class="text-gray-400">-</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">No bookings found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('user', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp\htdocs\CapStone\resources\views/booking-record.blade.php ENDPATH**/ ?>