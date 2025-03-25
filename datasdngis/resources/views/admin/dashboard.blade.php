<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - SDN Semarang</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" href="images/Lambang_Kota_Semarang.png">
    <style type="text/tailwindcss">
        @layer utilities {
            .grid-cols-custom {
                grid-template-columns: 60px minmax(200px, 1fr) minmax(150px, 1fr) 150px 120px;
            }
        }
    </style>
    @vite('resources/css/app.css')
    @vite('resources/js/app.js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
</head>

<body class="min-h-screen flex flex-col bg-gray-100">
    <!-- Header -->
    <header class="bg-white shadow-sm">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <h1 class="text-2xl font-semibold text-gray-900">Dashboard Admin</h1>
                <div class="flex items-center space-x-4">
                    <span class="text-gray-500">Welcome, Admin</span>
                    <a href="{{ route('logout') }}" class="text-red-600 hover:text-red-800">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1 py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
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

            <!-- Action Buttons -->
            <div class="mb-6 flex justify-between items-center">
                <a href="{{ route('sdn.create') }}"
                    class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i> Tambah SDN
                </a>
                <form action="{{ route('admin.dashboard') }}" method="GET" class="flex items-center space-x-2">
                    <input type="search" name="search" placeholder="Cari SDN..." value="{{ request('search') }}"
                        class="px-4 py-2 border-2 border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="grid grid-cols-custom gap-4 p-4 bg-gray-50 border-b font-semibold">
                    <div>NO</div>
                    <div>NAMA SEKOLAH</div>
                    <div>ALAMAT</div>
                    <div class="text-center">FOTO</div>
                    <div class="text-center">AKSI</div>
                </div>

                <div class="divide-y divide-gray-200">
                    @if ($sdn->isEmpty())
                        <div class="p-4 text-center text-gray-500">
                            Tidak ada data SDN yang ditemukan.
                        </div>
                    @else
                        @foreach ($sdn as $item)
                            <div class="grid grid-cols-custom gap-4 p-4 items-center hover:bg-gray-50">
                                <div>{{ ($sdn->currentPage() - 1) * $sdn->perPage() + $loop->iteration }}</div>
                                <div>
                                    <h3 class="font-medium text-gray-900">{{ $item->nama }}</h3>
                                    <div class="text-sm text-gray-500">{{ $item->latitude }}, {{ $item->longitude }}
                                    </div>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {!! nl2br(e($item->alamat)) !!}
                                </div>
                                <div class="flex justify-center">
                                    @if ($item->image)
                                        <img src="{{ asset('storage/' . $item->image) }}" alt="{{ $item->nama }}"
                                            class="h-12 w-12 object-cover rounded">
                                    @else
                                        <div class="h-12 w-12 rounded bg-gray-200 flex items-center justify-center">
                                            <i class="fas fa-image text-gray-400"></i>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex justify-center space-x-2">
                                    <a href="{{ route('sdn.edit', $item->slug) }}"
                                        class="bg-yellow-500 text-white p-2 rounded hover:bg-yellow-600">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="deleteSDN('{{ $item->slug }}')"
                                        class="bg-red-500 text-white p-2 rounded hover:bg-red-600">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-4">
                {{ $sdn->links() }}
            </div>
        </div>
    </main>

    <!-- Delete Modal -->
    <div id="deleteModal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-modal md:h-full">
        <div class="relative p-4 w-full max-w-md h-full md:h-auto">
            <!-- Modal content -->
            <div class="relative p-4 text-center bg-white rounded-lg shadow dark:bg-gray-800 sm:p-5">
                <button type="button"
                    class="text-gray-400 absolute top-2.5 right-2.5 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm p-1.5 ml-auto inline-flex items-center"
                    onclick="closeDeleteModal()">
                    <svg aria-hidden="true" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd"
                            d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z"
                            clip-rule="evenodd"></path>
                    </svg>
                    <span class="sr-only">Tutup modal</span>
                </button>
                <svg class="text-gray-400 w-11 h-11 mb-3.5 mx-auto" aria-hidden="true" fill="currentColor"
                    viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd"
                        d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z"
                        clip-rule="evenodd"></path>
                </svg>
                <p class="mb-4 text-gray-500">Apakah Anda yakin ingin menghapus data ini?</p>
                <div class="flex justify-center items-center space-x-4">
                    <button onclick="closeDeleteModal()" type="button"
                        class="py-2 px-3 text-sm font-medium text-gray-500 bg-white rounded-lg border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-primary-300 hover:text-gray-900">
                        Tidak, Batal
                    </button>
                    <button id="confirmDelete" type="button"
                        class="py-2 px-3 text-sm font-medium text-center text-white bg-red-600 rounded-lg hover:bg-red-700 focus:ring-4 focus:outline-none focus:ring-red-300">
                        Ya, Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let deleteSlug = '';
        const $targetEl = document.getElementById('deleteModal');
        const options = {
            onHide: () => {
                console.log('modal is hidden');
            },
            onShow: () => {
                console.log('modal is shown');
            },
            onToggle: () => {
                console.log('modal has been toggled');
            }
        };
        const modal = new Modal($targetEl, options);

        function deleteSDN(slug) {
            deleteSlug = slug;
            modal.show();
        }

        function closeDeleteModal() {
            modal.hide();
        }

        document.getElementById('confirmDelete').addEventListener('click', function() {
            if (deleteSlug) {
                fetch(`/sdn/${deleteSlug}`, {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.message) {
                            modal.hide();
                            window.location.reload();
                        } else {
                            alert('Gagal menghapus data');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat menghapus data');
                    });
            }
        });

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
