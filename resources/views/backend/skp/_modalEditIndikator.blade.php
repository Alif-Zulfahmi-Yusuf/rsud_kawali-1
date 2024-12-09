<!-- Modal -->
<div class="modal fade" id="modalEditIndikator" tabindex="-1" data-bs-backdrop="static"
    aria-labelledby="modalEditIndikatorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditIndikatorLabel">Edit Indikator Kinerja</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form Edit Indikator -->
                <form id="formEditIndikator" action="#" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="indikator_id" id="editIndikatorId">

                    <div class="form-group mb-3">
                        <label for="editRencanaPegawai" class="form-label">Rencana Pegawai</label>
                        <select class="form-select" id="editRencanaPegawai" name="rencana_kerja_pegawai_id" required>
                            <option value="" selected>Pilih Rencana</option>
                            @foreach ($skpDetail->rencanaPegawai as $rencana)
                            <option value="{{ $rencana->id }}">
                                {{ $rencana->rencana ?? '-' }}
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Aspek -->
                    <div class="mb-3">
                        <label for="editAspek" class="form-label">Aspek</label>
                        <select class="form-select" id="editAspek" name="aspek" required>
                            <option value="" disabled selected>-- pilih --</option>
                            <option value="kualitas">Kualitas</option>
                            <option value="kuantitas">Kuantitas</option>
                            <option value="waktu">Waktu</option>
                        </select>
                    </div>

                    <!-- Indikator Kinerja -->
                    <div class="mb-3">
                        <label for="editIndikatorKinerja" class="form-label">Indikator Kinerja</label>
                        <textarea name="indikator_kinerja" id="editIndikatorKinerja" class="form-control"></textarea>
                    </div>

                    <div class="row">
                        <!-- Kolom Kiri -->
                        <div class="col-md-6">
                            <!-- Tipe Target -->
                            <div class="mb-3">
                                <label for="editTipeTarget" class="form-label">Tipe Target</label>
                                <select class="form-select" id="editTipeTarget" name="tipe_target" required>
                                    <option value="" disabled selected>-- pilih --</option>
                                    <option value="satu_nilai">Satu Nilai</option>
                                    <option value="range_nilai">Range Nilai</option>
                                    <option value="kualitatif">Kualitatif</option>
                                </select>
                            </div>

                            <!-- Target Minimum -->
                            <div class="mb-3">
                                <label class="form-label">Target Minimum</label>
                                <input type="text" class="form-control" id="editTargetMinimum" name="target_minimum">
                            </div>
                        </div>

                        <!-- Kolom Kanan -->
                        <div class="col-md-6">
                            <!-- Target Maksimum -->
                            <div class="mb-3">
                                <label class="form-label">Target Maksimum</label>
                                <input type="text" class="form-control" id="editTargetMaximum" name="target_maksimum">
                            </div>

                            <!-- Satuan -->
                            <div class="mb-3">
                                <label class="form-label">Satuan</label>
                                <input type="text" class="form-control" id="editSatuan" name="satuan">
                            </div>
                        </div>
                    </div>

                    <!-- Report -->
                    <div class="mb-3">
                        <label for="editReport" class="form-label">Report</label>
                        <select class="form-select" id="editReport" name="report" required>
                            <option value="" disabled selected>-- pilih --</option>
                            <option value="bulanan">Bulanan</option>
                            <option value="triwulan">Triwulan</option>
                            <option value="semesteran">Semesteran</option>
                            <option value="tahunan">Tahunan</option>
                        </select>
                    </div>

                    <!-- Tombol Submit -->
                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" class="btn btn-outline-secondary">Update</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>