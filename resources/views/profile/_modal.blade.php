<!-- Modal for selecting Atasan -->
<div class="modal fade" id="atasanModal" tabindex="-1" aria-labelledby="atasanModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="atasanModalLabel">{{ __('Pilih Atasan') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Nama') }}</th>
                            <th>{{ __('Jabatan') }}</th>
                            <th>{{ __('Unit Kerja') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($atasans as $atasan)
                        <tr>
                            <td>{{ $atasan->name }}</td>
                            <td>{{ $atasan->jabatan }}</td>
                            <td>{{ $atasan->unit_kerja }}</td>
                            <td>
                                <button type="button" class="btn btn-outline-primary btn-sm select-atasan"
                                    data-id="{{ $atasan->id }}" data-name="{{ $atasan->name }}">
                                    {{ __('Pilih') }}
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>