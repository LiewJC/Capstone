<nav class="bg-primary-700 text-white shadow-md">
    <div class="max-w-7xl mx-auto px-6 py-3 flex justify-between items-center">
        <a href="{{ url('/') }}" class="flex items-center space-x-2 text-white font-semibold text-lg">
            <i data-feather="droplet" class="text-blue-200"></i>
            <span>Capstone</span>
        </a>

        <ul class="flex space-x-6 items-center text-sm">
            <li><a href="{{ url('/') }}" class="hover:text-blue-200 transition">Home</a></li>
            <li><a href="{{ url('our-service') }}" class="hover:text-blue-200 transition">Our Services</a></li>

            @if(Auth::check())
                <li><a href="{{ route('booking') }}" class="hover:text-blue-200 transition">Book a Service</a></li>
                <li><a href="{{ route('booking-cart') }}" class="hover:text-blue-200 transition">Booking Cart</a></li>
                <li><a href="{{ route('booking-record') }}" class="hover:text-blue-200 transition">My Bookings</a></li>
                <li><a href="{{ route('profile') }}" class="hover:text-blue-200 transition">Profile</a></li>

                <li class="text-blue-100 font-medium">
                    Welcome, <strong>{{ Auth::user()->user_name }}</strong>
                </li>

                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="ml-2 bg-red-500 hover:bg-red-600 px-3 py-1 rounded text-white text-sm transition">
                            Logout
                        </button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}" class="hover:text-blue-200 transition">Login</a></li>
                <li><a href="{{ route('admin.login') }}" class="hover:text-blue-200 transition">Admin Login</a></li>
                <li><a href="{{ route('register') }}" class="hover:text-blue-200 transition">Register</a></li>
            @endif
        </ul>
    </div>
</nav>
