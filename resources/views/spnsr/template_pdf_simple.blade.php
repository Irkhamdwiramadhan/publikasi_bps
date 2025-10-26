<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>SPNSR - {{ $nomor }}</title>
    
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.4;
        }

        /* * Wrapper utama untuk memberi margin kiri-kanan pada seluruh dokumen
         * agar tidak ada yang terpotong.
         */
        .container {
            margin-left: 1.5cm;
            margin-right: 1.5cm;
        }
        
        /* * REVISI 1 & 3: Layout Kop Surat & Logo Sejajar
         * Kita gunakan table untuk menyejajarkan logo dan teks.
         */
        .header-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        /* Kolom untuk logo */
        .header-logo {
            width: 90px; /* Lebar area logo */
            vertical-align: top;
            padding-right: 10px;
        }

        /* Kolom untuk teks header */
        .header-text {
            vertical-align: top;
            text-align: left; /* Sesuai permintaan: rata kiri */
        }
        
        /* REVISI 2: Font Italic */
        .header-text h1 {
            font-size: 16pt;
            font-weight: bold;
            font-style: italic; /* FONT MIRING */
            margin: 0;
            padding: 0;
            line-height: 1.2;
        }
        .header-text p {
            font-size: 10pt;
            margin: 0;
            padding: 0;
        }

        /* Garis pemisah tebal */
        .header-line {
            border-bottom: 3px solid black;
            width: 100%;
            margin-top: 5px;  /* Jarak dari teks kop surat ke garis */
            margin-bottom: 15px; /* Jarak dari garis ke nomor surat */
        }

        /* REVISI 4: Nomor Surat di Kanan */
        .nomor-surat {
            text-align: right; /* PINDAH KE KANAN */
            margin-bottom: 15px;
        }
        
        .penerima {
            margin-bottom: 15px;
        }
        
        .paragraf-pembuka p, .paragraf-isi p, .paragraf-penutup p {
            text-align: justify;
        }
        
        .data-table {
            width: 100%;
            margin-left: 20px;
            border-collapse: collapse;
        }
        .data-table td {
            vertical-align: top;
            padding: 2px 0;
        }
        .data-table td:first-child { width: 20%; }
        .data-table td:nth-child(2) { width: 2%; }
        
        .tabel-publikasi {
            width: 100%;
            border-collapse: collapse;
            border: 1px solid black;
            margin-top: 10px;
            margin-bottom: 10px;
        }
        .tabel-publikasi th, .tabel-publikasi td {
            border: 1px solid black;
            padding: 5px;
            text-align: left;
        }
        .tabel-publikasi th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .signature {
            margin-top: 30px;
            width: 45%; 
            margin-left: 55%;
            text-align: left;
        }
        
        .signature .nama-ttd {
            margin-top: 80px; /* Spasi untuk TTD & Stempel */
            font-weight: bold;
            text-decoration: underline;
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

        <div class="nomor-surat">
            Nomor: {{ $nomor }}
        </div>
        
        <div class="penerima">
            Yth: <br>
            Kepala Badan Pusat Statistik <br>
            Provinsi Jawa Tengah <br>
            di- <br>
            Tempat
        </div>

        <div class="paragraf-pembuka">
            <p>{{ $tanggal_prosa }}. yang bertanda tangan di bawah ini:</p>
        </div>

        <table class="data-table">
            <tr>
                <td>Nama</td>
                <td>:</td>
                <td>{{ $penanda_tangan['nama'] }}</td>
            </tr>
            <tr>
                <td>Jabatan</td>
                <td>:</td>
                <td>{{ $penanda_tangan['jabatan'] }}</td>
            </tr>
            <tr>
                <td>Unit Kerja</td>
                <td>:</td>
                <td>{{ $penanda_tangan['unit_kerja'] }}</td>
            </tr>
        </table>

        <div class="paragraf-isi">
            <p>Menyatakan bahwa:</p>
            <ol style="padding-left: 20px;">
                <li>
                    Softcopy publikasi yang tertera pada tabel di bawah ini telah diperiksa dan siap untuk dirilis pada website BPS.
                    <table class="tabel-publikasi">
                        <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Judul Buku</th>
                                <th style="width: 20%;">ARC/Non ARC</th>
                                <th style="width: 20%;">Ket</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center;">1</td>
                                <td>{{ $judul }}</td>
                                <td style="text-align: center;">{{ $tipe_arc }}</td>
                                <td>{{ $keterangan }}</td>
                            </tr>
                        </tbody>
                    </table>
                </li>
                <li>
                    Kebenaran isi publikasi menjadi tanggung jawab BPS pembuat naskah publikasi.
                </li>
            </ol>
        </div>

        <div class="paragraf-penutup">
            <p>Demikian surat persetujuan ini dibuat, untuk dipergunakan sebagaimana mestinya.</p>
        </div>

        <div class="signature">
            <p style="margin-bottom: 0;">Mengetahui,</p>
            <p style="margin-top: 0;">Kepala BPS Kabupaten Tegal</p>
            
            <div class="nama-ttd">
                <p style="margin-bottom: 0;">{{ $penanda_tangan['nama'] }}</p>
            </div>
        </div>
        
    </div> </body>
</html>