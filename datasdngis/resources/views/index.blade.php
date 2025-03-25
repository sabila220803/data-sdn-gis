<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Informasi SDN di Semarang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#5BABED',
                        secondary: '#e2f9ff',
                        tableBlue: '#F0F9FF'
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="{{ asset('images/Lambang_Kota_Semarang.png') }}">
    <style type="text/tailwindcss">
        @layer utilities {
            .grid-cols-custom {
                grid-template-columns: 60px minmax(300px, 2fr) minmax(200px, 1fr) 150px;
            }

            .grid-cols-footer {
                grid-template-columns: 200px repeat(3, 1fr);
            }
        }
    </style>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <link href="https://fonts.googleapis.com/css2?family=Dancing Script:wght@700&display=swap" rel="stylesheet">
</head>

<body class="min-h-screen flex flex-col bg-white">
    <!-- Header -->
    <header class="bg-primary text-white p-4">
        <div class="max-w-7xl mx-auto flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <button class="p-2 hover:bg-blue-600 rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
                <h1 class="text-xl font-medium">Beranda</h1>
            </div>

            <div class="flex items-center space-x-4">
                @auth
                    <a href="{{ route('admin.dashboard') }}"
                        class="flex items-center space-x-2 hover:bg-blue-600 px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-user-circle"></i>
                        <span>Dashboard</span>
                    </a>
                    <a href="{{ route('logout') }}"
                        class="flex items-center space-x-2 bg-red-500 hover:bg-red-600 px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-sign-out-alt"></i>
                        <span>Logout</span>
                    </a>
                @else
                    <a href="{{ route('login.page') }}"
                        class="flex items-center space-x-2 hover:bg-blue-600 px-4 py-2 rounded-lg transition-colors">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <div class="relative h-[500px] overflow-hidden bg-gray-700">
        <img src="images/semarang-skyline.jpg" alt="Semarang Skyline"
            class="absolute w-full h-full object-cover opacity-60">
        <!-- Overlay gelap -->
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <!-- Gradient overlay untuk efek lebih baik -->
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-transparent"></div>

        <div class="relative z-10 flex flex-col items-center justify-center h-full text-center text-white">
            {{-- <h1 class="text-6xl font-bold mb-4" style="font-family: 'Dancing Script', cursive; text-shadow: 2px 2px 4px rgba(0,0,0,0.5);">Semarang</h1> --}}
            <div class="text-center">
                <h2 class="text-5xl font-bold mb-2 text-shadow-lg">Daftar Informasi</h2>
                <h2 class="text-5xl font-bold text-shadow-lg">SDN di Semarang</h2>
            </div>
        </div>
    </div>

    <!-- Content Area -->
    <main class="flex-1 bg-white p-6">
        <div class="max-w-7xl mx-auto bg-tableBlue rounded-lg shadow-sm">
            <div class="flex justify-end p-4">
                <form action="{{ route('sdn.index') }}" method="GET" class="relative flex items-center">
                    <input type="search" name="search" id="searchInput" placeholder="Cari Data..."
                        value="{{ request('search') }}"
                        class="pl-4 pr-10 py-2 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="absolute right-2 p-2 bg-blue-500 rounded-lg text-white">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                        </svg>
                    </button>
                </form>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <div class="grid grid-cols-custom gap-8 p-4 bg-tableBlue rounded-t-lg font-semibold text-sm">
                    <div class="text-gray-700 pl-2">NO</div>
                    <div class="text-gray-700">NAMA ALAMAT</div>
                    <div class="text-gray-700 text-right pr-16">LINK MAPS</div>
                    <div class="text-gray-700 text-center">FOTO</div>
                </div>

                <!-- Data Cards -->
                <div class="divide-y divide-gray-100">
                    @if ($sdn->isEmpty())
                        <div class="p-4 bg-white">
                            <p class="text-gray-600 text-center">Tidak ada data SDN yang ditemukan.</p>
                        </div>
                    @else
                        @foreach ($sdn as $item)
                            <div class="grid grid-cols-custom gap-8 p-4 bg-white items-start data-card">
                                <div class="text-sm pl-2">
                                    {{ ($sdn->currentPage() - 1) * $sdn->perPage() + $loop->iteration }}</div>
                                <div>
                                    <a href="{{ route('sdn.show', $item->slug) }}"
                                        class="text-blue-500 hover:underline font-medium">{{ $item->nama }}</a>
                                    <div class="text-gray-600 mt-1 text-sm leading-relaxed">
                                        {!! nl2br(e($item->alamat)) !!}
                                    </div>
                                </div>
                                <div class="flex flex-col items-end pr-16">
                                    <a href="https://www.google.com/maps?q={{ $item->latitude }},{{ $item->longitude }}"
                                        target="_blank"
                                        class="inline-flex items-center justify-center px-4 py-1.5 bg-blue-500 text-white rounded-full text-sm hover:bg-blue-600 transition-colors mt-2">
                                        <i class="fas fa-map-marker-alt mr-2"></i> Lihat Peta
                                    </a>
                                    <div class="text-gray-600 text-sm mt-2">jarak: 6 km</div>
                                </div>
                                <div class="flex justify-center">
                                    <div class="border border-gray-200 rounded-lg p-1">
                                        @if ($item->image)
                                            <img src="{{ asset('storage/' . $item->image) }}"
                                                alt="{{ $item->nama }}"
                                                class="w-[120px] h-[80px] rounded-lg object-cover">
                                        @else
                                            <img src="{{ asset('images/default-school.jpg') }}" alt="Default Image"
                                                class="w-[120px] h-[80px] rounded-lg object-cover">
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                <!-- Pagination -->
                <div class="px-4 py-3 bg-white border-t border-gray-200 sm:px-6">
                    {{ $sdn->links() }}
                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8">
        <div class="max-w-7xl mx-auto px-4">
            <div class="grid grid-cols-footer gap-8">
                <div class="flex flex-col gap-4">
                    <img src="images/mudik.jpg" alt="Logo Mudik" class="w-[300px] h-[150px] object-cover">
                    <div class="text-sm space-y-1">
                        <p>Info Mudik 2024 adalah Portal Website</p>
                        <p>Dapatkan informasi seputar mudik tahun 2024</p>
                        <p>Kota Semarang disini.</p>
                    </div>
                </div>

                <div>
                    <h4 class="text-blue-400 mb-4">Link Terkait</h4>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-blue-400">Semarangkota.go.id</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-blue-400">Call Center 112</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-blue-400 mb-4">Kontak Kami</h4>
                    <div class="space-y-2 text-gray-300">
                        <p class="flex items-center gap-2">
                            <i class="fas fa-phone-alt text-blue-400"></i>
                            +62 812-3456-7890
                        </p>
                        <p class="flex items-center gap-2">
                            <i class="fas fa-map-marker-alt text-blue-400"></i>
                            Jl. Pemuda No.148, Kota Semarang
                        </p>
                        <p class="flex items-center gap-2">
                            <i class="fas fa-envelope text-blue-400"></i>
                            carisdn@gmail.com
                        </p>
                    </div>
                </div>

                <div>
                    <h4 class="text-blue-400 mb-4">Map</h4>
                    <div class="w-full h-[150px] rounded-lg overflow-hidden shadow-lg">
                        <iframe
                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126715.84304949311!2d110.33466416073549!3d-7.024552227648474!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e708b4d3f0d024d%3A0x1e0432b9da5cb9f2!2sKota%20Semarang%2C%20Jawa%20Tengah!5e0!3m2!1sid!2sid!4v1742630809946!5m2!1sid!2sid"
                            class="w-full h-full border-0" allowfullscreen="" loading="lazy">
                        </iframe>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-8 border-t border-gray-700 text-center text-gray-400">
                <p>Copyright © 2022 All Rights Reserved by carisah Semarang</p>
            </div>
        </div>
    </footer>
</body>

</html>
