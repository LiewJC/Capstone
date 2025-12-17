<div class="w-64 min-h-screen bg-primary-700 text-white fixed">
    <div class="p-6 flex items-center space-x-3 border-b border-blue-500">
        <i data-feather="shield" class="text-blue-200"></i>
        <span class="text-xl font-bold">Admin Panel</span>
    </div>

    <nav class="mt-6 px-4 space-y-2 text-sm">
        @if(session()->has('admin_email'))
            <div class="text-blue-100 px-4 py-2">
                <strong>Welcome,</strong><br>
                <span class="text-white">{{ session('admin_email') }}</span>
            </div>
        @endif

        <a href="{{ route('admin.dashboard') }}"
           class="block py-2 px-4 rounded nav-item flex items-center space-x-3 hover:bg-primary-500 transition">
            <i data-feather="home" class="w-4 h-4"></i>
            <span>Dashboard</span>
        </a>

        @if(session('role') === 'superadmin')
            <a href="{{ route('admin.manage-admin') }}"
               class="block py-2 px-4 rounded nav-item flex items-center space-x-3 hover:bg-primary-500 transition">
                <i data-feather="user-check" class="w-4 h-4"></i>
                <span>Manage Admins</span>
            </a>
        @endif

        <a href="{{ route('admin.manage-store') }}"
           class="block py-2 px-4 rounded nav-item flex items-center space-x-3 hover:bg-primary-500 transition">
            <i data-feather="map-pin" class="w-4 h-4"></i>
            <span>Manage Stores</span>
        </a>

        <a href="{{ route('admin.manage-booking') }}"
           class="block py-2 px-4 rounded nav-item flex items-center space-x-3 hover:bg-primary-500 transition">
            <i data-feather="calendar" class="w-4 h-4"></i>
            <span>Manage Bookings</span>
        </a>

        <a href="{{ route('admin.service') }}"
           class="block py-2 px-4 rounded nav-item flex items-center space-x-3 hover:bg-primary-500 transition">
            <i data-feather="settings" class="w-4 h-4"></i>
            <span>Manage Services</span>
        </a>

        <a href="{{ route('admin.manage-discount') }}"
           class="block py-2 px-4 rounded nav-item flex items-center space-x-3 hover:bg-primary-500 transition">
            <i data-feather="percent" class="w-4 h-4"></i>
            <span>Manage Discounts</span>
        </a>

        <a href="{{ route('admin.report') }}"
           class="block py-2 px-4 rounded nav-item flex items-center space-x-3 hover:bg-primary-500 transition">
            <i data-feather="bar-chart-2" class="w-4 h-4"></i>
            <span>Reports & Analytics</span>
        </a>

        <a href="{{ route('admin.profile') }}"
           class="block py-2 px-4 rounded nav-item flex items-center space-x-3 hover:bg-primary-500 transition">
            <i data-feather="user" class="w-4 h-4"></i>
            <span>Profile</span>
        </a>

        <form action="{{ route('admin.logout') }}" method="POST" class="mt-4 px-4">
            @csrf
            <button type="submit"
                    class="w-full text-left py-2 px-4 bg-red-600 hover:bg-red-700 rounded text-white flex items-center space-x-2 transition">
                <i data-feather="log-out" class="w-4 h-4"></i>
                <span>Logout</span>
            </button>
        </form>
    </nav>
</div>
