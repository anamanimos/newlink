@extends('layouts.app')

@section('title', 'Biolink Builder')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4 mt-2">
    <h4 class="fw-bold mb-0 d-flex align-items-center text-dark-custom" style="font-size: 1.5rem; letter-spacing: -0.5px;">
        <span data-duo-icons="app" style="width: 22px; height: 22px; margin-right: 12px;" class="text-muted"></span>
        Biolink Builder: {{ $link->url }}
    </h4>
    <a href="{{ url($link->url) }}" target="_blank" class="btn btn-outline-secondary d-flex align-items-center gap-1.5 py-2 px-3.5 fw-semibold rounded-3 shadow-sm">
        <span data-duo-icons="external-link" style="width: 16px; height: 16px;"></span>
        Lihat Halaman
    </a>
</div>

<div class="row g-4">
    <!-- Builder Controls -->
    <div class="col-md-7 col-lg-8">
        <div class="d-flex gap-2 mb-4">
            <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addLinkBlockModal">
                <span data-duo-icons="link"></span> Tambah Tautan
            </button>
            <button class="btn btn-primary d-flex align-items-center gap-2" data-bs-toggle="modal" data-bs-target="#addTextBlockModal">
                <span data-duo-icons="text-align-left"></span> Tambah Teks
            </button>
        </div>

        <div class="glass-card p-4 border-0 rounded-4">
            <h6 class="fw-bold mb-3">Blok Konten</h6>
            @if($blocks->isEmpty())
                <div class="text-center py-5 text-muted">
                    <p>Belum ada blok konten.</p>
                </div>
            @else
                <div class="d-flex flex-column gap-3" id="blocks-container">
                    @foreach($blocks as $block)
                        <div class="card border border-secondary border-opacity-10 rounded-3 shadow-sm">
                            <div class="card-body d-flex align-items-center justify-content-between p-3">
                                <div>
                                    <h6 class="fw-bold mb-1">
                                        @if($block->type == 'link')
                                            <span class="badge bg-primary rounded-pill me-2">Link</span>
                                            {{ $block->settings['title'] ?? 'Tanpa Judul' }}
                                        @elseif($block->type == 'text')
                                            <span class="badge bg-secondary rounded-pill me-2">Text</span>
                                            {{ Str::limit(strip_tags($block->settings['content'] ?? ''), 30) }}
                                        @endif
                                    </h6>
                                    @if($block->type == 'link')
                                        <a href="{{ $block->location_url }}" target="_blank" class="small text-muted text-decoration-none">{{ $block->location_url }}</a>
                                    @endif
                                </div>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('biolinks.blocks.destroy', [$link->id, $block->id]) }}" method="POST" onsubmit="return confirm('Hapus blok ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger"><span data-duo-icons="trash"></span></button>
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
        <div style="width: 320px; height: 640px; border: 12px solid #333; border-radius: 40px; overflow: hidden; background: #f8f9fa; position: relative; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25);">
            <!-- Mobile Notch -->
            <div style="position: absolute; top: 0; left: 50%; transform: translateX(-50%); width: 120px; height: 25px; background: #333; border-bottom-left-radius: 15px; border-bottom-right-radius: 15px; z-index: 10;"></div>
            
            <iframe src="{{ url($link->url) }}" style="width: 100%; height: 100%; border: none; padding-top: 30px;"></iframe>
        </div>
    </div>
</div>

<!-- Modal Add Link -->
<div class="modal fade glass-modal" id="addLinkBlockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('biolinks.blocks.store', $link->id) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="link">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Tambah Tautan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Judul Tautan</label>
                        <input type="text" name="settings[title]" class="form-control" required placeholder="Cek Promo Terbaru!">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">URL Tujuan</label>
                        <input type="url" name="location_url" class="form-control" required placeholder="https://example.com/promo">
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Add Text -->
<div class="modal fade glass-modal" id="addTextBlockModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <form action="{{ route('biolinks.blocks.store', $link->id) }}" method="POST">
                @csrf
                <input type="hidden" name="type" value="text">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title fw-bold">Tambah Teks</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Konten Teks</label>
                        <textarea name="settings[content]" class="form-control" rows="4" required placeholder="Tulis sesuatu yang menarik..."></textarea>
                    </div>
                </div>
                <div class="modal-footer border-top-0">
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
