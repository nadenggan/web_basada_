@extends("layout.main")
@section("content")
<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
    </div>
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row justify-content-center">
                <div class="col-lg-6">
                    <div class="card mb-4">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Presentase Status Pembayaran</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <!--  isi diagram -->
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
                <div class="col-lg-5">
                    <div class="card mb-4">
                        <div class="card-header border-0">
                            <div class="d-flex justify-content-between">
                                <h3 class="card-title">Rangkuman Informasi</h3>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-5">Total Siswa</div>
                                <div class="col-7">: 1000</div>
                            </div>
                            <div class="row">
                                <div class="col-5">Total Siswa Kelas X</div>
                                <div class="col-7">: 3000</div>
                            </div>
                            <div class="row">
                                <div class="col-5">Total Siswa Kelas XI</div>
                                <div class="col-7">: 300</div>
                            </div>
                            <div class="row">
                                <div class="col-5">Total Siswa Kelas XII</div>
                                <div class="col-7">: 400</div>
                            </div>
                            <div class="row">
                                <div class="col-6">Total Jenis Pembayaran</div>
                                <div class="col-6">: 16</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row  align-items-center d-flex justify-content-around">
                <div class="col-sm-5" style="font-size: 28px;">
                    <b>Data Siswa</b>
                </div>
                <div class="col-sm-5 d-flex justify-content-end" ">
                        <div class=" btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle me-1" style="min-width:80px "
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Kelas
                    </button>
                    <ul class="dropdown-menu" style="min-width:80px;">
                        <li><a class="dropdown-item" id="10" href="#">X</a></li>
                        <li><a class="dropdown-item" id="11" href="#">XI</a></li>
                        <li><a class="dropdown-item" id="12" href="#">XII</a></li>
                    </ul>
                </div>
                <div class="btn-group">
                    <button type="button" class="btn btn-primary dropdown-toggle" style="min-width:95px"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Jurusan
                    </button>
                    <ul class="dropdown-menu" style="min-width:95px;">
                        <li><a class="dropdown-item" href="#">TKJ</a></li>
                        <li><a class="dropdown-item" href="#">AK</a></li>
                        <li><a class="dropdown-item" href="#">MPLB</a></li>
                        <li><a class="dropdown-item" href="#">BDP</a></li>
                        <li><a class="dropdown-item" href="#">OTKP</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <!--end::Row-->
        <!--begin::Row-->
        <div class="row justify-content-center mt-2">
            <div class="col-md-11">
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="card-title">Bordered Table</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th style="width: 10px">NIS</th>
                                    <th>Nama</th>
                                    <th>Kelas</th>
                                    <th style="width: 40px">Jurusan</th>
                                    <th>Alamat</th>
                                    <th>Pembayaran</th>
                                    <th>Prediksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="align-middle">
                                    <td>133</td>
                                    <td>Ananda Kila</td>
                                    <td>10 </td>
                                    <td>TKJT A</td>
                                    <td>Jl. Pahlawan No.12, Kelurahan Bumirejo, Kecamatan Kebumen</td>
                                    <td><a class="lihat-rekap" href="" data-nis="133">Lihat Rekap</a></td>
                                    <td>Tepat Waktu</td>
                            </tbody>
                        </table>
                    </div>
                    <!-- /.card-body -->

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
    <!--end::Container-->
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".btn-group").forEach(group => {
                let button = group.querySelector(".dropdown-toggle");
                let defaultText = button.textContent;

                group.querySelectorAll(".dropdown-item").forEach(item => {
                    item.addEventListener("click", function (event) {
                        event.preventDefault(); // Prevents page reload when clicking item

                        let selectedText = this.textContent.replace(" ✓", ""); // Remove exist checklist
                        let menuItems = group.querySelectorAll(".dropdown-item");

                        // Find and remove checklist in items
                        menuItems.forEach(i => i.innerHTML = i.textContent.replace(" ✓", ""));

                        if (button.textContent === selectedText) {
                            button.textContent = defaultText;
                        } else {
                            this.innerHTML = selectedText + " ✓"; // Add checkmark
                            button.textContent = selectedText; // Update button text
                        }
                    })
                })

            });
        });


        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".lihat-rekap").forEach(item => {
                item.addEventListener("click", function (event) {
                    event.preventDefault();
                    let nis = this.getAttribute("data-nis");

                    fetch(`/rekap-pembayaran/${nis}`)
                        .then(response => response.text())
                        .then(html => {
                            document.querySelector(".app-main").innerHTML = html;

                            // Mengubah URL tanpa reload halaman
                            window.history.pushState({}, "", `/rekap-pembayaran/${nis}`);
                        })
                        .catch(error => console.error("Error:", error));
                });
            });
        });

    </script>
</main>
@endsection