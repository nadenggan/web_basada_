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
                <div class="row  align-items-center d-flex justify-content-center">
                    <div class="col-sm-auto text-center" style="font-size: 28px;">
                        <b>Data Siswa</b>
                    </div>
                    <div class="col-sm-6">
                        <form class="d-flex" role="search" method="GET" action="{{ route('home') }}">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"
                                name="search" value="{{ $request->get("search") }}">
                            <button class="btn btn-primary " type="submit">Search</button>
                        </form>
                    </div>
                    <div class="col-sm-auto text-center">
                        <form action="{{ route("home") }}" method="get" class="d-flex">
                            <!-- begin::Filter kelas-->
                            <select name="kelas" id="kelas" class="form-control me-2" style="width: 100px;">
                                <option selected>Kelas</option>
                                <option value="X">X</option>
                                <option value="XI">XI</option>
                                <option value="XII">XII</option>
                            </select>
                            <!-- end::Filter kelas-->
                            <button class="btn btn-primary " type="submit">Filter</button>
                        </form>
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
                                    @foreach ($users as $user)
                                        <tr class="align-middle">
                                            <td>{{ $user->nis }}</td>
                                            <td>{{ $user->name }}</td>
                                            <td>{{ $user->kelas->tingkat_kelas }} </td>
                                            <td>{{ $user->kelas->jurusan }}</td>
                                            <td>{{ $user->alamat }}</td>
                                            <td><a class="lihat-rekap" href="" data-nis="133">Lihat Rekap</a></td>
                                            <td>Tepat Waktu</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->

                        <div class="d-flex justify-content-center">
                            {{ $users->links() }}
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
                                filterTable("");
                            } else {
                                this.innerHTML = selectedText + " ✓"; // Add checkmark
                                button.textContent = selectedText; // Update button text
                                filterTable(selectedText);
                            }
                        })
                    })

                });
            });

            function filterTable(selectedClass) {
                let rows = document.querySelectorAll("tbody tr");
                rows.forEach(row => {
                    let kelasColumn = row.querySelector("td:nth-child(3)"); // Ambil kolom kelas dari setiap baris
                    let kelasValue = kelasColumn.textContent.trim(); // Ambil teks dari kolom kelas (tanpa spasi ekstra)


                    if (selectedClass === "") {
                        row.style.display = "";
                    } else if (kelasValue === selectedClass) {
                        row.style.display = "";
                    }
                })
            }

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