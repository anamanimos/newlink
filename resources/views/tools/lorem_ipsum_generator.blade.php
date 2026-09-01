@extends('layouts.app')

@section('title', 'Lorem Ipsum Generator')

@section('content')
<div class="d-flex flex-column gap-6">
    <div class="d-flex align-items-center justify-content-between gap-3">
        <div class="d-flex align-items-center gap-2">
            <a href="{{ route('tools.index') }}" class="btn btn-sm btn-icon btn-light me-2"><i class="ki-outline ki-arrow-left fs-2"></i></a>
            <div>
                <h1 class="text-gray-900 fw-bolder fs-3 my-0">Lorem Ipsum Generator</h1>
                <span class="text-muted fs-7">Generate teks placeholder dummy Lorem Ipsum untuk kebutuhan desain layout.</span>
            </div>
        </div>
        <a href="{{ route('tools.index') }}" class="btn btn-sm btn-light fw-bold"><i class="ki-outline ki-element-11 fs-4 me-1"></i> Semua Tools</a>
    </div>

    <div class="card card-flush border-0 shadow-sm">
        <div class="card-body p-6 p-lg-8">
            <div class="row g-4 align-items-end mb-5">
                <div class="col-md-4">
                    <label class="form-label fs-7 fw-semibold">Jumlah Paragraf</label>
                    <input type="number" id="numParagraphs" class="form-control form-control-solid" min="1" max="20" value="3">
                </div>
                <div class="col-md-4">
                    <button type="button" id="btnGenLorem" class="btn btn-primary btn-sm fw-bold w-100 py-3"><i class="ki-outline ki-arrows-circle fs-4 me-1"></i> Generate Teks</button>
                </div>
                <div class="col-md-4">
                    <button type="button" id="btnCopyLorem" class="btn btn-light-primary btn-sm fw-bold w-100 py-3"><i class="ki-outline ki-copy fs-4 me-1"></i> Salin Semua</button>
                </div>
            </div>
            <div>
                <label class="form-label fs-7 fw-semibold">Teks Hasil</label>
                <textarea id="loremOutput" class="form-control form-control-solid" rows="10" readonly></textarea>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rawLorem = [
        "Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.",
        "Curabitur pretium tincidunt lacus. Nulla gravida orci a odio. Nullam varius, turpis et commodo pharetra, est eros bibendum elit, nec luctus magna felis sollicitudin mauris. Integer in mauris eu nibh euismod gravida. Duis ac tellus et risus vulputate vehicula. Donec lobortis risus a elit. Etiam tempor. Ut ullamcorper, ligula eu tempor congue, eros est euismod turpis, id tincidunt sapien risus a quam.",
        "Pellentesque habitant morbi tristique senectus et netus et malesuada fames ac turpis egestas. Proin pharetra nonummy pede. Mauris et orci. Aenean nec lorem. In porttitor. Donec laoreet nonummy augue. Suspendisse dui purus, scelerisque at, vulputate vitae, pretium mattis, nunc. Mauris eget neque at sem venenatis eleifend. Ut nonummy.",
        "Fusce aliquet pede non pede. Suspendisse dapibus lorem pellentesque magna. Integer nulla. Donec blandit feugiat ligula. Donec hendrerit, felis et imperdiet euismod, purus ipsum pretium metus, in lacinia nulla nisl eu sapien. Fusce vulputate sem at sapien. Vivamus leo. Aliquam euismod libero eu enim. Nulla nec felis sed leo placerat imperdiet.",
        "Aenean tellus metus, bibendum sed, posuere ac, mattis non, nunc. Aliquam ornare hendrerit augue. Cras elit magna, congue quis, tristique adipiscing, rhoncus eget, urna. Curabitur a felis in nunc fringilla tristique. Morbi leo mi, nonummy eget tristique non, rhoncus non leo. Phasellus adipiscing semper elit. Proin fermentum massa ac quam."
    ];

    function gen() {
        const count = Math.max(1, Math.min(20, parseInt(document.getElementById('numParagraphs').value) || 3));
        let res = [];
        for (let i = 0; i < count; i++) {
            res.push(rawLorem[i % rawLorem.length]);
        }
        document.getElementById('loremOutput').value = res.join("\n\n");
    }

    document.getElementById('btnGenLorem').addEventListener('click', gen);
    document.getElementById('btnCopyLorem').addEventListener('click', () => {
        navigator.clipboard.writeText(document.getElementById('loremOutput').value).then(() => {
            Swal.fire({ text: 'Teks Lorem Ipsum berhasil disalin!', icon: 'success', timer: 2000, showConfirmButton: false, toast: true, position: 'top-end' });
        });
    });
    gen();
});
</script>
@endsection
