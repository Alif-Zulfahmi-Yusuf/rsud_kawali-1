<!-- Modal -->
<div class="modal fade" id="modalIndikator" tabindex="-1" data-bs-backdrop="static"
    aria-labelledby="modalIndikatorLabel" aria-hidden="true">
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
                    <div class="mb-3">
                        <label for="rencana_kerja_pegawai_id" class="form-label">Rencana Hasil Kerja</label>
                        <select class="form-select" id="rencana_kerja_pegawai_id" name="rencana_kerja_pegawai_id"
                            required>
                            <option value="" disabled selected>-- pilih --</option>
                            @foreach ($skpDetail->rencanaHasilKinerja as $rencanaHasil)
                            @foreach ($rencanaHasil->rencanaPegawai as $rencanaPegawai)
                            <option value="{{ $rencanaPegawai->id }}">
                                {{ $rencanaPegawai->rencana ?? '-' }} -
                            </option>
                            @endforeach
                            @endforeach
                        </select>
                    </div>

                    <!-- Aspek -->
                    <div class="mb-3">
                        <label for="aspek" class="form-label">Aspek</label>
                        <select class="form-select" id="aspek" name="aspek" required>
                            <option value="" disabled selected>-- pilih --</option>
                            <option value="kualitas">Kualitas</option>
                            <option value="kuantitas">Kuantitas</option>
                            <option value="waktu">Waktu</option>
                        </select>
                    </div>

                    <!-- Indikator Kinerja -->
                    <div class="mb-3">
                        <label for="indikator_kinerja" class="form-label">Indikator Kinerja</label>
                        <textarea name="indikator_kinerja" id="indikator_kinerja" class="form-control"></textarea>
                    </div>

                    <!-- Baris untuk layout menyamping -->
                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <!-- Tipe Target -->
                            <div class="mb-3">
                                <label for="tipe_target" class="form-label">Tipe Target</label>
                                <select class="form-select" id="tipe_target" name="tipe_target" required>
                                    <option value="" disabled selected>-- pilih --</option>
                                    <option value="satu_nilai">Satu Nilai</option>
                                    <option value="range_nilai">Range Nilai</option>
                                    <option value="kualitatif">Kualitatif</option>
                                </select>
                            </div>

                            <!-- Target Minimum -->
                            <div class="mb-3">
                                <label class="form-label">Target Minimum</label>
                                <input type="text" class="form-control" name="target_minimum">
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <!-- Target Maksimum -->
                            <div class="mb-3">
                                <label class="form-label">Target Maksimum</label>
                                <input type="text" class="form-control" name="target_maksimum">
                            </div>

                            <!-- Satuan -->
                            <div class="mb-3">
                                <label class="form-label">Satuan</label>
                                <input type="text" class="form-control" name="satuan">
                            </div>
                        </div>
                    </div>

                    <!-- Report -->
                    <div class="mb-3">
                        <label for="report" class="form-label">Report</label>
                        <select class="form-select" id="report" name="report" required>
                            <option value="" disabled selected>-- pilih --</option>
                            <option value="bulanan">Bulanan</option>
                            <option value="triwulan">Triwulan</option>
                            <option value="semesteran">Semesteran</option>
                            <option value="tahunan">Tahunan</option>
                        </select>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>