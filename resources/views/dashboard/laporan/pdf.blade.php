<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Audit</title>
    <style>
        @page { 
            size: A4 landscape; 
            margin: 10mm; 
        }
        body { 
            font-family: Arial, sans-serif; 
            font-size: 10px; 
            margin: 0; 
            padding: 0;
        }
        h1 { 
            text-align: center; 
            margin-bottom: 15px; 
            font-size: 16px; 
            font-weight: bold;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
        }
        th, td { 
            border: 1px solid #000; 
            padding: 2px; 
            vertical-align: middle; 
            word-wrap: break-word;
            overflow-wrap: break-word;
            line-height: 1.1;
        }
        th { 
            background-color: #4a90e2; 
            color: white; 
            font-weight: bold; 
            text-align: center; 
            font-size: 11px;
            line-height: 1.2;
        }
        .light-blue { 
            background-color: #e6f3ff; 
            color: #000;
        }
        .header { 
            margin-bottom: 15px; 
            text-align: center;
        }
        .footer { 
            margin-top: 20px; 
            text-align: right; 
        }
        .center { 
            text-align: center; 
        }
        .left { 
            text-align: left; 
        }
        .col-no { width: 2%; }
        .col-kode { width: 7%; }
        .col-indikator { width: 18%; }
        .col-pic { width: 5%; }
        .col-satuan { width: 5%; }
        .col-target { width: 15%; }
        .col-anggaran { width: 6%; }
        .col-dokumen { width: 8%; }
        .col-catatan { width: 8%; }
        .col-temuan { width: 5%; }
        .col-rekomendasi { width: 12%; }
        .col-hasil { width: 5%; }
        .col-auditor { width: 8%; }
        
        .small-text { font-size: 10px; }
        .very-small { font-size: 9px; }
        
        a { color: blue; text-decoration: underline; }
    </style>
</head>
<body>
    <div class="header">
        <h1>LAPORAN AUDIT MUTU INTERNAL</h1>
        <p style="font-size: 12px;"><strong>Politeknik Semen Indonesia</strong></p>
        <p style="font-size: 10px;">Tanggal Cetak: {{ date('d/m/Y H:i') }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" class="col-no">No</th>
                <th colspan="2">Indikator Kinerja Utama (IKU)/<br>Indikator Kinerja Tambahan (IKT)</th>
                <th rowspan="2" class="col-pic">PIC</th>
                <th rowspan="2" class="col-satuan">Satuan</th>
                <th rowspan="2" class="col-target">Target<br>Capaian</th>
                <th rowspan="2" class="col-anggaran">Anggaran</th>
                <th rowspan="2" class="col-dokumen">Bukti<br>Dokumen</th>
                <th rowspan="2" class="col-catatan">Catatan<br>Temuan</th>
                <th rowspan="2" class="col-temuan">Temuan<br>Audit</th>
                <th rowspan="2" class="col-rekomendasi">Rekomendasi<br>Peningkatan</th>
                <th rowspan="2" class="col-hasil">Hasil<br>Audit</th>
                <th rowspan="2" class="col-auditor">Auditor</th>
            </tr>
            <tr class="light-blue">
                <th class="col-kode">Kode</th>
                <th class="col-indikator">Indikator Kinerja/<br>KPI</th>
            </tr>
        </thead>
        <tbody>
            @forelse($laporan as $index => $item)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td class="center small-text">{{ $item->kode_indikator ?? 'IKU.1.0' . ($index + 1) . '.0' . ($index + 1) }}</td>
                <td class="left small-text">{{ $item->nama_indikator }}</td>
                <td class="center small-text">{{ $item->pic ?: 'Akademik' }}</td>
                <td class="center small-text">
                    @php
                        $target = json_decode($item->target, true);
                    @endphp
                    @if(is_array($target))
                        @foreach($target as $t)
                            {{ $t }}@if(!$loop->last)<br>@endif
                        @endforeach
                    @else
                        {{ $item->target ?: '4' }}
                    @endif
                </td>
                <td class="left small-text">
                    @php
                        $targets = json_decode($item->target_capaian, true);
                    @endphp
                    @if(is_array($targets))
                        @foreach($targets as $t)
                            {{ $t }}@if(!$loop->last)<br>@endif
                        @endforeach
                    @else
                        {{ $item->target_capaian }}
                    @endif
                </td>
                <td class="center very-small">{{ $item->anggaran ? number_format($item->anggaran, 0, ',', '.') : '5.000.000' }}</td>
                <td class="left very-small">
                    @if($item->dokumen_link)
                        @php
                            $dokumen = json_decode($item->dokumen_link, true);
                        @endphp
                        @if(is_array($dokumen))
                            @foreach($dokumen as $dok)
                                <a href="{{ $dok['url'] }}">{{ Str::limit($dok['judul'], 15) }}</a>@if(!$loop->last)<br>@endif
                            @endforeach
                        @else
                            <a href="{{ $item->dokumen_link }}">Link Dokumen</a>
                        @endif
                    @else
                        www.googledrive.com
                    @endif
                </td>
                <td class="left small-text">{{ $item->catatan_penutupan ?: 'Baik' }}</td>
                <td class="center small-text">{{ $item->hasil_audit ?: 'Mayor' }}</td>
                <td class="left small-text">{{ $item->rekomendasi_perbaikan ?: 'Ditingkatkan ke baik sekali' }}</td>
                <td class="center small-text">{{ $item->evaluasi_kesesuaian == 'Sesuai' ? 'Tercapai' : 'Tidak Tercapai' }}</td>
                <td class="left small-text">{{ $item->auditor }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="13" class="center">Tidak ada data</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        @php
            $auditors = collect($laporan)->pluck('auditor')->unique()->values();
        @endphp
        <div style="text-align: center; margin-top: 40px;">
            <p style="font-size: 10px; margin-bottom: 20px;">Gresik, {{ date('d F Y') }}</p>
            <table style="width: 100%; margin-top: 20px; border: none;">
                <tr>
                    @foreach($auditors as $auditor)
                    <td style="text-align: center; border: none; width: {{ 100 / $auditors->count() }}%; padding: 0 10px;">
                        <p style="font-size: 10px; margin-bottom: 60px;"><strong>Auditor {{ $loop->iteration }}</strong></p>
                        <p style="font-size: 10px;">{{ $auditor }}</p>
                    </td>
                    @endforeach
                </tr>
            </table>
        </div>
    </div>
</body>
</html>