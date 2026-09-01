@extends('layouts.app')

@section('title', 'Slug Generator')

@section('content')
<div class="d-flex flex-column gap-6">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('tools.index') }}" class="btn btn-sm btn-icon btn-light me-2"><i class="ki-outline ki-arrow-left fs-2"></i></a>
            <div>
                <h1 class="text-gray-900 fw-bolder fs-3 my-0">Slug Generator</h1>
                <span class="text-muted fs-7">Ubah judul artikel atau teks menjadi slug URL bersih yang ramah SEO.</span>
            </div>
        </div>
        <a href="{{ route('tools.index') }}" class="btn btn-sm btn-light fw-bold"><i class="ki-outline ki-element-11 fs-4 me-1"></i> Semua Tools</a>
    </div>

    <div class="card card-flush border-0 shadow-sm">
        <div class="card-body p-6 p-lg-8">
            <div class="mb-5">
                <label class="form-label fs-7 fw-semibold required">Input Teks / Judul</label>
                <input type="text" id="slugInputText" class="form-control form-control-solid" placeholder="Tulis judul di sini... (contoh: Panduan Lengkap Membuat Biolink Keren 2026!)" value="Panduan Lengkap Membuat Biolink Keren 2026!">
            </div>
            <div class="mb-5">
                <label class="form-label fs-7 fw-semibold">Hasil Slug URL</label>
                <div class="input-group">
                    <input type="text" id="slugOutput" class="form-control form-control-solid bg-light text-primary fw-bold" readonly>
                    <button type="button" id="btnCopySlug" class="btn btn-primary btn-sm fw-bold px-5"><i class="ki-outline ki-copy fs-4 me-1"></i> Salin</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('slugInputText');
    const output = document.getElementById('slugOutput');

    function makeSlug(str) {
        return str.toLowerCase()
            .trim()
            .replace(/[^\w\s-]/g, '')
            .replace(/[\s_-]+/g, '-')
            .replace(/^-+|-+$/g, '');
    }

    input.addEventListener('input', () => { output.value = makeSlug(input.value); });
    output.value = makeSlug(input.value);

    document.getElementById('btnCopySlug').addEventListener('click', function() {
        if (!output.value) return;
        navigator.clipboard.writeText(output.value).then(() => {
            Swal.fire({ text: 'Slug berhasil disalin!', icon: 'success', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
        });
    });
});
</script>
@endsection
