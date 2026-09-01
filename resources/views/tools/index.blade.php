@extends('layouts.app')

@section('title', 'Online Tools')

@section('content')
<div class="d-flex flex-column gap-6">
    <!-- Header Section -->
    <div class="card card-flush border-0 shadow-sm bg-white">
        <div class="card-body p-6 p-lg-8">
            <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between gap-4">
                <div>
                    <div class="d-flex align-items-center gap-3">
                        <div class="symbol symbol-45px symbol-circle bg-light-primary">
                            <span class="symbol-label">
                                <i class="ki-outline ki-wrench fs-2 text-primary"></i>
                            </span>
                        </div>
                        <div>
                            <h1 class="text-gray-900 fw-bolder fs-2 my-0">Online Tools</h1>
                            <span class="text-muted fs-7">Kumpulan alat bantu online praktis untuk optimasi link, marketing, dan utilitas digital.</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Instant Search Bar -->
            <div class="position-relative mt-6">
                <i class="ki-outline ki-magnifier fs-3 text-gray-500 position-absolute top-50 translate-middle-y ms-4"></i>
                <input type="text" id="toolSearchInput" class="form-control form-control-solid ps-12 py-3 fs-6 rounded-3" placeholder="Cari tools... (contoh: whatsapp, utm, slug, dns, password, base64)">
            </div>
        </div>
    </div>

    <!-- Category Sections -->
    @foreach($categories as $catKey => $category)
        <div class="tool-category-group" data-category="{{ $catKey }}">
            <!-- Category Header Accordion -->
            <div class="card card-flush border-0 shadow-sm text-white mb-4 rounded-3" style="background-color: {{ $category['color'] }};">
                <div class="card-body py-4 px-6 d-flex align-items-center justify-content-between cursor-pointer" data-bs-toggle="collapse" data-bs-target="#collapse-cat-{{ $catKey }}" aria-expanded="true">
                    <div class="d-flex align-items-center gap-3">
                        <div class="symbol symbol-35px symbol-circle bg-white bg-opacity-20 d-flex align-items-center justify-content-center">
                            <i class="ki-outline {{ $category['icon'] }} fs-3 text-white"></i>
                        </div>
                        <div>
                            <h2 class="text-white fw-bold fs-5 my-0">{{ $category['title'] }}</h2>
                            <span class="text-white text-opacity-75 fs-8">{{ $category['description'] }}</span>
                        </div>
                    </div>
                    <i class="ki-outline ki-down fs-2 text-white transition-transform"></i>
                </div>
            </div>

            <!-- Tools Grid for this Category -->
            <div class="collapse show" id="collapse-cat-{{ $catKey }}">
                <div class="row g-4 mb-6">
                    @foreach($category['tools'] as $tool)
                        <div class="col-md-6 col-xl-6 tool-card-item" data-title="{{ strtolower($tool['title']) }}" data-desc="{{ strtolower($tool['description']) }}">
                            @if(isset($tool['action']) && $tool['action'] === 'modal')
                                <div class="card card-flush h-100 border border-gray-200 border-hover-primary shadow-xs transition-all cursor-pointer tool-clickable-card" data-bs-toggle="modal" data-bs-target="{{ $tool['modal_target'] }}">
                            @else
                                <a href="{{ $tool['route'] }}" class="card card-flush h-100 border border-gray-200 border-hover-primary shadow-xs transition-all text-decoration-none tool-clickable-card">
                            @endif
                                <div class="card-body p-5 d-flex align-items-start gap-4">
                                    <div class="symbol symbol-45px symbol-rounded bg-light-primary flex-shrink-0 mt-1">
                                        <span class="symbol-label">
                                            <i class="ki-outline {{ $tool['icon'] }} fs-2 text-primary"></i>
                                        </span>
                                    </div>
                                    <div class="d-flex flex-column flex-grow-1">
                                        <div class="d-flex align-items-center justify-content-between gap-2 mb-1">
                                            <h3 class="text-gray-900 fw-bold fs-6 my-0 text-hover-primary">{{ $tool['title'] }}</h3>
                                            @if(isset($tool['badge']))
                                                <span class="badge {{ $tool['badge_class'] ?? 'badge-light-primary' }} fs-8 fw-semibold px-2 py-1">{{ $tool['badge'] }}</span>
                                            @endif
                                        </div>
                                        <p class="text-gray-600 fs-7 mb-0 line-clamp-2">{{ $tool['description'] }}</p>
                                    </div>
                                </div>
                            @if(isset($tool['action']) && $tool['action'] === 'modal')
                                </div>
                            @else
                                </a>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endforeach

    <!-- No Search Results Found Alert -->
    <div id="noSearchResults" class="card card-flush border-0 shadow-sm d-none py-10 text-center">
        <div class="card-body">
            <i class="ki-outline ki-search-list fs-3x text-muted mb-3"></i>
            <h3 class="text-gray-800 fw-bold fs-5 mb-1">Tidak ada tools yang cocok</h3>
            <p class="text-muted fs-7 mb-0">Coba gunakan kata kunci pencarian lain.</p>
        </div>
    </div>
</div>

<!-- Quick Utility Modals -->
<!-- DNS Lookup Modal -->
<div class="modal fade" id="dnsLookupModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h3 class="modal-title fw-bold text-gray-900"><i class="ki-outline ki-geolocation fs-2 text-primary me-2"></i> DNS Lookup Checker</h3>
                <button type="button" class="btn btn-sm btn-icon btn-light" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-2"></i></button>
            </div>
            <div class="modal-body">
                <div class="mb-4">
                    <label class="form-label fs-7 fw-semibold required">Nama Domain / Hostname</label>
                    <input type="text" id="dnsLookupHost" class="form-control form-control-solid" placeholder="contoh: google.com">
                </div>
                <button type="button" id="btnRunDnsLookup" class="btn btn-primary btn-sm fw-bold w-100 mb-4">Cek DNS Record</button>
                <div id="dnsLookupResult" class="p-3 bg-light rounded-3 d-none">
                    <pre id="dnsLookupOutput" class="mb-0 fs-8 text-gray-800" style="max-height: 250px; overflow-y: auto;"></pre>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Base64 Modal -->
<div class="modal fade" id="base64Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h3 class="modal-title fw-bold text-gray-900"><i class="ki-outline ki-code fs-2 text-success me-2"></i> Base64 Encoder / Decoder</h3>
                <button type="button" class="btn btn-sm btn-icon btn-light" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-2"></i></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fs-7 fw-semibold">Input Teks</label>
                    <textarea id="base64Input" class="form-control form-control-solid" rows="3" placeholder="Masukkan teks atau string base64..."></textarea>
                </div>
                <div class="d-flex gap-2 mb-3">
                    <button type="button" id="btnBase64Encode" class="btn btn-primary btn-sm fw-bold flex-grow-1">Encode ke Base64</button>
                    <button type="button" id="btnBase64Decode" class="btn btn-light-primary btn-sm fw-bold flex-grow-1">Decode dari Base64</button>
                </div>
                <div>
                    <label class="form-label fs-7 fw-semibold">Hasil</label>
                    <textarea id="base64Output" class="form-control form-control-solid" rows="3" readonly></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- URL Encoder Modal -->
<div class="modal fade" id="urlEncoderModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-3">
            <div class="modal-header">
                <h3 class="modal-title fw-bold text-gray-900"><i class="ki-outline ki-disconnect fs-2 text-info me-2"></i> URL Encoder / Decoder</h3>
                <button type="button" class="btn btn-sm btn-icon btn-light" data-bs-dismiss="modal"><i class="ki-outline ki-cross fs-2"></i></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label fs-7 fw-semibold">Input URL / Teks</label>
                    <textarea id="urlEncoderInput" class="form-control form-control-solid" rows="3" placeholder="Masukkan teks atau URL..."></textarea>
                </div>
                <div class="d-flex gap-2 mb-3">
                    <button type="button" id="btnUrlEncode" class="btn btn-info btn-sm fw-bold flex-grow-1">URL Encode</button>
                    <button type="button" id="btnUrlDecode" class="btn btn-light-info btn-sm fw-bold flex-grow-1">URL Decode</button>
                </div>
                <div>
                    <label class="form-label fs-7 fw-semibold">Hasil</label>
                    <textarea id="urlEncoderOutput" class="form-control form-control-solid" rows="3" readonly></textarea>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tool-clickable-card:hover {
    transform: translateY(-2px);
    border-color: var(--bs-primary) !important;
    box-shadow: 0 4px 15px rgba(0,0,0,0.06) !important;
}
.line-clamp-2 {
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // 1. Instant Realtime Search Filter
    const searchInput = document.getElementById('toolSearchInput');
    const toolItems = document.querySelectorAll('.tool-card-item');
    const categoryGroups = document.querySelectorAll('.tool-category-group');
    const noResults = document.getElementById('noSearchResults');

    searchInput.addEventListener('input', function() {
        const query = this.value.trim().toLowerCase();
        let totalVisible = 0;

        toolItems.forEach(item => {
            const title = item.getAttribute('data-title') || '';
            const desc = item.getAttribute('data-desc') || '';
            if (title.includes(query) || desc.includes(query)) {
                item.classList.remove('d-none');
                totalVisible++;
            } else {
                item.classList.add('d-none');
            }
        });

        // Hide empty category groups
        categoryGroups.forEach(group => {
            const visibleChildren = group.querySelectorAll('.tool-card-item:not(.d-none)');
            if (visibleChildren.length === 0) {
                group.classList.add('d-none');
            } else {
                group.classList.remove('d-none');
            }
        });

        if (totalVisible === 0) {
            noResults.classList.remove('d-none');
        } else {
            noResults.classList.add('d-none');
        }
    });

    // 2. Base64 encode/decode
    document.getElementById('btnBase64Encode')?.addEventListener('click', function() {
        const input = document.getElementById('base64Input').value;
        try {
            document.getElementById('base64Output').value = btoa(unescape(encodeURIComponent(input)));
        } catch (e) {
            document.getElementById('base64Output').value = 'Error encoding: ' + e.message;
        }
    });

    document.getElementById('btnBase64Decode')?.addEventListener('click', function() {
        const input = document.getElementById('base64Input').value;
        try {
            document.getElementById('base64Output').value = decodeURIComponent(escape(atob(input)));
        } catch (e) {
            document.getElementById('base64Output').value = 'Error decoding: String bukan format Base64 yang valid.';
        }
    });

    // 3. URL encode/decode
    document.getElementById('btnUrlEncode')?.addEventListener('click', function() {
        const input = document.getElementById('urlEncoderInput').value;
        document.getElementById('urlEncoderOutput').value = encodeURIComponent(input);
    });

    document.getElementById('btnUrlDecode')?.addEventListener('click', function() {
        const input = document.getElementById('urlEncoderInput').value;
        try {
            document.getElementById('urlEncoderOutput').value = decodeURIComponent(input);
        } catch (e) {
            document.getElementById('urlEncoderOutput').value = 'Error decoding: ' + e.message;
        }
    });

    // 4. DNS Lookup Quick Check
    document.getElementById('btnRunDnsLookup')?.addEventListener('click', function() {
        const host = document.getElementById('dnsLookupHost').value.trim();
        if (!host) {
            Swal.fire({ text: 'Masukkan nama domain terlebih dahulu!', icon: 'warning', confirmButtonText: 'OK', buttonsStyling: false, customClass: { confirmButton: 'btn btn-primary btn-sm' } });
            return;
        }
        
        const resultBox = document.getElementById('dnsLookupResult');
        const output = document.getElementById('dnsLookupOutput');
        resultBox.classList.remove('d-none');
        output.textContent = 'Memeriksa DNS records untuk ' + host + '...';

        fetch('https://dns.google/resolve?name=' + encodeURIComponent(host))
            .then(res => res.json())
            .then(data => {
                if (data.Answer) {
                    output.textContent = JSON.stringify(data.Answer, null, 2);
                } else {
                    output.textContent = 'Tidak ada DNS record ditemukan untuk host ini.';
                }
            })
            .catch(err => {
                output.textContent = 'Gagal menghubungi DNS server: ' + err.message;
            });
    });
});
</script>
@endsection
