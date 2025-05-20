@extends("layout.main")
@section("content")
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
           <div class="d-flex align-items-center">
            <div class="me-auto">
            </div>
            <div class="ms-2">
                <div class="dropdown">
                    <button class="btn btn-icon btn-warning dropdown-toggle" type="button"
                        id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-bell"></i>
                        @if ($notifications->isNotEmpty())
                            <span class="badge bg-danger rounded-pill">{{ $notifications->count() }}</span>
                        @endif
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationDropdown" style="max-height: 300px; overflow-y: auto;">
                        @if ($notifications->isNotEmpty())
                            <li>
                                <h6 class="dropdown-header">Pembayaran Terlambat</h6>
                            </li>
                            @foreach ($notifications as $notification)
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <small>
                                            {{ $notification->users->name }} (NIS: {{ $notification->users->nis }}, Kelas: {{ $notification->users->kelas ? $notification->users->id_kelas : '-' }}) -
                                            Jenis Pembayaran: {{ $notification->jenisPembayaran->nama_jenis_pembayaran }}
                                            @if ($notification->jenisPembayaran->periode == 'bulanan' && $notification->jenisPembayaran->tanggal_bulanan)
                                                Bulan: {{ \Illuminate\Support\Str::title($notification->bulan) }}
                                            @elseif ($notification->jenisPembayaran->tenggat_waktu)
                                                (Tenggat: {{ \Carbon\Carbon::parse($notification->jenisPembayaran->tenggat_waktu)->format('d F Y') }})
                                            @endif
                                        </small>
                                    </a>
                                </li>
                            @endforeach
                        @else
                            <li>
                                <span class="dropdown-item">Tidak ada notifikasi</span>
                            </li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
        </div>
        <div class="app-content">
            <!--begin::Container-->
            <div class="container-fluid">

                <!--begin::Row-->
                <div class="row justify-content-center">
                    <!-- begin::Diagram Pie -->
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header border-0" style="padding-bottom: 0.5rem;">
                                <div class="d-flex justify-content-between ">
                                    <h3 class="card-title" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 0;">
                                        Persentase Status Pembayaran</h3>
                                </div>
                            </div>
                            <hr class="mt-1 mb-2">
                            <div class="card-body" style="padding-top: 0.5rem;">
                                <form method="GET" action="{{ route('home') }}" class="mb-3">
                                    <div class="row align-items-center mb-2">
                                        <div class="col-md-5">
                                            <select class="form-select" id="filter_jenis_pembayaran"
                                                name="filter_jenis_pembayaran" onchange="this.form.submit()"
                                                style="font-size: 0.9rem;">
                                                @foreach($jenisPembayaranOptions as $option)
                                                
                                                    <option value="{{ $option->id }}" {{ request('filter_jenis_pembayaran') == $option->id ? 'selected' : '' }}>
                                                        {{ $option->nama_jenis_pembayaran }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="col-md-3">
                                            <select class="form-select" id="filter_kelas" name="filter_kelas"
                                                onchange="this.form.submit()" style="font-size: 0.9rem;">
                                                <option value="">Kelas</option>
                                                <option value="X" {{ request('filter_kelas') == 'X' ? 'selected' : '' }}>X
                                                </option>
                                                <option value="XI" {{ request('filter_kelas') == 'XI' ? 'selected' : '' }}>XI
                                                </option>
                                                <option value="XII" {{ request('filter_kelas') == 'XII' ? 'selected' : '' }}>
                                                    XII</option>
                                            </select>
                                        </div>
                                    </div>
                                </form>
                                <div>
                                @if ($hasDiagramData)
                                    <canvas id="paymentStatusChart" width="150" height="150"></canvas>
                                @else
                                <div style="height: 150px; display: flex; justify-content: center; align-items: center; font-size:larger;">
                                   <b> TIDAK ADA DATA</b>
                                </div>
                                    
                                @endif

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end::Diagram Pie -->
                    <!-- /.col-md-6 -->
                    <div class="col-lg-5">
                        <div class="card mb-4" style="height: auto;padding-bottom:3%;">
                            <div class="card-header border-0" style="padding-bottom: 0.5rem;">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title" style="font-size: 1.5rem; font-weight: bold; margin-bottom: 0;">
                                        Rangkuman Informasi</h3>
                                </div>
                            </div>
                            <hr class="mt-1 mb-2">
                            <div class="card-body" style="padding-top: 0.5rem;">
                                <div class="row mb-2">
                                    <div class="col-6" style="font-size: 1.3rem;">Total Siswa</div>
                                    <div class="col-6" style="font-size: 1.2rem;">: {{ $total }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6" style="font-size: 1.3rem;">Kelas X</div>
                                    <div class="col-6" style="font-size: 1.3rem;">: {{$totalX }}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6" style="font-size: 1.3rem;">Kelas XI</div>
                                    <div class="col-6" style="font-size: 1.3rem;">: {{$totalXI}}</div>
                                </div>
                                <div class="row mb-2">
                                    <div class="col-6" style="font-size: 1.3rem;">Kelas XII</div>
                                    <div class="col-6" style="font-size: 1.3rem;">: {{$totalXII}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-6" style=" font-size: 1.3rem;">Jenis Pembayaran</div>
                                    <div class="col-6" style="font-size: 1.3rem;">: {{ $totalJenisPembayaran }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row  align-items-center d-flex justify-content-center">
                <div class="col-sm-auto text-center" style="font-size: 28px;">
                    <b>Data Siswa</b>
                </div>
                <div class="col-sm-6">
                    <form class="d-flex" role="search" method="GET" action="{{ route('home') }}">
                        <input class="form-control me-2" type="search" placeholder="Cari" aria-label="Search" name="search"
                            value="{{ $request->get("search") }}">
                        <button class="btn btn-primary " type="submit">Search</button>
                    </form>
                </div>
                <div class="col-sm-auto text-center">
                    <form action="{{ route("home") }}" method="get" class="d-flex">
                        <!-- begin::Filter kelas-->
                        <select name="kelas" id="kelas" class="form-select me-2" style="width: 100px;">
                            <option value="">Kelas</option>
                            <option value="X">X</option>
                            <option value="XI">XI</option>
                            <option value="XII">XII</option>
                        </select>
                        <!-- end::Filter kelas-->
                        <button class="btn btn-primary " type="submit">Filter</button>
                    </form>
                </div>
            </div>
        </div>
        <!--end::Row-->
        <!--begin::Row-->
        <div class="row justify-content-center mt-2">
            <div class="col-md-11">
                <div class="card mb-4">
                    <!-- /.card-header -->
                    <div class="card-body">
                        {{-- Sukses --}}
                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Gagal --}}
                        @if (session('error'))
                            <div class="alert alert-danger" style="white-space: pre-line;">
                                {{ session('error') }}
                            </div>
                        @endif
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">NIS</th>
                                    <th>Nama</th>
                                    <th style="text-align: center;">Kelas</th>
                                    <th style="text-align: center;">Jurusan</th>
                                    <th style="text-align: center;"> Status Siswa</th>
                                    <th style="text-align: center;">Pembayaran</th>
                                    <th style="text-align: center;">Prediksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @if($users->isEmpty())
                                    <tr>
                                        <td colspan="8" style="text-align:center;">DATA TIDAK ADA</td>
                                    </tr>
                                @else
                                    @foreach ($users as $user)
                                        <tr class="align-middle" >
                                            <td>{{ $user->nis }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td style="text-align: center;"> 
                                                @if ($user->id_kelas)
                                                    {{ $user->kelas->tingkat_kelas }}
                                                @else
                                                    <span class="text-muted">-</span> 
                                                @endif
                                            </td>
                                            <td style="text-align: center;">
                                                @if ($user->id_kelas)
                                                    {{ $user->kelas->jurusan }}
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td style="text-align: center;">{{ $user->status_siswa }}</td>
                                            <td style="text-align: center;"><button class="btn btn-warning"><a class="lihat-rekap" href=""
                                                        data-nis="{{ $user->nis }}"> <i class="fa-solid fa-eye"
                                                            style="color: white;"></i></a></button>

                                                            @if(auth()->user()->role == "admin")
                                                <button class="btn btn-success"><a class="input-bayar" href=""
                                                        data-nis="{{ $user->nis }}"><i class="fa-solid fa-square-plus"
                                                            style="color: white;"></i></a></button>
                                                            @endif
                                            </td>
                                            <td style="text-align: center;">
                                                {{-- {{ dd($user->id) --}}
                                                @if(isset($prediksiMap[$user->id]))
                                                    <span
                                                        style="background-color: {{ $prediksiMap[$user->id]->prediksi == 1 ? 'rgba(255, 0, 0, 0.4)' : 'rgba(0, 255, 0, 0.3)' }}; color: black; padding: 5px 10px; border-radius: 5px; ">
                                                        {{ $prediksiMap[$user->id]->prediksi == 1 ? 'Telat Bayar' : 'Tepat Waktu' }}
                                                    </span>
                                                @else
                                                    -
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                @endif
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->

                    <div class="card-footer">
                    @if(auth()->user()->role == "admin")
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">Import
                            Pembayaran</button>
                            @endif

                        <!-- Upload Excel Modal -->
                        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="uploadModalLabel">Upload Excel File</h5>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <a href="template_pembayaran.xlsx" download class="text-primary"
                                                style="text-decoration:none;">*Download template
                                                Excel</a>
                                        </div>


                                        <form id="uploadForm" method="POST" enctype="multipart/form-data"
                                            action="{{ route('importExcelPembayaran') }}">
                                            @csrf
                                            <div class="mb-3">
                                                <label for="excelFile" class="form-label">Pilih File Excel</label>
                                                <input type="file" class="form-control" id="excelFile" accept=".xlsx, .xls"
                                                    name="file" required>
                                            </div>
                                            <div class="modal-footer d-flex justify-content-center">
                                                <button type="button" class="btn btn-secondary"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit" class="btn btn-primary">Upload</button>
                                            </div>
                                        </form>
                                    </div>

                                </div>
                            </div>
                        </div>

                        <div class="clearfix">
                        {{ $users->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--end::Row-->
        </div>
        <!--end::Container-->
        </div>

        <script>
            document.addEventListener("DOMContentLoaded", function () {
                document.querySelectorAll(".btn-group").forEach(group => {
                    let button = group.querySelector(".dropdown-toggle");
                    let defaultText = button.textContent;

                    group.querySelectorAll(".dropdown-item").forEach(item => {
                        item.addEventListener("click", function (event) {
                            event.preventDefault(); // Prevents page reload when clicking item

                            let selectedText = this.textContent.replace(" ✓", ""); // Remove exist checklist
                            let menuItems = group.querySelectorAll(".dropdown-item");

                            // Find and remove checklist in items
                            menuItems.forEach(i => i.innerHTML = i.textContent.replace(" ✓", ""));

                            if (button.textContent === selectedText) {
                                button.textContent = defaultText;
                                filterTable("");
                            } else {
                                this.innerHTML = selectedText + " ✓"; // Add checkmark
                                button.textContent = selectedText; // Update button text
                                filterTable(selectedText);
                            }
                        })
                    })

                });
            });

            function filterTable(selectedClass) {
                let rows = document.querySelectorAll("tbody tr");
                rows.forEach(row => {
                    let kelasColumn = row.querySelector("td:nth-child(3)"); // Ambil kolom kelas dari setiap baris
                    let kelasValue = kelasColumn.textContent.trim(); // Ambil teks dari kolom kelas (tanpa spasi ekstra)


                    if (selectedClass === "") {
                        row.style.display = "";
                    } else if (kelasValue === selectedClass) {
                        row.style.display = "";
                    }
                })
            }

            // Get Rekap Pembayaran Siswa Page
            document.addEventListener("DOMContentLoaded", function () {
                document.querySelectorAll(".lihat-rekap").forEach(item => {
                    item.addEventListener("click", function (event) {
                        event.preventDefault();
                        let nis = this.getAttribute("data-nis");

                        fetch(`/rekap-pembayaran/${nis}`)
                            .then(response => response.text())
                            .then(html => {
                                document.querySelector(".app-main").innerHTML = html;

                                // Filter Jenis Pembayaran
                                initializeRekapPembayaranFilter();

                                initializeTahunAjaranFilter()

                                // Delete Rekap Modal
                                initializeDeleteModal();

                                // Edit Rekap Modal
                                editRekapModal();

                                // Show Cicilan Modal
                                showCicilanModal();

                                deleteCicilanModal();

                                editCicilanModal();

                                updateCicilan();

                                tambahCicilan();
                               
                                setupPaginationHandler();
                                initializeBuktiPembayaranModal();

                                // Mengubah URL tanpa reload halaman
                                window.history.pushState({}, "", `/rekap-pembayaran/${nis}`);
                            })
                            .catch(error => console.error("Error:", error));
                    });
                });
            });

            function setupPaginationHandler() {
                const paginationContainer = document.querySelector('.clearfix');
                if (!paginationContainer) return;

                paginationContainer.addEventListener('click', function(event) {
                    const target = event.target.closest('a');
                    if (target && target.tagName === 'A') {
                        event.preventDefault();
                        const url = target.getAttribute('href');
                        fetchRekapPage(url);
                    }
                });
            }


            function fetchRekapPage(url) {
                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parsedHTML = new DOMParser().parseFromString(html, 'text/html');

                        // Change inside table
                        const newPembayaranTable = parsedHTML.querySelector('#pembayaran-table tbody');
                        if (newPembayaranTable) {
                            const oldPembayaranTableBody = document.querySelector('#pembayaran-table tbody');
                            oldPembayaranTableBody.innerHTML = newPembayaranTable.innerHTML;

                            // Change pagination
                            const newPagination = parsedHTML.querySelector('.clearfix');
                            const oldPagination = document.querySelector('.clearfix');
                            if (newPagination && oldPagination) {
                                oldPagination.innerHTML = newPagination.innerHTML;
                            }
                            initializeRekapPembayaranFilter();
                            initializeDeleteModal();
                            editRekapModal();
                            showCicilanModal();
                            editCicilanModal();
                            updateCicilan();
                            deleteCicilanModal();
                            initializeBuktiPembayaranModal();

                            // Update URL di address bar
                            window.history.pushState({}, '', url);
                        } else {
                            console.error('Tabel pembayaran tidak ditemukan di respons paginasi.');
                        }
                    })
                    .catch(error => console.error('Error fetching page:', error));
            }

            // Edit Cicilan Modal
            function editCicilanModal() {
                $(document).on('click', '.edit_cicilan_modal', function () {
                    const idCicilan = $(this).closest('tr').data('id-cicilan');
                    const nominal = $(this).closest('tr').data('nominal');
                    const tanggalBayar = $(this).closest('tr').data('tanggal-bayar');

                    $('#edit_id_cicilan').val(idCicilan);
                    $('#edit_nominal_cicilan').val(nominal);
                    $('#edit_tanggal_bayar_cicilan').val(tanggalBayar);

                    $('#viewCicilanModal').modal('hide');
                    $('#editCicilanModal').modal('show');

                });
            }

            function deleteCicilanModal() {
                $(document).on('click', '.delete-cicilan-btn', function () {
                    const idCicilan = $(this).data('id');
                    $('#hapus_id_cicilan').val(idCicilan);
                    
                    // Modal confirmation
                    var deleteCicilanModal = new bootstrap.Modal(document.getElementById('hapusCicilanModal'));
                    deleteCicilanModal.show();
                });

                // Handling delete cicilan
                $('#hapusCicilanForm').off('submit').on('submit', function (e) {
                    e.preventDefault();
                    let formData = $(this).serialize(); 
                    let url = $(this).attr('action'); 

                    $.ajax({
                        type: 'POST',
                        url: url,
                        data: formData,
                        success: function (response) {
                            $('#hapusCicilanModal').modal('hide'); // Close modal

                            // Get rekap page
                            fetch(`/rekap-pembayaran/${nis}`)
                                .then(response => response.text())
                                .then(html => {
                                    $('.app-main').html(html); 
                                    initializeRekapPembayaranFilter();
                                    initializeDeleteModal();
                                    showCicilanModal();
                                    deleteCicilanModal();
                                    editCicilanModal();
                                    initializeBuktiPembayaranModal();
                                })
                                .catch(error => {
                                    console.error('Error fetching full rekap:', error);
                                });
                        },
                        error: function (error) {
                            console.error('Error deleting cicilan:', error);
                        }
                    });
                });
            }

           function initializeBuktiPembayaranModal() {
                const modal = document.getElementById('buktiModal');
                const modalBody = document.getElementById('buktiModalBody');
                const uploadModal = document.getElementById('uploadBuktiModal');
                const uploadForm = uploadModal.querySelector('form');
                const uploadButton = modal.querySelector('.modal-footer .btn-success');
                const deleteButton = document.createElement('button');
                const buktiModalInstance = new bootstrap.Modal(modal);

                deleteButton.type = 'button';
                deleteButton.className = 'btn btn-danger ms-2';
                deleteButton.textContent = 'Hapus Bukti';

                modal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const imageUrl = button.getAttribute('data-img');
                    const pembayaranId = button.getAttribute('data-pembayaran-id');

                    // Clear delete button before
                    const existingDeleteButton = modal.querySelector('.modal-footer .btn-danger');
                    if (existingDeleteButton) {
                        existingDeleteButton.remove();
                    }

                    if (!imageUrl || imageUrl.trim() === '') {
                        modalBody.innerHTML = `<p class="text-muted">Tidak ada bukti pembayaran.</p>`;
                        if (uploadButton) {
                            uploadButton.style.display = 'inline-block';
                        }
                    } else {
                        modalBody.innerHTML = `<img src="${imageUrl}" class="img-fluid rounded" alt="Bukti Pembayaran">`;
                        if (uploadButton) {
                            uploadButton.style.display = 'none';
                        }
                        deleteButton.addEventListener('click', function() {
                            hapusBuktiPembayaran(pembayaranId);
                            buktiModalInstance.hide();
                        });
                        modal.querySelector('.modal-footer').prepend(deleteButton);
                    }

                    uploadButton.setAttribute('data-pembayaran-id', pembayaranId); // Save ID
                });

                uploadModal.addEventListener('show.bs.modal', function (event) {
                    const pembayaranId = uploadButton.getAttribute('data-pembayaran-id');
                    uploadForm.action = `/upload-bukti/${pembayaranId}`; // Set action form (mungkin tidak perlu jika pakai fetch)
                });

                uploadForm.addEventListener('submit', function(event) {
                    event.preventDefault(); // Prevent default form submission

                    const formData = new FormData(uploadForm);
                    const url = uploadForm.action;

                    fetch(url, {
                        method: 'POST',
                        body: formData,
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            bootstrap.Modal.getInstance(uploadModal).hide(); // Hide upload modal
                            if (data.nis) {
                                // Fetch ulang halaman rekap dengan NIS yang sesuai
                                fetch(`/rekap-pembayaran/${data.nis}`)
                                    .then(response => response.text())
                                    .then(html => {
                                        document.querySelector(".app-main").innerHTML = html;
                                        initializeRekapPembayaranFilter();
                                        initializeTahunAjaranFilter();
                                        initializeDeleteModal();
                                        editRekapModal();
                                        showCicilanModal();
                                        deleteCicilanModal();
                                        editCicilanModal();
                                        updateCicilan();
                                        tambahCicilan();
                                        setupPaginationHandler();
                                        initializeBuktiPembayaranModal(); // Re-initialize modal event listeners
                                        // Tampilkan pesan sukses (jika diperlukan)
                                        // Misalnya: showAlert('success', data.message);
                                    })
                                    .catch(error => console.error("Error fetching rekap:", error));
                            } else {
                                // Jika NIS tidak ada di respon, mungkin fetch ulang dengan NIS yang terakhir dilihat
                                const currentNis = document.getElementById('jenisPembayaran')?.getAttribute('data-nis');
                                if (currentNis) {
                                    fetch(`/rekap-pembayaran/${currentNis}`)
                                        .then(response => response.text())
                                        .then(html => {
                                            document.querySelector(".app-main").innerHTML = html;
                                            // ... re-initialize functions ...
                                            initializeBuktiPembayaranModal();
                                        })
                                        .catch(error => console.error("Error fetching rekap:", error));
                                } else {
                                    // Handle jika NIS tidak tersedia
                                    console.warn("NIS tidak tersedia untuk refresh rekap.");
                                    // Mungkin reload halaman home atau tampilkan pesan error
                                }
                            }
                        } else {
                            // Handle error response (jika ada)
                            // Misalnya: showAlert('error', 'Gagal mengupload bukti pembayaran.');
                            console.error("Gagal mengupload bukti:", data);
                        }
                    })
                    .catch(error => {
                        console.error("Error:", error);
                        // Handle network error
                    });
                });

                if (nis) {
                    fetch(`/rekap-pembayaran/${nis}`)
                        .then(response => response.text())
                        .then(html => {
                            document.querySelector(".app-main").innerHTML = html;
                            initializeRekapPembayaranFilter();
                            initializeTahunAjaranFilter();
                            initializeDeleteModal();
                            editRekapModal();
                            showCicilanModal();
                            deleteCicilanModal();
                            editCicilanModal();
                            updateCicilan();
                            tambahCicilan();
                            setupPaginationHandler();
                            initializeBuktiPembayaranModal();
                        })
                        .catch(error => console.error("Error fetching rekap:", error));
                }
            }

          function hapusBuktiPembayaran(pembayaranId) {
                fetch(`/hapus-bukti/${pembayaranId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        
                        //data-nis
                        const jenisPembayaranSelect = document.getElementById('jenisPembayaran');
                        const nis = jenisPembayaranSelect ? jenisPembayaranSelect.getAttribute('data-nis') : null;

                        console.log("nis dari select:", nis);

                        if (!nis) {
                            const currentUrl = window.location.pathname;
                            const parts = currentUrl.split('/');
                            let nis = parts[parts.length - 1];
                            if(nis === 'home'){
                            nis = null;
                            }
                            console.log("nis dari URL:", nis);
                        }

                        if (nis) {
                            fetch(`/rekap-pembayaran/${nis}`)
                                .then(response => response.text())
                                .then(html => {
                                    document.querySelector(".app-main").innerHTML = html;
                                    initializeRekapPembayaranFilter();
                                    initializeTahunAjaranFilter();
                                    initializeDeleteModal();
                                    editRekapModal();
                                    showCicilanModal();
                                    deleteCicilanModal();
                                    editCicilanModal();
                                    updateCicilan();
                                    tambahCicilan();
                                    setupPaginationHandler();
                                    initializeBuktiPembayaranModal();
                                })
                                .catch(error => console.error("Error fetching rekap:", error));
                        } else {
                            console.error("NIS tidak ditemukan setelah menghapus bukti.");
                            alert("NIS tidak ditemukan. Mungkin terjadi kesalahan.");
                        }
                    } else {
                        alert('Gagal menghapus bukti pembayaran.');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('Terjadi kesalahan saat menghapus bukti pembayaran.');
                });
            }

            // Show Cicilan Modal
            function showCicilanModal() {
                $(document).ready(function () {
                    $(document).off('click', '.showCicilan').on('click', '.showCicilan', function (e) {
                        e.preventDefault();
                        e.preventDefault();
                        // Get id from button
                        var id_pembayaran = $(this).val();
                        // Table body
                        var cicilanListContainer = $('#cicilan-list');
                        cicilanListContainer.empty();

                        // Set id_pembayaran at modal Tambah Cicilan
                        $('#tambah_id_pembayaran').val(id_pembayaran);



                        fetch(`/rekap-pembayaran/cicilan/${id_pembayaran}`)
                            .then(response => response.json()) // get data
                            .then(data => {
                                if (data.length > 0) {
                                    var table = $('<table class="table table-bordered"><thead><tr><th>No</th><th>Nominal</th><th>Tanggal Bayar</th>  @if(auth()->user()->role == "admin") <th>Aksi</th> @endif </tr></thead><tbody></tbody></table>');
                                    var tbody = table.find('tbody');
                                    $.each(data, function (index, cicilan) {

                                        // Format Tanggal Bayar 
                                        const tanggalBayar = new Date(cicilan.tanggal_bayar);
                                        const options = { day: 'numeric', month: 'long', year: 'numeric' };
                                        const formattedTanggalBayar = tanggalBayar.toLocaleDateString('id-ID', options);

                                        tbody.append(`
                                                                                                                                                                                                                                        <tr 
                                                                                                                                                                                                                                        data-id-cicilan="${cicilan.id}"
                                                                                                                                                                                                                                        data-nominal="${cicilan.nominal}"
                                                                                                                                                                                                                                        data-tanggal-bayar="${cicilan.tanggal_bayar}">

                                                                                                                                                                                                                                            <td>${index + 1}</td>
                                                                                                                                                                                                                                            <td>Rp ${new Intl.NumberFormat('id-ID').format(cicilan.nominal)}</td>
                                                                                                                                                                                                                                            <td>${formattedTanggalBayar}</td>
                                                                                            @if(auth()->user()->role == "admin")                                                                                                                                                  <td><button class="btn btn-primary btn-sm edit_cicilan_modal">
                                                                                                                                                                                <i class="fa-solid fa-pen-to-square"></i> 
                                                                                                                                                                            </button> <button class="btn btn-danger btn-sm delete-cicilan-btn" data-id="${cicilan.id}">
    <i class="fa-solid fa-trash"></i>
</button></td> @endif

                                                                                                                                                                                                                                        </tr>
                                                                                                                                                                                                                                    `);
                                    });

                                    cicilanListContainer.append(table);

                                    var viewCicilanModal = new bootstrap.Modal(document.getElementById('viewCicilanModal'));
                                    viewCicilanModal.show();
                                } else {
                                    cicilanListContainer.append('<p>Tidak ada data cicilan untuk pembayaran ini.</p>');

                                    var viewCicilanModal = new bootstrap.Modal(document.getElementById('viewCicilanModal'));
                                    viewCicilanModal.show();
                                }
                            })
                            .catch(error => {
                                console.error("Error fetching data cicilan:", error);
                            });

                    });
                });
            }


            function tambahCicilan(){
                $(document).ready(function () {
                     $('#tambahCicilanForm').on('submit', function(e) {
                                e.preventDefault();

                                let formData = $(this).serialize();
                                let url = $(this).attr('action');

                                fetch(url, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/x-www-form-urlencoded',
                                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                    },
                                    body: formData
                                })
                                .then(response => response.json())
                                .then(data => {
                                    if (data.success) {
                                        $('#tambahCicilanModal').modal('hide');
                                        
                                        fetch(`/rekap-pembayaran/${data.nis}`)
                                            .then(response => response.text())
                                            .then(newHtml => {
                                                document.querySelector(".app-main").innerHTML = newHtml;
                                                initializeRekapPembayaranFilter();
                                                initializeDeleteModal();
                                                editRekapModal();
                                                showCicilanModal();
                                                deleteCicilanModal();
                                                editCicilanModal();
                                                updateCicilan();
                                                tambahCicilan();
                                                initializeBuktiPembayaranModal();
                                            })
                                            .catch(error => console.error("Error reloading rekap:", error));
                                    } else {
                                        alert(data.message);
                                    }
                                })
                                .catch(error => {
                                    console.error('Error:', error);
                                    alert('Terjadi kesalahan saat menyimpan cicilan.');
                                });
                            });

                })

            }

            // UpdateCicilan
            function updateCicilan() {
                $(document).ready(function () {
                    $(document).off('submit', '#editCicilanForm').on('submit', '#editCicilanForm', function (e) {
                        e.preventDefault();
                        const url = $(this).attr('action');
                        const formData = $(this).serialize();
                        const idCicilan = $('#id_cicilan_edit').val();

                        $.ajax({
                            type: 'PUT',
                            url: url,
                            data: formData,
                            dataType: 'json',
                            success: function (response) {
                                $('#editCicilanModal').modal('hide');
                                const nis = $('.app-main').data('nis');
                                if (nis) {
                                    window.location.href = `/rekap-pembayaran/${nis}`;
                                } else {
                                    console.error('NIS tidak ditemukan untuk redirect.');
                                }
                            },
                            error: function (error) {
                                console.error('Error updating cicilan:', error);
                                alert('Terjadi kesalahan saat memperbarui cicilan.');
                                if (error.responseJSON && error.responseJSON.message) {
                                    alert(error.responseJSON.message);
                                }
                            }
                        });
                    });
                });
            }

            // Edit Rekap Pembayaran
            function editRekapModal() {
                $(document).ready(function () {
                    $(".edit").click(function (e) {
                        e.preventDefault();
                        var id_pembayaran = $(this).val();

                        fetch(`/rekap-pembayaran/detail/${id_pembayaran}`)
                            .then(response => response.json())
                            .then(data => {
                                $('#edit_id_pembayaran').val(data.id);
                                $('#edit_status_pembayaran').val(data.status_pembayaran);
                                $('#edit_tanggal_lunas').val(data.tanggal_lunas);

                                var editModal = new bootstrap.Modal(document.getElementById('editModal'));
                                editModal.show();

                                // Ajax after edit rekap and redirect to fetch page
                                $('#editForm').off('submit').on('submit', function (e) {
                                    e.preventDefault();
                                    let formData = $(this).serialize();
                                    let url = $(this).attr('action');

                                    $.ajax({
                                        type: 'PUT',
                                        url: url,
                                        data: formData,
                                        success: function (response) {
                                            $('#editModal').modal('hide');
                                            // reload  recap content with fetch
                                            let nis = response.nis;
                                            fetch(`/rekap-pembayaran/${nis}`)
                                                .then(response => response.text())
                                                .then(html => {
                                                    $('.app-main').html(html);
                                                    initializeRekapPembayaranFilter();
                                                    initializeDeleteModal();
                                                    editRekapModal();
                                                    showCicilanModal();
                                                    deleteCicilanModal();
                                                    editCicilanModal();
                                                    initializeBuktiPembayaranModal();
                                                })
                                                .catch(error => {
                                                    console.error('Error fetching rekap:', error);
                                                });
                                        },
                                        error: function (error) {
                                            console.error('Error updating rekap:', error);
                                        }
                                    });
                                });
                            })
                            .catch(error => console.error("Error fetching data:", error));
                    });
                });
            }


            // Delete Rekap Pembayaran
            function initializeDeleteModal() {
                $(document).ready(function () {
                    $(".hapus").click(function (e) {
                        e.preventDefault();
                        var idPembayaran = $(this).val();
                        $('#id_pembayaran').val(idPembayaran);
                        var deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
                        deleteModal.show();
                    });

                    // Ajax after delete rekap and redirect to fetch page
                    $('#deleteForm').off('submit').on('submit', function (e) {
                        e.preventDefault();
                        let formData = $(this).serialize();
                        let url = $(this).attr('action');

                        $.ajax({
                            type: 'POST',
                            url: url,
                            data: formData,
                            success: function (response) {
                                $('#deleteModal').modal('hide');
                                // reload  recap content with fetch
                                let nis = response.nis;
                                fetch(`/rekap-pembayaran/${nis}`)
                                    .then(response => response.text())
                                    .then(html => {
                                        $('.app-main').html(html);
                                        initializeRekapPembayaranFilter();
                                        initializeDeleteModal();
                                        editRekapModal();
                                        showCicilanModal();
                                        deleteCicilanModal();
                                        editCicilanModal();
                                        initializeBuktiPembayaranModal();
                                    })
                                    .catch(error => {
                                        console.error('Error fetching rekap:', error);
                                    });
                            },
                            error: function (error) {
                                console.error('Error deleting rekap:', error);
                            }
                        });
                    });
                });
            }

           function toggleBulanColumnByPeriode() {
                const jenisPembayaranSelect = document.getElementById('jenisPembayaran');
                const selectedOption = jenisPembayaranSelect.options[jenisPembayaranSelect.selectedIndex];
                const periode = selectedOption.dataset.periode;

                const bulanColumnHeader = document.querySelector('.bulan-column');
                const bulanCells = document.querySelectorAll('.bulan-cell');

                if (periode === 'bulanan') {
                    bulanColumnHeader.style.display = '';
                    bulanCells.forEach(cell => cell.style.display = '');
                } else {
                    bulanColumnHeader.style.display = 'none';
                    bulanCells.forEach(cell => cell.style.display = 'none');
                }
            }

            function applyServerFilter() {
                const jenisPembayaran = document.getElementById('jenisPembayaran').value;
                const tahunAjaran = document.getElementById('tahunAjaran').value;
                const nis = document.getElementById('jenisPembayaran').dataset.nis; 

                fetch(`/rekap-pembayaran/${nis}?jenisPembayaran=${jenisPembayaran}&tahunAjaran=${tahunAjaran}`)
                    .then(response => response.text())
                    .then(html => {
                        document.querySelector(".app-main").innerHTML = html;

                        initializeRekapPembayaranFilter();
                        initializeTahunAjaranFilter();
                        initializeDeleteModal();
                        editRekapModal();
                        showCicilanModal();
                        deleteCicilanModal();
                        editCicilanModal();
                        initializeBuktiPembayaranModal();
                    })
                    .catch(error => console.error("Error applying filter:", error));
            }


            // Initialize Rekap Pembayaran Filter
            function initializeRekapPembayaranFilter() {
                const jenisPembayaranSelect = document.getElementById('jenisPembayaran');   
                 jenisPembayaranSelect.addEventListener('change', applyServerFilter);
                 toggleBulanColumnByPeriode();
            }

            //Initialize Tahun Ajaran Filter
            function initializeTahunAjaranFilter() {
                const tahunAjaranSelect = document.getElementById('tahunAjaran');
                 tahunAjaranSelect.addEventListener('change', applyServerFilter);
            }

            document.addEventListener('DOMContentLoaded', function() {
                initializeRekapPembayaranFilter();
                initializeTahunAjaranFilter();
            });

            // Input Pembayaran Siswa
            document.addEventListener("DOMContentLoaded", function () {
                document.querySelectorAll(".input-bayar").forEach(item => {
                    item.addEventListener("click", function (event) {
                        event.preventDefault();
                        let nis = this.getAttribute("data-nis");

                        fetch(`/inputPembayaran/${nis}`)
                            .then(response => response.text())
                            .then(html => {
                                document.querySelector(".app-main").innerHTML = html;

                                // Periode & Tenggat Waktu
                                const selectJenisPembayaran = document.getElementById("id_jenis_pembayaran");
                                const bulanInput = document.getElementById("bulan");
                                if (selectJenisPembayaran && bulanInput) {
                                    selectJenisPembayaran.addEventListener("change", function () {
                                        const selectedOption = this.options[this.selectedIndex];
                                        const periode = selectedOption.getAttribute("data-periode");

                                        if (periode === "bulanan") {
                                            bulanInput.style.display = "block";
                                        } else {
                                            bulanInput.style.display = "none";
                                        }
                                    });
                                }

                                // Cicilan Option 
                                const selectStatusPembayaran = document.getElementById("status")
                                const nominalCicilan = document.getElementById("nominal_cicilan");
                                const tanggalBayar = document.getElementById("tanggal_bayar");
                                const tanggalLunas = document.getElementById("tanggal_lunas")

                                if (selectStatusPembayaran) {
                                    selectStatusPembayaran.addEventListener("change", function () {

                                        // Get the value
                                        const valueStatusPembayaran = this.value;

                                        if (valueStatusPembayaran === "Belum Lunas") {
                                            nominalCicilan.style.display = "block";
                                            tanggalBayar.style.display = "block";
                                            tanggalLunas.style.display = "none";
                                        } else {
                                            nominalCicilan.style.display = "none";
                                            tanggalBayar.style.display = "none";
                                            tanggalLunas.style.display = "block";
                                        }
                                    })

                                }

                                // Mengubah URL tanpa reload halaman
                                window.history.pushState({}, "", `/inputPembayaran/${nis}`);
                            })
                            .catch(error => console.error("Error:", error));
                    });
                });
            });


            const paymentStatusChartCanvas = document.getElementById('paymentStatusChart');
            const persentaseLunas = {{ $persentaseLunas }};
            const persentaseBelumLunas = {{ $persentaseBelumLunas }};

            new Chart(paymentStatusChartCanvas, {
                type: 'pie',
                data: {
                    labels: ['Lunas', 'Belum Lunas'],
                    datasets: [{
                        label: 'Status Pembayaran',
                        data: [persentaseLunas, persentaseBelumLunas],
                        backgroundColor: [
                            'rgb(54, 162, 235)', // Blue for Lunas
                            'rgb(255, 99, 132)'  // Red for Belum Lunas
                        ],
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function (context) {
                                    let label = context.label || '';
                                    if (context.parsed !== null) {
                                        label += ' (' + context.parsed.toFixed(2) + '%)';
                                    }
                                    return label;
                                }
                            }
                        },
                    }
                }
            });

        </script>
    </main>
@endsection