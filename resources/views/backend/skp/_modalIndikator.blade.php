<!-- Modal -->
<div class="modal fade" id="modalIndikator" tabindex="-1" aria-labelledby="modalIndikatorLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalIndikatorLabel">Add Form SKP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form SKP -->
                <form action="{{ route('skp.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="recana_kerja" class="form-label">Rencana Hasil Kerja</label>
                        <select class="form-select select-single" id="recana_kerja" name="module" required>
                            <option value="" disabled selected>Pilih Module</option>
                            <option value="kuantitatif">Kuantitatif</option>
                            <option value="kualitatif">Kualitatif</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="aspek" class="form-label">Aspek</label>
                        <select class="form-select select-single" id="aspek" name="module" required>
                            <option value="" disabled selected>Pilih Module</option>
                            <option value="kualitas">Kualitas</option>
                            <option value="kuantitas">Kuantitas</option>
                            <option value="waktu">Waktu</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="tipe_target" class="form-label">Tipe Target</label>
                        <select class="form-select select-single" id="tipe_target" name="module" required>
                            <option value="" disabled selected>Pilih Module</option>
                            <option value="kualitas">Kualitas</option>
                            <option value="kuantitas">Kuantitas</option>
                            <option value="waktu">Waktu</option>
                        </select>
                    </div>
                    <div class="form-group mb-3">
                        <label for="tipe_target" class="form-label">Target Minimum</label>
                        <input type="text" class="form-control" name="target_minimum">
                    </div>
                    <div class="form-group mb-3">
                        <label for="tipe_target" class="form-label">Target Maksimum</label>
                        <input type="text" class="form-control" name="target_maksimum">
                    </div>
                    <div class="form-group mb-3">
                        <label for="tipe_target" class="form-label">Satuan</label>
                        <input type="text" class="form-control" name="satuan">
                    </div>
                    <!-- Tombol Submit -->
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>