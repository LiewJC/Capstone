<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'User Dashboard') - Capstone</title>
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
            background-color: rgba(59, 130, 246, 0.1);
            border-radius: 0.5rem;
        }

    </style>

    @stack('styles')
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="flex flex-col min-h-screen">
        @include('nav')

        <main class="flex-1 p-6 max-w-6xl mx-auto w-full">
            @yield('content')
        </main>

        <footer class="bg-primary-700 text-white text-center py-4">
            <p>&copy; {{ date('Y') }} Capstone. All Rights Reserved.</p>
        </footer>
    </div>

    <script>
        feather.replace();
    </script>

    @stack('scripts')
</body>
</html>
