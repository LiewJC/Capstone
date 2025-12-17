

<?php $__env->startSection('title', 'Edit Store'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col items-center justify-center min-h-full py-12 px-4">

    <h2 class="text-3xl font-bold text-primary-700 mb-8 text-center">Edit Store</h2>

    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.stores.update', $store->store_id)); ?>" method="POST" class="max-w-4xl w-full bg-white rounded shadow p-6 space-y-6">
        <?php echo csrf_field(); ?>
        <?php echo method_field('PUT'); ?>

        <div>
            <label for="name" class="block font-semibold text-gray-700 mb-1">Store Name:</label>
            <input type="text" name="name" id="name" value="<?php echo e($store->name); ?>" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>

        <div>
            <label for="address" class="block font-semibold text-gray-700 mb-1">Address:</label>
            <input type="text" name="address" id="address" value="<?php echo e($store->address); ?>" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>

        <div>
            <label for="contact_number" class="block font-semibold text-gray-700 mb-1">Contact Number:</label>
            <input type="text" name="contact_number" value="<?php echo e($store->contact_number); ?>" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>

        <div id="map" class="rounded shadow border"></div>

        <div class="flex space-x-4">
            <div class="flex-1">
                <label for="latitude" class="block font-semibold text-gray-700 mb-1">Latitude:</label>
                <input type="text" name="latitude" id="latitude" value="<?php echo e($store->latitude); ?>" required readonly
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 focus:outline-none">
            </div>
            <div class="flex-1">
                <label for="longitude" class="block font-semibold text-gray-700 mb-1">Longitude:</label>
                <input type="text" name="longitude" id="longitude" value="<?php echo e($store->longitude); ?>" required readonly
                    class="w-full border border-gray-300 rounded px-3 py-2 bg-gray-100 focus:outline-none">
            </div>
        </div>

        <div>
            <label for="operation_hours" class="block font-semibold text-gray-700 mb-1">Operation Hours (e.g. 9AM - 6PM):</label>
            <input type="text" name="operation_hours" id="operation_hours" value="<?php echo e($store->operation_hours); ?>" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
        </div>

        <div>
            <label for="status" class="block font-semibold text-gray-700 mb-1">Status:</label>
            <select name="status" id="status" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
                <option value="active" <?php echo e($store->status === 'active' ? 'selected' : ''); ?>>Active</option>
                <option value="inactive" <?php echo e($store->status === 'inactive' ? 'selected' : ''); ?>>Inactive</option>
            </select>
        </div>

        <div>
            <label class="block font-semibold mb-1">Days Open:</label>
            <div class="grid grid-cols-3 gap-2">
                <?php
                    $existingDays = $store->schedules->pluck('day_of_week')->toArray();
                ?>
                <?php $__currentLoopData = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="days[]" value="<?php echo e($day); ?>" <?php echo e(in_array($day, $existingDays) ? 'checked' : ''); ?> class="form-checkbox">
                        <span><?php echo e($day); ?></span>
                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="flex space-x-4">
            <div class="flex-1">
                <label for="start_time" class="block font-semibold mb-1">Start Time:</label>
                <input type="time" name="start_time" id="start_time" value="<?php echo e($store->schedules[0]->start_time ?? ''); ?>" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>

            <div class="flex-1">
                <label for="end_time" class="block font-semibold mb-1">End Time:</label>
                <input type="time" name="end_time" id="end_time" value="<?php echo e($store->schedules[0]->end_time ?? ''); ?>" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500">
            </div>
        </div>

        <div class="flex space-x-4 pt-6">
    <button type="submit"
        class="flex-1 bg-primary-500 hover:bg-primary-700 text-white font-semibold px-6 py-2 rounded transition">
        Update Store
    </button>

    <a href="<?php echo e(route('admin.manage-store')); ?>" class="flex-1">
        <button type="button"
            class="w-full bg-gray-300 hover:bg-gray-400 text-gray-800 font-semibold px-6 py-2 rounded transition">
            Cancel
        </button>
    </a>
</div>

    </form>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<style>
    #map {
        height: 400px;
        width: 100%;
    }
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const defaultLat = <?php echo e($store->latitude ?? 3.1390); ?>;
        const defaultLng = <?php echo e($store->longitude ?? 101.6869); ?>;

        const map = L.map('map').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker = L.marker([defaultLat, defaultLng]).addTo(map);

        map.on('click', function (e) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);

            marker.setLatLng([lat, lng]);
            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&accept-language=en`)
                .then(res => res.json())
                .then(data => {
                    if (data && data.display_name) {
                        document.getElementById('address').value = data.display_name;
                    } else {
                        document.getElementById('address').value = 'Address not found';
                    }
                })
                .catch(() => {
                    document.getElementById('address').value = 'Error fetching address';
                });
        });

        
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\CapStone\resources\views/admin/edit-store.blade.php ENDPATH**/ ?>