<!-- Modal -->
<div class="modal fade" id="addEvaluasiModal" tabindex="-1" aria-labelledby="addSkpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSkpModalLabel">Pilih Bulan Evaluasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form SKP -->
                <form action="{{ route('evaluasi-pegawai.store') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="pegawai" class="form-label">Nama Pegawai*</label>
                        <br>
                        <small>
                            {{ Auth::user()->nip }} - {{ Auth::user()->name }}
                        </small>
                    </div>
                    <div class="form-group mb-3">
                        <label for="bulan" class="form-label">Bulan Evaluasi</label>
                        <input type="month" name="bulan" id="bulan" class="form-control">
                    </div>
                    <!-- Tombol Submit -->
                    <button type="submit" class="btn btn-outline-secondary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>