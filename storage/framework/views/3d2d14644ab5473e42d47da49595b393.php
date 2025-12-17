<!DOCTYPE html>
<html lang="en">

<head>
    <title>Homepage - Capstone Car Wash</title>
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

<body class="bg-gray-50 text-gray-900">
    <?php echo $__env->make('nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<section class="bg-gradient-to-r from-blue-500 to-indigo-600 min-h-screen w-full flex flex-col justify-center items-center text-white px-6 py-20">
        <h1 class="text-5xl font-extrabold mb-6 drop-shadow-lg">Welcome to Capstone Car Wash</h1>
        <p class="max-w-3xl text-lg mb-12 drop-shadow-md">
            Your one-stop solution for professional car washing, detailing, and more. Quality service, affordable prices, and customer satisfaction guaranteed.
        </p>
        

        <div class="grid grid-cols-1 md:grid-cols-3 gap-10 max-w-5xl w-full text-gray-100">
            <div class="bg-white bg-opacity-20 rounded-xl p-6 shadow-lg hover:shadow-2xl transition">
                <h3 class="text-2xl font-bold mb-3">Engine Cleaning</h3>
                <p class="text-sm leading-relaxed">
                    Thorough engine cleaning to remove dirt and grime, improving engine efficiency and extending its lifespan.
                </p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-xl p-6 shadow-lg hover:shadow-2xl transition">
                <h3 class="text-2xl font-bold mb-3">Waxing</h3>
                <p class="text-sm leading-relaxed">
                    Protect your vehicle’s paint and enhance its shine with our premium waxing service.
                </p>
            </div>
            <div class="bg-white bg-opacity-20 rounded-xl p-6 shadow-lg hover:shadow-2xl transition">
                <h3 class="text-2xl font-bold mb-3">Detailing</h3>
                <p class="text-sm leading-relaxed">
                    Deep cleaning inside and out for a showroom finish — including upholstery, windows, and tires.
                </p>
            </div>
        </div>
    </section>

    <!-- Services Section -->
    <main class="max-w-7xl mx-auto px-6 py-16">
        <h2 class="text-4xl font-extrabold mb-10 text-center text-gray-800">🚗 Our Car Wash Services - Comments & Ratings</h2>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <?php
                    $feedbacksWithComments = $service->bookingServices
                        ->map(fn($bs) => $bs->booking->feedback ?? null)
                        ->filter(fn($fb) => !empty($fb?->comment));

                    $validRatings = $feedbacksWithComments->filter(fn($fb) => is_numeric($fb->rating));

                    $avgRating = $validRatings->count() > 0
                        ? $validRatings->avg('rating')
                        : null;

                    $latestComments = $feedbacksWithComments->sortByDesc('created_at')->take(2);
                ?>

                <?php if($feedbacksWithComments->isNotEmpty()): ?>
                    <div class="bg-white rounded-lg p-8 shadow-md hover:shadow-xl transition">
                        <h3 class="text-2xl font-semibold mb-2 text-blue-600"><?php echo e($service->name); ?></h3>
                        <p class="text-gray-700 mb-1"><?php echo e($service->description); ?></p>
                        <p class="text-green-600 font-bold mb-4">Price: RM<?php echo e(number_format($service->price, 2)); ?></p>

                        <?php if($avgRating): ?>
                            <p class="text-yellow-500 font-semibold mb-4">⭐ Average Rating: <?php echo e(number_format($avgRating, 1)); ?>/5</p>
                        <?php endif; ?>

                        <h4 class="text-lg font-semibold mb-3">User Comments:</h4>
                        <?php $__currentLoopData = $latestComments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="bg-gray-100 rounded-md p-4 mb-4 border-l-4 border-blue-600 italic">
                                <p class="font-semibold"><?php echo e($comment->user->user_name ?? 'Anonymous'); ?></p>
                                <p class="text-yellow-400">⭐ <?php echo e($comment->rating); ?>/5</p>
                                <p class="text-gray-800">"<?php echo e($comment->comment); ?>"</p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                <?php endif; ?>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    </main>

    <footer class="bg-primary-700 text-white text-center py-6 mt-20">
        <p>&copy; <?php echo e(date('Y')); ?> Capstone. All Rights Reserved.</p>
    </footer>

    <script>
        feather.replace()
    </script>
</body>

</html>
<?php /**PATH C:\xampp1\htdocs\CapStone\resources\views/our-service.blade.php ENDPATH**/ ?>