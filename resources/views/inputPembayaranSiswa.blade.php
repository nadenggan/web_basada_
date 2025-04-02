<main class="app-main">

    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row mt-4 d-flex justify-content-center align-items-center">

                <!--begin::Col-->
                <div class="col-md-8">
                    <!--begin::Horizontal Form-->
                    <div class="card card-primary card-outline mb-4">
                        <!--begin::Header-->
                        <div class="card-header">
                            <div class="card-title">Input Pembayaran Siswa</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Form-->
                        <form method="POST" action="{{ route('storePembayaranSiswa')}}">
                            @csrf

                            <!--begin::Body-->
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">NIS</label>
                                    <input type="text" class="form-control" name="nis" value="{{ $data->nis }}" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Siswa</label>
                                    <input type="text" class="form-control" name="name" value="{{ $data->name }}" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Jenis Pembayaran</label>
                                    <select id="id_jenis_pembayaran" class="form-select" name="id_jenis_pembayaran">
                                        <option selected>Pilih Jenis Pembayaran</option>
                                        @foreach ($jenis_pembayaran as $jenis)
                                            <option value="{{ $jenis->id }}" data-periode="{{$jenis->periode}}">
                                                {{ $jenis->nama_jenis_pembayaran }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="mb-3" style="display: none;" id="bulan">
                                    <label class="form-label">Bulan</label>
                                    <select class="form-select" name="bulan">
                                        <option selected>Pilih Bulan</option>
                                        <option value="Januari">Januari</option>
                                        <option value="Februari">Februari</option>
                                        <option value="Maret">Maret</option>
                                        <option value="April">April</option>
                                        <option value="Mei">Mei</option>
                                        <option value="Juni">Juni</option>
                                        <option value="Juli">Juli</option>
                                        <option value="Agustus">Agustus</option>
                                        <option value="September">September</option>
                                        <option value="Oktober">Oktober</option>
                                        <option value="November">November</option>
                                        <option value="Desember">Desember</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Status Pembayaran</label>
                                    <select name="status_pembayaran" id="status" class="form-select">
                                        <option value="lunas">Lunas</option>
                                        <option value="belum-lunas">Belum Lunas</option>
                                    </select>
                                </div>

                                <div class="mb-3" style="display: none;" id="nominal_cicilan">
                                    <label  class="form-label">Nominal Cicilan</label>
                                    <input type="text" class="form-control" name="nominal_cicilan" >
                                </div>

                                <div class="mb-3" style="display: none;" id="tanggal_bayar">
                                    <label class="form-label">Tanggal Bayar</label>
                                    <input type="date" class="form-control" name="tanggal_bayar">
                                </div>

                                <div class="mb-3" id="tanggal_lunas">
                                    <label class="form-label">Tanggal Lunas</label>
                                    <input type="date" class="form-control" name="tanggal_lunas">
                                </div>
                                <!--end::Body-->
                                <!--begin::Footer-->
                                <div class="">
                                    <button type="button" class="btn btn-danger"><a href="{{ route("home") }}"
                                            style="color: white; text-decoration: none;">Kembali</a></button>
                                    <button type="submit" class="btn btn-primary">Input Pembayaran</button>
                                </div>
                                <!--end::Footer-->
                        </form>
                        <!--end::Form-->
                    </div>
                    <!--end::Horizontal Form-->
                </div>
                <!--end::Col-->
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->
</main>