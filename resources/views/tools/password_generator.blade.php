@extends('layouts.app')

@section('title', 'Password Generator')

@section('content')
<div class="d-flex flex-column gap-6">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('tools.index') }}" class="btn btn-sm btn-icon btn-light me-2"><i class="ki-outline ki-arrow-left fs-2"></i></a>
            <div>
                <h1 class="text-gray-900 fw-bolder fs-3 my-0">Password Generator</h1>
                <span class="text-muted fs-7">Generate kata sandi yang kuat dan aman secara instan.</span>
            </div>
        </div>
        <a href="{{ route('tools.index') }}" class="btn btn-sm btn-light fw-bold"><i class="ki-outline ki-element-11 fs-4 me-1"></i> Semua Tools</a>
    </div>

    <div class="card card-flush border-0 shadow-sm">
        <div class="card-body p-6 p-lg-8">
            <div class="mb-5">
                <label class="form-label fs-7 fw-semibold">Hasil Password</label>
                <div class="input-group">
                    <input type="text" id="passOutput" class="form-control form-control-solid bg-light text-primary fw-bold font-monospace fs-4" readonly>
                    <button type="button" id="btnGenPass" class="btn btn-light-primary btn-sm fw-bold px-4"><i class="ki-outline ki-arrows-circle fs-4 me-1"></i> Generate Ulang</button>
                    <button type="button" id="btnCopyPass" class="btn btn-primary btn-sm fw-bold px-4"><i class="ki-outline ki-copy fs-4 me-1"></i> Salin</button>
                </div>
            </div>

            <div class="row g-4 mb-4">
                <div class="col-md-6">
                    <label class="form-label fs-7 fw-semibold">Panjang Karakter: <span id="passLenVal" class="text-primary fw-bold">16</span></label>
                    <input type="range" id="passLen" class="form-range" min="8" max="64" value="16">
                </div>
                <div class="col-md-6">
                    <div class="d-flex flex-column gap-2 mt-4">
                        <label class="form-check form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" id="chkUpper" checked> <span class="form-check-label fs-7">Huruf Besar (A-Z)</span></label>
                        <label class="form-check form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" id="chkLower" checked> <span class="form-check-label fs-7">Huruf Kecil (a-z)</span></label>
                        <label class="form-check form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" id="chkNum" checked> <span class="form-check-label fs-7">Angka (0-9)</span></label>
                        <label class="form-check form-check-custom form-check-solid"><input class="form-check-input" type="checkbox" id="chkSym" checked> <span class="form-check-label fs-7">Simbol Khusus (!@#$%^&*)</span></label>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const lenInput = document.getElementById('passLen');
    const lenVal = document.getElementById('passLenVal');
    const output = document.getElementById('passOutput');

    function generate() {
        const len = parseInt(lenInput.value);
        lenVal.textContent = len;
        let chars = '';
        if (document.getElementById('chkUpper').checked) chars += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if (document.getElementById('chkLower').checked) chars += 'abcdefghijklmnopqrstuvwxyz';
        if (document.getElementById('chkNum').checked) chars += '0123456789';
        if (document.getElementById('chkSym').checked) chars += '!@#$%^&*()-_=+[]{}|;:,.<>?';

        if (!chars) chars = 'abcdefghijklmnopqrstuvwxyz';

        let res = '';
        const bytes = new Uint8Array(len);
        window.crypto.getRandomValues(bytes);
        for (let i = 0; i < len; i++) {
            res += chars[bytes[i] % chars.length];
        }
        output.value = res;
    }

    lenInput.addEventListener('input', generate);
    ['chkUpper', 'chkLower', 'chkNum', 'chkSym'].forEach(id => document.getElementById(id).addEventListener('change', generate));
    document.getElementById('btnGenPass').addEventListener('click', generate);
    document.getElementById('btnCopyPass').addEventListener('click', () => {
        navigator.clipboard.writeText(output.value).then(() => {
            Swal.fire({ text: 'Password berhasil disalin!', icon: 'success', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
        });
    });

    generate();
});
</script>
@endsection
