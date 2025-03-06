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
                        <form method="post" action="{{ route("storeDataSiswa") }}">
                            @csrf
                            <!--begin::Body-->
                            <div class="card-body">
                                <div class="row mb-3">
                                    <label for="inputEmail3" class="col-sm-2 col-form-label">NIS</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name="nis" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Nama</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" name='name'" />
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Alamat</label>
                                    <div class="col-sm-10">
                                        <textarea type="password" class="form-control" name="alamat"></textarea>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="inputPassword3" class="col-sm-2 col-form-label">Kelas</label>
                                    <div class="col-sm-10">
                                        <select name="id_kelas" id="kelas" class="form-select" style="width: 150px;">
                                            <option selected>Pilih Kelas</option>
                                            @foreach($kelas as $k)
                                                <option value="{{ $k->tingkat_kelas }}-{{ $k->jurusan}}">{{ $k->tingkat_kelas }}-{{ $k->jurusan}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

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