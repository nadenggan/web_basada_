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
                            <div class="card-title">Edit Jenis Pembayaran</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Form-->
                        <form method="POST" action="{{ route('updateJenisPembayaran', $data->id) }}">
                            @csrf
                            <!--begin::Body-->
                            <div class="card-body">

                                <!-- Nama Jenis Pembayaran -->
                                <div class="mb-3">
                                    <label class="form-label">Nama Jenis Pembayaran</label>
                                    <input type="text" class="form-control" name="name"
                                        value="{{ $data->nama_jenis_pembayaran }}" />
                                </div>

                                <!-- Deskripsi -->
                                <div class="mb-3">
                                    <label class="form-label">Deskripsi</label>
                                    <textarea class="form-control" name="deskripsi"
                                        rows="3">{{ $data->deskripsi }}</textarea>
                                </div>

                                <!-- Nominal -->
                                <div class="mb-3">
                                    <label class="form-label">Nominal</label>
                                    <input type="text" class="form-control" name="nominal"
                                        value="{{ $data->nominal}}" />
                                </div>

                                <!-- Tingkat Kelas -->
                                <div class="mb-3">
                                    <label class="form-label">Tingkat Kelas</label>
                                    <div>
                                        @php
                                            $kelasDipilih = json_decode($data->tingkat_kelas, true);
                                        @endphp
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tingkat_kelas[]"
                                                value="X" {{ in_array('X', $kelasDipilih) ? 'checked' : '' }}>
                                            <label class="form-check-label">X</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tingkat_kelas[]"
                                                value="XI" {{ in_array('XI', $kelasDipilih) ? 'checked' : '' }}>
                                            <label class="form-check-label">XI</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="tingkat_kelas[]"
                                                value="XII" {{ in_array('XII', $kelasDipilih) ? 'checked' : '' }}>
                                            <label class="form-check-label">XII</label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Periode -->
                                <div class="mb-3">
                                    <label class="form-label">Periode</label>
                                    <select name="periode" id="periode" class="form-select">
                                        <option value="bulanan" {{ $data->periode == 'bulanan' ? 'selected' : '' }}>
                                            Bulanan</option>
                                        <option value="sekali" {{ $data->periode == 'sekali' ? 'selected' : '' }}>Sekali
                                        </option>
                                        <option value="tahunan" {{ $data->periode == 'tahunan' ? 'selected' : '' }}>
                                            Tahunan</option>
                                    </select>
                                </div>

                                <!-- Tenggat Waktu / Tanggal Bulanan -->
                                <div class="mb-3">
                                    <label class="form-label">Tenggat Waktu</label>
                                    <input type="{{ $data->periode == 'bulanan' ? 'number' : 'date' }}"
                                        class="form-control"
                                        name="{{ $data->periode == 'bulanan' ? 'tanggal_bulanan' : 'tenggat_waktu' }}"
                                        id="tenggat_waktu"
                                        value="{{ $data->periode == 'bulanan' ? $data->tanggal_bulanan : $data->tenggat_waktu }}"
                                        {{ $data->periode == 'bulanan' ? 'min=1 max=31' : '' }}>
                                </div>
                            </div>
                            <!--end::Body-->
                            <!--begin::Footer-->
                            <div class="">
                                <button type="button" class="btn btn-danger"><a
                                        href="{{ route("jenisPembayaranAdmin") }}"
                                        style="color: white; text-decoration: none;">Kembali</a></button>
                                <button type="submit" class="btn btn-primary">Edit Data</button>
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


</main>