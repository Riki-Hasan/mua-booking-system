<style>
    [x-cloak] { display: none !important; }
    .animate-pop { animation: pop 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    @keyframes pop { 0% { transform: scale(0.9); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
    .sortable-chosen { background: #fdf2f8; cursor: grabbing; }
    .handle { cursor: grab; user-select: none; }
    #sortable-table tr { user-select: none; }
</style>

<script>
    // --- HELPER: MODAL STATUS ---
    function showStatusModal(type, title, msg) {
        const icon = document.getElementById('statusIcon');
        const titleEl = document.getElementById('statusTitle');
        const msgEl = document.getElementById('statusMsg');

        if (type === 'success') {
            icon.className = "w-20 h-20 bg-emerald-100 text-emerald-500 rounded-full flex items-center justify-center mx-auto mb-6";
            icon.innerHTML = '<i class="fa-solid fa-check text-3xl"></i>';
        } else {
            icon.className = "w-20 h-20 bg-rose-100 text-rose-500 rounded-full flex items-center justify-center mx-auto mb-6";
            icon.innerHTML = '<i class="fa-solid fa-circle-exclamation text-3xl"></i>';
        }

        titleEl.innerText = title;
        msgEl.innerText = msg;
        document.getElementById('modalStatus').classList.replace('hidden', 'flex');
    }

    function closeModal(id) {
        document.getElementById(id).classList.replace('flex', 'hidden');
    }

    // --- AJAX: DELETE (UNIVERSAL) ---
    let currentDeleteAction = "";
    function confirmDelete(id, name, type) {
        document.getElementById('deletePackageName').innerText = name;
        let baseUrl = "";
        if(type === 'category') baseUrl = "/admin/categories/";
        else if(type === 'portfolio') baseUrl = "/admin/portfolios/";
        else if(type === 'bundling') baseUrl = "/admin/bundlings/";
        else if(type === 'kebaya') baseUrl = "/admin/kebayas/";
        
        currentDeleteAction = baseUrl + id;
        document.getElementById('modalDelete').classList.replace('hidden', 'flex');
    }

    async function submitDeleteAjax() {
        try {
            const response = await fetch(currentDeleteAction, {
                method: 'DELETE',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const result = await response.json();
            if (response.ok) {
                closeModal('modalDelete');
                showStatusModal('success', 'Terhapus!', result.message);
                setTimeout(() => window.location.reload(), 1200);
            }
        } catch (error) {
            showStatusModal('error', 'Gagal!', 'Data tidak bisa dihapus.');
        }
    }

    // --- AJAX: EDIT PAKET ---
    function openEditModal(category) {
        document.getElementById('edit_name').value = category.name;
        document.getElementById('edit_price').value = category.base_price;
        document.getElementById('edit_duration').value = category.duration_minutes;
        document.getElementById('editForm').action = `/admin/categories/${category.id}`;
        document.getElementById('modalEdit').classList.replace('hidden', 'flex');
    }

    async function submitEditAjax() {
        const form = document.getElementById('editForm');
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            const result = await response.json();
            if (response.ok) {
                closeModal('modalEdit');
                showStatusModal('success', 'Tersimpan!', result.message);
                setTimeout(() => window.location.reload(), 1200);
            } else {
                showStatusModal('error', 'Gagal!', result.message || 'Cek kembali data Anda.');
            }
        } catch (error) {
            showStatusModal('error', 'Error!', 'Terjadi kesalahan komunikasi dengan server.');
        }
    }

    // --- AJAX: EDIT BUNDLING ---
    function openEditBundlingModal(bundling) {
        document.getElementById('edit_bundling_subject').value = bundling.subject;
        document.getElementById('edit_bundling_price').value = bundling.price;
        document.getElementById('edit_bundling_short').value = bundling.short_description;
        document.getElementById('edit_bundling_desc').value = bundling.description;
        document.getElementById('edit_bundling_duration').value = bundling.duration_minutes;
        document.getElementById('edit_bundling_target').value = bundling.target_person_count;
        document.getElementById('editBundlingForm').action = `/admin/bundlings/${bundling.id}`;
        document.getElementById('modalEditBundling').classList.replace('hidden', 'flex');
    }

    async function submitEditBundlingAjax() {
        const form = document.getElementById('editBundlingForm');
        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: formData
            });
            const result = await response.json();
            if (response.ok) {
                closeModal('modalEditBundling');
                showStatusModal('success', 'Tersimpan!', result.message);
                setTimeout(() => window.location.reload(), 1200);
            } else {
                showStatusModal('error', 'Gagal!', result.message || 'Cek kembali data bundling.');
            }
        } catch (error) {
            showStatusModal('error', 'Error!', 'Gagal menghubungi server.');
        }
    }

    function closeEditBundlingModal() {
        document.getElementById('modalEditBundling').classList.replace('flex', 'hidden');
    }

    // --- URUTAN PAKET ---
    async function saveNewOrder() {
        const rows = document.querySelectorAll('#sortable-table tr');
        const ids = Array.from(rows).map(row => row.dataset.id);
        try {
            const response = await fetch("{{ route('admin.categories.update_order') }}", {
                method: 'POST',
                headers: { 
                    'Content-Type': 'application/json', 
                    'X-CSRF-TOKEN': '{{ csrf_token() }}', 
                    'X-Requested-With': 'XMLHttpRequest' 
                },
                body: JSON.stringify({ ids: ids })
            });
            const result = await response.json();
            if (response.ok) {
                showStatusModal('success', 'Berhasil!', 'Urutan paket rias telah diperbarui.');
                setTimeout(() => window.location.reload(), 1200);
            }
        } catch (error) {
            showStatusModal('error', 'Gagal!', 'Urutan tidak bisa disimpan.');
        }
    }

    // --- AJAX: EDIT PORTFOLIO ---
    function openEditPortfolioModal(id, categoryId) {
        document.getElementById('edit_portfolio_category_id').value = categoryId;
        document.getElementById('editPortfolioForm').action = `/admin/portfolios/${id}`;
        
        const fileInput = document.querySelector('#editPortfolioForm input[type="file"]');
        if (fileInput) fileInput.value = '';

        document.getElementById('modalEditPortfolio').classList.replace('hidden', 'flex');
    }

    async function submitEditPortfolioAjax() {
        const form = document.getElementById('editPortfolioForm');
        const formData = new FormData(form);
        formData.append('_method', 'PUT');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const result = await response.json();
            if (response.ok) {
                closeModal('modalEditPortfolio');
                showStatusModal('success', 'Tersimpan!', result.message);
                setTimeout(() => window.location.reload(), 1200);
            } else {
                showStatusModal('error', 'Gagal!', result.message || 'Gagal merubah data.');
            }
        } catch (error) { 
            showStatusModal('error', 'Error!', 'Gagal mengubah kategori portfolio.'); 
        }
    }

    // --- AJAX: EDIT KEBAYA ---
    function openEditKebayaModal(id, name) {
        document.getElementById('edit_kebaya_name').value = name;
        document.getElementById('editKebayaForm').action = `/admin/kebayas/${id}`;
        
        const fileInput = document.querySelector('#editKebayaForm input[type="file"]');
        if (fileInput) fileInput.value = '';

        document.getElementById('modalEditKebaya').classList.replace('hidden', 'flex');
    }

    async function submitEditKebayaAjax() {
        const form = document.getElementById('editKebayaForm');
        const formData = new FormData(form);
        formData.append('_method', 'PUT');

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });
            const result = await response.json();
            if (response.ok) {
                closeModal('modalEditKebaya');
                showStatusModal('success', 'Tersimpan!', result.message);
                setTimeout(() => window.location.reload(), 1200);
            } else {
                showStatusModal('error', 'Gagal!', result.message || 'Gagal merubah data.');
            }
        } catch (error) { 
            showStatusModal('error', 'Error!', 'Gagal mengubah data kebaya.'); 
        }
    }

    // --- INITIALIZATION ---
    document.addEventListener('DOMContentLoaded', function() {
        const el = document.getElementById('sortable-table');
        if (el) {
            Sortable.create(el, { handle: '.handle', animation: 200, ghostClass: 'bg-pink-50', forceFallback: true });
        }

        document.addEventListener('change', function(e) {
            if (e.target && e.target.type === 'file') {
                const file = e.target.files[0];
                if (file && file.size > 2 * 1024 * 1024) { 
                    document.getElementById('fileSizeLabel').innerText = (file.size / (1024 * 1024)).toFixed(2) + ' MB';
                    document.getElementById('modalLimit').classList.replace('hidden', 'flex');
                    e.target.value = ''; 
                }
            }
        });
    });

    @if(session('success_edit') || session('success_delete'))
        window.onload = () => {
            showStatusModal('success', 'Berhasil!', "{{ session('success_edit') ?? session('success_delete') }}");
        }
    @endif
</script>