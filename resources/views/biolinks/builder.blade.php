@extends('layouts.app')

@section('title', 'Biolink Builder')

@section('content')
@php
    $fullUrl = $link->domain_id && $link->domain ? $link->domain->scheme . $link->domain->host . '/' . $link->url : url('/') . '/' . $link->url;
@endphp

<!-- CSS for Cropper.js & Interactive Editor -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css">
<style>
    /* Desktop layout overrides tabs */
    @media (min-width: 768px) {
        #builderTabContent.tab-content > .tab-pane {
            display: block !important;
            opacity: 1 !important;
        }
        #builderMobileTabs {
            display: none !important;
        }
    }

    /* Theme-specific mobile tab colors */
    #builderMobileTabs .nav-link.active {
        background-color: var(--primary-color) !important;
        color: var(--active-text) !important;
    }
    #builderMobileTabs .nav-link {
        color: var(--text-secondary) !important;
    }

    /* Drag & Drop overlays */
    #coverDropzone:hover .dropzone-overlay,
    #avatarDropzone:hover .dropzone-overlay {
        opacity: 1 !important;
    }

    .dropzone-overlay {
        pointer-events: none;
    }

    /* Image Cropper sizing */
    .cropper-container-wrapper {
        max-height: 400px;
        overflow: hidden;
    }
    .cropper-container-wrapper img {
        max-width: 100%;
        display: block;
    }
</style>

<div class="d-flex align-items-center justify-content-between mb-4 mt-2">
    <div class="d-flex align-items-center gap-3">
        <a href="{{ route('biolinks.index') }}" class="btn btn-light d-flex align-items-center justify-content-center p-2 rounded-circle shadow-sm" style="width: 38px; height: 38px; border-radius: 50% !important; border: 1px solid rgba(0,0,0,0.05);" title="Kembali ke Daftar Biolink">
            <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" class="text-secondary">
                <line x1="19" y1="12" x2="5" y2="12"></line>
                <polyline points="12 19 5 12 12 5"></polyline>
            </svg>
        </a>
        <h4 class="fw-bold mb-0 d-flex align-items-center text-dark-custom" style="font-size: 1.5rem; letter-spacing: -0.5px;">
            Biolink Builder: {{ $link->url }}
        </h4>
    </div>
    <a href="{{ $fullUrl }}" target="_blank" class="btn btn-outline-secondary d-flex align-items-center gap-2 py-2 px-3.5 fw-semibold rounded-3 shadow-sm" style="border-radius: 12px !important;">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
            <polyline points="15 3 21 3 21 9"></polyline>
            <line x1="10" y1="14" x2="21" y2="3"></line>
        </svg>
        Lihat Halaman
    </a>
</div>

<!-- Mobile View Toggle Tabs (Only visible on mobile < 768px) -->
<ul class="nav nav-pills nav-fill d-flex d-md-none mb-4 p-1 bg-light rounded-3 shadow-sm" id="builderMobileTabs" role="tablist" style="border: 1px solid rgba(0,0,0,0.04);">
    <li class="nav-item" role="presentation">
        <button class="nav-link active py-2 fw-semibold d-flex align-items-center justify-content-center gap-1.5" id="design-tab" data-bs-toggle="tab" data-bs-target="#design-pane" type="button" role="tab" aria-controls="design-pane" aria-selected="true" style="border-radius: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="9"></rect><rect x="14" y="3" width="7" height="5"></rect><rect x="14" y="12" width="7" height="9"></rect><rect x="3" y="16" width="7" height="5"></rect></svg>
            Design
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link py-2 fw-semibold d-flex align-items-center justify-content-center gap-1.5" id="preview-tab" data-bs-toggle="tab" data-bs-target="#preview-pane" type="button" role="tab" aria-controls="preview-pane" aria-selected="false" style="border-radius: 8px;">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="5" y="2" width="14" height="20" rx="2" ry="2"></rect><line x1="12" y1="18" x2="12.01" y2="18"></line></svg>
            Pratinjau
        </button>
    </li>
</ul>

<div class="tab-content row g-4" id="builderTabContent">
    <!-- Builder Controls Column (Design Pane) -->
    <div class="tab-pane fade show active col-md-7 col-lg-8" id="design-pane" role="tabpanel" aria-labelledby="design-tab" tabindex="0">
        
        <!-- Interactive Cover & Avatar Editor Zone -->
        <div class="glass-card mb-4 overflow-hidden position-relative" style="border-radius: 16px; border: 1px solid var(--card-border);">
            <!-- Visual Cover Zone (Drag & Drop or Click) -->
            <div id="coverDropzone" class="position-relative" style="height: 160px; background: {{ isset($link->settings['cover_url']) ? 'url(' . $link->settings['cover_url'] . ') center/cover no-repeat' : 'linear-gradient(135deg, #a4e5bd 0%, #7dd3a1 100%)' }}; cursor: pointer;">
                <div class="dropzone-overlay d-flex flex-column align-items-center justify-content-center text-white" style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.45); opacity: 0; transition: opacity 0.2s ease;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mb-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                    <span class="small fw-semibold">Ubah Cover (Seret & Jatuhkan)</span>
                </div>
            </div>
            <input type="file" id="coverInput" class="d-none" accept="image/*">
            
            <!-- Visual Avatar Zone (Drag & Drop or Click) -->
            <div class="d-flex flex-column align-items-center" style="margin-top: -60px; padding-bottom: 24px;">
                <div id="avatarDropzone" class="position-relative rounded-circle" style="width: 110px; height: 110px; border: 4px solid var(--card-bg-blur); box-shadow: 0 4px 12px rgba(0,0,0,0.15); cursor: pointer; overflow: hidden; background: #fff;">
                    <img id="avatarPreview" src="{{ $link->settings['avatar_url'] ?? 'https://ui-avatars.com/api/?name=' . urlencode($link->settings['title'] ?? 'BL') . '&background=a4e5bd&color=111827&size=128' }}" style="width:100%; height:100%; object-fit:cover;">
                    <div class="dropzone-overlay d-flex flex-column align-items-center justify-content-center text-white" style="position: absolute; top:0; left:0; width:100%; height:100%; background: rgba(0,0,0,0.55); opacity: 0; transition: opacity 0.2s ease; border-radius: 50%;">
                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" class="mb-1"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4M17 8l-5-5-5 5M12 3v12"/></svg>
                        <span style="font-size: 0.65rem;" class="fw-semibold">Ubah Foto</span>
                    </div>
                </div>
                <input type="file" id="avatarInput" class="d-none" accept="image/*">

                <div class="d-flex align-items-center gap-1.5 mt-3 mb-1">
                    <h5 class="fw-bold mb-0 text-dark-custom">{{ $link->settings['title'] ?? 'My Biolink' }}</h5>
                    @if($link->is_verified)
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#0095f6" style="color: white; flex-shrink: 0;" title="Verified Profile">
                            <path d="M9 16.2L4.8 12l-1.4 1.4L9 19 21 7l-1.4-1.4L9 16.2z"/>
                        </svg>
                    @endif
                </div>
                <p class="text-secondary small mb-0 px-4 text-center text-truncate" style="max-width: 100%;">{{ $link->settings['description'] ?? 'Belum ada deskripsi bio.' }}</p>
            </div>
        </div>

        <!-- Builder buttons -->
        <div class="d-flex flex-column flex-sm-row gap-2 mb-4">
            <button class="btn btn-outline-secondary d-flex align-items-center justify-content-center gap-2 px-3.5 py-2.5 fw-semibold shadow-sm w-100 w-sm-auto" style="border-radius: 12px !important;" data-bs-toggle="modal" data-bs-target="#editProfileModal">
                <span data-duo-icons="user" style="width: 16px; height: 16px;"></span> Edit Bio
            </button>
            <button class="btn btn-primary d-flex align-items-center justify-content-center gap-2 px-3.5 py-2.5 fw-semibold shadow-sm w-100 w-sm-auto" style="border-radius: 12px !important;" data-bs-toggle="modal" data-bs-target="#addLinkBlockModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 17H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
                    <path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"></path>
                    <line x1="8" y1="12" x2="16" y2="12"></line>
                </svg>
                Tambah Tautan
            </button>
            <button class="btn btn-primary d-flex align-items-center justify-content-center gap-2 px-3.5 py-2.5 fw-semibold shadow-sm w-100 w-sm-auto" style="border-radius: 12px !important;" data-bs-toggle="modal" data-bs-target="#addTextBlockModal">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                    <line x1="21" y1="10" x2="3" y2="10" opacity="0.3"></line>
                    <line x1="21" y1="18" x2="3" y2="18" opacity="0.3"></line>
                    <line x1="17" y1="6" x2="3" y2="6"></line>
                    <line x1="17" y1="14" x2="3" y2="14"></line>
                </svg>
                Tambah Teks
            </button>
        </div>

        <!-- Content list -->
        <div id="blocks-container-wrapper" class="glass-card p-4">
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
                        <div class="card border border-secondary border-opacity-10 rounded-3 shadow-sm" data-id="{{ $block->id }}" style="background: var(--card-bg-blur); border-radius: 12px !important;">
                            <div class="card-body d-flex align-items-center justify-content-between p-3">
                                <div class="d-flex align-items-center gap-3">
                                    <!-- Drag Handle -->
                                    <div class="drag-handle text-muted" style="cursor: grab; display: flex; align-items: center; padding: 4px;">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                            <circle cx="9" cy="5" r="1.5"></circle>
                                            <circle cx="9" cy="12" r="1.5"></circle>
                                            <circle cx="9" cy="19" r="1.5"></circle>
                                            <circle cx="15" cy="5" r="1.5"></circle>
                                            <circle cx="15" cy="12" r="1.5"></circle>
                                            <circle cx="15" cy="19" r="1.5"></circle>
                                        </svg>
                                    </div>
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

    <!-- Mobile Preview Column -->
    <div class="tab-pane fade d-md-block col-md-5 col-lg-4 d-flex justify-content-center" id="preview-pane" role="tabpanel" aria-labelledby="preview-tab" tabindex="0">
        <div style="width: 320px; height: 640px; border: 12px solid #333; border-radius: 40px; overflow: hidden; background: #f8f9fa; position: relative; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.15); flex-shrink:0;">
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
            <form action="{{ route('biolinks.settings.update', $link->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="modal-header border-bottom-0 pb-1">
                    <h5 class="modal-title fw-bold">Edit Profil Biolink</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-2">
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Nama / Judul</label>
                        <div class="input-group glass-input-group">
                            <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px; border: none; background: transparent;">
                                <span data-duo-icons="user" style="width: 18px; height: 18px;"></span>
                            </span>
                            <input type="text" name="title" class="form-control border-0 ps-1 pe-0 bg-transparent" placeholder="Nama Anda" value="{{ $link->settings['title'] ?? '' }}">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">Deskripsi Singkat</label>
                        <div class="input-group glass-input-group align-items-start">
                            <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; border: none; background: transparent;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary);">
                                    <line x1="21" y1="10" x2="3" y2="10" opacity="0.3"></line>
                                    <line x1="21" y1="18" x2="3" y2="18" opacity="0.3"></line>
                                    <line x1="17" y1="6" x2="3" y2="6"></line>
                                    <line x1="17" y1="14" x2="3" y2="14"></line>
                                </svg>
                            </span>
                            <textarea name="description" class="form-control border-0 ps-1 pe-0 pt-2.5 bg-transparent" rows="3" placeholder="Tulis bio singkat...">{{ $link->settings['description'] ?? '' }}</textarea>
                        </div>
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
                        <div class="input-group glass-input-group">
                            <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px; border: none; background: transparent;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary);">
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
                                    <path d="M20.59 13.41l-7.17 7.17a2 2 0 0 1-2.83 0L2 12V2h10l8.59 8.59a2 2 0 0 1 0 2.82z"></path>
                                    <line x1="7" y1="7" x2="7.01" y2="7" stroke-width="3"></line>
                                </svg>
                            </span>
                            <input type="text" name="settings[title]" class="form-control border-0 ps-1 pe-0 bg-transparent" required placeholder="Cek Promo Terbaru!">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-semibold text-secondary">URL Tujuan <span class="text-danger">*</span></label>
                        <div class="input-group glass-input-group">
                            <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px; border: none; background: transparent;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary);">
                                    <path d="M9 17H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
                                    <path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"></path>
                                    <line x1="8" y1="12" x2="16" y2="12"></line>
                                </svg>
                            </span>
                            <input type="url" name="location_url" class="form-control border-0 ps-1 pe-0 bg-transparent" required placeholder="https://example.com/promo">
                        </div>
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
                        <div class="input-group glass-input-group align-items-start">
                            <span class="input-group-text d-flex align-items-center justify-content-center" style="width: 46px; height: 46px; border: none; background: transparent;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" style="color: var(--text-secondary);">
                                    <line x1="21" y1="10" x2="3" y2="10" opacity="0.3"></line>
                                    <line x1="21" y1="18" x2="3" y2="18" opacity="0.3"></line>
                                    <line x1="17" y1="6" x2="3" y2="6"></line>
                                    <line x1="17" y1="14" x2="3" y2="14"></line>
                                </svg>
                            </span>
                            <textarea name="settings[content]" class="form-control border-0 ps-1 pe-0 pt-2.5 bg-transparent" rows="4" required placeholder="Tulis sesuatu yang menarik..."></textarea>
                        </div>
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

<!-- Modal Image Cropper -->
<div class="modal fade" id="cropperModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg" style="border-radius: 16px;">
            <div class="modal-header border-bottom-0 pb-1">
                <h5 class="modal-title fw-bold" id="cropperModalTitle">Sesuaikan Gambar</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-2">
                <div class="cropper-container-wrapper rounded-3 border">
                    <img id="cropperImage" src="">
                </div>
            </div>
            <div class="modal-footer border-top-0 pt-1">
                <button type="button" class="btn btn-light btn-sm rounded-3 px-3.5 py-2 fw-semibold" data-bs-dismiss="modal">Batal</button>
                <button type="button" id="cropAndSaveBtn" class="btn btn-primary btn-sm rounded-3 px-3.5 py-2 fw-semibold" style="background-color: var(--primary-color); border-color: var(--primary-color);">Potong & Simpan</button>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Sortable/1.15.2/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Show success toast
    function showSuccessToast(message) {
        const toast = $('<div class="position-fixed bottom-0 end-0 p-3" style="z-index: 9999;">' +
            '<div class="toast show align-items-center text-white border-0" role="alert" style="background-color: #10b981; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.15);">' +
            '<div class="d-flex py-2 px-3 align-items-center gap-2">' +
            '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>' +
            '<span class="fw-semibold small">' + message + '</span>' +
            '</div>' +
            '</div>' +
            '</div>');
        $('body').append(toast);
        setTimeout(() => {
            toast.fadeOut(400, function() { $(this).remove(); });
        }, 2500);
    }

    // Initialize SortableJS
    function initializeSortable() {
        const el = document.getElementById('blocks-container');
        if (el) {
            new Sortable(el, {
                animation: 150,
                ghostClass: 'bg-light',
                handle: '.drag-handle',
                onEnd: function() {
                    const orders = {};
                    $('#blocks-container .card').each(function(index) {
                        const blockId = $(this).attr('data-id');
                        orders[blockId] = index;
                    });

                    $.ajax({
                        url: '{{ route('biolinks.blocks.reorder', $link->id) }}',
                        method: 'POST',
                        data: {
                            _token: '{{ csrf_token() }}',
                            orders: orders
                        },
                        dataType: 'json',
                        success: function(response) {
                            const iframe = document.querySelector('iframe');
                            if (iframe) {
                                iframe.contentWindow.location.reload();
                            }
                        },
                        error: function() {
                            alert('Gagal memperbarui urutan blok.');
                        }
                    });
                }
            });
        }
    }

    // Initial SortableJS load
    initializeSortable();

    // Refresh layout, reload iframe
    function refreshBuilderUI(successMessage) {
        const iframe = document.querySelector('iframe');
        if (iframe) {
            iframe.contentWindow.location.reload();
        }
        
        // Reload blocks list dynamically
        $('#blocks-container-wrapper').load(window.location.href + ' #blocks-container-wrapper > *', function() {
            if (typeof createIcons === 'function') {
                createIcons({
                    icons: window.DuoIcons || {}
                });
            }
            initializeSortable();
        });

        // Also reload top bio editor content visually
        location.reload(); // Reloading the page visually matches new cover and avatar instantly

        if (successMessage) {
            showSuccessToast(successMessage);
        }
    }

    // Modal submit interceptor
    $('#editProfileModal form, #addLinkBlockModal form, #addTextBlockModal form').on('submit', function(e) {
        e.preventDefault();
        const form = $(this);
        const modalEl = form.closest('.modal')[0];
        const modal = bootstrap.Modal.getInstance(modalEl);
        const submitBtn = form.find('button[type="submit"]');
        const originalText = submitBtn.text();

        submitBtn.prop('disabled', true).text('Loading...');
        const formData = new FormData(form[0]);

        $.ajax({
            url: form.attr('action'),
            method: form.attr('method') || 'POST',
            data: formData,
            processData: false,
            contentType: false,
            dataType: 'json',
            success: function(response) {
                modal.hide();
                if (modalEl.id !== 'editProfileModal') {
                    form[0].reset();
                } else {
                    form.find('input[type="file"]').val('');
                }
                
                refreshBuilderUI(response.message || 'Berhasil disimpan!');
            },
            error: function() {
                alert('Terjadi kesalahan, silakan coba lagi.');
            },
            complete: function() {
                submitBtn.prop('disabled', false).text(originalText);
            }
        });
    });

    // Block delete handler
    $(document).on('submit', '#blocks-container-wrapper form', function(e) {
        e.preventDefault();
        const form = $(this);
        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            dataType: 'json',
            success: function(response) {
                refreshBuilderUI(response.message || 'Blok berhasil dihapus!');
            },
            error: function() {
                alert('Gagal menghapus blok.');
            }
        });
    });

    // ──────────────────────────────────────────────────────────────────────────
    // CROPPING & DRAG-AND-DROP FILE UPLOAD LOGIC
    // ──────────────────────────────────────────────────────────────────────────
    let cropper = null;
    let targetType = 'avatar'; // 'avatar' or 'cover'
    const cropperModal = new bootstrap.Modal(document.getElementById('cropperModal'));
    const cropperImage = document.getElementById('cropperImage');
    const cropAndSaveBtn = document.getElementById('cropAndSaveBtn');

    // Trigger file dialog on click
    $('#avatarDropzone').on('click', function() { $('#avatarInput').click(); });
    $('#coverDropzone').on('click', function() { $('#coverInput').click(); });

    // Handle standard file selection
    $('#avatarInput').on('change', function(e) { handleFileSelect(e.target.files[0], 'avatar'); });
    $('#coverInput').on('change', function(e) { handleFileSelect(e.target.files[0], 'cover'); });

    // Drag and drop event listeners
    setupDragAndDrop(document.getElementById('avatarDropzone'), 'avatar');
    setupDragAndDrop(document.getElementById('coverDropzone'), 'cover');

    function setupDragAndDrop(element, type) {
        element.addEventListener('dragover', (e) => {
            e.preventDefault();
            element.classList.add('border-primary');
        });
        element.addEventListener('dragleave', () => {
            element.classList.remove('border-primary');
        });
        element.addEventListener('drop', (e) => {
            e.preventDefault();
            element.classList.remove('border-primary');
            if (e.dataTransfer.files.length > 0) {
                handleFileSelect(e.dataTransfer.files[0], type);
            }
        });
    }

    function handleFileSelect(file, type) {
        if (!file || !file.type.match(/^image\//)) {
            alert('Silakan pilih file gambar yang valid.');
            return;
        }

        targetType = type;
        const reader = new FileReader();
        reader.onload = function(e) {
            // Set image source and open modal
            cropperImage.src = e.target.result;
            
            // Set modal title depending on target
            document.getElementById('cropperModalTitle').textContent = type === 'avatar' ? 'Sesuaikan Foto Profil' : 'Sesuaikan Sampul Belakang';

            cropperModal.show();
        };
        reader.readAsDataURL(file);
    }

    // Initialize Cropper when modal finishes showing
    document.getElementById('cropperModal').addEventListener('shown.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
        }

        cropper = new Cropper(cropperImage, {
            aspectRatio: targetType === 'avatar' ? 1 : (16 / 6),
            viewMode: 1,
            dragMode: 'move',
            autoCropArea: 1,
            restore: false,
            guides: true,
            center: true,
            highlight: false,
            cropBoxMovable: true,
            cropBoxResizable: true,
            toggleDragModeOnDblclick: false
        });
    });

    // Cleanup Cropper when modal is hidden
    document.getElementById('cropperModal').addEventListener('hidden.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
            cropper = null;
        }
        // Clear file input inputs so same image can be re-selected
        $('#avatarInput').val('');
        $('#coverInput').val('');
    });

    // Save Cropped image via AJAX
    cropAndSaveBtn.addEventListener('click', function() {
        if (!cropper) return;

        cropAndSaveBtn.disabled = true;
        cropAndSaveBtn.textContent = 'Menyimpan...';

        // Get canvas
        const canvas = cropper.getCroppedCanvas({
            width: targetType === 'avatar' ? 300 : 960,
            height: targetType === 'avatar' ? 300 : 360,
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        });

        // Convert canvas to Blob
        canvas.toBlob(function(blob) {
            const formData = new FormData();
            formData.append(targetType, blob, targetType + '_cropped.png');
            
            // Append profile name and description from form, or empty strings to satisfy backend merges
            formData.append('title', $('input[name="title"]').val() || '{{ $link->settings['title'] ?? '' }}');
            formData.append('description', $('textarea[name="description"]').val() || '{{ $link->settings['description'] ?? '' }}');
            formData.append('_token', '{{ csrf_token() }}');
            formData.append('_method', 'PUT');

            $.ajax({
                url: '{{ route('biolinks.settings.update', $link->id) }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    cropperModal.hide();
                    showSuccessToast(response.message || 'Gambar berhasil diperbarui!');
                    
                    // Delay reload slightly to let user read toast and see changes
                    setTimeout(() => {
                        location.reload();
                    }, 1200);
                },
                error: function() {
                    alert('Gagal mengunggah foto. Silakan coba lagi.');
                    cropAndSaveBtn.disabled = false;
                    cropAndSaveBtn.textContent = 'Potong & Simpan';
                }
            });
        }, 'image/png');
    });
});
</script>
@endsection
