<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Preview & Edit Sertifikat - {{ $nama }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Cinzel:wght@700&family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Roboto:wght@300;400;700&display=swap');

        :root {
            --primary-teal: #0f766e;
            --gold: #b45309;
        }

        body {
            background-color: #525659;
            margin: 0;
            padding: 40px 0;
            font-family: 'Roboto', sans-serif;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        /* Container Sertifikat (A4 Landscape) */
        .certificate-page {
            background-color: white;
            width: 297mm;
            height: 210mm;
            position: relative;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            overflow: hidden;
            box-sizing: border-box;
            padding: 50px; /* Jarak agar tidak mepet atas */
        }

        /* Border Background */
        .bg-border {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background-image: url("{{ asset('images/templat-sertifikat.png') }}");
            background-size: 100% 100%;
            pointer-events: none;
            z-index: 1;
        }

        .content {
            position: relative;
            z-index: 2;
            height: 100%;
            display: flex;
            flex-direction: column;
        }

        /* Editable Style */
        [contenteditable="true"] {
            transition: all 0.3s;
            outline: none;
            border: 1px dashed transparent;
            cursor: text;
        }

        [contenteditable="true"]:hover {
            background-color: rgba(15, 118, 110, 0.05);
            border-color: var(--primary-teal);
        }

        [contenteditable="true"]:focus {
            background-color: rgba(15, 118, 110, 0.1);
            border-color: var(--primary-teal);
            box-shadow: 0 0 5px rgba(15, 118, 110, 0.3);
        }

        /* Header / Kop */
        .header-table {
            width: 100%;
            margin-bottom: 30px;
            border-collapse: collapse;
        }
        
        .logo-cell {
            width: 90px;
            vertical-align: middle;
        }

        .logo-img {
            width: 85px;
            height: auto;
            max-width: 250px;
            max-height: 120px;
            object-fit: contain;
        }

        .header-text {
            text-align: center;
        }

        .company-name {
            font-family: 'Cinzel', serif;
            font-size: 28px;
            color: #000;
            margin: 0;
            line-height: 1.2;
            text-transform: uppercase;
        }

        .company-address {
            font-size: 13px;
            color: #444;
            margin: 5px 0 0 0;
            font-style: italic;
        }

        .divider {
            height: 3px;
            background: linear-gradient(to right, transparent, #000, transparent);
            margin: 15px auto;
            width: 90%;
        }

        /* Main Body */
        .title {
            font-family: 'Cinzel', serif;
            font-size: 52px;
            color: var(--primary-teal);
            text-align: center;
            margin: 10px 0;
            letter-spacing: 12px;
            text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
        }

        .subtitle {
            text-align: center;
            font-family: 'Playfair Display', serif;
            font-style: italic;
            font-size: 20px;
            color: #555;
            margin-bottom: 25px;
        }

        .biodata-table {
            margin: 0 auto;
            border-collapse: collapse;
            font-size: 18px;
        }

        .biodata-table td {
            padding: 8px 10px;
        }

        .label {
            font-weight: 700;
            width: 220px;
        }

        .colon {
            width: 20px;
        }

        .value {
            font-weight: 700;
            color: #222;
        }

        .main-text {
            text-align: center;
            margin: 30px auto;
            width: 85%;
            font-size: 18px;
            line-height: 1.6;
            color: #333;
        }

        .highlight {
            font-weight: 700;
            color: #000;
        }

        .predikat-box {
            font-size: 24px;
            color: var(--primary-teal);
            font-weight: 700;
            text-transform: uppercase;
        }

        /* Footer */
        .footer-table {
            width: 100%;
            margin-top: auto;
        }

        .foto-box {
            width: 3cm;
            height: 4cm;
            border: 2px dashed #ccc;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #999;
            font-size: 12px;
            margin-left: 50px;
        }

        .signature-section {
            text-align: center;
            padding-right: 50px;
        }

        .signature-date {
            margin-bottom: 10px;
            font-size: 17px;
        }

        .signature-role {
            font-weight: 700;
            margin-bottom: 70px;
            font-size: 17px;
        }

        .signature-name {
            font-weight: 700;
            font-size: 19px;
            text-decoration: underline;
            margin: 0;
        }

        /* Floating Toolbar */
        .toolbar {
            position: fixed;
            bottom: 30px;
            right: 30px;
            display: flex;
            gap: 15px;
            z-index: 1000;
        }

        .btn-action {
            padding: 12px 25px;
            border-radius: 50px;
            border: none;
            cursor: pointer;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 5px 15px rgba(0,0,0,0.3);
            transition: all 0.3s;
        }

        .btn-cancel {
            background-color: #fff;
            color: #333;
        }

        .btn-cancel:hover {
            background-color: #f1f1f1;
        }

        .btn-print {
            background: linear-gradient(135deg, #0f766e, #14b8a6);
            color: white;
        }

        .btn-print:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(15, 118, 110, 0.4);
        }

        .hint {
            position: fixed;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            background: rgba(0,0,0,0.7);
            color: white;
            padding: 10px 20px;
            border-radius: 30px;
            font-size: 14px;
            pointer-events: none;
            z-index: 1000;
        }

        @media print {
            .toolbar, .hint { display: none; }
            body { background: white; padding: 0; }
            .certificate-page { box-shadow: none; }
        }
    </style>
</head>
<body>

    <div class="hint">
        <i class="fas fa-info-circle me-2"></i> Klik pada teks mana saja untuk mengedit isinya
    </div>

    <div class="certificate-page" id="sertifikat">
        <div class="bg-border"></div>
        
        <div class="content">
            <!-- Kop -->
            <table class="header-table" style="border-bottom: 3px solid #000; padding-bottom: 10px; margin-bottom: 2px;">
                <tr>
                    <td class="logo-cell" style="width: 15%; position: relative;">
                        @if($foto_iduka)
                            <img src="{{ asset('storage/' . $foto_iduka) }}" id="cert-logo" class="logo-img" 
                                 style="width: 85px; transition: none;">
                        @else
                            <div style="width: 80px; height: 80px; background: #eee; display: flex; align-items: center; justify-content: center; font-size: 10px; color: #999;">LOGO</div>
                        @endif
                    </td>
                    <td class="header-text" style="width: 85%; padding-right: 15%;">
                        <div class="company-name" contenteditable="true" id="field-nama_iduka" style="font-weight: bold; font-family: 'Times New Roman', serif;">{{ $nama_iduka }}</div>
                        <div class="company-address" contenteditable="true" id="field-alamat_iduka" style="font-style: normal; font-family: 'Times New Roman', serif;">{{ $alamat_iduka }}</div>
                    </td>
                </tr>
            </table>
            <div style="border-top: 1px solid #000; margin-bottom: 20px;"></div>

            <!-- Title -->
            <div class="title" style="font-family: 'Times New Roman', serif; font-size: 42px; margin-top: 0;">SERTIFIKAT</div>
            <div class="subtitle" style="font-family: 'Times New Roman', serif;">Diberikan kepada:</div>

            <!-- Biodata -->
            <div style="margin: 0 auto; width: 75%;">
                <table class="biodata-table" style="width: 100%; font-family: 'Times New Roman', serif;">
                    <tr>
                        <td class="label" style="width: 35%;">Nama Siswa</td>
                        <td class="colon" style="width: 3%;">:</td>
                        <td class="value" style="width: 62%;"><span contenteditable="true" id="field-nama">{{ $nama }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Nomor Induk Siswa</td>
                        <td class="colon">:</td>
                        <td class="value"><span contenteditable="true" id="field-nis">{{ $nis }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Tempat, Tanggal Lahir</td>
                        <td class="colon">:</td>
                        <td class="value">
                            <span contenteditable="true" id="field-tempat_lahir">{{ $tempat_lahir }}</span>, 
                            <span contenteditable="true" id="field-tgl_lahir">{{ $tgl_lahir }}</span>
                        </td>
                    </tr>
                    <tr>
                        <td class="label">Asal Sekolah</td>
                        <td class="colon">:</td>
                        <td class="value"><span contenteditable="true" id="field-asal_sekolah">{{ $asal_sekolah }}</span></td>
                    </tr>
                    <tr>
                        <td class="label">Tahun Pelajaran</td>
                        <td class="colon">:</td>
                        <td class="value"><span contenteditable="true" id="field-tahun_ajaran">{{ $tahun_ajaran }}</span></td>
                    </tr>
                </table>
            </div>

            <!-- Body Text -->
            <div class="main-text" id="field-full_body" contenteditable="true" style="font-family: 'Times New Roman', serif; text-align: justify; margin-bottom: 50px; line-height: 1.8;">
                Telah melaksanakan Praktik Kerja Lapangan (PKL) untuk Konsentrasi Keahlian 
                <b class="highlight">{{ $konsentrasi }}</b> 
                selama <b class="highlight">{{ $lama }}</b> 
                dari tanggal <b class="highlight">{{ $tanggal_mulai }}</b> 
                sampai dengan <b class="highlight">{{ $tanggal_selesai }}</b> 
                dengan nilai yang tercantum di Rapor dengan Predikat : 
                <b class="predikat-box" style="font-size: 18px; color: #0f766e;">{{ $predikat }}</b>
            </div>

            <!-- Footer -->
            <div style="position: relative; height: 160px; width: 90%; margin: 0 auto;">
                <div class="foto-box" style="position: absolute; left: 350px; top: 0; width: 113px; height: 151px; margin: 0;">
                    Foto 3x4
                </div>
                
                <div class="signature-section" style="position: absolute; right: 0; top: 0; width: 300px; padding: 0;">
                    <div class="signature-date" id="field-full_date" contenteditable="true" style="font-family: 'Times New Roman', serif; font-size: 17px; margin-bottom: 10px;">
                        {{ $kota_iduka }}, 14 Februari 2026
                    </div>
                    <div class="signature-role" id="field-signature_role" contenteditable="true" style="font-family: 'Times New Roman', serif; margin-bottom: 80px; font-size: 17px; font-weight: bold;">Pimpinan IDUKA,</div>
                    <p class="signature-name" contenteditable="true" id="field-nama_pimpinan" style="font-family: 'Times New Roman', serif; font-size: 19px; font-weight: bold; text-decoration: underline;">{{ $nama_pimpinan }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Hidden Form for Printing -->
    <form id="print-form" action="{{ route('sertifikat.cetak.custom', $user->id) }}" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="nama" id="input-nama">
        <input type="hidden" name="nis" id="input-nis">
        <input type="hidden" name="tempat_lahir" id="input-tempat_lahir">
        <input type="hidden" name="tgl_lahir" id="input-tgl_lahir">
        <input type="hidden" name="asal_sekolah" id="input-asal_sekolah">
        <input type="hidden" name="tahun_ajaran" id="input-tahun_ajaran">
        
        <!-- Field Baru untuk menampung teks penuh -->
        <input type="hidden" name="full_body" id="input-full_body">
        <input type="hidden" name="full_date" id="input-full_date">
        <input type="hidden" name="signature_role" id="input-signature_role">
        <input type="hidden" name="logo_width" id="input-logo_width" value="85">
        <input type="hidden" name="logo_top" id="input-logo_top" value="0">
        <input type="hidden" name="logo_left" id="input-logo_left" value="0">
        <input type="hidden" name="top_margin" id="input-top_margin" value="50">
        
        <input type="hidden" name="nama_iduka" id="input-nama_iduka">
        <input type="hidden" name="alamat_iduka" id="input-alamat_iduka">
        <input type="hidden" name="nama_pimpinan" id="input-nama_pimpinan">
    </form>

    <div class="toolbar">
        <div class="logo-resizer" style="background: rgba(0,0,0,0.1); padding: 5px 20px; border-radius: 50px; display: flex; align-items: center; gap: 10px; border: 1px solid #ddd;">
            <label style="font-size: 10px; font-weight: bold; color: #333;">Logo Ukuran:</label>
            <input type="range" id="slider-logo-width" min="50" max="300" value="85" style="width: 70px; cursor: pointer;">
            
            <label style="font-size: 10px; font-weight: bold; color: #333;">Logo Atas:</label>
            <input type="range" id="slider-logo-top" min="-50" max="50" value="0" style="width: 60px; cursor: pointer;">
            
            <label style="font-size: 10px; font-weight: bold; color: #333;">Logo Kanan:</label>
            <input type="range" id="slider-logo-left" min="-50" max="50" value="0" style="width: 60px; cursor: pointer;">

            <span style="font-size: 10px; font-weight: bold; background: #0f766e; color: white; padding: 2px 8px; border-radius: 10px; min-width: 30px; text-align: center;"><span id="val-width">85</span></span>
        </div>
        
        <div style="width: 2px; height: 30px; background: #ddd; margin: 0 10px;"></div>

        <button class="btn-action btn-cancel" onclick="window.close()">
            <i class="fas fa-times"></i> Tutup
        </button>
        <button class="btn-action btn-print" onclick="submitForm()">
            <i class="fas fa-file-pdf"></i> Download PDF
        </button>
    </div>

    <script>
        // Logo & Content Logic
        const sliderWidth   = document.getElementById('slider-logo-width');
        const sliderLogoTop = document.getElementById('slider-logo-top');
        const sliderLeft    = document.getElementById('slider-logo-left');
        
        const logo          = document.getElementById('cert-logo');
        const valWidth      = document.getElementById('val-width');

        const inputLogoWidth = document.getElementById('input-logo_width');
        const inputLogoTop   = document.getElementById('input-logo_top');
        const inputLogoLeft  = document.getElementById('input-logo_left');

        // DRAG LOGO LOGIC
        let isDragging = false;
        let startX, startY;

        if (logo) {
            logo.style.position = 'relative';
            logo.style.cursor = 'move';
            
            logo.addEventListener('mousedown', function(e) {
                isDragging = true;
                startX = e.clientX;
                startY = e.clientY;
                startTop  = parseInt(sliderLogoTop.value) || 0;
                startLeft = parseInt(sliderLeft.value) || 0;
                logo.style.opacity = '0.7';
                e.preventDefault();
            });

            document.addEventListener('mousemove', function(e) {
                if (!isDragging) return;
                
                const dx = e.clientX - startX;
                const dy = e.clientY - startY;
                
                const newTop  = startTop + dy;
                const newLeft = startLeft + dx;
                
                // Update Logo with relative positioning (doesn't push other elements)
                logo.style.top  = newTop + 'px';
                logo.style.left = newLeft + 'px';
                
                // Sync to Sliders
                sliderLogoTop.value = newTop;
                sliderLeft.value = newLeft;
                
                // Sync to Hidden Inputs
                inputLogoTop.value = newTop;
                inputLogoLeft.value = newLeft;
            });

            document.addEventListener('mouseup', function() {
                if (isDragging) {
                    isDragging = false;
                    logo.style.opacity = '1';
                }
            });

            // Resize
            sliderWidth.addEventListener('input', function() {
                logo.style.width = this.value + 'px';
                valWidth.innerText = this.value;
                inputLogoWidth.value = this.value;
            });
            // Manual Sliders
            sliderLogoTop.addEventListener('input', function() {
                logo.style.top = this.value + 'px';
                inputLogoTop.value = this.value;
            });
            sliderLeft.addEventListener('input', function() {
                logo.style.left = this.value + 'px';
                inputLogoLeft.value = this.value;
            });
        }

        if (content) {
            sliderContTop.addEventListener('input', function() {
                content.style.paddingTop = this.value + 'px';
                inputTopMargin.value = this.value;
            });
        }

        function submitForm() {
            // Collect data from editable fields
            document.getElementById('input-nama').value = document.getElementById('field-nama').innerText;
            document.getElementById('input-nis').value = document.getElementById('field-nis').innerText;
            document.getElementById('input-tempat_lahir').value = document.getElementById('field-tempat_lahir').innerText;
            document.getElementById('input-tgl_lahir').value = document.getElementById('field-tgl_lahir').innerText;
            document.getElementById('input-asal_sekolah').value = document.getElementById('field-asal_sekolah').innerText;
            document.getElementById('input-tahun_ajaran').value = document.getElementById('field-tahun_ajaran').innerText;
            
            // Ambil teks penuh (termasuk tag bold/span) dari elemen yang diedit
            document.getElementById('input-full_body').value = document.getElementById('field-full_body').innerHTML;
            document.getElementById('input-full_date').value = document.getElementById('field-full_date').innerHTML;
            document.getElementById('input-signature_role').value = document.getElementById('field-signature_role').innerHTML;

            document.getElementById('input-nama_iduka').value = document.getElementById('field-nama_iduka').innerText;
            document.getElementById('input-alamat_iduka').value = document.getElementById('field-alamat_iduka').innerText;
            document.getElementById('input-nama_pimpinan').value = document.getElementById('field-nama_pimpinan').innerText;

            // Submit hidden form
            document.getElementById('print-form').submit();
        }

        // Prevent Enter key in some fields if needed
        document.querySelectorAll('[contenteditable="true"]').forEach(el => {
            el.addEventListener('keydown', e => {
                if (e.key === 'Enter' && !el.id.includes('full_body')) {
                    e.preventDefault(); 
                }
            });
        });
    </script>
    </script>
</body>
</html>
