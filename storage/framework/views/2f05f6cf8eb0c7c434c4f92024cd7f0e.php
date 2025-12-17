<nav class="bg-primary-700 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-3 flex justify-between items-center">
        <a href="<?php echo e(url('/')); ?>" class="flex items-center space-x-2 text-white font-semibold text-lg">
            <i data-feather="droplet" class="text-blue-200"></i>
            <span>Capstone</span>
        </a>

        <ul class="flex space-x-6 items-center text-sm">
            <li><a href="<?php echo e(url('/')); ?>" class="hover:text-blue-200 transition">Home</a></li>
            <li><a href="<?php echo e(url('our-service')); ?>" class="hover:text-blue-200 transition">Our Services</a></li>

            <?php if(Auth::check()): ?>
                <li><a href="<?php echo e(route('booking')); ?>" class="hover:text-blue-200 transition">Book a Service</a></li>
                <li><a href="<?php echo e(route('booking-cart')); ?>" class="hover:text-blue-200 transition">Booking Cart</a></li>
                <li><a href="<?php echo e(route('booking-record')); ?>" class="hover:text-blue-200 transition">My Bookings</a></li>
                <li><a href="<?php echo e(route('profile')); ?>" class="hover:text-blue-200 transition">Profile</a></li>

                <li class="text-blue-100 font-medium">
                    Welcome, <strong><?php echo e(Auth::user()->user_name); ?></strong>
                </li>

                <li>
                    <form method="POST" action="<?php echo e(route('logout')); ?>">
                        <?php echo csrf_field(); ?>
                        <button type="submit"
                            class="ml-2 bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-white text-sm transition">
                            Logout
                        </button>
                    </form>
                </li>
            <?php else: ?>
                <li><a href="<?php echo e(route('login')); ?>" class="hover:text-blue-200 transition">Login</a></li>
                <li><a href="<?php echo e(route('admin.login')); ?>" class="hover:text-blue-200 transition">Admin Login</a></li>
                <li><a href="<?php echo e(route('register')); ?>" class="hover:text-blue-200 transition">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
<?php /**PATH C:\xampp\htdocs\CapStone\resources\views/nav.blade.php ENDPATH**/ ?>