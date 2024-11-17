<!-- Modal for selecting Atasan -->
<div class="modal fade" id="atasanModal" tabindex="-1" aria-labelledby="atasanModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <!-- Menggunakan modal besar untuk tabel yang lebih luas -->
        <div class="modal-content">
            <!-- Header Modal -->
            <div class="modal-header">
                <h5 class="modal-title fw-bold" id="atasanModalLabel">{{ __('Pilih Atasan') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <!-- Body Modal -->
            <div class="modal-body">
                <p class="mb-3 text-muted">
                    {{ __('Silakan pilih atasan yang sesuai dengan mengklik tombol "Pilih" di kolom Action.') }}
                </p>

                <!-- Table -->
                <div class="table-responsive">
                    <table id="atasanTable" class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th class="text-center" style="width: 30%;"> {{ __('Nama') }} </th>
                                <th class="text-center" style="width: 25%;"> {{ __('Jabatan') }} </th>
                                <th class="text-center" style="width: 25%;"> {{ __('Pangkat') }} </th>
                                <th class="text-center" style="width: 25%;"> {{ __('Unit Kerja') }}</th>
                                <th class="text-center" style="width: 20%;"> {{ __('Action') }} </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($atasans as $atasan)
                            <tr>
                                <td> {{ $atasan->name }} </td>
                                <td> {{ $atasan->jabatan }} </td>
                                <td> {{ $atasan->pangkat->name ?? 'Tidak ada pangkat' }} </td>
                                <td> {{ $atasan->unit_kerja }} </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-outline-secondary btn-sm select-atasan"
                                        data-id="{{ $atasan->id }}" data-name="{{ $atasan->name }}"
                                        data-pangkat="{{ $atasan->pangkat->name ?? '' }}"
                                        data-unit-kerja="{{ $atasan->unit_kerja }}"
                                        data-jabatan="{{ $atasan->jabatan }}">
                                        {{ __('Pilih') }}
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Footer Modal -->
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Tutup') }}</button>
            </div>
        </div>
    </div>
</div>