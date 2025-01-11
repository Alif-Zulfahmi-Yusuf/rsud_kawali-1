<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Laporan Kinerja Bulanan</title>
    <style>
    body {
        font-family: DejaVu Sans, sans-serif;
        /* Pastikan font mendukung Unicode */
        line-height: 1.6;
        margin: 0;
    }

    p {
        font-size: 11px;
    }

    h1 {
        font-size: 16px;
    }

    h3 {

        font-size: 14px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
        page-break-inside: avoid;
        font-size: 9px;

    }

    thead {
        background-color: #f2f2f2;

    }

    th,
    td {
        border: 1px solid #000;
        padding: 5px;
        text-align: left;
        word-break: break-all;
    }

    .text-center {
        text-align: center;
    }

    @media print {
        @page {
            size: A4;
            margin: 0;
        }

        table {
            font-size: 8px;
        }
    }
    </style>
</head>

<body>
    <h1 class="text-center">
        LAPORAN KINERJA BULAN {{ \Carbon\Carbon::parse($evaluasi->bulan)->translatedFormat('F Y') }}
    </h1>
    <p>Nama: {{ $evaluasi->user->name }}</p>
    <p>NIP: {{ $evaluasi->user->nip }}</p>
    <p>Golongan: {{ $evaluasi->user->pangkat->name }}</p>
    <p>Atasan Langung: {{ $evaluasi->user->atasan->name }}</p>

    <h3>A. Capaian Kinerja Bulanan</h3>
    <table>
        <thead>
            <tr>
                <th rowspan="2" class="text-center align-middle" width="5%">No</th>
                <th rowspan="2" class="text-center align-middle" width="20%">Rencana Hasil Kerja</th>
                <th rowspan="2" class="text-center align-middle" width="20%">Rencana Aksi</th>
                <th rowspan="2" class="text-center align-middle" width="15%">Target</th>
                <th colspan="5" class="text-center align-middle">Realisasi</th>
            </tr>
            <tr>
                <th colspan="2" class="text-center align-middle">Kuantitas Output</th>
                <th colspan="2" class="text-center align-middle">Kualitas</th>
                <th class="text-center align-middle">Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($dataRencanaAksi as $index => $item)
            <tr>
                <td class="text-center align-middle">{{ $index + 1 }}</td>
                <td>{{ $item->nama_rencana_pimpinan }}</td>
                <td>{{ $item->nama_rencana_pegawai }}</td>
                <td>
                    {{ $item->bulan_muncul }}
                    {{ $item->satuan }}
                </td>
                <td>
                    {{ $item->bulan_muncul }}
                    {{ $item->satuan }}
                </td>
                <td>
                    {{ isset($evaluasi->laporan[$loop->index]) && $evaluasi->laporan[$loop->index] == 'ada' ? 'Ada' : 'Tidak Ada' }}
                </td>
                <td>
                    {{ isset($evaluasi->kualitas[$loop->index]) ? ucwords(str_replace('_', ' ', $evaluasi->kualitas[$loop->index])) : 'Pilih' }}
                </td>
                <td>

                </td>
                <td>
                    {{ isset($item->waktu_mulai, $item->waktu_selesai) 
                                    ? \Carbon\Carbon::parse($item->waktu_mulai)->diffInHours(\Carbon\Carbon::parse($item->waktu_selesai)) . ' Jam' 
                                    : '-' }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <h3>B. Laporan Aktivitas Harian</h3>
    <table>
        <thead>
            <tr>
                <th rowspan="2" width="5%">NO.</th>
                <th rowspan="2" width="7%">HARI/TANGGAL</th>
                <th rowspan="2" class="text-center" width="20%">AKTIVITAS</th>
                <th rowspan="2" width="5%">KUANTITAS / OUTPUT</th>
                <th rowspan="2" width="5%">WAKTU</th>
                <th rowspan="2" class="text-center" width="10%">JUMLAH JAM KERJA EFEKTIF</th>
                <th colspan="3" class="text-center" width="10%">VERIFIKASI WAKTU DAN AKTIVITAS
                </th>
                <th rowspan="2" class="text-center" width="10%">NOMOR RENCANA AKSI YANG DI
                    INTERVENSI</th>
                <th rowspan="2" width="10%">KETERANGAN</th>
            </tr>
            <tr>
                <th>LOGIS</th>
                <th>KURANG LOGIS</th>
                <th>TIDAK LOGIS</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($filteredKegiatanHarian as $item)
            <tr>
                <td>
                    {{ $loop->iteration }}
                </td>
                <td>
                    {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d F Y') }}
                </td>
                <td>{{ $item->uraian }}</td>
                <td>
                    {{ $item->jumlah }}
                    {{ $item->output }}
                </td>
                <td>
                    {{ $item->waktu_mulai }} - {{ $item->waktu_selesai }}
                </td>
                <td>
                    {{ isset($item->waktu_mulai, $item->waktu_selesai) 
                                    ? \Carbon\Carbon::parse($item->waktu_mulai)->diffInHours(\Carbon\Carbon::parse($item->waktu_selesai)) . ' Jam' 
                                    : '-' }}
                </td>
                <td class="text-center">
                    {{ $item->penilaian == 'logis' ? '✔' : '' }}
                </td>
                <td class="text-center">
                    {{ $item->penilaian == 'kurang_logis' ? '✔' : '' }}
                </td>
                <td class="text-center">
                    {{ $item->penilaian == 'tidak_logis' ? '✔' : '' }}
                </td>
                <td></td>
                <td>{{ ucwords(str_replace('_', ' ', $item->jenis_kegiatan)) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="display: flex; justify-content: space-between;">
        <div>
            <p>Pejabat Penilai,</p>
            <br>
            <p>{{ Auth::user()->atasan->name }}</p>
            <p>{{ Auth::user()->atasan->nip }}</p>
        </div>
        <div style="text-align: right;">
            <p>Ciamis, {{ \Carbon\Carbon::parse($evaluasi->tanggal_capaian)->translatedFormat('Y-m-d') ?? '' }}</p>
            <p>Pegawai Negri Sipil Yang Dinilai</p>
            <br>
            <p>{{ Auth::user()->name }}</p>
            <p>{{ Auth::user()->nip }}</p>
        </div>
    </div>
</body>

</html>