@php
    $activeFilters = [];
    if (request('search')) {
        $activeFilters[] = [
            'type' => 'search',
            'label' => 'Kata Kunci: "' . request('search') . '"'
        ];
    }
    if (request('status')) {
        $statusLabel = request('status') === 'active' ? 'Aktif' : 'Nonaktif';
        $activeFilters[] = [
            'type' => 'status',
            'label' => 'Status: ' . $statusLabel
        ];
    }
    if (request('project_id') && isset($projects)) {
        $proj = $projects->firstWhere('id', request('project_id'));
        if ($proj) {
            $activeFilters[] = [
                'type' => 'project',
                'label' => 'Proyek: ' . $proj->name
            ];
        }
    }
    if (request('domain_id') !== null && request('domain_id') !== '' && isset($domains)) {
        if (request('domain_id') === '0') {
            $activeFilters[] = [
                'type' => 'domain',
                'label' => 'Domain: Domain Bawaan'
            ];
        } else {
            $dom = $domains->firstWhere('id', request('domain_id'));
            if ($dom) {
                $activeFilters[] = [
                    'type' => 'domain',
                    'label' => 'Domain: ' . $dom->host
                ];
            }
        }
    }
@endphp

@if(!empty($activeFilters))
    <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
        <span class="text-secondary small fw-semibold">Filter Aktif:</span>
        @foreach($activeFilters as $filter)
            <span class="badge bg-light text-secondary border d-inline-flex align-items-center gap-2 py-1.5 px-2.5 rounded-3 fw-medium" style="font-size: 0.75rem;">
                {{ $filter['label'] }}
                <button type="button" class="btn-close btn-remove-filter p-0 m-0 bg-none border-0 text-muted hover-text-dark d-inline-flex align-items-center" data-filter-type="{{ $filter['type'] }}" aria-label="Clear filter" style="font-size: 0.65rem; width: 0.65rem; height: 0.65rem; line-height: 1;">
                    <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </span>
        @endforeach
        
        <button type="button" id="btnClearAllFilters" class="btn btn-link text-decoration-none p-0 small fw-semibold text-danger" style="font-size: 0.75rem;">
            Bersihkan Semua
        </button>
    </div>
@endif

<!-- Table -->
@if($links->isEmpty())
    <div class="text-center py-5">
        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="text-muted mb-3">
            <circle cx="12" cy="12" r="10"></circle>
            <line x1="12" y1="16" x2="12" y2="12"></line>
            <line x1="12" y1="8" x2="12.01" y2="8"></line>
        </svg>
        <p class="text-secondary mb-0">Tidak ada link ditemukan. Silakan buat baru atau sesuaikan kata kunci filter Anda!</p>
    </div>
@else
    <div class="table-responsive">
        <table class="table align-middle mb-0" style="border-collapse: separate; border-spacing: 0 10px;">
            @php
                $currentSort = request('sort', 'latest');
                $linkSort = $currentSort == 'title_asc' ? 'title_desc' : 'title_asc';
                $klikSort = $currentSort == 'clicks_desc' ? 'clicks_asc' : 'clicks_desc';
                $dibuatSort = ($currentSort == 'latest' || empty(request('sort'))) ? 'oldest' : 'latest';
            @endphp
            <thead>
                <tr style="border-bottom: 2px solid var(--glass-border);">
                    <th class="ps-3 py-3" style="width: 40px; border: none;">
                        <input type="checkbox" id="selectAllLinks" class="form-check-input">
                    </th>
                    <th class="text-secondary small fw-bold py-3" colspan="2" style="border: none; padding-left: 20px;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $linkSort]) }}" class="text-secondary text-decoration-none d-inline-flex align-items-center gap-1">
                            Link
                            @if($currentSort == 'title_asc')
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
                            @elseif($currentSort == 'title_desc')
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>
                            @endif
                        </a>
                    </th>
                    <th class="text-secondary small fw-bold py-3" style="border: none; min-width: 180px;">Original URL</th>
                    <th class="text-secondary small fw-bold py-3" style="border: none; width: 120px;">Project</th>
                    <th class="text-secondary small fw-bold py-3" style="border: none; width: 100px;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $klikSort]) }}" class="text-secondary text-decoration-none d-inline-flex align-items-center gap-1">
                            Klik
                            @if($currentSort == 'clicks_asc')
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
                            @elseif($currentSort == 'clicks_desc')
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>
                            @endif
                        </a>
                    </th>
                    <th class="text-secondary small fw-bold py-3" style="border: none; width: 120px;">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $dibuatSort]) }}" class="text-secondary text-decoration-none d-inline-flex align-items-center gap-1">
                            Dibuat
                            @if($currentSort == 'oldest')
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="19" x2="12" y2="5"></line><polyline points="5 12 12 5 19 12"></polyline></svg>
                            @elseif($currentSort == 'latest' || empty(request('sort')))
                                <svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"></line><polyline points="19 12 12 19 5 12"></polyline></svg>
                            @endif
                        </a>
                    </th>
                    <th class="text-secondary small fw-bold text-end pe-3 py-3" style="border: none; width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($links as $link)
                    @php
                        $domainHost = null;
                        if ($link->domain_id > 0 && isset($domains)) {
                            $domObj = $domains->firstWhere('id', $link->domain_id);
                            if ($domObj) {
                                $domainHost = $domObj->host;
                            }
                        }
                        
                        if ($domainHost) {
                            $fullShortenedUrl = $domObj->scheme . $domainHost . '/' . $link->url;
                        } else {
                            $fullShortenedUrl = url($link->url);
                        }
                    @endphp
                    <tr style="background: rgba(255, 255, 255, 0.01); transition: all 0.2s ease;">
                        <!-- Bulk Checkbox -->
                        <td class="ps-3" style="width: 40px; border: none;">
                            <input type="checkbox" class="form-check-input link-checkbox" value="{{ $link->id }}">
                        </td>
                        <!-- Icon/Avatar -->
                        <td class="ps-3 pe-2 py-2.5" style="width: 56px; border: none;">
                            @if($link->type == 'biolink')
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(164, 229, 189, 0.2); color: #166534;">
                                    <span data-duo-icons="app" style="width: 16px; height: 16px;"></span>
                                </div>
                            @else
                                <div class="rounded-circle d-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background: rgba(164, 229, 189, 0.2); color: #166534;">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M9 17H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3" opacity="0.25" fill="currentColor" style="stroke: none;"></path>
                                        <path d="M15 7h3a5 5 0 0 1 5 5 5 5 0 0 1-5 5h-3m-6 0H6a5 5 0 0 1-5-5 5 5 0 0 1 5-5h3"></path>
                                        <line x1="8" y1="12" x2="16" y2="12"></line>
                                    </svg>
                                </div>
                            @endif
                        </td>

                        <!-- Link Title & URL -->
                        <td class="px-2 py-2.5" style="border: none;">
                            <a href="{{ route('links.show', $link->id) }}" class="fw-semibold text-decoration-none text-primary d-block mb-0.5" style="font-size: 0.925rem; letter-spacing: -0.2px;">
                                {{ $link->url }}
                            </a>
                            <a href="{{ $fullShortenedUrl }}" target="_blank" class="text-muted text-decoration-none small text-truncate d-block" style="max-width: 280px; font-size: 0.75rem;">
                                {{ $fullShortenedUrl }}
                            </a>
                        </td>

                        <!-- Original URL -->
                        <td class="px-2 py-2.5" style="border: none; max-width: 220px;">
                            <span class="text-muted small d-block text-truncate" style="max-width: 200px; font-size: 0.8rem;" title="{{ $link->location_url }}">
                                {{ $link->location_url }}
                            </span>
                        </td>

                        <!-- Project -->
                        <td class="px-2 py-2.5" style="border: none;">
                            @php
                                $projectName = null;
                                if ($link->project_id && isset($projects)) {
                                    $proj = $projects->firstWhere('id', $link->project_id);
                                    if ($proj) $projectName = $proj->name;
                                }
                            @endphp
                            @if($projectName)
                                <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-10 rounded-pill px-2.5 py-1" style="font-size: 0.7rem; font-weight: 500;">
                                    {{ $projectName }}
                                </span>
                            @else
                                <span class="text-muted small" style="font-size: 0.75rem;">—</span>
                            @endif
                        </td>

                        <!-- Clicks counter -->
                        <td class="px-2 py-2.5" style="border: none; width: 90px;">
                             <div class="d-flex align-items-center text-secondary small gap-2" style="font-size: 0.8rem;">
                                 <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round">
                                    <line x1="18" y1="20" x2="18" y2="10"></line>
                                    <line x1="12" y1="20" x2="12" y2="4"></line>
                                    <line x1="6" y1="20" x2="6" y2="14"></line>
                                </svg>
                                <span class="fw-bold">{{ number_format($link->clicks) }}</span>
                            </div>
                        </td>

                        <!-- Created Date -->
                        <td class="px-2 py-2.5 d-none d-md-table-cell" style="border: none; width: 100px;">
                            <span class="text-muted small" style="font-size: 0.775rem;">
                                {{ date('j M', strtotime($link->created_at)) }}
                            </span>
                        </td>

                        <!-- Suffix controls -->
                        <td class="pe-3 ps-2 py-2.5 text-end" style="border: none; width: 150px;">
                            <div class="d-inline-flex align-items-center gap-2.5">
                                <!-- Copy -->
                                <button class="btn p-1.5 text-muted border-0 bg-transparent rounded-circle hover-bg-light btn-copy-link" data-url="{{ $fullShortenedUrl }}" title="Salin Link">
                                    <span data-duo-icons="copy" style="width: 16px; height: 16px;"></span>
                                </button>

                                 <!-- Toggle -->
                                 <div class="form-check form-switch p-0 m-0 d-flex align-items-center">
                                     <input class="form-check-input m-0 link-status-toggle" type="checkbox" role="switch" data-id="{{ $link->id }}" {{ $link->is_enabled ? 'checked' : '' }} style="width: 28px; height: 16px; cursor: pointer;">
                                 </div>

                                 <!-- Dropdown menu -->
                                 <div class="dropdown">
                                     <button class="btn p-1 text-muted border-0 bg-transparent rounded-circle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                         <span data-duo-icons="app_dots" style="width: 16px; height: 16px;"></span>
                                     </button>
                                     <ul class="dropdown-menu dropdown-menu-end glass-card border-0 shadow-lg p-1.5">
                                         <li>
                                             @if($link->type == 'biolink')
                                                 <a class="dropdown-item rounded-2 py-1.5 small" href="{{ route('biolinks.builder', $link->id) }}">
                                                     Edit Biolink
                                                 </a>
                                             @else
                                                 <a class="dropdown-item rounded-2 py-1.5 small btn-edit-link" href="#"
                                                    data-id="{{ $link->id }}"
                                                    data-url="{{ $link->url }}"
                                                    data-location="{{ $link->location_url }}"
                                                    data-project="{{ $link->project_id }}"
                                                    data-domain="{{ $link->domain_id }}"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#editLinkModal">
                                                     Edit Link
                                                 </a>
                                             @endif
                                         </li>
                                         <li>
                                             <a class="dropdown-item rounded-2 py-1.5 small text-danger btn-delete-link" href="#" data-id="{{ $link->id }}">
                                                 Delete Link
                                             </a>
                                         </li>
                                    </ul>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-between align-items-center mt-4">
        <span class="text-muted small">
            Showing {{ $links->firstItem() }}-{{ $links->lastItem() }} of {{ $links->total() }} results
        </span>
        <div>
            {{ $links->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
