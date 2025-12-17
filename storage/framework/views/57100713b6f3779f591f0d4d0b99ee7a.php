

<?php $__env->startSection('title', 'Add Store'); ?>

<?php $__env->startSection('content'); ?>
<div class="flex flex-col items-center justify-center min-h-full py-12 px-4">

    <h2 class="text-3xl font-bold text-primary-700 mb-8 text-center">Add Store</h2>

    <?php if(session('success')): ?>
        <script>
            alert("<?php echo e(session('success')); ?>");
        </script>
    <?php endif; ?>

    <form action="<?php echo e(route('admin.stores.store')); ?>" method="POST" class="max-w-4xl w-full bg-white rounded shadow p-6 space-y-6">
        <?php echo csrf_field(); ?>

        <div>
            <label for="name" class="block font-semibold text-gray-700 mb-1">Store Name:</label>
            <input type="text" id="name" name="name" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <div>
            <label for="address" class="block font-semibold text-gray-700 mb-1">Address:</label>
            <input type="text" id="address" name="address" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <div>
            <label for="contact_number" class="block font-semibold text-gray-700 mb-1">Contact Number:</label>
            <input type="text" id="contact_number" name="contact_number" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <div id="map" class="mb-4 rounded shadow border"></div>

        <div class="flex space-x-4">
            <div class="flex-1">
                <label for="latitude" class="block font-semibold text-gray-700 mb-1">Latitude (Read-Only):</label>
                <input type="text" name="latitude" id="latitude" required readonly
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 bg-gray-100"
                >
            </div>
            <div class="flex-1">
                <label for="longitude" class="block font-semibold text-gray-700 mb-1">Longitude (Read-Only):</label>
                <input type="text" name="longitude" id="longitude" required readonly
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500 bg-gray-100"
                >
            </div>
        </div>

        <div>
            <label for="operation_hours" class="block font-semibold text-gray-700 mb-1">Operation Hours (e.g. 9AM - 6PM):</label>
            <input type="text" id="operation_hours" name="operation_hours" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
        </div>

        <div>
            <label for="status" class="block font-semibold text-gray-700 mb-1">Status:</label>
            <select name="status" id="status" required
                class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
            >
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>

        <div>
            <label class="font-semibold mb-1 block">Select Days Open:</label>
            <div class="grid grid-cols-3 gap-2">
                <?php $__currentLoopData = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $day): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <label class="inline-flex items-center space-x-2">
                        <input type="checkbox" name="days[]" value="<?php echo e($day); ?>" class="form-checkbox">
                        <span><?php echo e($day); ?></span>
                    </label>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>

        <div class="flex space-x-4">
            <div class="flex-1">
                <label class="block font-semibold mb-1" for="start_time">Start Time:</label>
                <input type="time" name="start_time" id="start_time" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
            </div>

            <div class="flex-1">
                <label class="block font-semibold mb-1" for="end_time">End Time:</label>
                <input type="time" name="end_time" id="end_time" required
                    class="w-full border border-gray-300 rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-primary-500"
                >
            </div>
        </div>

        <button type="submit"
            class="bg-primary-500 hover:bg-primary-700 text-white font-semibold px-6 py-2 rounded w-full transition">
            Add Store
        </button>
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
    document.addEventListener('DOMContentLoaded', function() {
        const defaultLat = 3.1390;
        const defaultLng = 101.6869;

        const map = L.map('map').setView([defaultLat, defaultLng], 13);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        let marker;

        map.on('click', function(e) {
            const lat = e.latlng.lat.toFixed(6);
            const lng = e.latlng.lng.toFixed(6);

            if (marker) {
                marker.setLatLng([lat, lng]);
            } else {
                marker = L.marker([lat, lng]).addTo(map);
            }

            document.getElementById('latitude').value = lat;
            document.getElementById('longitude').value = lng;

            fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&accept-language=en`)
                .then(response => response.json())
                .then(data => {
                    if (data && data.display_name) {
                        document.getElementById('address').value = data.display_name;
                    } else {
                        document.getElementById('address').value = 'Address not found';
                    }
                })
                .catch(error => {
                    console.error('Error fetching address:', error);
                    document.getElementById('address').value = 'Error fetching address';
                });
        });

        map.locate({ setView: true, maxZoom: 15 })
            .on('locationfound', function(e) {
                const lat = e.latitude.toFixed(6);
                const lng = e.longitude.toFixed(6);
                
                if (!marker) {
                    marker = L.marker(e.latlng).addTo(map);
                } else {
                    marker.setLatLng(e.latlng);
                }
                document.getElementById('latitude').value = lat;
                document.getElementById('longitude').value = lng;

                fetch(`https://nominatim.openstreetmap.org/reverse?format=jsonv2&lat=${lat}&lon=${lng}&accept-language=en`)
                    .then(response => response.json())
                    .then(data => {
                        if (data && data.display_name) {
                            document.getElementById('address').value = data.display_name;
                        }
                    });
            })
            .on('locationerror', function() {
                console.log('Location access denied or unavailable. Default location used.');
            });
    });
</script>
<?php $__env->stopPush(); ?>
<?php echo $__env->make('admin.admin', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\xampp1\htdocs\CapStone\resources\views/admin/add-store.blade.php ENDPATH**/ ?>