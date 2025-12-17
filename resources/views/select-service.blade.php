@extends('user')

@section('title', 'Select Services')

@section('content')
<div class="max-w-5xl mx-auto py-10">

    <h2 class="text-3xl font-bold text-primary-700 mb-8 text-center">Select Services</h2>

    @if(session('error'))
        <script>alert("{{ session('error') }}");</script>
    @endif

    @if(session('message'))
        <script>alert("{{ session('message') }}");</script>
    @endif

    @if($services->count())
        <div class="space-y-6">
            @foreach($services as $service)
                <div class="flex flex-col md:flex-row items-center bg-white shadow rounded-lg p-6 gap-6 hover:shadow-lg transition">

                    <img src="{{ $service->image_url ?? 'https://via.placeholder.com/120x80?text=No+Image' }}"
                         alt="Service Image"
                         class="w-32 h-24 object-cover rounded-md border border-gray-200">

                    <div class="flex-grow text-center md:text-left">
                        <h3 class="text-lg font-semibold text-gray-800">{{ $service->name }}</h3>
                        <p class="text-sm text-gray-600 mt-1">
                            RM{{ number_format($service->price, 2) }} | 
                            Duration: {{ $service->duration ?? 'N/A' }} mins
                        </p>
                        <p class="text-gray-700 mt-2">{{ $service->description }}</p>
                    </div>

                    <form method="POST" action="{{ route('cart.add') }}" class="flex-shrink-0 w-full md:w-auto">
                        @csrf
                        <input type="hidden" name="service_id" value="{{ $service->service_id }}">
                        <button type="submit"
                            class="w-full md:w-auto bg-green-500 hover:bg-green-600 text-white font-semibold px-5 py-2 rounded transition">
                            Add to Cart
                        </button>
                    </form>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-center text-gray-600 mt-10">No services available at this store.</p>
    @endif

</div>
@endsection
