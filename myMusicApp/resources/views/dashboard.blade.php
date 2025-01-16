{{-- <x-app-layout>
    <x-slot name="header">
         <p style="font-size: 48px; font-weight: bold; background: linear-gradient(to right, #800080, #FFD700, #00FF00, #0000FF); -webkit-background-clip: text; color: transparent;">
             Your<span style="background: linear-gradient(to right, #0000FF, #00FF00, #FFD700, #800080); -webkit-background-clip: text; color: transparent;">MUSIC</span>
         </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                   <h2 class="text-2xl font-bold mb-4">Artists</h2>
                   <div class="artist-carousel">
                        <div class="artist-container">
                            @foreach($artists as $artist)
                                 <div class="artist-card">
                                    <div class="artist-image">
                                        <img src="https://placekitten.com/200/200" alt="{{$artist->name}}" class="rounded-full">
                                     </div>
                                     <div class="artist-name">
                                          <p class="text-center mt-2 font-semibold">{{$artist->name}}</p>
                                     </div>

                                </div>
                            @endforeach
                        </div>
                    </div>

                     <h2 class="text-2xl font-bold mb-4 mt-8">Albums</h2>
                     <div class="album-carousel">
                        <div class="album-container">
                             @foreach($albums as $album)
                                 <div class="album-card">
                                    <div class="album-image">
                                        <img src="https://placekitten.com/200/200" alt="{{$album->name}}" class="rounded">
                                     </div>
                                      <div class="album-name">
                                          <p class="text-center mt-2 font-semibold">{{$album->name}}</p>
                                     </div>
                                </div>
                             @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>

.artist-carousel, .album-carousel {
  overflow-x: auto;
  white-space: nowrap;
    padding-bottom: 20px;

}
.artist-container, .album-container {
  display: inline-flex;
}


.artist-card, .album-card {
    margin-right: 20px;
   text-align: center;
    min-width: 150px; /* Adjust as needed */
    max-width: 200px;
}
.artist-image img , .album-image img {
   width: 150px;
   height:150px;
   object-fit: cover;
    border-radius: 10px;
}
/* Add these styles for the sliding animation */
.artist-carousel, .album-carousel {
    scroll-behavior: smooth;
}
</style> --}}

<x-app-layout>
    <x-slot name="header">
        {{-- <p class="text-4xl font-bold bg-gradient-to-r from-purple-600 via-yellow-400 to-blue-500 text-transparent bg-clip-text">
            Your<span class="bg-gradient-to-r from-blue-500 via-yellow-400 to-purple-600 text-transparent bg-clip-text">MUSIC</span>
        </p> --}}
        <p style="font-size: 48px; font-weight: bold; background: linear-gradient(to right, #800080, #FFD700, #00FF00, #0000FF); -webkit-background-clip: text; color: transparent;">
            Your<span style="background: linear-gradient(to right, #0000FF, #00FF00, #FFD700, #800080); -webkit-background-clip: text; color: transparent;">MUSIC</span>
        </p>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Artists Section -->
                    <div class="mb-8">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold">Artists</h2>
                            <button class="text-blue-500 hover:text-blue-700" onclick="scrollCarousel('artist-carousel', 'left')">
                                <i class="bi bi-chevron-left text-xl"></i>
                            </button>
                            <button class="text-blue-500 hover:text-blue-700" onclick="scrollCarousel('artist-carousel', 'right')">
                                <i class="bi bi-chevron-right text-xl"></i>
                            </button>
                        </div>
                        <div class="artist-carousel" id="artist-carousel">
                            <div class="artist-container">
                                @foreach($artists as $artist)
                                    <div class="artist-card hover:scale-105 transition-transform duration-200">
                                        <div class="artist-image">
                                            @if($artist->image)
                                                <img src="{{ asset('storage/' . $artist->image) }}" alt="{{$artist->name}}" class="rounded-full">
                                            @else
                                                <div class="w-[150px] h-[150px] rounded-full bg-gradient-to-r from-purple-400 to-blue-500 flex items-center justify-center">
                                                    <span class="text-3xl text-white">{{ strtoupper(substr($artist->name, 0, 1)) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="artist-name">
                                            <p class="text-center mt-2 font-semibold truncate">{{$artist->name}}</p>
                                            <p class="text-center text-sm text-gray-500">{{ $artist->songs->count() }} songs</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <!-- Albums Section -->
                    <div>
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-bold">Albums</h2>
                            <button class="text-blue-500 hover:text-blue-700" onclick="scrollCarousel('album-carousel', 'left')">
                                <i class="bi bi-chevron-left text-xl"></i>
                            </button>
                            <button class="text-blue-500 hover:text-blue-700" onclick="scrollCarousel('album-carousel', 'right')">
                                <i class="bi bi-chevron-right text-xl"></i>
                            </button>
                        </div>
                        <div class="album-carousel" id="album-carousel">
                            <div class="album-container">
                                @foreach($albums as $album)
                                    <div class="album-card hover:scale-105 transition-transform duration-200">
                                        <div class="album-image">
                                            @if($album->cover_image)
                                                <img src="{{ asset('storage/' . $album->cover_image) }}" alt="{{$album->title}}" class="rounded">
                                            @else
                                                <div class="w-[150px] h-[150px] rounded bg-gradient-to-r from-indigo-400 to-purple-500 flex items-center justify-center">
                                                    <span class="text-3xl text-white">{{ strtoupper(substr($album->title, 0, 1)) }}</span>
                                                </div>
                                            @endif
                                        </div>
                                        <div class="album-name">
                                            <p class="text-center mt-2 font-semibold truncate">{{$album->title}}</p>
                                            <p class="text-center text-sm text-gray-500">{{ $album->songs->count() }} songs</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
    function scrollCarousel(carouselId, direction) {
        const carousel = document.getElementById(carouselId);
        const scrollAmount = 300; // Adjust this value as needed
        
        if (direction === 'left') {
            carousel.scrollLeft -= scrollAmount;
        } else {
            carousel.scrollLeft += scrollAmount;
        }
    }
    </script>
    @endpush

</x-app-layout>

<style>
.artist-carousel, .album-carousel {
    overflow-x: auto;
    white-space: nowrap;
    padding-bottom: 20px;
    scrollbar-width: none; /* Firefox */
    -ms-overflow-style: none; /* IE and Edge */
}

.artist-carousel::-webkit-scrollbar, .album-carousel::-webkit-scrollbar {
    display: none; /* Chrome, Safari, Opera */
}

.artist-container, .album-container {
    display: inline-flex;
    gap: 20px;
    padding: 10px;
}

.artist-card, .album-card {
    text-align: center;
    min-width: 150px;
    max-width: 200px;
}

.artist-image img, .album-image img {
    width: 150px;
    height: 150px;
    object-fit: cover;
    border-radius: 10px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
}

/* Smooth scrolling */
.artist-carousel, .album-carousel {
    scroll-behavior: smooth;
}

/* Hover effects */
.artist-card:hover, .album-card:hover {
    transform: translateY(-5px);
    transition: transform 0.2s ease-in-out;
}
</style>