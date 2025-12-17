@extends('user')

@section('title', 'Select Store')

@section('content')
<div class="max-w-5xl mx-auto py-10">

    <h2 class="text-3xl font-bold text-primary-700 mb-8 text-center">Select a Store</h2>

    {{-- Success message --}}
    @if(session('success'))
        <script>alert("{{ session('success') }}");</script>
    @endif

    {{-- Selected Store Section --}}
    @if($selectedStore)
        <div class="mb-6 p-4 border border-gray-300 rounded bg-white shadow-sm">
            <h3 class="text-lg font-semibold text-gray-800 mb-1">Your Selected Store:</h3>
            <p class="text-gray-700">{{ $selectedStore->name }}</p>
            <p class="text-gray-600">{{ $selectedStore->address }}</p>
            <small class="text-gray-500">Contact: {{ $selectedStore->contact_number }}</small>
        </div>
    @else
        <p class="text-gray-600 italic mb-6 text-center">You haven’t selected a store yet.</p>
    @endif

    {{-- Map Section --}}
    <div id="map" class="w-full h-[500px] rounded-lg shadow-md mb-6"></div>

    {{-- Hidden Form --}}
    <form id="storeForm" method="POST" action="{{ route('select.store') }}">
        @csrf
        <input type="hidden" name="store_id" id="store_id">
    </form>

    {{-- Proceed Button --}}
    <div class="text-center mt-8">
        <a href="{{ route('select.service') }}"
           class="inline-block bg-primary-500 hover:bg-primary-700 text-white font-semibold px-6 py-2 rounded-lg transition">
            Proceed to Select Service
        </a>
    </div>

</div>

{{-- Leaflet JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

<script>
    const stores = @json($stores);

    const map = L.map('map').setView([3.1390, 101.6869], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; OpenStreetMap contributors'
    }).addTo(map);

    // Try to get user's current location
    navigator.geolocation.getCurrentPosition(function (position) {
        const userLat = position.coords.latitude;
        const userLng = position.coords.longitude;

        const redIcon = new L.Icon({
            iconUrl: 'https://raw.githubusercontent.com/pointhi/leaflet-color-markers/master/img/marker-icon-red.png',
            shadowUrl: 'https://unpkg.com/leaflet@1.7.1/dist/images/marker-shadow.png',
            iconSize: [25, 41],
            iconAnchor: [12, 41],
            popupAnchor: [1, -34],
            shadowSize: [41, 41]
        });

        L.marker([userLat, userLng], { icon: redIcon, title: 'Your Location' })
            .addTo(map)
            .bindPopup('You are here')
            .openPopup();

        map.setView([userLat, userLng], 13);

        stores.forEach(store => {
            const lat = parseFloat(store.latitude);
            const lng = parseFloat(store.longitude);
            const distance = getDistance(userLat, userLng, lat, lng);

            L.marker([lat, lng])
                .addTo(map)
                .bindPopup(`
                    <div class='text-sm'>
                        <strong>${store.name}</strong><br>
                        ${store.address}<br>
                        <i>${distance.toFixed(2)} km away</i><br><br>
                        <button onclick="selectStore(${store.store_id})"
                                class='bg-primary-500 hover:bg-primary-700 text-white font-semibold px-3 py-1 rounded text-xs'>
                            Select This Store
                        </button>
                    </div>
                `);
        });
    }, function () {
        alert('Unable to access your location. Showing all stores without distance.');
        stores.forEach(store => {
            L.marker([parseFloat(store.latitude), parseFloat(store.longitude)])
                .addTo(map)
                .bindPopup(`
                    <div class='text-sm'>
                        <strong>${store.name}</strong><br>
                        ${store.address}<br><br>
                        <button onclick="selectStore(${store.store_id})"
                                class='bg-primary-500 hover:bg-primary-700 text-white font-semibold px-3 py-1 rounded text-xs'>
                            Select This Store
                        </button>
                    </div>
                `);
        });
    });

    function getDistance(lat1, lon1, lat2, lon2) {
        function toRad(x) { return x * Math.PI / 180; }
        const R = 6371;
        const dLat = toRad(lat2 - lat1);
        const dLon = toRad(lon2 - lon1);
        const a = Math.sin(dLat / 2) ** 2 +
                  Math.cos(toRad(lat1)) * Math.cos(toRad(lat2)) *
                  Math.sin(dLon / 2) ** 2;
        const c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
        return R * c;
    }

    function selectStore(storeId) {
        document.getElementById('store_id').value = storeId;
        document.getElementById('storeForm').submit();
    }
</script>
@endsection
