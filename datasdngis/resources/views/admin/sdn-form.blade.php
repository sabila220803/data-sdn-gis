<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ isset($sdn) ? 'Edit' : 'Tambah' }} SDN - Admin Dashboard</title>
    <link rel="icon" href="images/Lambang_Kota_Semarang.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
</head>

<body class="min-h-screen bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900">{{ isset($sdn) ? 'Edit' : 'Tambah' }} Data SDN</h1>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center text-gray-500 hover:text-gray-700">
                    <i class="fas fa-arrow-left mr-2"></i>
                    <span>Kembali</span>
                </a>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <!-- Alert Success/Error -->
            @if (session('success'))
                <div id="alert-success"
                    class="mb-4 bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-check-circle mr-2"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div id="alert-error" class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
                    <div class="flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Form -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <form action="{{ isset($sdn) ? route('sdn.update', $sdn->slug) : route('sdn.store') }}" method="POST"
                    enctype="multipart/form-data" class="p-6 space-y-6">
                    @csrf
                    @if (isset($sdn))
                        @method('PUT')
                    @endif

                    <!-- Nama SDN -->
                    <div>
                        <label for="nama" class="block text-sm font-medium text-gray-700">Nama SDN</label>
                        <input type="text" name="nama" id="nama"
                            value="{{ old('nama', isset($sdn) ? $sdn->nama : '') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-md focus:border-blue-500 focus:ring-blue-500">
                        @error('nama')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div>
                        <label for="alamat" class="block text-sm font-medium text-gray-700">Alamat</label>
                        <textarea name="alamat" id="alamat" rows="3"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-md focus:border-blue-500 focus:ring-blue-500">{{ old('alamat', isset($sdn) ? $sdn->alamat : '') }}</textarea>
                        @error('alamat')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Koordinat -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude</label>
                            <input type="text" name="latitude" id="latitude"
                                value="{{ old('latitude', isset($sdn) ? $sdn->latitude : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-md focus:border-blue-500 focus:ring-blue-500">
                            @error('latitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude</label>
                            <input type="text" name="longitude" id="longitude"
                                value="{{ old('longitude', isset($sdn) ? $sdn->longitude : '') }}"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-md focus:border-blue-500 focus:ring-blue-500">
                            @error('longitude')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Foto -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Foto SDN</label>
                        <div class="mt-2 flex items-center space-x-4">
                            <div class="w-32 h-32 relative">
                                @if (isset($sdn) && $sdn->image)
                                    <img src="{{ asset('storage/' . $sdn->image) }}" alt="{{ $sdn->nama }}"
                                        class="w-full h-full object-cover rounded-lg">
                                @else
                                    <div class="w-full h-full rounded-lg bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-image text-gray-400 text-3xl"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1">
                                <input type="file" name="image" id="image" accept="image/*"
                                    class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-2 text-sm text-gray-500">
                                    PNG, JPG, JPEG hingga 2MB
                                </p>
                                @error('image')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Buttons -->
                    <div class="flex justify-end space-x-3 pt-4">
                        <a href="{{ route('admin.dashboard') }}"
                            class="inline-flex justify-center rounded-md border border-gray-300 bg-white py-2 px-4 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex justify-center rounded-md border border-transparent bg-blue-600 py-2 px-4 text-sm font-medium text-white shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            {{ isset($sdn) ? 'Update' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </main>

    <script>
        // Preview image before upload
        document.getElementById('image').onchange = function(evt) {
            const [file] = this.files;
            if (file) {
                // Find the preview container
                const preview = this.parentElement.previousElementSibling.querySelector('img');
                if (preview) {
                    preview.src = URL.createObjectURL(file);
                } else {
                    // Create new preview if doesn't exist
                    const newPreview = document.createElement('img');
                    newPreview.src = URL.createObjectURL(file);
                    newPreview.className = 'w-full h-full object-cover rounded-lg';
                    this.parentElement.previousElementSibling.innerHTML = '';
                    this.parentElement.previousElementSibling.appendChild(newPreview);
                }
            }
        };

        // Auto hide alerts
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                const alerts = document.querySelectorAll('#alert-success, #alert-error');
                alerts.forEach(alert => {
                    alert.style.transition = 'opacity 0.5s ease-in-out';
                    alert.style.opacity = '0';
                    setTimeout(() => alert.remove(), 500);
                });
            }, 3000);
        });
    </script>
</body>

</html>
