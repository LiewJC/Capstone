<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo $__env->yieldContent('title', 'Admin Dashboard'); ?> - SparkleSuds</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

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
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 0.5rem;
        }
    </style>

    <?php echo $__env->yieldPushContent('styles'); ?>
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="flex">
        <?php echo $__env->make('admin.admin-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="flex-1 ml-64 p-6">
            <?php echo $__env->yieldContent('content'); ?>
        </div>
    </div>

    <script>
        feather.replace();
    </script>
    <?php echo $__env->yieldPushContent('scripts'); ?>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\CapStone\resources\views/admin/admin.blade.php ENDPATH**/ ?>