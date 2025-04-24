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
                            <div class="card-title">Edit Data Siswa</div>
                        </div>
                        <!--end::Header-->
                        <!--begin::Form-->
                        <form method="POST" action="{{ route('updateDataSiswa', $data->nis) }}">
                            @csrf

                            <!--begin::Body-->
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="form-label">NIS</label>
                                    <input type="text" class="form-control" name="nis" value="{{ $data->nis }}" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Nama Siswa</label>
                                    <input type="text" class="form-control" name="name" value="{{ $data->name }}" />
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Alamat</label>
                                    <textarea class="form-control" name="alamat" rows="3">{{ $data->alamat }}</textarea>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Kelas</label>
                                    <select name="id_kelas" class="form-select" style="width:150px;">
                                        @foreach ($kelas as $k)
                                            <option value="{{ $k->id_kelas }}" {{ $data->id_kelas == $k->id_kelas ? 'selected' : '' }}>
                                                {{ $k->tingkat_kelas }} - {{ $k->jurusan }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label for="statusSiswa" class="form-label">Status Siswa</label>
                                    <select name="status_siswa" id="statusSiswa" class="form-select" style="width: 150px;">
                                        <option value="">Pilih Status</option>
                                        <option value="aktif" {{ $data->status_siswa == 'Aktif' ? 'selected' : '' }}>Aktif</option>
                                        <option value="lulus" {{ $data->status_siswa == 'Lulus' ? 'selected' : '' }}>Lulus</option>
                                        <option value="pindah" {{ $data->status_siswa == 'Pindah' ? 'selected' : '' }}>Pindah</option>
                                        <option value="keluar" {{ $data->status_siswa == 'Keluar' ? 'selected' : '' }}>Keluar</option>
                                    </select>
                                </div>

                                <!--end::Body-->
                                <!--begin::Footer-->
                                <div>
                                    <button type="button" class="btn btn-danger"><a href="{{ route("dataSiswaAdmin") }}"
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