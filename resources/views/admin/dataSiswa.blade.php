@extends("layout.main")
@section("content")

<main class="app-main">
    <!--begin::App Content Header-->
    <div class="app-content-header">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row align-items-center d-flex justify-content-center">
                <div class="col-sm-2 text-center">
                    <h3 class="mb-0">Data Siswa</h3>
                </div>
                <div class="col-sm-6">
                    <form class="d-flex" role="search" method="GET" action="{{ route('dataSiswaAdmin') }}">
                        <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search" name="search" value="{{ $request->get('search') }}">
                        <button class="btn btn-primary " type="submit">Search</button>
                    </form>
                </div>
                <div class="col-sm-3 text-center">
                    <div class="btn-group">
                        <button type="button" class="btn btn-primary dropdown-toggle" style="min-width:80px "
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
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content Header-->

    <!--begin::App Content-->
    <div class="app-content">
        <!--begin::Container-->
        <div class="container-fluid">
            <!--begin::Row-->
            <div class="row justify-content-center">
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
                                        <th>NAMA</th>
                                        <th>KELAS</th>
                                        <th style="width: 40px">JURUSAN</th>
                                        <th>ALAMAT</th>
                                        <th>AKSI</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($users as $user)
                                        <tr class="align-middle">
                                            <td>{{$user->nis}}</td>
                                            <td>{{$user->name}}</td>
                                            <td>{{$user->kelas->tingkat_kelas}}</td>
                                            <td>{{$user->kelas->jurusan}}</td>
                                            <td>{{$user->alamat}}</td>
                                            <td>
                                                <button class="btn btn-primary">Edit</button>
                                                <button class="btn btn-danger">Hapus</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.card-body -->

                        <div class="card-footer clearfix">
                            <button class="tambah-data btn btn-primary">Tambah Data</button>
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#uploadModal">Import
                                Excel</button>

                            <!-- Upload Excel Modal -->
                            <div class="modal fade" id="uploadModal" tabindex="-1" aria-labelledby="uploadModalLabel"
                                aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="uploadModalLabel">Upload Excel File</h5>
                                        </div>
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <a href="template.xlsx" download class="text-primary"
                                                    style="text-decoration:none;">*Download template
                                                    Excel</a>
                                            </div>
                                            <form id="uploadForm" action="{{route("importExcel")}}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <input type="file" class="form-control" id="excelFile"
                                                    accept=".xlsx, .xls" name="file" required>

                                                <div class="modal-footer d-flex justify-content-center">
                                                    <button type="button" class="btn btn-secondary"
                                                        data-bs-dismiss="modal">Batal</button>
                                                    <button type="submit" class="btn btn-primary">Upload</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                </div>
                            </div>

                           <div class="float-end">
                             {{$users->links()}}
                           </div>
                        </div>
                    </div>
                </div>
            </div>
            <!--end::Row-->
        </div>
        <!--end::Container-->
    </div>
    <!--end::App Content-->

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
            document.querySelectorAll(".tambah-data").forEach(item => {
                item.addEventListener("click", function (event) {
                    event.preventDefault();

                    fetch(`/tambah-data/`)
                        .then(response => response.text())
                        .then(html => {
                            document.querySelector(".app-main").innerHTML = html;

                            // Change URL without reload page
                            window.history.pushState({}, "", `/tambah-data/`);
                        })
                        .catch(error => console.error("Error:", error));
                });
            });
        });

        document.getElementById("uploadButton").addEventListener("click", function () {
            document.getElementById("uploadForm").submit();
        })

    </script>

</main>
@endsection