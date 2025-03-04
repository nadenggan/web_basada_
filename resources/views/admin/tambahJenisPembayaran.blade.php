@extends("layout.main")
@section("content")
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
                                <div class="card-title">Tambah Jenis Pembayaran</div>
                            </div>
                            <!--end::Header-->
                            <!--begin::Form-->
                            <form method="POST" action="{{ route('inputJenisPembayaran') }}">
                                @csrf

                                <!--begin::Body-->
                                <div class="card-body">
                                    <div class="mb-3">
                                        <label class="form-label">Nama Jenis Pembayaran</label>
                                        <input type="text" class="form-control" name="nama_jenis_pembayaran" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="deskripsi" rows="3"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Nominal</label>
                                        <input class="form-control" name="nominal"></input>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tingkat Kelas</label>
                                        <select name="tingkat_kelas" id="tingkatKelas" class="form-control">
                                            <option selected>Pilih Tingkat Kelas</option>
                                            <option value="X">X</option>
                                            <option value="XI">XI</option>
                                            <option value="XII">XII</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label"> Periode</label>
                                        <select name="periode" id="periode" class="form-control">
                                            <option selected>Pilih Periode</option>
                                            <option value="bulanan">Bulanan</option>
                                            <option value="sekali">Sekali</option>
                                            <option value="tahunan">Tahunan</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label">Tenggat Waktu</label>
                                        <input type="date" class="form-control" name="tenggat_waktu">
                                    </div>
                                </div>
                                <!--end::Body-->
                                <!--begin::Footer-->
                                <div class="card-footer">
                                    <button type="submit" class="btn btn-primary">Submit</button>
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

@endsection