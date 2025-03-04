@extends("layout.main")
@section("content")
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row align-items-center d-flex justify-content-center">
                    <div class="col-sm-3 text-center">
                        <h3 class="mb-0">Jenis Pembayaran</h3>
                    </div>
                    <div class="col-sm-6">
                        <form class="d-flex" role="search" method="get" action="{{ route('jenisPembayaranAdmin') }}">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"
                                name="search" value="{{  $request->get('search')}}">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </form>
                    </div>
                    <div class="col-sm-2 text-center">
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
                                            <th style="width: 10px">No</th>
                                            <th>Jenis Pembayaran</th>
                                            <th>Deskripsi</th>
                                            <th style="width: 40px">Kelas</th>
                                            <th>Nominal</th>
                                            <th>Periode</th>
                                            <th>Deadline</th>
                                            <th>AKSI</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $item)
                                            <tr class="align-middle">
                                                <td>1</td>
                                                <td>{{ $item->nama_jenis_pembayaran }}</td>
                                                <td>{{ $item->deskripsi }}</td>
                                                <td>{{ $item->tingkat_kelas }}</td>
                                                <td>Rp.{{ $item->nominal }}</td>
                                                <td>{{ $item->periode }}</td>
                                                <td>{{ $item->tenggat_waktu }}</td>
                                                <td>Button</td>
                                        @endforeach
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
        </script>
    </main>

@endsection