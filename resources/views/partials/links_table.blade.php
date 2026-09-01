@php
    $activeFilters = [];
    if (request('search')) {
        $activeFilters[] = [
            'type' => 'search',
            'label' => 'Keyword: "' . request('search') . '"'
        ];
    }
    if (request('status')) {
        $statusLabel = request('status') === 'active' ? 'Active' : 'Inactive';
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
                'label' => 'Project: ' . $proj->name
            ];
        }
    }
    if (request('domain_id') !== null && request('domain_id') !== '' && isset($domains)) {
        if (request('domain_id') === '0') {
            $activeFilters[] = [
                'type' => 'domain',
                'label' => 'Domain: Default Domain'
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
    <div class="d-flex flex-wrap align-items-center gap-2 mb-4">
        <span class="text-gray-600 fs-7 fw-semibold">Active Filters:</span>
        @foreach($activeFilters as $filter)
            <span class="badge badge-light-primary d-inline-flex align-items-center gap-2 py-2 px-3 fs-7 fw-medium">
                {{ $filter['label'] }}
                <button type="button" class="btn-close btn-remove-filter p-0 m-0 bg-none border-0 text-muted d-inline-flex align-items-center" data-filter-type="{{ $filter['type'] }}" aria-label="Clear filter" style="font-size: 0.65rem; width: 0.65rem; height: 0.65rem; line-height: 1;">
                    <i class="ki-outline ki-cross fs-7"></i>
                </button>
            </span>
        @endforeach
        
        <button type="button" id="btnClearAllFilters" class="btn btn-link text-danger text-decoration-none p-0 fs-7 fw-semibold">
            Clear All
        </button>
    </div>
@endif

<!-- Table -->
@if($links->isEmpty())
    <div class="text-center py-10">
        <i class="ki-outline ki-search-list fs-4x text-muted mb-3"></i>
        <p class="text-gray-600 fw-semibold fs-6 mb-0">No links found. Create a new link or adjust your search filters!</p>
    </div>
@else
    <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-4 mb-0" id="kt_table_links">
            @php
                $currentSort = request('sort', 'latest');
                $linkSort = $currentSort == 'title_asc' ? 'title_desc' : 'title_asc';
                $klikSort = $currentSort == 'clicks_desc' ? 'clicks_asc' : 'clicks_desc';
                $dibuatSort = ($currentSort == 'latest' || empty(request('sort'))) ? 'oldest' : 'latest';
            @endphp
            <thead>
                <tr class="text-start text-muted fw-bold fs-7 text-uppercase gs-0">
                    <th class="w-10px pe-2">
                        <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                            <input class="form-check-input" type="checkbox" id="selectAllLinks" />
                        </div>
                    </th>
                    <th class="min-w-250px">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $linkSort]) }}" class="text-muted text-hover-primary d-inline-flex align-items-center gap-1">
                            Link
                            @if($currentSort == 'title_asc')
                                <i class="ki-outline ki-arrow-up fs-6 text-primary"></i>
                            @elseif($currentSort == 'title_desc')
                                <i class="ki-outline ki-arrow-down fs-6 text-primary"></i>
                            @endif
                        </a>
                    </th>
                    <th class="min-w-150px">Original Target</th>
                    <th class="min-w-100px">Project</th>
                    <th class="min-w-80px">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $klikSort]) }}" class="text-muted text-hover-primary d-inline-flex align-items-center gap-1">
                            Clicks
                            @if($currentSort == 'clicks_asc')
                                <i class="ki-outline ki-arrow-up fs-6 text-primary"></i>
                            @elseif($currentSort == 'clicks_desc')
                                <i class="ki-outline ki-arrow-down fs-6 text-primary"></i>
                            @endif
                        </a>
                    </th>
                    <th class="min-w-100px d-none d-md-table-cell">
                        <a href="{{ request()->fullUrlWithQuery(['sort' => $dibuatSort]) }}" class="text-muted text-hover-primary d-inline-flex align-items-center gap-1">
                            Created
                            @if($currentSort == 'oldest')
                                <i class="ki-outline ki-arrow-up fs-6 text-primary"></i>
                            @elseif($currentSort == 'latest' || empty(request('sort')))
                                <i class="ki-outline ki-arrow-down fs-6 text-primary"></i>
                            @endif
                        </a>
                    </th>
                    <th class="text-end min-w-120px pe-3">Actions</th>
                </tr>
            </thead>
            <tbody class="text-gray-600 fw-semibold">
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

                        if ($link->type === 'biolink') {
                            $detailRoute = route('biolinks.show', $link->id);
                        } elseif ($link->type === 'warotator') {
                            $detailRoute = route('warotators.show', $link->id);
                        } else {
                            $detailRoute = route('links.show', $link->id);
                        }
                    @endphp
                    <tr>
                        <!-- Bulk Checkbox -->
                        <td>
                            <div class="form-check form-check-sm form-check-custom form-check-solid">
                                <input class="form-check-input link-checkbox" type="checkbox" value="{{ $link->id }}" />
                            </div>
                        </td>

                        <!-- Link with Symbol/Avatar -->
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="symbol symbol-40px symbol-circle me-4">
                                    @if($link->type == 'biolink')
                                        <span class="symbol-label bg-light-primary">
                                            <i class="ki-outline ki-abstract-26 fs-2 text-primary"></i>
                                        </span>
                                    @elseif($link->type == 'warotator')
                                        <span class="symbol-label bg-light-success">
                                            <i class="ki-outline ki-whatsapp fs-2 text-success"></i>
                                        </span>
                                    @else
                                        <span class="symbol-label bg-light-info">
                                            <i class="ki-outline ki-disconnect fs-2 text-info"></i>
                                        </span>
                                    @endif
                                </div>
                                <div class="d-flex flex-column">
                                    <a href="{{ $detailRoute }}" class="text-gray-800 text-hover-primary fs-6 fw-bold mb-1">
                                        {{ $link->url }}
                                    </a>
                                    <a href="{{ $fullShortenedUrl }}" target="_blank" class="text-muted text-hover-primary fs-7 text-truncate d-inline-block" style="max-width: 250px;">
                                        {{ $fullShortenedUrl }}
                                    </a>
                                </div>
                            </div>
                        </td>

                        <!-- Original Target URL -->
                        <td>
                            @if($link->type == 'warotator')
                                <span class="badge badge-light-success fs-8">WA Rotator</span>
                            @else
                                <span class="text-gray-600 fs-7 d-block text-truncate" style="max-width: 200px;" title="{{ $link->location_url }}">
                                    {{ $link->location_url }}
                                </span>
                            @endif
                        </td>

                        <!-- Project -->
                        <td>
                            @php
                                $projectName = null;
                                if ($link->project_id && isset($projects)) {
                                    $proj = $projects->firstWhere('id', $link->project_id);
                                    if ($proj) $projectName = $proj->name;
                                }
                            @endphp
                            @if($projectName)
                                <span class="badge badge-light-primary fw-semibold fs-8">
                                    {{ $projectName }}
                                </span>
                            @else
                                <span class="text-muted fs-8">—</span>
                            @endif
                        </td>

                        <!-- Clicks counter -->
                        <td>
                            <div class="badge badge-light fw-bold text-gray-800 fs-7">
                                <i class="ki-outline ki-chart-simple fs-6 text-primary me-1"></i>{{ number_format($link->clicks) }}
                            </div>
                        </td>

                        <!-- Created Date -->
                        <td class="d-none d-md-table-cell">
                            <span class="text-muted fs-7">
                                {{ date('j M Y', strtotime($link->created_at)) }}
                            </span>
                        </td>

                        <!-- Actions -->
                        <td class="text-end pe-3">
                            <div class="d-inline-flex align-items-center gap-2">
                                <!-- Copy Button -->
                                <button class="btn btn-icon btn-sm btn-light-primary btn-copy-link" data-url="{{ $fullShortenedUrl }}" title="Copy link">
                                    <i class="ki-outline ki-copy fs-4"></i>
                                </button>

                                <!-- Status Switch -->
                                <div class="form-check form-switch form-check-custom form-check-solid p-0 m-0">
                                    <input class="form-check-input h-20px w-35px link-status-toggle" type="checkbox" data-id="{{ $link->id }}" {{ $link->is_enabled ? 'checked' : '' }} title="Toggle status" />
                                </div>

                                <!-- Action Dropdown -->
                                <div class="dropdown">
                                    <button class="btn btn-icon btn-sm btn-light-secondary dropdown-toggle no-caret" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ki-outline ki-dots-vertical fs-4"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0 py-3 px-2 rounded-3 w-175px">
                                        <li>
                                            <a class="dropdown-item rounded-2 py-2 fs-7 d-flex align-items-center gap-2" href="{{ $detailRoute }}">
                                                <i class="ki-outline ki-chart-line fs-5 text-primary"></i> Analytics
                                            </a>
                                        </li>
                                        <li>
                                            @if($link->type == 'biolink')
                                                <a class="dropdown-item rounded-2 py-2 fs-7 d-flex align-items-center gap-2" href="{{ route('biolinks.builder', $link->id) }}">
                                                    <i class="ki-outline ki-pencil fs-5 text-success"></i> Edit Biolink
                                                </a>
                                                <form action="{{ route('biolinks.duplicate', $link->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Duplikat Biolink ini beserta seluruh blok kontennya?')">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item rounded-2 py-2 fs-7 d-flex align-items-center gap-2 text-gray-800 border-0 bg-transparent w-100">
                                                        <i class="ki-outline ki-copy fs-5 text-info"></i> Duplicate
                                                    </button>
                                                </form>
                                                <a class="dropdown-item rounded-2 py-2 fs-7 d-flex align-items-center gap-2 btn-edit-link" href="#"
                                                   data-id="{{ $link->id }}"
                                                   data-type="{{ $link->type }}"
                                                   data-url="{{ $link->url }}"
                                                   data-location="{{ $link->location_url }}"
                                                   data-project="{{ $link->project_id }}"
                                                   data-domain="{{ $link->domain_id }}"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#editLinkModal">
                                                    <i class="ki-outline ki-setting-2 fs-5 text-muted"></i> Settings
                                                </a>
                                            @elseif($link->type == 'warotator')
                                                <a class="dropdown-item rounded-2 py-2 fs-7 d-flex align-items-center gap-2" href="{{ route('warotators.builder', $link->id) }}">
                                                    <i class="ki-outline ki-pencil fs-5 text-success"></i> Edit Rotator
                                                </a>
                                                <a class="dropdown-item rounded-2 py-2 fs-7 d-flex align-items-center gap-2 btn-edit-link" href="#"
                                                   data-id="{{ $link->id }}"
                                                   data-type="{{ $link->type }}"
                                                   data-url="{{ $link->url }}"
                                                   data-location="{{ $link->location_url }}"
                                                   data-project="{{ $link->project_id }}"
                                                   data-domain="{{ $link->domain_id }}"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#editLinkModal">
                                                    <i class="ki-outline ki-setting-2 fs-5 text-muted"></i> Settings
                                                </a>
                                            @else
                                                <a class="dropdown-item rounded-2 py-2 fs-7 d-flex align-items-center gap-2 btn-edit-link" href="#"
                                                   data-id="{{ $link->id }}"
                                                   data-type="{{ $link->type }}"
                                                   data-url="{{ $link->url }}"
                                                   data-location="{{ $link->location_url }}"
                                                   data-project="{{ $link->project_id }}"
                                                   data-domain="{{ $link->domain_id }}"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#editLinkModal">
                                                    <i class="ki-outline ki-pencil fs-5 text-primary"></i> Edit Link
                                                </a>
                                            @endif
                                        </li>
                                        <li><hr class="dropdown-divider my-1 opacity-25"></li>
                                        <li>
                                            <a class="dropdown-item rounded-2 py-2 fs-7 text-danger d-flex align-items-center gap-2 btn-delete-link" href="#" data-id="{{ $link->id }}">
                                                <i class="ki-outline ki-trash fs-5 text-danger"></i> Delete Link
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
    <div class="d-flex justify-content-between align-items-center mt-6">
        <span class="text-gray-500 fs-7">
            Showing {{ $links->firstItem() }}-{{ $links->lastItem() }} of {{ $links->total() }} results
        </span>
        <div>
            {{ $links->links('pagination::bootstrap-5') }}
        </div>
    </div>
@endif
