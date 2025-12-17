<!DOCTYPE html>
<html>

<head>
    <title>Homepage</title>
      <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: {
                            100: '#dbeafe',
                            500: '#3b82f6',
                            700: '#1d4ed8',
                        },
                        secondary: {
                            100: '#e0f2fe',
                            500: '#0ea5e9',
                            700: '#0369a1',
                        }
                    }
                }
            }
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/feather-icons/dist/feather.min.js"></script>

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap');
        body {
            font-family: 'Poppins', sans-serif;
        }
        .nav-item:hover {
            background-color: rgba(59, 130, 246, 0.1);
            border-radius: 0.5rem;
        }
</style>
</head>
<?php echo $__env->make('nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<body>
<section class="bg-gradient-to-r from-blue-500 to-indigo-600 min-h-screen w-full flex flex-col justify-center items-center text-white px-6 py-20">
    <h1 class="text-5xl font-extrabold mb-6 text-center drop-shadow-lg max-w-4xl">Welcome to Capstone Car Wash</h1>
    <p class="text-lg max-w-3xl text-center mb-10 drop-shadow-md">
        Experience the best car wash service with easy booking, professional cleaning, and top-rated customer satisfaction.
    </p>

    <a href="<?php echo e(url('our-service')); ?>" class="bg-white text-blue-600 font-semibold px-8 py-3 rounded-lg shadow-lg hover:bg-blue-50 transition">
        Explore Our Services
    </a>

    <div class="mt-20 grid grid-cols-1 md:grid-cols-3 gap-10 max-w-6xl w-full">
        <div class="bg-white bg-opacity-20 rounded-xl p-6 text-center shadow-lg hover:shadow-2xl transition">
            <div class="mb-4 text-5xl">🚗</div>
            <h3 class="text-2xl font-bold mb-2">Convenient Booking</h3>
            <p>Book your preferred time slot with ease from anywhere.</p>
        </div>
        <div class="bg-white bg-opacity-20 rounded-xl p-6 text-center shadow-lg hover:shadow-2xl transition">
            <div class="mb-4 text-5xl">💦</div>
            <h3 class="text-2xl font-bold mb-2">Quality Cleaning</h3>
            <p>Professional car wash using eco-friendly products and techniques.</p>
        </div>
        <div class="bg-white bg-opacity-20 rounded-xl p-6 text-center shadow-lg hover:shadow-2xl transition">
            <div class="mb-4 text-5xl">⭐</div>
            <h3 class="text-2xl font-bold mb-2">Trusted by Customers</h3>
            <p>Highly rated with verified customer feedback and ratings.</p>
        </div>
    </div>
</section>


<section class="bg-white text-gray-800 py-20 px-6">
    <div class="max-w-7xl mx-auto text-center">
        <h2 class="text-4xl font-extrabold mb-8">Why Choose Capstone Car Wash?</h2>
        <p class="max-w-3xl mx-auto mb-12 text-lg leading-relaxed">
            At Capstone Car Wash, we combine expertise, convenience, and eco-conscious practices to deliver an unmatched car cleaning experience.
            Whether you prefer a quick exterior wash or a complete interior detailing, our professionals are ready to exceed your expectations.
        </p>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 max-w-5xl mx-auto">
            <div>
                <div class="text-blue-600 text-5xl mb-4">⏰</div>
                <h3 class="text-2xl font-semibold mb-2">Flexible Scheduling</h3>
                <p>Choose a time that suits your busy lifestyle with our easy-to-use booking system.</p>
            </div>
            <div>
                <div class="text-blue-600 text-5xl mb-4">🌿</div>
                <h3 class="text-2xl font-semibold mb-2">Eco-Friendly Products</h3>
                <p>We use biodegradable soaps and water-saving methods to protect the environment.</p>
            </div>
            <div>
                <div class="text-blue-600 text-5xl mb-4">👷‍♂️</div>
                <h3 class="text-2xl font-semibold mb-2">Professional Team</h3>
                <p>Our experienced staff take pride in providing the highest quality car care.</p>
            </div>
        </div>
    </div>
</section>

</body>
        <footer class="bg-primary-700 text-white text-center py-4">
            <p>&copy; <?php echo e(date('Y')); ?> Capstone. All Rights Reserved.</p>
        </footer>

</html><?php /**PATH C:\xampp\htdocs\CapStone\resources\views/index.blade.php ENDPATH**/ ?>