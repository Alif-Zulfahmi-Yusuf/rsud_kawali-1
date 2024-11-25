<!-- Modal -->
<div class="modal fade" id="modalIndikator" tabindex="-1" aria-labelledby="modalIndikatorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalIndikatorLabel">Form Input Indikator Kinerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form SKP -->
                <form action="{{ route('indikator-kinerja.store') }}" method="POST">
                    @csrf

                    <!-- Rencana Hasil Kerja -->
                    <div class="row mb-3 align-items-center">
                        <label for="rencana_kerja_pegawai_id" class="col-sm-4 col-form-label">Rencana Hasil
                            Kerja</label>
                        <div class="col-sm-8">
                            <select class="form-select select-single" id="rencana_kerja_pegawai_id"
                                name="rencana_kerja_pegawai_id" required>
                                <option value="" disabled selected>--pilih--</option>
                                @foreach ($skpDetail->rencanaHasilKinerja as $rencanaHasil)
                                @foreach ($rencanaHasil->rencanaPegawai as $rencanaPegawai)
                                <option value="{{ $rencanaPegawai->id }}">
                                    {{ $rencanaPegawai->rencana ?? '-' }} -
                                </option>
                                @endforeach
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <!-- Aspek -->
                    <div class="row mb-3 align-items-center">
                        <label for="aspek" class="col-sm-4 col-form-label">Aspek</label>
                        <div class="col-sm-8">
                            <select class="form-select select-single" id="aspek" name="module" required>
                                <option value="" disabled selected>--pilih--</option>
                                <option value="kualitas">Kualitas</option>
                                <option value="kuantitas">Kuantitas</option>
                                <option value="waktu">Waktu</option>
                            </select>
                        </div>
                    </div>

                    <!-- Indikator Kinerja -->
                    <div class="row mb-3 align-items-center">
                        <label for="indikator_kinerja" class="col-sm-4 col-form-label">Indikator Kinerja</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="indikator_kinerja" name="indikator_kinerja">
                        </div>
                    </div>

                    <!-- Tipe Target -->
                    <div class="row mb-3 align-items-center">
                        <label for="tipe_target" class="col-sm-4 col-form-label">Tipe Target</label>
                        <div class="col-sm-8">
                            <select class="form-select select-single" id="tipe_target" name="module" required>
                                <option value="" disabled selected>--pilih--</option>
                                <option value="kualitas">Kualitas</option>
                                <option value="kuantitas">Kuantitas</option>
                                <option value="waktu">Waktu</option>
                            </select>
                        </div>
                    </div>

                    <!-- Target Minimum -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Target Minimum</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="target_minimum">
                        </div>
                    </div>

                    <!-- Target Maksimum -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Target Maksimum</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="target_maksimum">
                        </div>
                    </div>

                    <!-- Satuan -->
                    <div class="row mb-3 align-items-center">
                        <label class="col-sm-4 col-form-label">Satuan</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" name="satuan">
                        </div>
                    </div>

                    <!-- Report -->
                    <div class="row mb-3 align-items-center">
                        <label for="report" class="col-sm-4 col-form-label">Report</label>
                        <div class="col-sm-8">
                            <select class="form-select select-single" id="report" name="report" required>
                                <option value="" disabled selected>--pilih--</option>
                                <option value="bulanan">Bulanan</option>
                                <option value="triwulan">Triwulan</option>
                                <option value="semesteran">Semesteran</option>
                                <option value="tahunan">Tahunan</option>
                            </select>
                        </div>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</div>