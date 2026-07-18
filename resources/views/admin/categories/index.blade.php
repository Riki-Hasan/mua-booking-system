<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        .back-btn-content { display: flex; align-items: center; gap: 0.5rem; }
        .animate-pop { animation: pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
        @keyframes pop { 0% { transform: scale(0.95); } 100% { transform: scale(1); } }
        
        @media (max-width: 768px) {
            .mobile-card-grid { display: block; }
            .desktop-table { display: none; }
            .back-btn-content { flex-direction: column; gap: 0.1rem; }
        }

        /* ======================================================== */
        /* 🚨 REVISI 1: OVERRIDES KETERBACAAN FORM PADA TABLET LAMA */
        /* ======================================================== */
        input[type="text"], input[type="number"], select, textarea {
            background-color: #ffffff !important;
            color: #1f2937 !important;
            font-size: 14px !important;
            font-weight: 700 !important;
            border: 2px solid #d1d5db !important;
        }
        label, .block.text-\[9px\] {
            font-size: 12px !important;
            font-weight: 900 !important;
            color: #374151 !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            margin-bottom: 6px !important;
        }

        /* ======================================================== */
        /* 🚨 REVISI 2: TOMBOL AKSI TABEL DIPERBESAR & DIBERI JARAK  */
        /* ======================================================== */
        table td button, table td a, .desktop-table td button, .desktop-table td a {
            padding: 12px 16px !important;
            font-size: 14px !important;
            margin: 0 6px !important;
            display: inline-flex !important;
            align-items: center !important;
            justify-content: center !important;
            border-radius: 12px !important;
        }
        table td i, .desktop-table td i {
            font-size: 15px !important;
        }

        /* ======================================================== */
        /* 🚨 REVISI BARU: PAKSA SEMUA BG OVERLAY MODAL RAMAH TABLET */
        /* ======================================================== */
        /* Mengganti sistem flex modern ke posisi mutlak RGBA agar dijamin pas di tengah layar tablet */
        #modalEditBundling, #modalDelete, #modalEdit, #modalStatus, #modalLimit, #modalEditPortfolio, #modalEditKebaya {
            position: fixed !important;
            top: 0 !important;
            left: 0 !important;
            right: 0 !important;
            bottom: 0 !important;
            width: 100% !important;
            height: 100% !important;
            background-color: rgba(15, 23, 42, 0.8) !important; /* RGBA Klasik Tahan Tablet */
            align-items: center !important;
            justify-content: center !important;
            z-index: 2147483647 !important; /* Z-Index Maksimal Browser */
        }
    </style>

    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-pink-600 leading-tight tracking-tighter italic">Manajemen Studio</h2>
            <a href="{{ route('dashboard') }}" class="flex items-center gap-2 bg-white text-gray-500 px-4 py-3 rounded-xl shadow-sm border border-gray-100 hover:text-pink-600 transition-all active:scale-95">
                <i class="fa-solid fa-arrow-left mr-1"></i> Back
            </a>
        </div>
    </x-slot>

    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>

    {{-- TAB PERSISTENCE --}}
    <div class="py-6 md:py-12" x-data="{ tab: '{{ session('current_tab', 'paket') }}' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            {{-- Bagian Navigasi --}}
            @include('admin.categories.partials._tabs_navigation')
            
            {{-- Tab Paket --}}
            <div id="tab-pane-paket" class="tab-pane-native" style="display: {{ session('current_tab', 'paket') === 'paket' ? 'block' : 'none' }}">
                @include('admin.categories.partials._tab_paket')
            </div>

            {{-- Tab Konten Makeup --}}
            <div id="tab-pane-konten" class="tab-pane-native" style="display: {{ session('current_tab', 'paket') === 'konten' ? 'block' : 'none' }}">
                @include('admin.categories.partials._tab_konten')
            </div>

            {{-- Tab Kebaya --}}
            <div id="tab-pane-kebaya" class="tab-pane-native" style="display: {{ session('current_tab', 'paket') === 'kebaya' ? 'block' : 'none' }}">
                @include('admin.categories.partials._tab_kebaya')
            </div>

            {{-- Tab Bundling --}}
            <div id="tab-pane-bundling" class="tab-pane-native" style="display: {{ session('current_tab', 'paket') === 'bundling' ? 'block' : 'none' }}">
                @include('admin.categories.partials._tab_bundling')
            </div>

        </div>
    </div>

    {{-- Bagian Modal --}}
    @include('admin.categories.partials._modals')

    {{-- Bagian Script --}}
    @include('admin.categories.partials._scripts')

    <!-- ======================================================== -->
    <!-- JAVASCRIPT BYPASS TOTAL SYSTEM (MODAL & TAB NAVIGATION)  -->
    <!-- ======================================================== -->
    <script>
        // --- 1. OVERRIDE FUNGSI MODAL BAWAAN (_scripts.blade.php) ---
        // Kita timpa fungsi bawaan dengan logic native JS murni agar kebal mampet di tablet

        function showStatusModal(type, title, msg) {
            const icon = document.getElementById('statusIcon');
            const titleEl = document.getElementById('statusTitle');
            const msgEl = document.getElementById('statusMsg');
            const modal = document.getElementById('modalStatus');

            if (type === 'success') {
                icon.className = "w-20 h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6";
                icon.innerHTML = '<i class="fa-solid fa-check text-3xl"></i>';
            } else {
                icon.className = "w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6";
                icon.innerHTML = '<i class="fa-solid fa-circle-exclamation text-3xl"></i>';
            }

            titleEl.innerText = title;
            msgEl.innerText = msg;
            
            // Perbaikan: Paksa modal status membesar proporsional & tepat di tengah layar tablet
            if (modal) {
                modal.classList.remove('hidden');
                modal.style.setProperty('display', 'flex', 'important');
                
                // Cari kotak putih di dalamnya, paksa max-width pas dan tampil di tengah
                const innerBox = modal.querySelector('.bg-white');
                if (innerBox) {
                    innerBox.style.setProperty('max-width', '380px', 'important');
                    innerBox.style.setProperty('width', '90%', 'important');
                    innerBox.style.setProperty('display', 'block', 'important');
                }
            }
        }

        // Fungsi Universal Buka Modal secara Native (Menimpa classList.replace yang bikin error di tablet)
        function bukaModalNative(idModal) {
            var modal = document.getElementById(idModal);
            if (modal) {
                modal.classList.remove('hidden');
                modal.style.setProperty('display', 'flex', 'important');
                
                // Memastikan box putih di dalamnya tampil 100% solid
                var innerBox = modal.querySelector('.bg-white');
                if (innerBox) {
                    innerBox.style.setProperty('display', 'block', 'important');
                }
            }
        }

        // Fungsi Universal Tutup Modal secara Native
        function closeModal(id) {
            var modal = document.getElementById(id);
            if (modal) {
                modal.classList.add('hidden');
                modal.style.setProperty('display', 'none', 'important');
            }
        }
        function closeEditBundlingModal() { closeModal('modalEditBundling'); }

        // Inject interseptor pada fungsi pemanggil modal bawaan agar otomatis lewat jalur native JS
        var originalConfirmDelete = confirmDelete;
        confirmDelete = function(id, name, type) {
            document.getElementById('deletePackageName').innerText = name;
            let baseUrl = "";
            if(type === 'category') baseUrl = "/admin/categories/";
            else if(type === 'portfolio') baseUrl = "/admin/portfolios/";
            else if(type === 'bundling') baseUrl = "/admin/bundlings/";
            else if(type === 'kebaya') baseUrl = "/admin/kebayas/";
            
            currentDeleteAction = baseUrl + id;
            bukaModalNative('modalDelete'); // Panggil via native
        };

        var originalOpenEditModal = openEditModal;
        openEditModal = function(category) {
            document.getElementById('edit_name').value = category.name;
            document.getElementById('edit_price').value = category.base_price;
            document.getElementById('edit_duration').value = category.duration_minutes;
            document.getElementById('editForm').action = `/admin/categories/${category.id}`;
            bukaModalNative('modalEdit'); // Panggil via native
        };

        var originalOpenEditBundlingModal = openEditBundlingModal;
        openEditBundlingModal = function(bundling) {
            document.getElementById('edit_bundling_subject').value = bundling.subject;
            document.getElementById('edit_bundling_price').value = bundling.price;
            document.getElementById('edit_bundling_short').value = bundling.short_description;
            document.getElementById('edit_bundling_desc').value = bundling.description;
            document.getElementById('edit_bundling_duration').value = bundling.duration_minutes;
            document.getElementById('edit_bundling_target').value = bundling.target_person_count;
            document.getElementById('editBundlingForm').action = `/admin/bundlings/${bundling.id}`;
            bukaModalNative('modalEditBundling'); // Panggil via native
        };

        var originalOpenEditPortfolioModal = openEditPortfolioModal;
        openEditPortfolioModal = function(id, categoryId) {
            document.getElementById('edit_portfolio_category_id').value = categoryId;
            document.getElementById('editPortfolioForm').action = `/admin/portfolios/${id}`;
            const fileInput = document.querySelector('#editPortfolioForm input[type="file"]');
            if (fileInput) fileInput.value = '';
            bukaModalNative('modalEditPortfolio'); // Panggil via native
        };

        var originalOpenEditKebayaModal = openEditKebayaModal;
        openEditKebayaModal = function(id, name) {
            document.getElementById('edit_kebaya_name').value = name;
            document.getElementById('editKebayaForm').action = `/admin/kebayas/${id}`;
            const fileInput = document.querySelector('#editKebayaForm input[type="file"]');
            if (fileInput) fileInput.value = '';
            bukaModalNative('modalEditKebaya'); // Panggil via native
        };


        // --- 2. BYPASS NAVIGATION TAB VIA NATIVE ---
        document.addEventListener('DOMContentLoaded', function() {
            var tabButtons = document.querySelectorAll('button[\\@click*="tab ="]');
            tabButtons.forEach(function(btn) {
                var clickAttr = btn.getAttribute('@click') || btn.getAttribute('x-on:click');
                if (clickAttr) {
                    var match = clickAttr.match(/tab\s*=\s*'([^']+)'/);
                    if (match && match[1]) {
                        var targetTab = match[1];
                        btn.setAttribute('id', 'btn-tab-nav-' + targetTab);
                        btn.addEventListener('click', function(e) {
                            e.preventDefault();
                            gantiTabSecaraNative(targetTab);
                        });
                    }
                }
            });
        });

        function gantiTabSecaraNative(namaTab) {
            var panes = document.querySelectorAll('.tab-pane-native');
            panes.forEach(function(pane) {
                pane.style.setProperty('display', 'none', 'important');
            });

            var activePane = document.getElementById('tab-pane-' + namaTab);
            if (activePane) {
                activePane.style.setProperty('display', 'block', 'important');
            }

            var tabButtons = document.querySelectorAll('button[id^="btn-tab-nav-"]');
            tabButtons.forEach(function(btn) {
                btn.classList.remove('bg-pink-600', 'text-white', 'shadow-lg', 'border-pink-600');
                btn.classList.add('bg-white', 'text-gray-400', 'border-gray-100');
            });

            var activeBtn = document.getElementById('btn-tab-nav-' + namaTab);
            if (activeBtn) {
                activeBtn.classList.remove('bg-white', 'text-gray-400', 'border-gray-100');
                activeBtn.classList.add('bg-pink-600', 'text-white', 'shadow-lg', 'border-pink-600');
            }

            var alpineEl = document.querySelector('[x-data]');
            if (alpineEl && alpineEl.__x && alpineEl.__x.$data) {
                alpineEl.__x.$data.tab = namaTab;
            }
        }
    </script>
</x-app-layout>