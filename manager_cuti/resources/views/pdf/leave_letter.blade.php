<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Izin Cuti</title>
    <style>
        @page {
            margin: 3cm 2.5cm 2.5cm 2.5cm;
            size: A4;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Times New Roman', 'Garamond', 'Cambria', serif;
            font-size: 12pt;
            line-height: 1.5;
            color: #1a1a1a;
            position: relative;
            background: #ffffff;
            padding: 0;
            margin: 0;
        }
        
        /* Watermark - Nama perusahaan di tengah dengan opacity 5-8% */
        .watermark {
            position: absolute;
            top: 50%;
            left: 50%;
            margin-left: -180px;
            margin-top: -60px;
            font-size: 90pt;
            font-weight: 100;
            color: rgba(0, 0, 0, 0.05);
            z-index: 0;
            pointer-events: none;
            letter-spacing: 12px;
            text-align: center;
            width: 360px;
            opacity: 0.06;
        }
        
        .content-wrapper {
            position: relative;
            z-index: 1;
            padding: 30px 20px 0 20px;
            max-width: 100%;
            margin: 0 auto;
        }
        
        /* Header Section */
        .header {
            margin-bottom: 35px;
            margin-top: 0;
            padding-bottom: 18px;
            padding-top: 0;
            padding-left: 0;
            padding-right: 0;
            border-bottom: 1px solid #1a1a1a;
            position: relative;
        }
        
        .header-content {
            display: table;
            width: 100%;
            padding: 0;
            margin: 0;
        }
        
        .header-row {
            display: table-row;
        }
        
        .logo-cell {
            display: table-cell;
            vertical-align: middle;
            width: 75px;
            padding-right: 20px;
            padding-left: 0;
        }
        
        /* Logo Monokrom */
        .logo-placeholder {
            width: 50px;
            height: 50px;
            border: 2px solid #000;
            text-align: center;
            line-height: 46px;
            font-size: 20pt;
            font-weight: bold;
            background: #ffffff;
            color: #000;
        }
        
        .company-info {
            display: table-cell;
            vertical-align: middle;
            text-align: left;
            padding: 0;
        }
        
        .company-name {
            font-size: 16pt;
            font-weight: bold;
            letter-spacing: 0.5px;
            margin-bottom: 5px;
            color: #000;
            font-family: 'Times New Roman', serif;
        }
        
        .company-address {
            font-size: 10pt;
            line-height: 1.5;
            color: #555;
            font-weight: normal;
        }
        
        .company-address span {
            margin: 0 6px;
            color: #999;
        }
        
        /* Title Section */
        .title-section {
            text-align: center;
            margin: 35px 0 30px 0;
            padding: 15px 0;
        }
        
        .title {
            font-size: 18pt;
            font-weight: bold;
            letter-spacing: 4px;
            margin-bottom: 10px;
            color: #000;
            text-transform: uppercase;
        }
        
        /* Garis Dekoratif Double-Line */
        .title-underline {
            width: 250px;
            margin: 0 auto;
            padding-top: 6px;
        }
        
        .title-line-top {
            width: 100%;
            height: 1px;
            background: #1a1a1a;
            margin-bottom: 3px;
        }
        
        .title-line-bottom {
            width: 100%;
            height: 1px;
            background: #1a1a1a;
        }
        
        /* Content Section */
        .content {
            text-align: justify;
            margin: 30px 0;
            line-height: 1.5;
            padding: 0;
            max-width: 100%;
        }
        
        .content p {
            margin-bottom: 16px;
            text-indent: 50px;
            padding: 0;
        }
        
        .content-intro {
            margin-bottom: 22px;
        }
        
        /* Employee Data Table - Modern dengan bottom border tipis */
        .employee-data {
            margin: 22px 0 25px 0;
            width: 100%;
            max-width: 100%;
            border-collapse: collapse;
            padding: 0;
        }
        
        .employee-data tr {
            border-bottom: 1px solid #e8e8e8;
        }
        
        .employee-data tr:last-child {
            border-bottom: none;
        }
        
        .employee-data td {
            padding: 10px 0;
            vertical-align: top;
        }
        
        .employee-data td:first-child {
            width: 200px;
            font-weight: bold;
            color: #1a1a1a;
            padding-left: 50px;
            padding-right: 10px;
            font-size: 12pt;
        }
        
        .employee-data td:nth-child(2) {
            width: 20px;
            color: #666;
            padding: 10px 5px;
        }
        
        .employee-data td:last-child {
            color: #1a1a1a;
            font-weight: normal;
            padding-right: 0;
        }
        
        .employee-data strong {
            font-weight: 600;
            color: #000;
        }
        
        /* Leave Details */
        .leave-details {
            margin: 22px 0;
            padding-left: 50px;
            padding-right: 0;
        }
        
        .leave-details p {
            text-indent: 0;
            margin-bottom: 12px;
            line-height: 1.5;
            padding: 0;
        }
        
        /* Box Alasan - Background #f5f5f5 dengan italic */
        .reason-box {
            margin: 15px 0 18px 0;
            padding: 14px 18px;
            background: #f5f5f5;
            font-style: italic;
            color: #444;
            line-height: 1.6;
            border-left: 2px solid #d0d0d0;
        }
        
        .closing {
            margin-top: 25px;
            margin-bottom: 0;
            text-indent: 50px;
            line-height: 1.5;
            padding: 0;
        }
        
        /* Signature Section */
        .signature-section {
            margin-top: 70px;
            margin-bottom: 20px;
            display: block;
            width: 100%;
            padding: 0;
            max-width: 100%;
        }
        
        .signature-row {
            display: block;
        }
        
        .signature-block {
            display: block;
            width: 100%;
            text-align: center;
            vertical-align: top;
            padding: 0;
            margin: 0 auto;
            max-width: 400px;
        }
        
        .signature-label {
            font-size: 11pt;
            margin-bottom: 0;
            color: #333;
            font-weight: 500;
            padding: 0;
            height: 20px;
            line-height: 20px;
        }
        
        /* Spacer untuk alignment - sama tinggi dengan approved container */
        .signature-spacer {
            height: 40px;
            margin-bottom: 0;
            padding: 0;
        }
        
        /* Approved Badge untuk HRD */
        .approved-container {
            margin-bottom: 0;
            margin-top: 0;
            padding: 0;
            height: 40px;
            width: 100%;
        }
        
        .approved-badge {
            font-size: 13pt;
            font-weight: bold;
            color: #000;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 6px;
            padding: 0;
        }
        
        .approved-divider {
            width: 70px;
            height: 1px;
            background: #666;
            margin: 0 auto;
            padding: 0;
        }
        
        .signature-line {
            border-top: 1px solid #1a1a1a;
            padding-top: 8px;
            margin-top: 0;
            min-height: 55px;
            max-width: 200px;
            margin-left: auto;
            margin-right: auto;
        }
        
        .signature-name {
            font-weight: 600;
            font-size: 12pt;
            margin-top: 6px;
            color: #000;
            padding: 0;
        }
        
        .signature-title {
            font-size: 10pt;
            color: #666;
            margin-top: 4px;
            padding: 0;
        }
        
        .signature-date {
            font-size: 10pt;
            color: #666;
            margin-top: 10px;
            padding: 0;
        }
        
        /* Utility Classes */
        .text-center {
            text-align: center;
        }
        
        .mt-1 {
            margin-top: 8px;
        }
        
        .mb-1 {
            margin-bottom: 8px;
        }
    </style>
</head>
<body>
    <!-- Watermark - Nama perusahaan di tengah -->
    <div class="watermark">{{ strtoupper(config('app.name', 'COMPANY')) }}</div>
    
    <div class="content-wrapper">
        <!-- Header / Kop Surat -->
        <div class="header">
            <div class="header-content">
                <div class="header-row">
                    <div class="logo-cell">
                        <!-- Logo Monokrom -->
                        <div class="logo-placeholder">{{ strtoupper(substr(config('app.name', 'PT'), 0, 1)) }}</div>
                    </div>
                    <div class="company-info">
                        <div class="company-name">{{ config('app.name', 'PT. Perusahaan') }}</div>
                        <div class="company-address">
                            Jl. Raya Perusahaan No. 123, Jakarta 12345
                            <span>|</span>
                            Telp: (021) 12345678
                            <span>|</span>
                            Email: info@perusahaan.com
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Judul Surat -->
        <div class="title-section">
            <div class="title">SURAT IZIN CUTI</div>
            <div class="title-underline">
                <div class="title-line-top"></div>
                <div class="title-line-bottom"></div>
            </div>
        </div>
        
        <!-- Isi Surat -->
        <div class="content">
            <p class="content-intro">
                Yang bertanda tangan di bawah ini, menerangkan dengan sebenarnya bahwa:
            </p>
            
            <!-- Biodata Karyawan - Tabel Modern -->
            <table class="employee-data">
                <tr>
                    <td>Nama Lengkap</td>
                    <td>:</td>
                    <td><strong>{{ $user->name }}</strong></td>
                </tr>
                <tr>
                    <td>NIK / ID Karyawan</td>
                    <td>:</td>
                    <td>{{ $user->id }}</td>
                </tr>
                <tr>
                    <td>Divisi</td>
                    <td>:</td>
                    <td>{{ $user->division ? $user->division->name : '-' }}</td>
                </tr>
                <tr>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $user->role)) }}</td>
                </tr>
            </table>
            
            <!-- Detail Cuti -->
            <div class="leave-details">
                <p>
                    Mengajukan izin cuti <strong>{{ $leaveRequest->leave_type === 'annual' ? 'Tahunan' : 'Sakit' }}</strong> 
                    selama <strong>{{ $leaveRequest->total_days }} hari kerja</strong>, 
                    terhitung mulai tanggal <strong>{{ \Carbon\Carbon::parse($leaveRequest->start_date)->format('d F Y') }}</strong> 
                    sampai dengan tanggal <strong>{{ \Carbon\Carbon::parse($leaveRequest->end_date)->format('d F Y') }}</strong>.
                </p>
                
                <p style="margin-top: 15px;">
                    Adapun alasan pengajuan cuti tersebut adalah sebagai berikut:
                </p>
                
                <!-- Box Alasan dengan Background #f5f5f5 -->
                <div class="reason-box">
                    "{{ $leaveRequest->reason }}"
                </div>
            </div>
            
            <!-- Penutup -->
            <p class="closing">
                Demikian surat izin cuti ini dibuat dengan sebenarnya untuk dapat dipergunakan sebagaimana mestinya. Atas perhatian dan persetujuan Bapak/Ibu, kami ucapkan terima kasih.
            </p>
        </div>
        
        <!-- Tanda Tangan -->
        <div class="signature-section">
            <div class="signature-row">
                <!-- HRD dengan APPROVED -->
                <div class="signature-block">
                    <div class="signature-label">Disetujui oleh,</div>
                    <!-- APPROVED Badge -->
                    <div class="approved-container">
                        <div style="text-align: center; margin-bottom: 6px;">
                            <div class="approved-badge" style="display: block;">APPROVED</div>
                            <div class="approved-divider" style="display: block; margin: 0 auto;"></div>
                        </div>
                    </div>
                    <div class="signature-line">
                        <div class="signature-name">
                            @if($approver)
                                {{ $approver->name }}
                            @else
                                [Nama HRD]
                            @endif
                        </div>
                        <div class="signature-title">Kepala Divisi HRD</div>
                    </div>
                    <div class="signature-date">
                        {{ \Carbon\Carbon::parse($leaveRequest->updated_at)->format('d F Y') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
