@extends("layout.main")
@section("content")
    <main class="app-main">
        <div class="app-content-header mt-2">
            <div class="container-fluid">
                <div class="row align-items-center d-flex justify-content-around">
                    <div class="col-sm-4">
                        <div class="d-flex align-items-center">
                            <h4><b>Informasi Siswa</b></h4>
                        </div>
                    </div>
                    <div class="col-sm-7">
                        <select name="jenisPembayaran" id="jenisPembayaran" class="form-select me-2" style="width: 220px;">
                            <option value="">Semua Jenis Pembayaran</option>
                            @foreach ($jenisPembayaran as $jenis)
                                <option value="{{ $jenis->id }}" data-periode="{{ $jenis->periode }}">
                                    {{$jenis->nama_jenis_pembayaran}}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="app-content mt-2">
            <div class="container-fluid">
                <div class="row justify-content-around">
                    <l class="col-lg-4">
                        <div class="card mb-4">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-4 fw-bold">NIS</div>
                                    <div class="col-8">: {{ $siswa->nis }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-4 fw-bold">Nama</div>
                                    <div class="col-8">: {{ $siswa->name }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-4 fw-bold">Kelas</div>
                                    <div class="col-8">: {{ $siswa->kelas->tingkat_kelas }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-4 fw-bold">Jurusan</div>
                                    <div class="col-8">: {{ $siswa->kelas->jurusan }}</div>
                                </div>
                                <div class="row">
                                    <div class="col-4 fw-bold">Alamat</div>
                                    <div class="col-8">: {{ $siswa->alamat}}</div>
                                </div>
                            </div>
                        </div>
                    </l>
                    <div class="col-lg-7">
                        <div class="card mb-4">
                            <div class="card-body">
                                <h4>Rekap Pembayaran</h4>
                                <table class="table table-bordered" id="pembayaran-table">
                                    <thead>
                                        <tr>
                                            <th style="width: 10px; display: none;" class="bulan-column">Bulan</th>
                                            <th>Nama</th>
                                            <th>Nominal</th>
                                            <th>Status</th>
                                            <th>Tanggal Lunas</th>
                                            <th>Cicilan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($pembayarans as $pembayaran)
                                        <tr class="align-middle"
                                            data-jenis-pembayaran-id="{{ $pembayaran->id_jenis_pembayaran }}">
                                            <td class="bulan-cell" style="display: none;">{{ $pembayaran->bulan }}</td>
                                            <td>{{ $pembayaran->jenisPembayaran->nama_jenis_pembayaran }}</td>
                                            <td>Rp {{ number_format($pembayaran->jenisPembayaran->nominal, 0, ',', '.') }}
                                            </td>
                                            <td>{{ $pembayaran->status_pembayaran }}</td>
                                            <td>{{ $pembayaran->tanggal_lunas ? \Carbon\Carbon::parse($pembayaran->tanggal_lunas)->format('d F Y') : '-' }}
                                            </td>
                                            <th>
                                                <button type="button" class="btn btn-warning showCicilan"
                                                    value="{{ $pembayaran->id }}" data-bs-toggle="modal"
                                                    style="padding: 0.2rem 0.3rem; font-size: 0.6rem;">
                                                    <i class="fa-solid fa-eye" style="color: white;"></i>
                                                </button>
                                            </th>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="5" class="text-center">Tidak ada data pembayaran.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <div class="modal fade" id="viewCicilanModal" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5">Cicilan</h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <div id="cicilan-list">
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <script>

        // Filter Jenis Pembayaran
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


        // Show Modal Cicilan
        $(document).ready(function () {
            $(".showCicilan").click(function (e) {
                e.preventDefault();
                // Get id from button
                var id_pembayaran = $(this).val();
                // Table body
                var cicilanListContainer = $('#cicilan-list');
                cicilanListContainer.empty();

                fetch(`/rekap-pembayaran/cicilanSiswaPage/${id_pembayaran}`)
                    .then(response => response.json()) // get data
                    .then(data => {
                        if (data.length > 0) {
                            var table = $('<table class="table table-bordered"><thead><tr><th>No</th><th>Nominal</th><th>Tanggal Bayar</th></tr></thead><tbody></tbody></table>');
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
    </script>
@endsection