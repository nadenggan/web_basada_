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
                                        <label class="form-label required">Nama Jenis Pembayaran</label>
                                        <input type="text" class="form-control" name="nama_jenis_pembayaran" />
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label">Deskripsi</label>
                                        <textarea class="form-control" name="deskripsi" rows="3"></textarea>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label required">Nominal</label>
                                        <input class="form-control" name="nominal"></input>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label required">Tingkat Kelas</label>
                                        <div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="tingkat_kelas[]"
                                                    value="X">
                                                <label class="form-check-label" for="tingkatKelasX">X</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="tingkat_kelas[]"
                                                    value="XI">
                                                <label class="form-check-label" for="tingkatKelasXI">XI</label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="tingkat_kelas[]"
                                                    value="XII">
                                                <label class="form-check-label" for="tingkatKelasXII">XII</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label required"> Periode</label>
                                        <select name="periode" id="periode" class="form-select">
                                            <option selected>Pilih Periode</option>
                                            <option value="bulanan">Bulanan</option>
                                            <option value="tahunan">Tahunan</option>

                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label required">Tenggat Waktu</label>
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            console.log('Event listener berjalan');
            const periodeSelect = document.getElementById("periode");
            const tenggatWaktuInput = document.querySelector("input[name='tenggat_waktu']");

            periodeSelect.addEventListener("change", function () {
                if (this.value === "bulanan") {
                    tenggatWaktuInput.type = "number";
                    tenggatWaktuInput.placeholder = "Masukkan tanggal (1-31)";
                    tenggatWaktuInput.min = 1;
                    tenggatWaktuInput.max = 31;
                } else {
                    tenggatWaktuInput.type = "date";
                    tenggatWaktuInput.placeholder = "";
                }
            });
        });

    </script>
@endsection