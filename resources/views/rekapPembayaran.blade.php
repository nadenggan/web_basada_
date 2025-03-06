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
                    <select name="jenisPembayaran" id="jenisPembayaran" class="form-select me-2" style="width: 172px;">
                        <option selected>Jenis Pembayaran</option>
                        <option value="SPP">SPP</option>
                        <option value="Uang Kegiatan">Uang Kegiatan</option>
                        <option value="Uang Praktikum">Uang Praktikum</option>
                        <option value="Sarpras">Sarpras</option>
                        <option value="Seragam Putra">Seragam Putra</option>
                        <option value="Seragam Putri">Seragam Putri</option>
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
                        <div class="card-header">
                            <h3 class="card-title">Bordered Table</h3>
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">Bulan</th>
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
                                    <tr class="align-middle">
                                        <td>1</td>
                                        <td>Rp 130,000</td>
                                        <td>Lunas</td>
                                        <td>2 Januari 2024</td>
                                        @if(auth()->user()->role == "admin")
                                            <th><ion-icon name="create"></ion-icon></th>
                                            <th><ion-icon name="eye" data-bs-toggle="modal" data-bs-target="#uploadModal">
                                                </ion-icon><ion-icon name="create"></ion-icon></th>
                                        @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- View Cicilan Modal -->
                        <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel"
                            aria-hidden="true">
                            <div class="modal-dialog modal-dialog-centered">
                                <div class="modal-content">
                                    <div class="modal-header d-flex justify-content-between">
                                        <h5 class="modal-title" id="uploadModalLabel">Cicilan SPP Bulan
                                            ke-8
                                        </h5>
                                        <ion-icon name="close" data-bs-dismiss="modal"></ion-icon>
                                    </div>
                                    <div class="modal-body">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th style="width: 10px">No</th>
                                                    <th>Nominal</th>
                                                    <th>Tanggal Bayar</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>1</td>
                                                    <td>Rp 50,000</td>
                                                    <td>1 Agustus 2024</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

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