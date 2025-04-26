<!-- begin::Delete Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteForm" action="{{ route('deleteDataRekapSiswa') }}" method="post">
                @csrf
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="deleteModalLabel">Hapus Rekap Data Siswa</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="delete_pembayaran" id="id_pembayaran">
                    <p>Apakah kamu yakin akan menghapus rekap data ini?</p>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Ya</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end::Delete Modal -->


<!-- start:: Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editForm" action="{{ route('updateDataRekapSiswa') }}" method="post">
                @csrf
                @method('PUT') <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editModalLabel">Edit Data Pembayaran</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_pembayaran" id="edit_id_pembayaran">
                    <div class="mb-3">
                        <label for="edit_status_pembayaran" class="form-label">Status Pembayaran</label>
                        <select class="form-select" id="edit_status_pembayaran" name="status_pembayaran">
                            <option value="belum lunas">Belum Lunas</option>
                            <option value="lunas">Lunas</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tanggal_lunas" class="form-label">Tanggal Lunas</label>
                        <input type="date" class="form-control" id="edit_tanggal_lunas" name="tanggal_lunas">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end:: Edit Modal -->

<!-- start:: Edit Cicilan Modal -->
<div class="modal fade" id="editCicilanModal" tabindex="-1" aria-labelledby="editCicilanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editCicilanForm" action="{{ route('updateCicilan')}}" method="post">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editCicilanModalLabel">Edit Cicilan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="id_cicilan" id="edit_id_cicilan">
                    <div class="mb-3">
                        <label for="edit_nominal_cicilan" class="form-label">Nominal Cicilan</label>
                        <input type="number" class="form-control" id="edit_nominal_cicilan" name="nominal" required>
                    </div>
                    <div class="mb-3">
                        <label for="edit_tanggal_bayar_cicilan" class="form-label">Tanggal Bayar</label>
                        <input type="date" class="form-control" id="edit_tanggal_bayar_cicilan" name="tanggal_bayar"
                            required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- end:: Edit Cicilan Modal -->

<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header mt-2">
        <div class="container-fluid">
            <div class="row  align-items-center d-flex justify-content-around">
                <div class="col-sm-4">
                    <div class="d-flex align-items-center"> <a href="{{route("home")}}"><ion-icon
                                name="caret-back-outline"></ion-icon></a>
                        <h4><b>Rekap Pembayaran Siswa</b></h4>
                    </div>
                </div>
                <div class="col-sm-7" style="display: flex;">
                    <!-- begin::Filter Jenis Pembayaran-->
                    <select name="jenisPembayaran" id="jenisPembayaran" class="form-select me-2" style="width: 170px;">
                        <option value="">Jenis Pembayaran</option>
                        @foreach ($jenisPembayaran as $jenis)
                            <option value="{{ $jenis->id }}" data-periode="{{ $jenis->periode }}">
                                {{$jenis->nama_jenis_pembayaran}}
                            </option>
                        @endforeach
                    </select>
                    <!-- end::Filter Jenis Pembayaran-->
                     
                    <!-- start::Filter Tahun Ajaran-->
                    <select name="tahunAjaran" id="tahunAjaran" class="form-select" style="width: 140px;">
                        <option value="">Tahun Ajaran</option>
                        @php
                            $tahunAjaranUnik = $pembayarans->pluck('tahun_ajaran')->unique()->sortDesc();
                        @endphp
                        @foreach ($tahunAjaranUnik as $tahun)
                            <option value="{{ $tahun }}">{{ $tahun }}</option>
                        @endforeach
                    </select>
                    <!-- end::Filter Tahun Ajaran-->
                </div>
            </div>
        </div>
    </div>
    <!--end::App Content Header-->
    <div class="app-content mt-2">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row justify-content-around">
                <l class="col-lg-4">
                    <div class="card mb-4">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-3 fw-bold">NIS</div>
                                <div class="col-9">: {{ $data->nis }}</div>
                            </div>
                            <div class="row">
                                <div class="col-3 fw-bold">Nama</div>
                                <div class="col-9">: {{ $data->name }}</div>
                            </div>
                            <div class="row">
                                <div class="col-3 fw-bold">Kelas</div>
                                <div class="col-9">: {{ $data->kelas->tingkat_kelas }}</div>
                            </div>
                            <div class="row">
                                <div class="col-3 fw-bold">Jurusan</div>
                                <div class="col-9">: {{ $data->kelas->jurusan }}</div>
                            </div>
                            <div class="row">
                                <div class="col-3 fw-bold">Alamat</div>
                                <div class="col-9">: {{ $data->alamat}}</div>
                            </div>
                            <div class="row">
                                <div class="col-3 fw-bold">Prediksi</div>
                                <div class="col-9">:
                                    @if(isset($prediksiMap[$data->id]))
                                        <span
                                            style="background-color: {{ $prediksiMap[$data->id]->prediksi == 1 ? 'rgba(255, 0, 0, 0.4)' : 'rgba(0, 255, 0, 0.3)' }}; color: black; padding: 1px 5px; border-radius: 5px; ">
                                            {{ $prediksiMap[$data->id]->prediksi == 1 ? 'Telat Bayar' : 'Tepat Waktu' }}
                                        </span>
                                    @else
                                        -
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('exportData', ['nis' => $data->nis]) }}" method="GET">
                        <button type="submit" class="btn btn-primary">Export File</button>
                    </form>
                </l>
                <div class="col-lg-7">
                    <div class="card mb-4">
                        <div class="card-body">
                            <table class="table table-bordered" id="pembayaran-table">
                                <thead>
                                    <tr>
                                        <th style="width: 10px; display: none;" class="bulan-column">Bulan</th>
                                        <th>Nominal</th>
                                        <th>Status</th>
                                        <th>Tanggal Lunas</th>
                                        @if(auth()->user()->role == "admin")
                                            <th>Aksi</th>
                                            <th>Cicilan</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($pembayarans as $pembayaran)
                                        <tr class="align-middle"
                                            data-jenis-pembayaran-id="{{ $pembayaran->id_jenis_pembayaran }}">
                                            <td class="bulan-cell" style="display: none;">{{ $pembayaran->bulan }}</td>
                                            <td class="tahun-ajaran-cell" style="display: none;">{{ $pembayaran->tahun_ajaran }}</td> 
                                            <td>Rp {{ number_format($pembayaran->jenisPembayaran->nominal, 0, ',', '.') }}
                                            </td>
                                            <td>{{ $pembayaran->status_pembayaran }}</td>
                                            <td>{{ $pembayaran->tanggal_lunas ? \Carbon\Carbon::parse($pembayaran->tanggal_lunas)->format('d F Y') : '-' }}
                                            </td>
                                            @if(auth()->user()->role == "admin")
                                                <th>
                                                    <button class="btn btn-primary edit" value="{{ $pembayaran->id }}"
                                                        style="color: white; text-decoration: none; padding: 0.2rem 0.3rem; font-size: 0.6rem;"><i
                                                            class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button class="btn btn-danger hapus" value="{{ $pembayaran->id }}"
                                                        style="padding: 0.2rem 0.3rem; font-size: 0.6rem;"><i
                                                            class="fa-solid fa-trash"></i></button>
                                                </th>
                                                <th>
                                                    <button type="button" class="btn btn-warning showCicilan"
                                                        value="{{ $pembayaran->id }}" data-bs-toggle="modal"
                                                        style="padding: 0.2rem 0.3rem; font-size: 0.6rem;">
                                                        <i class="fa-solid fa-eye" style="color: white;"></i>
                                                    </button>
                                                </th>
                                            @endif
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="{{ auth()->user()->role == 'admin' ? 5 : 4 }}" class="text-center">
                                                Tidak ada data pembayaran.
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <!-- View Cicilan Modal -->
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
                                        <button type="button" class="btn btn-success  ms-2" data-bs-toggle="modal"
                                            data-bs-target="#tambahCicilanModal"> Tambah
                                        </button>
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Tambah Cicilan -->
                        <div class="modal fade" id="tambahCicilanModal" tabindex="-1"
                            aria-labelledby="tambahCicilanModalLabel" aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <form id="tambahCicilanForm" action="{{ route('tambahCicilan') }}" method="post">
                                        @csrf
                                        <div class="modal-header">
                                            <h1 class="modal-title fs-5" id="tambahCicilanModalLabel">Tambah Cicilan
                                            </h1>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" name="id_pembayaran" id="tambah_id_pembayaran">
                                            <div class="mb-3">
                                                <label for="tambah_nominal_cicilan" class="form-label">Nominal
                                                    Cicilan</label>
                                                <input type="number" class="form-control" id="tambah_nominal_cicilan"
                                                    name="nominal" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="tambah_tanggal_bayar_cicilan" class="form-label">Tanggal
                                                    Bayar</label>
                                                <input type="date" class="form-control"
                                                    id="tambah_tanggal_bayar_cicilan" name="tanggal_bayar" required>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-primary">Simpan Cicilan</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <!-- Card Footer-->
                        <div class="card-footer clearfix">
                            {{ $pembayarans->links() }}
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row-->
        </div>
    </div>
    <!--end::Row-->

    </div>
    <!--end::Container-->
    </div>
</main>