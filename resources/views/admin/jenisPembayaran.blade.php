@extends("layout.main")
@section("content")
    <!-- begin::Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form id="deleteForm" action="{{ route('deleteJenisPembayaran') }}" method="post">
                    @csrf
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Hapus Jenis Pembayaran</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" name="delete_id" id="jenisPembayaran">
                        <p>Apakah kamu yakin akan menghapus Jenis Pembayaran ini?</p>
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
                    <div class="col-auto text-center">
                        <h3 class="mb-0">Jenis Pembayaran</h3>
                    </div>
                    <div class="col-sm-6">
                        <form class="d-flex" role="search" method="get" action="{{ route('jenisPembayaranAdmin') }}">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"
                                name="search" value="{{  $request->get('search')}}">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </form>
                    </div>
                    <div class="col-auto text-center">
                        <form action="{{ route("jenisPembayaranAdmin") }}" method="get" class="d-flex">
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
                                        @if($data->isEmpty())
                                            <tr>
                                                <td colspan="8" style="text-align: center;"> DATA TIDAK ADA</td>
                                            </tr>
                                        @else
                                            @foreach($data as $item)
                                                <tr class="align-middle">
                                                    <td>{{ $loop->iteration  }}</td>
                                                    <td>{{ $item->nama_jenis_pembayaran }}</td>
                                                    <td>{{ $item->deskripsi }}</td>
                                                    <td>{{ implode(', ', json_decode($item->tingkat_kelas, true)) }}</td>
                                                    <td>Rp.{{ $item->nominal }}</td>
                                                    <td>{{ $item->periode }}</td>
                                                    <td>{{ $item->periode === 'bulanan' ? $item->tanggal_bulanan : $item->tenggat_waktu }}
                                                    </td>
                                                    <td> <button class="btn btn-primary "><a href="" class="edit"
                                                                value="{{ $item->id }}"
                                                                style="color: white; text-decoration: none;">Edit</a></button>
                                                        <button class="btn btn-danger hapus" value="{{ $item->id }}">Hapus</button>
                                                    </td>
                                            @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer clearfix">
                                {{$data->links()}}
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
                    // Getidfrom button
                    var jenisPembayaran = $(this).val();
                    // Send to modal
                    $('#jenisPembayaran').val(jenisPembayaran);
                    // Show modal
                    $("#deleteModal").modal("show");
                });
            });

            // Get Edit Jenis Pembayaran Page
            document.addEventListener("DOMContentLoaded", function () {
                document.querySelector(".app-main").addEventListener("click", function (e) {
                    if (e.target.classList.contains("edit")) {
                        e.preventDefault();

                        // Get id from button
                        var jenisPembayaran = e.target.getAttribute("value");

                        fetch(`/jenisPembayaranAdmin/edit/${jenisPembayaran}`)
                            .then(response => response.text())
                            .then(html => {
                                document.querySelector(".app-main").innerHTML = html;

                                const periodeSelect = document.getElementById("periode");
                                const tenggatWaktuInput = document.querySelector("input[name='tenggat_waktu']");

                                if (periodeSelect && tenggatWaktuInput) {
                                    periodeSelect.addEventListener("change", function () {
                                        if (this.value === "bulanan") {
                                            tenggatWaktuInput.type = "number";
                                            tenggatWaktuInput.placeholder = "Masukkan tanggal (1-31)";
                                            tenggatWaktuInput.min = 1;
                                            tenggatWaktuInput.max = 31;
                                        } else {
                                            tenggatWaktuInput.type = "date";
                                            tenggatWaktuInput.placeholder = "";
                                            tenggatWaktuInput.removeAttribute("min");
                                            tenggatWaktuInput.removeAttribute("max");
                                        }
                                    });

                                    // Change periode and tenggat waktu in form edit
                                    if (periodeSelect.value === "bulanan") {
                                        tenggatWaktuInput.type = "number";
                                        tenggatWaktuInput.placeholder = "Masukkan tanggal (1-31)";
                                        tenggatWaktuInput.min = 1;
                                        tenggatWaktuInput.max = 31;
                                    } else {
                                        tenggatWaktuInput.type = "date";
                                        tenggatWaktuInput.placeholder = "";
                                        tenggatWaktuInput.removeAttribute("min");
                                        tenggatWaktuInput.removeAttribute("max");
                                    }
                                }

                                // Mengubah URL tanpa reload halaman
                                window.history.pushState({}, "", `/jenisPembayaranAdmin/edit/${jenisPembayaran}`);
                            })
                            .catch(error => console.error("Error:", error));
                    }
                });
            });

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