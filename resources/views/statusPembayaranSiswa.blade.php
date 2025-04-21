@extends("layout.main")
@section("content")
    <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Row-->
                <div class="row align-items-center d-flex justify-content-around">
                    <div class="col-6">
                        <form class="d-flex" role="search" method="get" action="{{ route("statusPembayaranSiswa") }}">
                            <input class="form-control me-2" type="search" placeholder="Search" aria-label="Search"
                                name="search" value="{{ $request->get("search") }}">
                            <input type="hidden" name="jenisPembayaran" value="{{ $request->get('jenisPembayaran') }}">
                            <button class="btn btn-primary" type="submit">Search</button>
                        </form>
                    </div>
                    <div class="col-auto">
                        <form action="{{ route("statusPembayaranSiswa") }}" method="get" class="d-flex">
                            <!-- begin::Filter kelas-->
                            <select name="kelas" id="kelas" class="form-select me-2" style="width: 100px;">
                                <option value="">Kelas</option>
                                <option value="X">X</option>
                                <option value="XI">XI</option>
                                <option value="XII">XII</option>
                            </select>
                            <!-- end::Filter kelas-->

                            <!-- begin::Filter Jenis Pembayaran-->
                            <select name="jenisPembayaran" id="jenisPembayaran" class="form-select me-2"
                                style="width: 220px;">
                                @if($jenisPembayaran->isEmpty())
                                    <option value="">Tidak Ada Data</option>
                                @else
                                    @foreach ($jenisPembayaran as $jenis)
                                        <option value="{{ $jenis->id }}" data-periode="{{ $jenis->periode }}" {{ $request->get('jenisPembayaran') == $jenis->id ? 'selected' : '' }}>
                                            {{$jenis->nama_jenis_pembayaran}}
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            <!-- end::Filter Jenis Pembayaran-->

                            <button class="btn btn-primary " type="submit">Filter</button>
                        </form>
                    </div>
                </div>

                <div class="row mt-3">
                    <div class="col-sm-12" style="font-size: 25px; padding-left:5%"> <b>Deadline Pembayaran:
                            @if ($request->get('jenisPembayaran'))
                                                    @php
                                                        $filteredJenisPembayaran = \App\Models\JenisPembayaran::find($request->get('jenisPembayaran'));
                                                    @endphp
                                                    @if ($filteredJenisPembayaran)
                                                        @if ($filteredJenisPembayaran->periode === 'bulanan')
                                                            Setiap tanggal {{ $filteredJenisPembayaran->tanggal_bulanan }}
                                                        @else
                                                            @if ($filteredJenisPembayaran->tenggat_waktu)
                                                                {{ \Carbon\Carbon::parse($filteredJenisPembayaran->tenggat_waktu)->locale('id')->isoFormat('D MMMM YYYY') }}
                                                            @else
                                                                Tidak Ada Tenggat Waktu
                                                            @endif
                                                        @endif
                                                    @else
                                                        Jenis Pembayaran Tidak Ditemukan
                                                    @endif
                            @else
                                -
                            @endif
                        </b>
                    </div>
                    
                    <!-- Bulan Filter -->
                    @if ($showBulanFilter)
                            <div class="row mt-1 mb-2">
                                <div class="col-sm-3" style=" padding-left:5%">
                                    <form action="{{ route("statusPembayaranSiswa") }}" method="get" class="d-flex">
                                        <select name="bulan" id="bulan" class="form-select me-2">
                                            <option value="">Bulan</option>
                                            @foreach ($bulanList as $bulan)
                                                <option value="{{ $bulan }}" {{ $request->get('bulan') == $bulan ? 'selected' : '' }}>
                                                    {{ $bulan }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <input type="hidden" name="kelas" value="{{ $request->get('kelas') }}">
                                        <input type="hidden" name="jenisPembayaran"
                                            value="{{ $request->get('jenisPembayaran') }}">
                                        <input type="hidden" name="search" value="{{ $request->get('search') }}">
                                        <button class="btn btn-primary" type="submit">Filter</button>
                                    </form>
                                </div>
                            </div>
                        @endif
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
                                            <th style="width: 10px">NIS</th>
                                            <th>Nama</th>
                                            <th>Kelas</th>
                                            <th style="width: 40px">Jurusan</th>
                                            <th>Alamat</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @if($users->isEmpty())
                                            <tr>
                                                <td colspan="7" style="text-align: center;">DATA TIDAK ADA</td>
                                            </tr>
                                        @else
                                                                    @foreach ($users as $user)
                                                                                                <tr class="align-middle">
                                                                                                    <td>{{ $user->nis }}</td>
                                                                                                    <td>{{ $user->name }}</td>
                                                                                                    <td>{{ $user->kelas->tingkat_kelas  }}</td>
                                                                                                    <td>{{ $user->kelas->jurusan }}</td>
                                                                                                    <td>{{ $user->alamat }}</td>
                                                                                                    <td>
                                                                                                        @if ($request->get('jenisPembayaran'))
                                                                                                                                            @php
                                                                                                                                                $filteredJenisPembayaran = \App\Models\JenisPembayaran::find($request->get('jenisPembayaran'));
                                                                                                                                            @endphp
                                                                                                                                            @if ($filteredJenisPembayaran && $filteredJenisPembayaran->periode === 'bulanan')
                                                                                                                                                                            @php
                                                                                                                                                                                $pembayaranBulanIni = $user->pembayarans->where('bulan', $request->get('bulan') ?? now()->format('F'))->first();
                                                                                                                                                                            @endphp
                                                                                                                                                                            @if ($pembayaranBulanIni)
                                                                                                                                                                                {{ $pembayaranBulanIni->status_pembayaran }}
                                                                                                                                                                            @else
                                                                                                                                                                                -
                                                                                                                                                                            @endif
                                                                                                                                            @else
                                                                                                                                                @if ($user->pembayarans->isNotEmpty())
                                                                                                                                                    {{ $user->pembayarans->first()->status_pembayaran }}
                                                                                                                                                @else
                                                                                                                                                    -
                                                                                                                                                @endif
                                                                                                                                            @endif
                                                                                                        @else
                                                                                                            @if ($user->pembayarans->isNotEmpty())
                                                                                                                {{ $user->pembayarans->first()->status_pembayaran }}
                                                                                                            @else
                                                                                                                -
                                                                                                            @endif

                                                                                                        @endif
                                                                                                    </td>
                                                                                                </tr>
                                                                    @endforeach
                                        @endif
                                    </tbody>
                                </table>
                            </div>
                            <!-- /.card-body -->

                            <div class="card-footer clearfix">
                                {{$users->links()}}
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