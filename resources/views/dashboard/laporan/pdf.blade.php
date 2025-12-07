<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Audit</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h1 { text-align: center; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; font-weight: bold; }
        .header { margin-bottom: 20px; }
        .footer { margin-top: 30px; text-align: right; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN AUDIT MUTU INTERNAL</h1>
        <p><strong>Institut Pertanian Stiper Yogyakarta</strong></p>
        <p>Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kriteria</th>
                <th>Indikator</th>
                <th>Unit</th>
                <th>Tahun</th>
                <th>Tanggal Audit</th>
                <th>Evaluasi</th>
                <th>Auditor</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $item->nama_kriteria }}</td>
                <td>{{ $item->nama_indikator }}</td>
                <td>{{ $item->nama_unit }}</td>
                <td>{{ $item->tahun }}</td>
                <td>{{ date('d/m/Y', strtotime($item->tanggal_audit)) }}</td>
                <td>{{ $item->evaluasi_kesesuaian }}</td>
                <td>{{ $item->auditor }}</td>
            </tr>
            <tr>
                <td colspan="8">
                    <strong>Target Capaian:</strong>
                    @php
                        $targets = json_decode($item->target_capaian, true);
                    @endphp
                    @if(is_array($targets))
                        <ul style="margin: 5px 0; padding-left: 20px;">
                            @foreach($targets as $t)
                                <li>{{ $t }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{ $item->target_capaian }}
                    @endif
                </td>
            </tr>
            @if($item->rekomendasi_perbaikan)
            <tr>
                <td colspan="8">
                    <strong>Rekomendasi Perbaikan:</strong> {{ $item->rekomendasi_perbaikan }}
                </td>
            </tr>
            @endif
            @if($item->catatan_penutupan)
            <tr>
                <td colspan="8">
                    <strong>Catatan Penutupan:</strong> {{ $item->catatan_penutupan }}
                </td>
            </tr>
            @endif
            @if($item->dokumen_link)
            <tr>
                <td colspan="8">
                    <strong>Dokumen:</strong>
                    @php
                        $dokumen = json_decode($item->dokumen_link, true);
                    @endphp
                    @if(is_array($dokumen))
                        <ul style="margin: 5px 0; padding-left: 20px;">
                            @foreach($dokumen as $dok)
                                <li>{{ $dok['judul'] }}: {{ $dok['url'] }}</li>
                            @endforeach
                        </ul>
                    @else
                        {{ $item->dokumen_link }}
                    @endif
                </td>
            </tr>
            @endif
            @empty
            <tr>
                <td colspan="8" style="text-align: center;">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Yogyakarta, {{ date('d F Y') }}</p>
        <p style="margin-top: 60px;">
            <strong>Kepala Lembaga Penjaminan Mutu</strong>
        </p>
    </div>
</body>
</html>
