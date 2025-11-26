<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Surat Izin Cuti</title>

<style>
    @page {
        margin: 2.5cm 2cm 2.5cm 2cm;
    }

    body {
        font-family: DejaVu Sans, Helvetica, Arial;
        font-size: 11pt;
        color: #000;
    }

    /* =============== HEADER =============== */
    .header-table {
        width: 100%;
        border-bottom: 2px solid #007bff;
        margin-bottom: 15px;
    }

    .logo-box {
        width: 50px;
        height: 50px;
        background: #007bff;
        text-align: center;
        vertical-align: middle;
        color: #fff;
        font-weight: bold;
        font-size: 20pt;
        border-radius: 8px;
    }
    
    .logo-box img {
        width: 50px;
        height: 50px;
        object-fit: contain;
    }

    .company-title {
        font-size: 16pt;
        font-weight: bold;
        color: #007bff;
    }

    .company-info {
        font-size: 9pt;
        color: #555;
    }

    /* =============== TITLE =============== */
    .title {
        text-align: center;
        font-size: 16pt;
        font-weight: bold;
        color: #007bff;
        margin-top: 15px;
        margin-bottom: 20px;
        letter-spacing: 1px;
    }

    /* =============== TABLE DATA KARYAWAN =============== */
    .data-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 15px;
        border: 1px solid #ccc;
    }

    .data-table td {
        padding: 6px 10px;
        font-size: 10.5pt;
        border-bottom: 1px solid #eee;
    }

    .data-table tr:last-child td {
        border-bottom: none;
    }

    .label {
        width: 160px;
        font-weight: bold;
    }

    .colon {
        width: 10px;
    }

    /* =============== INFO BADGE =============== */
    .badge {
        display: inline-block;
        padding: 2px 10px;
        border-radius: 10px;
        font-size: 9pt;
        color: #fff;
    }

    .annual { background: #007bff; }
    .sick { background: #dc3545; }

    /* =============== ALASAN BOX =============== */
    .reason-box {
        background: #f4f4f4;
        padding: 10px 12px;
        border-left: 3px solid #007bff;
        margin-top: 5px;
        margin-bottom: 20px;
    }

    /* =============== SIGNATURE =============== */
    .signature-section {
        margin-top: 50px;
        text-align: center;
    }

    .approved {
        display: inline-block;
        background: #28a745;
        color: #fff;
        padding: 6px 20px;
        border-radius: 18px;
        font-weight: bold;
        margin-bottom: 5px;
        font-size: 11pt;
    }

    .line {
        width: 200px;
        border-top: 2px solid #007bff;
        margin: 10px auto 5px auto;
    }

    .sig-name {
        font-weight: bold;
        font-size: 11pt;
    }

    .sig-title {
        font-size: 10pt;
        color: #555;
    }

    .sig-date {
        font-size: 10pt;
        color: #777;
        margin-top: 5px;
    }

</style>

</head>
<body>

<!-- ========== HEADER ========== -->
<table class="header-table">
    <tr>
        <td class="logo-box">
            {{ strtoupper(substr(config('app.name'), 0, 1)) }}
        </td>

        <td style="padding-left:10px;">
            <div class="company-title">{{ config('app.name') }}</div>
            <div class="company-info">
                Jl. Tamalanrea Indah No. 123, Makassar 12345<br>
                Telp: (021) 12345678 | Email: suhada.dalvyn@gmail.com
            </div>
        </td>
    </tr>
</table>

<!-- ========== TITLE ========== -->
<div class="title">SURAT IZIN CUTI</div>

<p style="text-align: justify; margin-bottom:15px;">
    Yang bertanda tangan di bawah ini, menerangkan bahwa:
</p>

<!-- ========== DATA KARYAWAN ========== -->
<table class="data-table">
    <tr>
        <td class="label">Nama Lengkap</td>
        <td class="colon">:</td>
        <td><b>{{ $user->name }}</b></td>
    </tr>

    <tr>
        <td class="label">NIK / ID Karyawan</td>
        <td class="colon">:</td>
        <td>{{ $user->id }}</td>
    </tr>

    <tr>
        <td class="label">Divisi</td>
        <td class="colon">:</td>
        <td>{{ $user->division? $user->division->name : '-' }}</td>
    </tr>

    <tr>
        <td class="label">Jabatan</td>
        <td class="colon">:</td>
        <td>{{ ucfirst(str_replace('_',' ',$user->role)) }}</td>
    </tr>
</table>

<!-- ========== DETAIL CUTI ========== -->
<p style="text-align: justify;">
    Mengajukan izin cuti 
    <span class="badge {{ $leaveRequest->leave_type=='annual' ? 'annual' : 'sick' }}">
        {{ $leaveRequest->leave_type=='annual' ? 'Tahunan' : 'Sakit' }}
    </span>
    selama <b>{{ $leaveRequest->total_days }} hari kerja</b>,
    dari tanggal 
    <b>{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('d F Y') }}</b>
    sampai
    <b>{{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('d F Y') }}</b>.
</p>

<p style="margin-top:10px;">Adapun alasan pengajuan cuti tersebut adalah:</p>

<div class="reason-box">
    {{ $leaveRequest->reason }}
</div>

<p style="text-align: justify;">
    Demikian surat izin cuti ini dibuat untuk digunakan sebagaimana mestinya.
    Atas perhatian Bapak/Ibu, kami ucapkan terima kasih atas kerja keras dan partisipasi Bapak/Ibu dalam mengembangkan perusahaan.
</p>

<!-- ========== SIGNATURE ========== -->
<div class="signature-section">
    <div class="approved">APPROVED</div>

    <div class="line"></div>

    <div class="sig-name">
        {{ $approver ? $approver->name : '[Nama HRD]' }}
    </div>
    <div class="sig-title">Kepala Divisi HRD</div>
    <div class="sig-date">
        {{ \Carbon\Carbon::parse($leaveRequest->updated_at)->format('d F Y') }}
    </div>
</div>

</body>
</html>
