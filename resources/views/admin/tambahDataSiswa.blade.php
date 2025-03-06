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
                            <div class="card-title">Tambah Data Siswa</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Form-->
                        <form>
                            <!--begin::Body-->
                            <div class="card-body">
                                <div class="row mb-3">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">NIS</label>
                                    <div class="col-sm-10">
                                        <input type="email" class="form-control" id="inputEmail3" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Nama</label>
                                    <div class="col-sm-10">
                                        <input type="password" class="form-control" id="inputPassword3" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Alamat</label>
                                    <div class="col-sm-10">
                                        <textarea type="password" class="form-control" id="inputPassword3"></textarea>
                                    </div>
                                </div>
                                <fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Kelas</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios"
                                                id="gridRadios1" value="option1" checked />
                                            <label class="form-check-label" for="gridRadios1">X</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios"
                                                id="gridRadios2" value="option2" />
                                            <label class="form-check-label" for="gridRadios2">XI</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios"
                                                id="gridRadios2" value="option2" />
                                            <label class="form-check-label" for="gridRadios2">XII</label>
                                        </div>
                                    </div>
                                </fieldset>
                                <fieldset class="row mb-3">
                                    <legend class="col-form-label col-sm-2 pt-0">Jurusan</legend>
                                    <div class="col-sm-10">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios"
                                                id="gridRadios1" value="option1" checked />
                                                <label class="form-check-label" for="gridRadios1">TJKT A</label>
                                                <label class="form-check-label" for="gridRadios1">TJKT B</label>
                                            <label class="form-check-label" for="gridRadios1">TKJ</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios"
                                                id="gridRadios2" value="option2" />
                                            <label class="form-check-label" for="gridRadios2">AK</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios"
                                                id="gridRadios2" value="option2" />
                                                <label class="form-check-label" for="gridRadios2">AKL</label>
                                                <label class="form-check-label" for="gridRadios2">MPLB A</label>
                                            <label class="form-check-label" for="gridRadios2">MPLB B</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios"
                                                id="gridRadios2" value="option2" />
                                            <label class="form-check-label" for="gridRadios2">BDP</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="gridRadios"
                                                id="gridRadios2" value="option2" />
                                            <label class="form-check-label" for="gridRadios2">OTKP</label>
                                        </div>
                                    </div>
                                </fieldset>
                            </div>
                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="card-footer">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <a href="{{ route('dataSiswaAdmin') }}" class="btn btn-secondary">
                                    Batal
                                </a>
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