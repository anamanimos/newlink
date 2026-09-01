@extends('layouts.app')

@section('title', 'WhatsApp Link Generator')

@section('content')
<div class="d-flex flex-column gap-6">
    <!-- Breadcrumb & Header -->
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('tools.index') }}" class="btn btn-sm btn-icon btn-light me-2">
                <i class="ki-outline ki-arrow-left fs-2"></i>
            </a>
            <div>
                <h1 class="page-heading d-flex text-gray-900 fw-bolder fs-3 my-0">
                    WhatsApp Link Generator
                </h1>
                <span class="text-muted fs-7">Buat tautan chat WhatsApp instan dengan nomor dan template pesan otomatis.</span>
            </div>
        </div>
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('tools.index') }}" class="btn btn-sm btn-light fw-bold">
                <i class="ki-outline ki-element-11 fs-4 me-1"></i> Semua Tools
            </a>
        </div>
    </div>

    <!-- Main Generator Card Row -->
    <div class="row g-6 g-xl-9">
        <!-- Left Column: Input Form -->
        <div class="col-lg-7">
            <div class="card card-flush border-0 shadow-sm">
                <div class="card-header pt-6 pb-2">
                    <h3 class="card-title fw-bold text-gray-900 fs-4">
                        <i class="ki-outline ki-whatsapp fs-2 text-success me-2"></i> Konfigurasi Pesan WhatsApp
                    </h3>
                </div>
                <div class="card-body p-6">
                    <!-- Phone Number Input -->
                    <div class="mb-5">
                        <label class="form-label fs-7 fw-semibold text-gray-900 required">Nomor Telepon / WhatsApp</label>
                        <div class="input-group input-group-solid">
                            <select id="countryPrefix" class="form-select form-select-solid" style="max-width: 140px;">
                                <option value="62" selected>🇮🇩 +62 (ID)</option>
                                <option value="60">🇲🇾 +60 (MY)</option>
                                <option value="65">🇸🇬 +65 (SG)</option>
                                <option value="1">🇺🇸 +1 (US)</option>
                                <option value="44">🇬🇧 +44 (UK)</option>
                                <option value="61">🇦🇺 +61 (AU)</option>
                                <option value="81">🇯🇵 +81 (JP)</option>
                                <option value="966">🇸🇦 +966 (SA)</option>
                            </select>
                            <input type="text" id="waPhoneInput" class="form-control form-control-solid" placeholder="81234567890 (tanpa awalan 0)" value="81234567890">
                        </div>
                        <div class="form-text fs-8 text-muted mt-1">
                            Tip: Anda bisa mengetik langsung dengan awalan <code>08...</code> atau <code>628...</code>, sistem akan memformat otomatis.
                        </div>
                    </div>

                    <!-- Preset Message Chips -->
                    <div class="mb-3">
                        <label class="form-label fs-8 fw-semibold text-gray-700">Contoh Template Cepat:</label>
                        <div class="d-flex flex-wrap gap-2">
                            <button type="button" class="btn btn-xs btn-light-primary fw-semibold preset-chip" data-msg="Halo Admin, saya ingin bertanya tentang produk ini.">
                                💬 Tanya Produk
                            </button>
                            <button type="button" class="btn btn-xs btn-light-success fw-semibold preset-chip" data-msg="Halo kak, saya mau pesan sekarang. Mohon info no rekening.">
                                🛒 Order Sekarang
                            </button>
                            <button type="button" class="btn btn-xs btn-light-info fw-semibold preset-chip" data-msg="Halo, saya butuh bantuan terkait layanan Anda.">
                                🛠️ Layanan Support
                            </button>
                        </div>
                    </div>

                    <!-- Message Textarea -->
                    <div class="mb-5">
                        <div class="d-flex align-items-center justify-content-between mb-1">
                            <label class="form-label fs-7 fw-semibold text-gray-900 mb-0">Template Pesan Otomatis (Opsional)</label>
                            <span id="charCount" class="text-muted fs-8">0 karakter</span>
                        </div>
                        <textarea id="waMessageInput" class="form-control form-control-solid" rows="5" placeholder="Tulis pesan yang akan otomatis terisi saat pengunjung mengklik link WhatsApp Anda...">Halo Admin, saya ingin bertanya mengenai informasi layanan Anda.</textarea>
                    </div>

                    <div class="d-flex align-items-center justify-content-between pt-2">
                        <button type="button" id="btnResetWa" class="btn btn-sm btn-light text-muted fw-semibold">
                            <i class="ki-outline ki-arrows-circle fs-4 me-1"></i> Reset
                        </button>
                        <span class="badge badge-light-success fw-bold fs-8">
                            <i class="ki-outline ki-check-circle fs-8 text-success me-1"></i> Live Generator Aktif
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right Column: Output & QR Code -->
        <div class="col-lg-5">
            <div class="card card-flush border-0 shadow-sm sticky-top" style="top: 90px;">
                <div class="card-header pt-6 pb-2">
                    <h3 class="card-title fw-bold text-gray-900 fs-4">
                        <i class="ki-outline ki-link fs-2 text-primary me-2"></i> Hasil Tautan WhatsApp
                    </h3>
                </div>
                <div class="card-body p-6">
                    <!-- Link Output Box -->
                    <div class="mb-4">
                        <label class="form-label fs-7 fw-semibold text-gray-800">Direct Link (wa.me)</label>
                        <div class="input-group">
                            <input type="text" id="waGeneratedUrl" class="form-control form-control-solid bg-light text-primary fw-semibold fs-7" readonly>
                            <button type="button" id="btnCopyWaLink" class="btn btn-primary btn-sm px-4 fw-bold">
                                <i class="ki-outline ki-copy fs-4 me-1"></i> Salin
                            </button>
                        </div>
                    </div>

                    <!-- Test Button -->
                    <div class="d-grid gap-2 mb-6">
                        <a id="btnTestWaLink" href="#" target="_blank" class="btn btn-success btn-sm fw-bold d-flex align-items-center justify-content-center gap-2">
                            <i class="ki-outline ki-whatsapp fs-3"></i> Tes Buka Chat WhatsApp
                        </a>
                    </div>

                    <div class="separator separator-dashed my-5"></div>

                    <!-- QR Code Preview Box -->
                    <div class="text-center">
                        <label class="form-label fs-7 fw-semibold text-gray-800 mb-3 d-block">QR Code WhatsApp Instan</label>
                        <div class="d-inline-flex p-3 bg-white border border-gray-200 rounded-3 shadow-xs mb-3">
                            <img id="waQrImage" src="" alt="QR Code WhatsApp" style="width: 180px; height: 180px;" class="img-fluid">
                        </div>
                        <div>
                            <button type="button" id="btnDownloadQr" class="btn btn-light-primary btn-sm fw-semibold">
                                <i class="ki-outline ki-file-down fs-4 me-1"></i> Download QR Code (PNG)
                            </button>
                        </div>
                    </div>

                    <div class="separator separator-dashed my-5"></div>

                    <!-- Shortcut Next Actions -->
                    <div>
                        <span class="fs-8 text-muted d-block mb-2 fw-semibold">Gunakan Tautan Ini Di Platform NewLink:</span>
                        <div class="d-flex gap-2">
                            <a id="btnCreateShortlink" href="{{ route('links.index') }}" class="btn btn-light-info btn-xs fw-semibold flex-grow-1">
                                <i class="ki-outline ki-disconnect fs-7 me-1"></i> Buat Short Link
                            </a>
                            <a href="{{ route('warotators.create') }}" class="btn btn-light-success btn-xs fw-semibold flex-grow-1">
                                <i class="ki-outline ki-whatsapp fs-7 me-1"></i> Buat WA Rotator
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- SEO & FAQ Section -->
    <div class="card card-flush border-0 shadow-sm mt-4">
        <div class="card-body p-6 p-lg-8">
            <h2 class="text-gray-900 fw-bold fs-4 mb-4">Tentang WhatsApp Link Generator</h2>
            <div class="row g-6">
                <div class="col-md-6">
                    <h3 class="text-gray-800 fw-semibold fs-6 mb-2">Apa itu WhatsApp Link Generator?</h3>
                    <p class="text-gray-600 fs-7 mb-0">
                        WhatsApp Link Generator memungkinkan Anda membuat tautan <code>https://wa.me/...</code> langsung yang ketika diklik oleh pelanggan, otomatis membuka aplikasi WhatsApp dengan pesan pembuka yang sudah terisi rapi tanpa perlu menyimpan nomor kontak terlebih dahulu.
                    </p>
                </div>
                <div class="col-md-6">
                    <h3 class="text-gray-800 fw-semibold fs-6 mb-2">Mengapa Memakai QR Code WhatsApp?</h3>
                    <p class="text-gray-600 fs-7 mb-0">
                        QR Code sangat cocok dicetak pada kemasan produk, banner, brosur, atau kartu nama bisnis. Pelanggan cukup memindai kamera smartphone untuk langsung terhubung ke admin WhatsApp Anda.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const prefixSelect = document.getElementById('countryPrefix');
    const phoneInput = document.getElementById('waPhoneInput');
    const msgInput = document.getElementById('waMessageInput');
    const urlOutput = document.getElementById('waGeneratedUrl');
    const btnTest = document.getElementById('btnTestWaLink');
    const qrImage = document.getElementById('waQrImage');
    const charCount = document.getElementById('charCount');

    function cleanPhoneNumber(raw, prefix) {
        let clean = raw.replace(/\D/g, ''); // strip non-digits
        
        // If starts with 0 (e.g. 0812...), replace with prefix
        if (clean.startsWith('0')) {
            clean = prefix + clean.substring(1);
        } else if (!clean.startsWith(prefix)) {
            clean = prefix + clean;
        }
        return clean;
    }

    function updateWaLink() {
        const prefix = prefixSelect.value;
        const rawPhone = phoneInput.value.trim();
        const message = msgInput.value;

        charCount.textContent = message.length + ' karakter';

        if (!rawPhone) {
            urlOutput.value = '';
            btnTest.href = '#';
            qrImage.src = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https://wa.me/';
            return;
        }

        const formattedPhone = cleanPhoneNumber(rawPhone, prefix);
        let finalUrl = 'https://wa.me/' + formattedPhone;
        
        if (message.trim()) {
            finalUrl += '?text=' + encodeURIComponent(message);
        }

        urlOutput.value = finalUrl;
        btnTest.href = finalUrl;
        
        // Generate QR code
        qrImage.src = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(finalUrl);
    }

    // Event listeners for live typing
    phoneInput.addEventListener('input', updateWaLink);
    msgInput.addEventListener('input', updateWaLink);
    prefixSelect.addEventListener('change', updateWaLink);

    // Preset chips
    document.querySelectorAll('.preset-chip').forEach(chip => {
        chip.addEventListener('click', function() {
            msgInput.value = this.getAttribute('data-msg');
            updateWaLink();
        });
    });

    // Reset button
    document.getElementById('btnResetWa').addEventListener('click', function() {
        phoneInput.value = '';
        msgInput.value = '';
        updateWaLink();
    });

    // Copy button with SweetAlert2 Toast
    document.getElementById('btnCopyWaLink').addEventListener('click', function() {
        if (!urlOutput.value) {
            Swal.fire({ text: 'Masukkan nomor WhatsApp terlebih dahulu!', icon: 'warning', confirmButtonText: 'OK', buttonsStyling: false, customClass: { confirmButton: 'btn btn-primary btn-sm' } });
            return;
        }

        navigator.clipboard.writeText(urlOutput.value).then(function() {
            Swal.fire({
                text: 'Tautan WhatsApp berhasil disalin ke clipboard!',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false,
                toast: true,
                position: 'top-end'
            });
        }).catch(function() {
            urlOutput.select();
            document.execCommand('copy');
            Swal.fire({ text: 'Tautan berhasil disalin!', icon: 'success', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
        });
    });

    // Download QR
    document.getElementById('btnDownloadQr').addEventListener('click', function() {
        if (!urlOutput.value) return;
        const qrUrl = qrImage.src;
        fetch(qrUrl)
            .then(res => res.blob())
            .then(blob => {
                const a = document.createElement('a');
                a.href = URL.createObjectURL(blob);
                a.download = 'whatsapp-qr-code.png';
                document.body.appendChild(a);
                a.click();
                document.body.removeChild(a);
            });
    });

    // Initial trigger
    updateWaLink();
});
</script>
@endsection
