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
                        <form>
                            <!--begin::Body-->
                            <div class="card-body">
                                <div class="mb-3">
                                    <label for="exampleInputEmail1" class="form-label">Nama Jenis Pembayaran</label>
                                    <input type="email" class="form-control" id="exampleInputEmail1"
                                        aria-describedby="emailHelp" />
                                </div>
                                <div class="mb-3">
                                    <label for="exampleInputPassword1" class="form-label">Deskripsi</label>
                                    <textarea class="form-control" id="exampleFormControlTextarea1" rows="3"></textarea>
                                </div>
                                <fieldset class="row mb-3">
                                    <div class="col-md-6">
                                        <legend class="col-form-label  pt-0">Tingkat Kelas</legend>
                                        <div class="col-sm-10">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tingkatKelas"
                                                    id="kelas10" value="kelas10" checked />
                                                <label class="form-check-label" for="kelas10"> Kelas 10 </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tingkatKelas"
                                                    id="kelas11" value="kelas11" />
                                                <label class="form-check-label" for="kelas11"> Kelas 11 </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="tingkatKelas"
                                                    id="kelas12" value="kelas12" />
                                                <label class="form-check-label" for="kelas12"> Kelas 12 </label>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="col-md-6">
                                        <legend class="col-form-label  pt-0">Periode</legend>
                                        <div class="col-sm-10">
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="periode"
                                                    id="gridRadios1" value="bulanan" checked />
                                                <label class="form-check-label" for="gridRadios1"> Bulanan </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="periode"
                                                    id="gridRadios2" value="tahunan" />
                                                <label class="form-check-label" for="gridRadios2"> Tahunan </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="periode"
                                                    id="gridRadios2" value="sekali" />
                                                <label class="form-check-label" for="gridRadios2"> Sekali </label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <row class="row mb-3">
                                    <div class="col-md-6">
                                        <label for="exampleInputPassword1" class="form-label">Nominal</label>
                                        <input id="nominal" class="form-control">
                                    </div>

                                    <!--  Sekali option  -->
                                    <div style="display:none;" class="col-md-6">
                                        <label for="deadline" class="form-label">Deadline
                                            Pembayaran</label>
                                        <input type="date" id="deadlineSekali" class="form-control">
                                    </div>
                                    <!--  Bulanan option  -->
                                    <div style="display:none;" class="col-md-6">
                                        <label for="deadline" class="form-label">Deadline
                                            Pembayaran</label>
                                        <input id="deadlineBulanan" class="form-control" placeholder="Masukkan tanggal">
                                    </div>
                                </row>
                            </div>
                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Tambah</button>
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

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const periode = document.querySelectorAll('input[name="periode"]');
            const deadlineSekaliDiv = document.getElementById("deadlineSekali").parentElement;
            const deadlineBulananDiv = document.getElementById("deadlineBulanan").parentElement; /* parentElement for div */

            function updateDeadline() {
                let selectedValue = document.querySelector('input[name="periode"]:checked').value;

                if (selectedValue === "sekali") {
                    deadlineSekaliDiv.style.display = "block";
                    deadlineBulananDiv.style.display = "none"
                } else if (selectedValue === "bulanan") {
                    deadlineSekaliDiv.style.display = "none";
                    deadlineBulananDiv.style.display = "block";
                } else {
                    deadlineSekaliDiv.style.display = "none";
                    deadlineBulananDiv.style.display = "none";
                }
            }

            periode.forEach(radio => {
                radio.addEventListener("change", updateDeadline)
            })

            updateDeadline(); // Visible on page load
        })
    </script>
</main>

@endsection