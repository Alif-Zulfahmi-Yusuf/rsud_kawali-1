<!-- Modal -->
<div class="modal fade" id="modalRencanaPegawai" tabindex="-1" aria-labelledby="addSkpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSkpModalLabel">Add Form SKP</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Form SKP -->
                <form action="{{ route('rencana-kerja-pegawai.store') }}" method="POST">
                    @csrf
                    <!-- Rencana Atasan -->
                    <div class="form-group mb-3">
                        <label for="rencana_atasan_id" class="form-label">Rencana Atasan</label>
                        <select class="form-select select-single" id="rencana_atasan_id" name="rencana_atasan_id"
                            required>
                            <option value="" disabled selected>Pilih Rencana Atasan</option>

                            @if ($skpDetail && $skpDetail->rencanaHasilKinerja)
                            @foreach ($skpDetail->rencanaHasilKinerja as $rencana)
                            @foreach ($rencana->rencanaPegawai as $pegawai)
                            @if (optional($pegawai->rencanaAtasan)->id)
                            <option value="{{ optional($pegawai->rencana_atasan_id)->id }}">
                                {{ optional($pegawai->rencanaAtasan)->rencana }} -
                                {{ optional($pegawai->rencanaAtasan->atasan)->name }}
                            </option>
                            @endif
                            @endforeach
                            @endforeach
                            @else
                            <option value="" disabled>Tidak ada data rencana hasil kerja atasan.</option>
                            @endif
                        </select>
                    </div>
                    <!-- Rencana Hasil Kerja -->
                    <div class="form-group mb-3">
                        <label for="rencana_hasil_kerja" class="form-label">Rencana Hasil Kerja</label>
                        <input type="text" name="rencana_hasil_kerja" class="form-control">
                    </div>
                    <!-- Tombol Submit -->
                    <button type="submit" class="btn btn-primary">Save</button>
                </form>
            </div>
        </div>
    </div>
</div>