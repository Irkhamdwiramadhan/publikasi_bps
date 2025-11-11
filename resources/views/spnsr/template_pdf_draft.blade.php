<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>SPNSR - {{ $nomor }}</title>

    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
        }

        /* Wrapper utama agar tidak terpotong */
        .container {
            margin-left: 1.5cm;
            margin-right: 1.5cm;
        }

        /* --- KOP SURAT --- */
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        .header-logo {
            width: 90px;
            vertical-align: top;
            padding-right: 10px;
        }
        .header-text {
            vertical-align: top;
            text-align: left;
        }
        /* Style Teks Kop (Ini dari template Anda, sudah bagus) */
        .header-text h1 {
            font-size: 16pt;
            font-weight: bold;
            font-style: italic;
            margin: 0; padding: 0;
            line-height: 1.2;
        }
        .header-text p {
            font-size: 10pt;
            margin: 0; padding: 0;
        }
        .header-line {
            border-bottom: 3px solid black;
            width: 100%;
            margin-top: 5px;
            margin-bottom: 10px;
        }
        
        /* --- JUDUL & NOMOR (Sesuai Gambar) --- */
        .title {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
            text-decoration: underline;
            margin-top: 20px;
        }
        .nomor-surat {
            text-align: center;
            font-size: 11pt;
            margin-bottom: 25px;
        }

        /* --- ISI SURAT --- */
        .paragraf {
            text-align: justify;
            margin-bottom: 15px;
        }
        
        /* Tabel untuk biodata penanda tangan (Nama, NIP, Jabatan) */
        .biodata-table {
            width: 100%;
            margin-left: 20px; /* Sedikit menjorok */
            border-collapse: collapse;
            margin-bottom: 15px;
        }
        .biodata-table td {
            vertical-align: top;
            padding: 1px 0;
        }
        .biodata-table td:first-child { width: 15%; }
        .biodata-table td:nth-child(2) { width: 2%; }
        
        /* Tabel utama untuk data publikasi (Sesuai Gambar) */
        .tabel-publikasi {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
            margin-top: 10px;
            margin-bottom: 15px;
        }
        .tabel-publikasi th,
        .tabel-publikasi td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }
        .tabel-publikasi th {
            background-color: #f2f2f2;
            text-align: center;
        }

        /* --- TANDA TANGAN (Sesuai Gambar) --- */
        .signature-table {
            width: 100%;
            margin-top: 30px;
        }
        .signature-table .left-col {
            width: 60%;
        }
        .signature-table .right-col {
            width: 40%;
            text-align: left;
        }
        .signature-space {
            height: 100px; /* Ruang kosong untuk TTD */
        }
        
        /* --- CATATAN KAKI (Sesuai Gambar) --- */
        .footer-note {
            margin-top: 50px;
            font-size: 10pt;
        }

    </style>
</head>
<body>

    <div class="container">

        <table class="header-table">
            <tr>
                <td class="header-logo">
                    <img src="{{ public_path('images/logo_bps.png') }}" alt="Logo BPS" style="width: 80px;">
                </td>
                <td class="header-text">
                    <h1>BADAN PUSAT STATISTIK<br>KABUPATEN TEGAL</h1>
                    <p>Jl. Ade Irma Suryani No. 1 Slawi - Tegal Telp. (0283) 491253</p>
                    <p>Homepage: https://tegalkab.bps.go.id E-mail: bps3328@bps.go.id</p>
                </td>
            </tr>
        </table>
        <div class="header-line"></div>
        <p class="title">SURAT PERNYATAAN RILIS PUBLIKASI</p>
        <p class="nomor-surat">Nomor: {{ $nomor }}</p>

        <p>Yang bertanda tangan di bawah ini:</p>
        <table class="biodata-table">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $penanda_tangan['nama'] }}</td>
            </tr>
            <tr>
                <td>NIP</td>
                <td>:</td>
                <td>{{ $penanda_tangan['nip'] }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $penanda_tangan['jabatan'] }}</td>
            </tr>
        </table>

        <p class="paragraf">
            dengan ini menyatakan bahwa pada hari ini, {{ $tanggal_prosa }}, judul publikasi yang tertera pada tabel di bawah ini <strong>telah diperiksa</strong>, baik konten maupun standardisasi publikasi, dan <strong>siap untuk dirilis</strong> pada website BPS.
        </p>

        <table class="tabel-publikasi">
            <thead>
                <tr>
                    <th style="width: 5%;">No<br>(1)</th>
                    <th>Judul Publikasi<br>(2)</th>
                    <th style="width: 18%;">ARC/Non-ARC*<br>(3)</th>
                    
                    <th style="width: 18%;">Tanggal Rilis<br>(4)</th>
                    
                    <th style="width: 20%;">Keterangan<br>(5)</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="text-align: center;">1</td>
                    <td>{{ $judul }}</td>
                    <td style="text-align: center;">{{ $tipe_arc }}</td>
                    
                    <td style="text-align: center;">{{ $tanggal_rilis }}</td>
                    
                    <td>{{ $keterangan }}</td>
                </tr>
                
                {{-- Baris kosong (sesuai gambar) --}}
                <tr>
                    <td style="text-align: center;">2.</td> <td></td> <td></td> <td></td> <td></td>
                </tr>
                <tr>
                    <td style="text-align: center;">3.</td> <td></td> <td></td> <td></td> <td></td>
                </tr>
            </tbody>
        </table>

        <p class="paragraf">
            Demikian surat pernyataan ini dibuat untuk digunakan sebagaimana mestinya.
        </p>

        <table class="signature-table">
            <tr>
                <td class="left-col">
                    </td>
                <td class="right-col">
                    <p>Slawi, {{ $tanggal_surat_dibuat }}</p>
                    <p>{{ $penanda_tangan['jabatan'] }} BPS Kabupaten Tegal</p>
                    <br>
                    <div class="signature-space">
                        </div>
                        <br>

                    <p style="font-weight: bold; margin: 0;">{{ $penanda_tangan['nama'] }}</p>
                </td>
            </tr>
        </table>
        
   

    </div>
</body>
</html>