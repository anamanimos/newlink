@extends('layouts.app')

@section('title', 'UUID v4 Generator')

@section('content')
<div class="d-flex flex-column gap-6">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('tools.index') }}" class="btn btn-sm btn-icon btn-light me-2"><i class="ki-outline ki-arrow-left fs-2"></i></a>
            <div>
                <h1 class="text-gray-900 fw-bolder fs-3 my-0">UUID v4 Generator</h1>
                <span class="text-muted fs-7">Generate Universally Unique Identifier (UUID) versi 4 acak yang valid.</span>
            </div>
        </div>
        <a href="{{ route('tools.index') }}" class="btn btn-sm btn-light fw-bold"><i class="ki-outline ki-element-11 fs-4 me-1"></i> Semua Tools</a>
    </div>

    <div class="card card-flush border-0 shadow-sm">
        <div class="card-body p-6 p-lg-8">
            <div class="mb-5">
                <label class="form-label fs-7 fw-semibold">Hasil UUID v4</label>
                <div class="input-group">
                    <input type="text" id="uuidOutput" class="form-control form-control-solid bg-light text-primary fw-bold font-monospace fs-4" readonly>
                    <button type="button" id="btnGenUuid" class="btn btn-light-primary btn-sm fw-bold px-4"><i class="ki-outline ki-arrows-circle fs-4 me-1"></i> Generate Ulang</button>
                    <button type="button" id="btnCopyUuid" class="btn btn-primary btn-sm fw-bold px-4"><i class="ki-outline ki-copy fs-4 me-1"></i> Salin</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const output = document.getElementById('uuidOutput');
    function gen() {
        output.value = ([1e7]+-1e3+-4e3+-8e3+-1e11).replace(/[018]/g, c =>
            (c ^ crypto.getRandomValues(new Uint8Array(1))[0] & 15 >> c / 4).toString(16)
        );
    }
    document.getElementById('btnGenUuid').addEventListener('click', gen);
    document.getElementById('btnCopyUuid').addEventListener('click', () => {
        navigator.clipboard.writeText(output.value).then(() => {
            Swal.fire({ text: 'UUID berhasil disalin!', icon: 'success', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
        });
    });
    gen();
});
</script>
@endsection
