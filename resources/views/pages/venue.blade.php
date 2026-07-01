@extends('layouts.public')

@push('styles')
    <link
        rel="stylesheet"
        href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
        crossorigin=""
    >
@endpush

@section('content')
    @php
        $venueAddress = 'Falside Mill, Kingsbarns, KY16 8PT';
        $encodedVenueAddress = urlencode($venueAddress);
    @endphp

    <section class="space-y-8">
        <div class="shadow-garden rounded-[2rem] border border-white/80 bg-white/92 p-8">
            <p class="text-sage text-xs uppercase tracking-[0.35em]">Venue</p>
            <h1 class="font-display mt-4 text-5xl text-[#4d513f]">{{ $venue['name'] }}</h1>
            <p class="mt-3 text-base text-stone-600">{{ $venueAddress }}</p>
            <p class="mt-4 max-w-4xl text-base leading-7 text-stone-600">
                {{ $venue['name'] }} will host the full celebration. From a get together on Friday night,
                to the ceremony and reception on Saturday, and maybe a farewell brunch on Sunday, the venue is the central hub for our wedding weekend.
            </p>
            <a class="bg-sage-deep mt-6 inline-flex rounded-full px-5 py-3 text-sm font-semibold text-white hover:opacity-95" href="{{ $venue['url'] }}" target="_blank" rel="noreferrer">
                Visit falside.co.uk
            </a>
        </div>

        @php
            $venueImages = [
                asset('images/venue/fall2.jpg'),
                asset('images/venue/fall1.jpg'),
                asset('images/venue/fall3.jpg'),
                asset('images/venue/fall4.jpg'),
            ];
        @endphp

        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @foreach ($venueImages as $image)
                <div class="aspect-[4/3] overflow-hidden rounded-[1.75rem] border border-white/80 bg-white/70 shadow-garden">
                    <img class="h-full w-full object-cover" src="{{ $image }}" alt="{{ $venue['name'] }} photo">
                </div>
            @endforeach
        </div>

        <section class="shadow-garden rounded-[2rem] border border-white/80 bg-white/92 p-6">
            <p class="text-sage text-xs uppercase tracking-[0.35em]">Map</p>
            <div class="mt-4 grid gap-5 lg:grid-cols-[minmax(0,1fr)_18rem] lg:items-start">
                <div
                    id="venue-map"
                    class="h-[26rem] rounded-[1.75rem] border border-[#d8d8ca]"
                    data-venue-name="{{ $venue['name'] }}"
                    data-venue-location="{{ $venueAddress }}"
                    data-venue-url="{{ $venue['url'] }}"
                ></div>
                <aside class="bg-garden-blend rounded-[1.75rem] border border-[#d8d8ca] p-5">
                    <p class="text-taupe text-xs uppercase tracking-[0.35em]">Get Directions</p>
                    <h2 class="font-display mt-3 text-2xl text-[#4d513f]">{{ $venue['name'] }}</h2>
                    <p class="mt-3 text-sm leading-7 text-stone-600">{{ $venueAddress }}</p>
                    <p class="mt-3 text-sm leading-7 text-stone-600">
                        Get directions from your current location using your preferred maps app.
                    </p>
                    <div class="mt-5 flex flex-col gap-3 sm:flex-row">
                        <a
                            class="bg-sage-deep inline-flex justify-center rounded-full px-4 py-2 text-sm font-semibold text-white hover:opacity-95"
                            href="https://www.google.com/maps/dir/?api=1&destination={{ $encodedVenueAddress }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Get directions to Falside Mill using Google Maps"
                        >
                            Google Maps
                        </a>
                        <a
                            class="bg-blush-button inline-flex justify-center rounded-full px-4 py-2 text-sm font-semibold text-[#4d513f] hover:bg-[#f6d6d4]"
                            href="https://maps.apple.com/?daddr={{ $encodedVenueAddress }}"
                            target="_blank"
                            rel="noopener noreferrer"
                            aria-label="Get directions to Falside Mill using Apple Maps"
                        >
                            Apple Maps
                        </a>
                    </div>
                </aside>
            </div>
        </section>
    </section>
@endsection

@push('scripts')
    <script
        src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
        crossorigin=""
    ></script>
    <script>
        document.addEventListener('DOMContentLoaded', async () => {
            const mapElement = document.getElementById('venue-map');

            if (! mapElement || typeof L === 'undefined') {
                return;
            }

            const venueName = mapElement.dataset.venueName ?? '';
            const venueLocation = mapElement.dataset.venueLocation ?? '';
            const venueUrl = mapElement.dataset.venueUrl ?? '#';
            const query = [venueName, venueLocation].filter(Boolean).join(', ');

            const map = L.map(mapElement, {
                scrollWheelZoom: false,
            });

            const streetLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '&copy; OpenStreetMap contributors',
                maxZoom: 19,
            });

            const satelliteLayer = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
                attribution: 'Tiles &copy; Esri',
                maxZoom: 19,
            });

            streetLayer.addTo(map);

            L.control.layers(
                {
                    Streets: streetLayer,
                    Satellite: satelliteLayer,
                },
                {},
                {
                    position: 'topright',
                }
            ).addTo(map);

            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/search?format=jsonv2&limit=1&q=${encodeURIComponent(query)}`, {
                    headers: {
                        Accept: 'application/json',
                    },
                });

                if (! response.ok) {
                    throw new Error('Geocoding request failed.');
                }

                const [result] = await response.json();

                if (! result) {
                    throw new Error('Venue location not found.');
                }

                const latitude = Number.parseFloat(result.lat);
                const longitude = Number.parseFloat(result.lon);

                map.setView([latitude, longitude], 13);

                L.marker([latitude, longitude])
                    .addTo(map)
                    .bindPopup(`
                        <strong>${venueName}</strong><br>
                        ${venueLocation}<br>
                        <a href="${venueUrl}" target="_blank" rel="noreferrer">Visit venue website</a>
                    `)
                    .openPopup();
            } catch (error) {
                map.setView([56.3008, -2.7634], 11);

                L.popup()
                    .setLatLng(map.getCenter())
                    .setContent(`${venueName}<br>${venueLocation}`)
                    .openOn(map);
            }
        });
    </script>
@endpush
