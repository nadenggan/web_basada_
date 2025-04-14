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
                <div class="col-sm-7">
                    <!-- begin::Filter Jenis Pembayaran-->
                    <select name="jenisPembayaran" id="jenisPembayaran" class="form-select me-2" style="width: 220px;">
                        <option value="">Semua Jenis Pembayaran</option>
                        @foreach ($jenisPembayaran as $jenis)
                            <option value="{{ $jenis->id }}" data-periode="{{ $jenis->periode }}">
                                {{$jenis->nama_jenis_pembayaran}}</option>
                        @endforeach
                    </select>
                    <!-- end::Filter Jenis Pembayaran-->
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
                <div class="col-lg-4">
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
                                <div class="col-9">: Tepat Waktu</div>
                            </div>
                        </div>
                    </div>
                    <button class="btn btn-primary">Export File</button>
                </div>
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
                                            <td>Rp {{ number_format($pembayaran->jenisPembayaran->nominal, 0, ',', '.') }}</td>
                                            <td>{{ $pembayaran->status_pembayaran }}</td>
                                            <td>{{ $pembayaran->tanggal_lunas ? \Carbon\Carbon::parse($pembayaran->tanggal_lunas)->format('d F Y') : '-' }}
                                            </td>
                                            @if(auth()->user()->role == "admin")
                                                <th>
                                                    <button class="btn btn-primary"
                                                        style="color: white; text-decoration: none;"><i
                                                            class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                                                </th>
                                                <th>
                                                    <button class="btn btn-primary"
                                                        style="color: white; text-decoration: none;"><i
                                                            class="fa-solid fa-pen-to-square"></i>
                                                    </button>
                                                    <button type="button" class="btn btn-warning" data-bs-toggle="modal"
                                                        data-bs-target="#viewCicilanModal">
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
                                        ...
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Close</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Card Footer-->
                        <div class="card-footer clearfix">
                            <ul class="pagination pagination-sm m-0 float-end">
                                <li class="page-item"><a class="page-link" href="#">&laquo;</a></li>
                                <li class="page-item"><a class="page-link" href="#">1</a></li>
                                <li class="page-item"><a class="page-link" href="#">2</a></li>
                                <li class="page-item"><a class="page-link" href="#">3</a></li>
                                <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                            </ul>
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