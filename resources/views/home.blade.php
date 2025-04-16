@extends("layout.main")
@section("content")
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
        </div>
        <div class="app-content">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card mb-4">
                            <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Presentase Status Pembayaran</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <!--  isi diagram -->
                            </div>
                        </div>
                    </div>
                    <!-- /.col-md-6 -->
                    <div class="col-lg-5">
                        <div class="card mb-4">
                            <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Rangkuman Informasi</h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-5">Total Siswa</div>
                                    <div class="col-7">: {{ $total }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-5">Total Siswa Kelas X</div>
                                    <div class="col-7">: {{$totalX }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-5">Total Siswa Kelas XI</div>
                                    <div class="col-7">: {{$totalXI}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-5">Total Siswa Kelas XII</div>
                                    <div class="col-7">: {{$totalXII}}</div>
                                </div>
                                <div class="row">
                                    <div class="col-6">Total Jenis Pembayaran</div>
                                    <div class="col-6">: {{  $totalJenisPembayaran }}</div>
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
                            <input class="form-control me-2" type="search" placeholder="Cari" aria-label="Search"
                                name="search" value="{{ $request->get("search") }}">
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
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">NIS</th>
                                        <th>Nama</th>
                                        <th>Kelas</th>
                                        <th style="width: 40px">Jurusan</th>
                                        <th>Alamat</th>
                                        <th>Pembayaran</th>
                                        <th>Prediksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if($users->isEmpty())
                                        <tr>
                                            <td colspan="7" style="text-align:center;">DATA TIDAK ADA</td>
                                        </tr>
                                    @else
                                        @foreach ($users as $user)
                                            <tr class="align-middle">
                                                <td>{{ $user->nis }}</td>
                                                <td>{{ $user->name }}</td>
                                                <td>{{ $user->kelas->tingkat_kelas }} </td>
                                                <td>{{ $user->kelas->jurusan }}</td>
                                                <td>{{ $user->alamat }}</td>
                                                <td><button class="btn btn-warning"><a class="lihat-rekap" href=""
                                                            data-nis="{{ $user->nis }}"> <i class="fa-solid fa-eye"
                                                                style="color: white;"></i></a></button>

                                                    <button class="btn btn-success"><a class="input-bayar" href=""
                                                            data-nis="{{ $user->nis }}"><i class="fa-solid fa-square-plus"
                                                                style="color: white;"></i></a></button>
                                                </td>
                                                <td>Tepat Waktu</td>
                                            </tr>
                                        @endforeach
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->

                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
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

                                // Delete Rekap Modal
                                initializeDeleteModal();

                                // Edit Rekap Modal
                                editRekapModal();

                                // Show Cicilan Modal
                                showCicilanModal();

                                editCicilanModal();

                                // Mengubah URL tanpa reload halaman
                                window.history.pushState({}, "", `/rekap-pembayaran/${nis}`);
                            })
                            .catch(error => console.error("Error:", error));
                    });
                });
            });

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

            // Show Cicilan Modal
            function showCicilanModal() {
                $(document).ready(function () {
                    $(".showCicilan").click(function (e) {
                        e.preventDefault();
                        // Get id from button
                        var id_pembayaran = $(this).val();
                        // Table body
                        var cicilanListContainer = $('#cicilan-list');
                        cicilanListContainer.empty();

                        fetch(`/rekap-pembayaran/cicilan/${id_pembayaran}`)
                            .then(response => response.json()) // get data
                            .then(data => {
                                if (data.length > 0) {
                                    var table = $('<table class="table table-bordered"><thead><tr><th>No</th><th>Nominal</th><th>Tanggal Bayar</th><th>Edit</th></tr></thead><tbody></tbody></table>');
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
                                                                                                                    <td><button class="btn btn-primary btn-sm edit_cicilan_modal">
                                                        <i class="fa-solid fa-pen-to-square"></i> Edit
                                                    </button></td>

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
                                                    editCicilanModal();
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
                                        editCicilanModal();
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

            // Filter Jenis Pembayaran 
            function initializeRekapPembayaranFilter() {
                const jenisPembayaranSelect = document.getElementById('jenisPembayaran');
                const pembayaranTable = document.getElementById('pembayaran-table').getElementsByTagName('tbody')[0];
                const bulanColumnHeader = document.querySelector('#pembayaran-table thead .bulan-column');
                const bulanCells = document.querySelectorAll('#pembayaran-table tbody .bulan-cell');

                function filterPembayaranTable(selectedJenisPembayaranId) {
                    let rowCount = 0;
                    Array.from(pembayaranTable.rows).forEach(row => {
                        const jenisPembayaranId = row.dataset.jenisPembayaranId;
                        if (selectedJenisPembayaranId === '' || jenisPembayaranId === selectedJenisPembayaranId) {
                            row.style.display = '';
                            rowCount++;
                        } else {
                            row.style.display = 'none';
                        }
                    });

                    // Delete "Empty Data" text if there is data from selected jenis pembayaran
                    const noDataRow = pembayaranTable.querySelector('.no-data');
                    if (noDataRow) {
                        pembayaranTable.removeChild(noDataRow);
                    }

                    // "Empty Data" text if there is no data from selected jenis pembayaran
                    if (rowCount === 0) {
                        const newRow = pembayaranTable.insertRow();
                        newRow.classList.add('no-data');
                        const cell = newRow.insertCell();
                        cell.colSpan = document.querySelector('#pembayaran-table thead tr').cells.length;
                        cell.style.textAlign = 'center';
                        cell.textContent = 'TIDAK ADA RIWAYAT PEMBAYARAN';
                    }
                }

                jenisPembayaranSelect.addEventListener('change', function () {
                    const selectedOption = this.options[this.selectedIndex];
                    const selectedJenisPembayaranId = this.value;
                    const periode = selectedOption.dataset.periode;

                    if (periode === 'bulanan') {
                        bulanColumnHeader.style.display = '';
                        bulanCells.forEach(cell => cell.style.display = '');
                    } else {
                        bulanColumnHeader.style.display = 'none';
                        bulanCells.forEach(cell => cell.style.display = 'none');
                    }

                    filterPembayaranTable(selectedJenisPembayaranId);
                });
            }

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

                                        if (valueStatusPembayaran === "belum lunas") {
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
        </script>
    </main>
@endsection