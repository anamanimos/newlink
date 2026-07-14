@extends('layouts.app')

@section('title', 'Biolink Builder')

@section('content')
@php
    $fullUrl = $link->domain_id && $link->domain ? $link->domain->scheme . $link->domain->host . '/' . $link->url : url('/') . '/' . $link->url;
@endphp
<div class="d-flex align-items-center justify-content-between mb-4 mt-2">
    <h4 class="fw-bold mb-0 d-flex align-items-center text-dark-custom" style="font-size: 1.5rem; letter-spacing: -0.5px;">
        <span data-duo-icons="app" style="width: 22px; height: 22px; margin-right: 12px;" class="text-muted"></span>
        Biolink Builder: {{ $link->url }}
    </h4>
    <a href="{{ $fullUrl }}" target="_blank" class="btn btn-outline-secondary d-flex align-items-center gap-2 py-2 px-3.5 fw-semibold rounded-3 shadow-sm" style="border-radius: 12px !important;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <!-- Shaded background box (Secondary layer) -->
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
            <!-- Outlines and arrow (Primary layer) -->
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
            <polyline points="15 3 21 3 21 9"></polyline>
            <line x1="10" y1="14" x2="21" y2="3"></line>
        </svg>
        Lihat Halaman
    </a>
</div>

<div class="row g-4">
    <!-- Builder Controls -->
    <div class="col-md-7 col-lg-8">
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-outline-secondary d-flex align-items-center gap-2 px-3.5 py-2.5 fw-semibold shadow-sm" style="border-radius: 12px !important;" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <span data-duo-icons="user" style="width: 16px; height: 16px;"></span> Edit Profil
            </button>
            <button class="btn btn-primary d-flex align-items-center gap-2 px-3.5 py-2.5 fw-semibold shadow-sm" style="border-radius: 12px !important;" data-bs-toggle="modal" data-bs-target="#addLinkBlockModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 17H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
                    <path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"></path>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
                Tambah Tautan
            </button>
            <button class="btn btn-primary d-flex align-items-center gap-2 px-3.5 py-2.5 fw-semibold shadow-sm" style="border-radius: 12px !important;" data-bs-toggle="modal" data-bs-target="#addTextBlockModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <!-- Shaded secondary line blocks (Secondary layer) -->
                    <line x1="21" y1="10" x2="3" y2="10" opacity="0.3"></line>
                    <line x1="21" y1="18" x2="3" y2="18" opacity="0.3"></line>
                    <!-- Solid primary line blocks (Primary layer) -->
                    <line x1="17" y1="6" x2="3" y2="6"></line>
                    <line x1="17" y1="14" x2="3" y2="14"></line>
                </svg>
                Tambah Teks
            </button>
        </div>

        <div class="glass-card p-4">
            <h6 class="fw-bold mb-4 d-flex align-items-center gap-2">
                <span data-duo-icons="folder-open" style="width: 18px; height: 18px;" class="text-muted"></span>
                Blok Konten
            </h6>
            
            @if($blocks->isEmpty())
                <div class="text-center py-5 text-secondary">
                    <div class="d-inline-flex p-3 rounded-circle mb-3" style="background-color: var(--primary-light) !important;">
                        <span data-duo-icons="info" style="width: 32px; height: 32px; color: #166534;"></span>
                    </div>
                    <p class="mb-1 fw-bold text-dark-custom">Belum ada blok konten</p>
                    <p class="text-muted small mb-0">Mulai tambahkan tautan atau teks di atas untuk mengisi halaman Biolink Anda.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-3" id="blocks-container">
                    @foreach($blocks as $block)
                        <div class="card border border-secondary border-opacity-10 rounded-3 shadow-sm" style="background: var(--card-bg-blur); border-radius: 12px !important;">
                            <div class="card-body d-flex align-items-center justify-content-between p-3">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        @if($block->type == 'link')
                                            <span class="badge rounded-pill px-2.5 py-1" style="background-color: var(--primary-light) !important; color: #166534 !important; border: 1px solid rgba(164, 229, 189, 0.3); font-weight: 600; font-size: 0.725rem;">Link</span>
                                            <span class="fw-bold text-dark-custom">{{ $block->settings['title'] ?? 'Tanpa Judul' }}</span>
                                        @elseif($block->type == 'text')
                                            <span class="badge rounded-pill px-2.5 py-1" style="background-color: rgba(107, 114, 128, 0.1) !important; color: #374151 !important; border: 1px solid rgba(107, 114, 128, 0.15); font-weight: 600; font-size: 0.725rem;">Text</span>
                                            <span class="fw-bold text-dark-custom">{{ Str::limit(strip_tags($block->settings['content'] ?? ''), 30) }}</span>
                                        @endif
                                    </div>
                                    @if($block->type == 'link')
                                        <a href="{{ $block->location_url }}" target="_blank" class="small text-muted text-decoration-none d-block ms-1" style="word-break: break-all; opacity: 0.85;">{{ $block->location_url }}</a>
                                    @endif
                                </div>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('biolinks.blocks.destroy', [$link->id, $block->id]) }}" method="POST" onsubmit="return confirm('Hapus blok ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger p-2 d-flex align-items-center justify-content-center" style="width: 32px; height: 32px; border-radius: 8px;">
                                            <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
                                                <polyline points="3 6 5 6 21 6"></polyline>
                                                <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                                                <line x1="10" y1="11" x2="10" y2="17"></line>
                                                <line x1="14" y1="11" x2="14" y2="17"></line>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <!-- Mobile Preview -->
    <div class="col-md-5 col-lg-4 d-flex justify-content-center">
        <div style="width: 320px; height: 640px; border: 12px solid #333; border-radius: 40px; overflow: hidden; background: #f8f9fa; position: relative; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15);">
            <!-- Mobile Notch -->
            <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 120px; height: 25px; background: #333; border-bottom-left-radius: 15px; border-bottom-right-radius: 15px; z-index: 10;"></div>
            
            <iframe src="{{ $fullUrl }}" style="width: 100%; height: 100%; border: none; padding-top: 30px;"></iframe>
        </div>
    </div>
</div>

<!-- Modal Edit Profile -->
<div class="modal fade" id="editProfileModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <form action="{{ route('biolinks.settings.update', $link->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom-0 pb-1">
                    <h5 class="modal-title fw-bold">Edit Profil Biolink</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-2">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Nama / Judul</label>
                        <input type="text" name="title" class="form-control" placeholder="Nama Anda" value="{{ $link->settings['title'] ?? '' }}">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Deskripsi Singkat</label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Tulis bio singkat...">{{ $link->settings['description'] ?? '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-1">
                    <button type="button" class="btn btn-light btn-sm rounded-3 px-3.5 py-2 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold" style="background-color: var(--primary-color); border-color: var(--primary-color);">Simpan Profil</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Add Link -->
<div class="modal fade" id="addLinkBlockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <form action="{{ route('biolinks.blocks.store', $link->id) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="link">
                <div class="modal-header border-bottom-0 pb-1">
                    <h5 class="modal-title fw-bold">Tambah Tautan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-2">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Judul Tautan <span class="text-danger">*</span></label>
                        <input type="text" name="settings[title]" class="form-control" required placeholder="Cek Promo Terbaru!">
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">URL Tujuan <span class="text-danger">*</span></label>
                        <input type="url" name="location_url" class="form-control" required placeholder="https://example.com/promo">
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-1">
                    <button type="button" class="btn btn-light btn-sm rounded-3 px-3.5 py-2 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold" style="background-color: var(--primary-color); border-color: var(--primary-color);">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Add Text -->
<div class="modal fade" id="addTextBlockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <form action="{{ route('biolinks.blocks.store', $link->id) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="text">
                <div class="modal-header border-bottom-0 pb-1">
                    <h5 class="modal-title fw-bold">Tambah Teks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-2">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Konten Teks <span class="text-danger">*</span></label>
                        <textarea name="settings[content]" class="form-control" rows="4" required placeholder="Tulis sesuatu yang menarik..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-1">
                    <button type="button" class="btn btn-light btn-sm rounded-3 px-3.5 py-2 fw-semibold" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold" style="background-color: var(--primary-color); border-color: var(--primary-color);">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
