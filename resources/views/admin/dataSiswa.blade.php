@extends("layout.main")
@section("content")

    <!-- begin::Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteForm" action="{{ route('deleteDataSiswaAdmin') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Data Siswa</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="delete_nis" id="siswa_nis">
                        <p>Apakah kamu yakin akan menghapus data NIS <span id="nis_display"></span>?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Ya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end::Delete Modal -->
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row align-items-center d-flex justify-content-center">
                    <div class="col-sm-auto text-center">
                        <h3 class="mb-0">Data Siswa</h3>
                    </div>
                    <div class="col-sm-6">
                        <form class="d-flex" role="search" method="GET" action="{{ route('dataSiswaAdmin') }}">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"
                                name="search" value="{{ $request->get("search") }}">
                            <button class="btn btn-primary " type="submit">Search</button>
                        </form>
                    </div>
                    <div class="col-sm-auto text-center">
                        <form action="{{ route("dataSiswaAdmin") }}" method="get" class="d-flex">
                            <!-- begin::Filter kelas-->
                            <select name="kelas" id="kelas" class="form-select me-2" style="width: 100px;">
                                <option value="">Kelas</option>
                                <option value="X">X</option>
                                <option value="XI">XI</option>
                                <option value="XII">XII</option>
                            </select>
                            <!-- end::Filter kelas-->
                            <button class="btn btn-primary " type="submit">Filter</button>
                        </form>
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
                                        @if($users->isEmpty())
                                            <tr>
                                                <td colspan="6" style="text-align: center;"> DATA TIDAK ADA</td>
                                            </tr>
                                        @else
                                            @foreach($users as $user)
                                                <tr class="align-middle">
                                                    <td>{{$user->nis}}</td>
                                                    <td>{{$user->name}}</td>
                                                    <td>{{$user->kelas->tingkat_kelas}}</td>
                                                    <td>{{$user->kelas->jurusan}}</td>
                                                    <td>{{$user->alamat}}</td>
                                                    <td>
                                                        <button class="btn btn-primary"><a href="" class="edit"
                                                                value="{{ $user->nis }}"
                                                                style="color: white; text-decoration: none;"><i class="fa-solid fa-pen-to-square"></i></a></button>
                                                        <button class="btn btn-danger hapus" value="{{ $user->nis }}"><i class="fa-solid fa-trash"></i></button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        @endif
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

                                <div class="pagination float-end">
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
            // Delete Data
            $(document).ready(function () {
                $(".hapus").click(function (e) {

                    e.preventDefault();
                    // Get NIS from button
                    var siswa_nis = $(this).val();
                    // Send nis to modal
                    $('#siswa_nis').val(siswa_nis);
                    // Show nis in body modal
                    $('#nis_display').text(siswa_nis);
                    // Show modal
                    $("#deleteModal").modal("show");
                });
            });


            // Get Edit Data Siswa Page
            document.addEventListener("DOMContentLoaded", function () {
                document.querySelector(".app-main").addEventListener("click", function (e) {
                    if (e.target.classList.contains("edit")) {
                        e.preventDefault();

                        // Get NIS from button
                        var siswa_nis = e.target.getAttribute("value");

                        fetch(`/dataSiswaAdmin/edit/${siswa_nis}`)
                            .then(response => response.text())
                            .then(html => {
                                document.querySelector(".app-main").innerHTML = html;

                                // Mengubah URL tanpa reload halaman
                                window.history.pushState({}, "", `/dataSiswaAdmin/edit/${siswa_nis}`);
                            })
                            .catch(error => console.error("Error:", error));
                    }
                });
            });

            // Add Data
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