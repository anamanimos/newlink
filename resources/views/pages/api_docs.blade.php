@extends('layouts.app')

@section('title', 'REST API Documentation')

@section('content')
<!-- Documentation Header -->
<div class="card card-flush shadow-sm border-0 mb-8 bg-light-primary">
    <div class="card-body p-8">
        <div class="d-flex flex-wrap align-items-center justify-content-between gap-4">
            <div>
                <div class="d-flex align-items-center gap-3 mb-2">
                    <span class="badge badge-primary fw-bolder fs-8 px-3 py-1">API v1.0</span>
                    <h1 class="text-gray-900 fw-bolder fs-2hx mb-0">REST API Documentation</h1>
                </div>
                <p class="text-gray-700 fs-6 mb-0">
                    Dokumentasi teknis lengkap integrasi API platform NewLink untuk pengembang (developers).
                </p>
            </div>
            <div class="d-flex align-items-center gap-3">
                @auth
                    <a href="{{ route('user.api') }}" class="btn btn-primary fw-bold">
                        <i class="ki-outline ki-key fs-4 me-1"></i> Kunci API & Statistik Saya
                    </a>
                @else
                    <a href="{{ route('login') }}" class="btn btn-primary fw-bold">
                        <i class="ki-outline ki-entrance-left fs-4 me-1"></i> Masuk untuk Dapatkan Kunci API
                    </a>
                @endauth
            </div>
        </div>
    </div>
</div>

<div class="row g-8">
    <!-- Left Sticky Table of Contents -->
    <div class="col-12 col-lg-3">
        <div class="card card-flush shadow-sm border-0 position-sticky" style="top: 115px; z-index: 90;">
            <div class="card-header pt-5 pb-2">
                <h5 class="card-title fw-bold text-gray-900 fs-6">Daftar Isi API</h5>
            </div>
            <div class="card-body pt-0 px-4 pb-5">
                <div class="menu menu-column menu-rounded menu-gray-700 menu-state-bg-light-primary menu-state-title-primary fw-semibold fs-8 gap-1">
                    <div class="menu-item"><a href="#section-overview" class="menu-link py-2 px-3"><i class="ki-outline ki-information fs-5 me-2 text-primary"></i> 1. Ringkasan & Base URL</a></div>
                    <div class="menu-item"><a href="#section-auth" class="menu-link py-2 px-3"><i class="ki-outline ki-key fs-5 me-2 text-warning"></i> 2. Autentikasi</a></div>
                    <div class="menu-item"><a href="#section-errors" class="menu-link py-2 px-3"><i class="ki-outline ki-shield-cross fs-5 me-2 text-danger"></i> 3. Status Kode & Error</a></div>
                    <div class="separator separator-dashed my-2"></div>
                    <div class="menu-content pb-1 px-3 text-uppercase text-muted fs-9 fw-bold">Endpoints</div>
                    <div class="menu-item"><a href="#endpoint-user-profile" class="menu-link py-1.5 px-3"><span class="badge badge-light-primary fw-bold me-2 fs-9">GET</span> /user</a></div>
                    <div class="menu-item"><a href="#endpoint-user-regenerate" class="menu-link py-1.5 px-3"><span class="badge badge-light-success fw-bold me-2 fs-9">POST</span> /user/regenerate-key</a></div>
                    <div class="menu-content pb-1 px-3 text-uppercase text-muted fs-9 fw-bold">QR Codes API</div>
                    <div class="menu-item"><a href="#endpoint-qr-generate" class="menu-link py-1.5 px-3"><span class="badge badge-light-success fw-bold me-2 fs-9">POST</span> /qr-codes/generate</a></div>
                    <div class="menu-item"><a href="#endpoint-qr-list" class="menu-link py-1.5 px-3"><span class="badge badge-light-primary fw-bold me-2 fs-9">GET</span> /qr-codes</a></div>
                    <div class="menu-item"><a href="#endpoint-qr-create" class="menu-link py-1.5 px-3"><span class="badge badge-light-success fw-bold me-2 fs-9">POST</span> /qr-codes</a></div>
                    <div class="menu-item"><a href="#endpoint-qr-get" class="menu-link py-1.5 px-3"><span class="badge badge-light-primary fw-bold me-2 fs-9">GET</span> /qr-codes/{id}</a></div>
                    <div class="separator separator-dashed my-2"></div>
                    <div class="menu-content pb-1 px-3 text-uppercase text-muted fs-9 fw-bold">Links & Biolinks</div>
                    <div class="menu-item"><a href="#endpoint-links-list" class="menu-link py-1.5 px-3"><span class="badge badge-light-primary fw-bold me-2 fs-9">GET</span> /links</a></div>
                    <div class="menu-item"><a href="#endpoint-links-create" class="menu-link py-1.5 px-3"><span class="badge badge-light-success fw-bold me-2 fs-9">POST</span> /links</a></div>
                    <div class="menu-item"><a href="#endpoint-links-get" class="menu-link py-1.5 px-3"><span class="badge badge-light-primary fw-bold me-2 fs-9">GET</span> /links/{id}</a></div>
                    <div class="menu-item"><a href="#endpoint-links-update" class="menu-link py-1.5 px-3"><span class="badge badge-light-warning fw-bold me-2 fs-9">PUT</span> /links/{id}</a></div>
                    <div class="menu-item"><a href="#endpoint-links-delete" class="menu-link py-1.5 px-3"><span class="badge badge-light-danger fw-bold me-2 fs-9">DEL</span> /links/{id}</a></div>
                    <div class="menu-item"><a href="#endpoint-biolinks-list" class="menu-link py-1.5 px-3"><span class="badge badge-light-primary fw-bold me-2 fs-9">GET</span> /biolinks</a></div>
                    <div class="menu-item"><a href="#endpoint-biolinks-create" class="menu-link py-1.5 px-3"><span class="badge badge-light-success fw-bold me-2 fs-9">POST</span> /biolinks</a></div>
                    <div class="menu-item"><a href="#endpoint-biolinks-get" class="menu-link py-1.5 px-3"><span class="badge badge-light-primary fw-bold me-2 fs-9">GET</span> /biolinks/{id}</a></div>
                    <div class="menu-item"><a href="#endpoint-biolinks-block" class="menu-link py-1.5 px-3"><span class="badge badge-light-success fw-bold me-2 fs-9">POST</span> /biolinks/{id}/blocks</a></div>
                    <div class="menu-item"><a href="#endpoint-projects" class="menu-link py-1.5 px-3"><span class="badge badge-light-primary fw-bold me-2 fs-9">GET</span> /projects</a></div>
                    <div class="menu-item"><a href="#endpoint-domains" class="menu-link py-1.5 px-3"><span class="badge badge-light-primary fw-bold me-2 fs-9">GET</span> /domains</a></div>
                    <div class="menu-item"><a href="#endpoint-pixels" class="menu-link py-1.5 px-3"><span class="badge badge-light-primary fw-bold me-2 fs-9">GET</span> /pixels</a></div>
                    <div class="menu-item"><a href="#endpoint-statistics" class="menu-link py-1.5 px-3"><span class="badge badge-light-primary fw-bold me-2 fs-9">GET</span> /statistics</a></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Right Documentation Content -->
    <div class="col-12 col-lg-9">

        <!-- 1. Overview -->
        <div class="card card-flush shadow-sm border-0 mb-8" id="section-overview">
            <div class="card-header pt-6 pb-2">
                <h3 class="fw-bold text-gray-900 fs-4">1. Ringkasan & Base URL</h3>
            </div>
            <div class="card-body pt-0">
                <p class="text-gray-700 fs-6 leading-relaxed">
                    NewLink REST API memungkinkan Anda untuk membuat tautan pendek, mengelola halaman biolink, menarik analitik klik, dan mengelola entitas akun secara terprogram dari aplikasi atau skrip eksternal Anda.
                </p>
                <div class="bg-light p-4 rounded-3 border">
                    <div class="text-muted fs-8 fw-bold text-uppercase mb-1">Base URL Endpoint:</div>
                    <code class="fs-5 text-primary fw-bold font-monospace">{{ url('/api/v1') }}</code>
                </div>
                <div class="mt-4">
                    <div class="fs-7 text-gray-700">Format respon API selalu dikembalikan dalam format <strong>JSON</strong> standar dengan format envelope <code>{ "status": "success|error", "data": ... }</code>.</div>
                </div>
            </div>
        </div>

        <!-- 2. Authentication -->
        <div class="card card-flush shadow-sm border-0 mb-8" id="section-auth">
            <div class="card-header pt-6 pb-2">
                <h3 class="fw-bold text-gray-900 fs-4">2. Autentikasi (Authentication)</h3>
            </div>
            <div class="card-body pt-0">
                <p class="text-gray-700 fs-6 leading-relaxed">
                    Setiap permintaan ke API harus menyertakan **Kunci API (API Key)** rahasia Anda pada Header HTTP:
                </p>
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle fs-7">
                        <thead class="bg-light">
                            <tr class="fw-bold text-gray-900">
                                <th>Header Key</th>
                                <th>Value Format</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-monospace fw-bold text-primary">Authorization</td>
                                <td class="font-monospace">Bearer YOUR_API_KEY</td>
                                <td>Format standar OAuth2/Bearer Token (Sangat Direkomendasikan)</td>
                            </tr>
                            <tr>
                                <td class="font-monospace fw-bold text-primary">X-API-KEY</td>
                                <td class="font-monospace">YOUR_API_KEY</td>
                                <td>Header kustom alternatif</td>
                            </tr>
                            <tr>
                                <td class="font-monospace fw-bold">Accept</td>
                                <td class="font-monospace">application/json</td>
                                <td>Meminta respon dalam format JSON</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="alert alert-primary d-flex align-items-center p-4 rounded-3">
                    <i class="ki-outline ki-key fs-2hx text-primary me-3"></i>
                    <div class="fs-7 text-gray-800">
                        @auth
                            Kunci API Anda saat ini: <strong class="font-monospace">{{ Auth::user()->api_key }}</strong>. Kelola kunci Anda di halaman <a href="{{ route('user.api') }}" class="fw-bold text-primary">API Dashboard</a>.
                        @else
                            Anda dapat melihat dan menyalin Kunci API Anda di dashboard akun setelah masuk.
                        @endauth
                    </div>
                </div>
            </div>
        </div>

        <!-- 3. Errors -->
        <div class="card card-flush shadow-sm border-0 mb-8" id="section-errors">
            <div class="card-header pt-6 pb-2">
                <h3 class="fw-bold text-gray-900 fs-4">3. Status Kode HTTP & Format Error</h3>
            </div>
            <div class="card-body pt-0">
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle fs-7">
                        <thead class="bg-light">
                            <tr class="fw-bold text-gray-900">
                                <th style="width: 120px;">Kode</th>
                                <th style="width: 180px;">Status</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><span class="badge badge-light-success fw-bold">200 OK</span></td>
                                <td class="fw-bold">Permintaan Berhasil</td>
                                <td>Permintaan berhasil diproses dan mengembalikan data.</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-light-success fw-bold">201 Created</span></td>
                                <td class="fw-bold">Data Dibuat</td>
                                <td>Entitas baru (shortlink/biolink/project) berhasil dibuat.</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-light-warning fw-bold">401 Unauthorized</span></td>
                                <td class="fw-bold">Kunci API Tidak Valid</td>
                                <td>Kunci API tidak disertakan atau salah.</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-light-warning fw-bold">404 Not Found</span></td>
                                <td class="fw-bold">Tidak Ditemukan</td>
                                <td>Sumber daya / ID tautan tidak ditemukan pada akun Anda.</td>
                            </tr>
                            <tr>
                                <td><span class="badge badge-light-warning fw-bold">422 Unprocessable</span></td>
                                <td class="fw-bold">Validasi Gagal</td>
                                <td>Input payload tidak valid atau alias URL kustom sudah terpakai.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="bg-dark rounded-3 p-4 text-white font-monospace fs-8">
                    <div class="text-muted mb-2">// Contoh Format Respon Error:</div>
                    <pre class="text-danger mb-0"><code>{
    "status": "error",
    "message": "The location_url field must be a valid URL.",
    "error_code": 422
}</code></pre>
                </div>
            </div>
        </div>

        <!-- ================= ENDPOINT: GET /user ================= -->
        <div class="card card-flush shadow-sm border-0 mb-8" id="endpoint-user-profile">
            <div class="card-header pt-6 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge badge-primary fw-bolder fs-7 px-3 py-2">GET</span>
                    <h3 class="fw-bold text-gray-900 fs-4 mb-0">/user</h3>
                </div>
                <div class="card-toolbar">
                    <span class="text-muted fs-8">Profil Pengguna & Sisa Kuota</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <p class="text-gray-700 fs-7">Mengembalikan informasi akun pengguna, paket langganan saat ini, dan penggunaan kuota entitas.</p>

                <!-- Code Example Tabs -->
                <div class="bg-dark rounded-3 p-4 text-white">
                    <ul class="nav nav-pills nav-pills-custom mb-3 gap-2 border-bottom border-gray-700 pb-2" role="tablist">
                        <li class="nav-item"><a class="nav-link active py-1 px-3 fs-8 fw-bold" data-bs-toggle="pill" href="#curl_user">cURL</a></li>
                        <li class="nav-item"><a class="nav-link py-1 px-3 fs-8 fw-bold" data-bs-toggle="pill" href="#php_user">PHP</a></li>
                        <li class="nav-item"><a class="nav-link py-1 px-3 fs-8 fw-bold" data-bs-toggle="pill" href="#js_user">JavaScript</a></li>
                        <li class="nav-item"><a class="nav-link py-1 px-3 fs-8 fw-bold" data-bs-toggle="pill" href="#py_user">Python</a></li>
                    </ul>
                    <div class="tab-content font-monospace fs-8">
                        <div class="tab-pane fade show active" id="curl_user">
                            <pre class="text-info mb-0"><code>curl -X GET "{{ url('/api/v1/user') }}" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Accept: application/json"</code></pre>
                        </div>
                        <div class="tab-pane fade" id="php_user">
                            <pre class="text-info mb-0"><code>$ch = curl_init("{{ url('/api/v1/user') }}");
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer YOUR_API_KEY",
    "Accept: application/json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);
$data = json_decode($response, true);</code></pre>
                        </div>
                        <div class="tab-pane fade" id="js_user">
                            <pre class="text-info mb-0"><code>const response = await fetch("{{ url('/api/v1/user') }}", {
    headers: {
        "Authorization": "Bearer YOUR_API_KEY",
        "Accept": "application/json"
    }
});
const result = await response.json();</code></pre>
                        </div>
                        <div class="tab-pane fade" id="py_user">
                            <pre class="text-info mb-0"><code>import requests
headers = {"Authorization": "Bearer YOUR_API_KEY", "Accept": "application/json"}
res = requests.get("{{ url('/api/v1/user') }}", headers=headers)
print(res.json())</code></pre>
                        </div>
                    </div>
                </div>

                <!-- Example Response -->
                <div class="mt-4">
                    <span class="fs-8 fw-bold text-muted text-uppercase d-block mb-2">Contoh Respon 200 OK:</span>
                    <div class="bg-gray-100 rounded-3 p-4 font-monospace fs-8 text-gray-900 border">
                        <pre class="mb-0"><code>{
  "status": "success",
  "data": {
    "id": 1,
    "name": "Alex Pratama",
    "email": "alex@example.com",
    "plan_id": "pro",
    "plan_expiration_date": "2027-12-31 23:59:59",
    "status": "active",
    "role": "user",
    "usage": {
      "shortlinks_count": 28,
      "biolinks_count": 5,
      "projects_count": 3,
      "domains_count": 2,
      "pixels_count": 4
    },
    "created_at": "2026-07-05T11:00:00.000000Z"
  }
}</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= ENDPOINT: POST /qr-codes/generate ================= -->
        <div class="card card-flush shadow-sm border-0 mb-8" id="endpoint-qr-generate">
            <div class="card-header pt-6 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge badge-success fw-bolder fs-7 px-3 py-2">POST</span>
                    <h3 class="fw-bold text-gray-900 fs-4 mb-0">/qr-codes/generate</h3>
                </div>
                <div class="card-toolbar">
                    <span class="text-muted fs-8">Instant QR Code Generator (Stateless)</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <p class="text-gray-700 fs-7">
                    Membuat gambar kode QR secara instan (on-the-fly) tanpa harus menyimpannya ke database. Mengembalikan data vektor SVG murni dan Data-URI Base64 yang dapat langsung dipasang pada tag <code>&lt;img src="..."&gt;</code>.
                </p>

                <!-- Parameter Table -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle fs-7">
                        <thead class="bg-light">
                            <tr class="fw-bold text-gray-900">
                                <th>Parameter (Body JSON)</th>
                                <th>Tipe</th>
                                <th>Wajib?</th>
                                <th>Deskripsi & Contoh</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-monospace fw-bold text-primary">content</td>
                                <td><code>string | object</code></td>
                                <td><span class="badge badge-light-danger fw-bold fs-9">Wajib</span></td>
                                <td>Konten yang ingin di-encode (URL, teks, atau object data khusus seperti WiFi / vCard)</td>
                            </tr>
                            <tr>
                                <td class="font-monospace fw-bold">type</td>
                                <td><code>string</code></td>
                                <td><span class="badge badge-light-secondary fw-bold fs-9">Opsional</span></td>
                                <td>Tipe konten: <code>url</code> (default), <code>text</code>, <code>whatsapp</code>, <code>wifi</code>, <code>vcard</code>, <code>email</code>, <code>phone</code>, <code>sms</code></td>
                            </tr>
                            <tr>
                                <td class="font-monospace fw-bold">foreground_color</td>
                                <td><code>string (hex)</code></td>
                                <td><span class="badge badge-light-secondary fw-bold fs-9">Opsional</span></td>
                                <td>Warna QR Code (default: <code>#000000</code>)</td>
                            </tr>
                            <tr>
                                <td class="font-monospace fw-bold">background_color</td>
                                <td><code>string (hex)</code></td>
                                <td><span class="badge badge-light-secondary fw-bold fs-9">Opsional</span></td>
                                <td>Warna latar belakang (default: <code>#ffffff</code> atau <code>transparent</code>)</td>
                            </tr>
                            <tr>
                                <td class="font-monospace fw-bold">size</td>
                                <td><code>integer</code></td>
                                <td><span class="badge badge-light-secondary fw-bold fs-9">Opsional</span></td>
                                <td>Ukuran dimensi dalam piksel (100 - 2000, default: <code>300</code>)</td>
                            </tr>
                            <tr>
                                <td class="font-monospace fw-bold">margin</td>
                                <td><code>integer</code></td>
                                <td><span class="badge badge-light-secondary fw-bold fs-9">Opsional</span></td>
                                <td>Jarak margin putih / quiet zone (0 - 10, default: <code>2</code>)</td>
                            </tr>
                            <tr>
                                <td class="font-monospace fw-bold">format</td>
                                <td><code>string</code></td>
                                <td><span class="badge badge-light-secondary fw-bold fs-9">Opsional</span></td>
                                <td>Format output: <code>json</code> (default) atau <code>svg</code> (mengembalikan raw image/svg+xml)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Code Example Tabs -->
                <div class="bg-dark rounded-3 p-4 text-white">
                    <ul class="nav nav-pills nav-pills-custom mb-3 gap-2 border-bottom border-gray-700 pb-2" role="tablist">
                        <li class="nav-item"><a class="nav-link active py-1 px-3 fs-8 fw-bold" data-bs-toggle="pill" href="#curl_qr_gen">cURL</a></li>
                        <li class="nav-item"><a class="nav-link py-1 px-3 fs-8 fw-bold" data-bs-toggle="pill" href="#php_qr_gen">PHP</a></li>
                        <li class="nav-item"><a class="nav-link py-1 px-3 fs-8 fw-bold" data-bs-toggle="pill" href="#js_qr_gen">JavaScript</a></li>
                        <li class="nav-item"><a class="nav-link py-1 px-3 fs-8 fw-bold" data-bs-toggle="pill" href="#py_qr_gen">Python</a></li>
                    </ul>
                    <div class="tab-content font-monospace fs-8">
                        <div class="tab-pane fade show active" id="curl_qr_gen">
                            <pre class="text-success mb-0"><code>curl -X POST "{{ url('/api/v1/qr-codes/generate') }}" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "type": "whatsapp",
    "content": {
      "phone": "6281234567890",
      "message": "Halo, saya tertarik dengan layanan Anda"
    },
    "foreground_color": "#1e40af",
    "background_color": "#ffffff",
    "size": 400
  }'</code></pre>
                        </div>
                        <div class="tab-pane fade" id="php_qr_gen">
                            <pre class="text-info mb-0"><code>$payload = [
    "type" => "url",
    "content" => "https://newlink.test/promo-spesial",
    "foreground_color" => "#0d6efd",
    "size" => 350
];

$ch = curl_init("{{ url('/api/v1/qr-codes/generate') }}");
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer YOUR_API_KEY",
    "Content-Type: application/json",
    "Accept: application/json"
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = json_decode(curl_exec($ch), true);
curl_close($ch);</code></pre>
                        </div>
                        <div class="tab-pane fade" id="js_qr_gen">
                            <pre class="text-info mb-0"><code>const res = await fetch("{{ url('/api/v1/qr-codes/generate') }}", {
    method: "POST",
    headers: {
        "Authorization": "Bearer YOUR_API_KEY",
        "Content-Type": "application/json",
        "Accept": "application/json"
    },
    body: JSON.stringify({
        type: "url",
        content: "https://mywebsite.com",
        size: 300
    })
});
const { data } = await res.json();
// data.data_uri can be placed directly into <img src="..." /></code></pre>
                        </div>
                        <div class="tab-pane fade" id="py_qr_gen">
                            <pre class="text-info mb-0"><code>import requests

payload = {
    "type": "url",
    "content": "https://mywebsite.com",
    "foreground_color": "#111827",
    "size": 300
}
res = requests.post(
    "{{ url('/api/v1/qr-codes/generate') }}",
    json=payload,
    headers={"Authorization": "Bearer YOUR_API_KEY"}
)
qr_data = res.json()["data"]
print(qr_data["data_uri"])</code></pre>
                        </div>
                    </div>
                </div>

                <!-- Example Response -->
                <div class="mt-4">
                    <span class="fs-8 fw-bold text-muted text-uppercase d-block mb-2">Contoh Respon 200 OK:</span>
                    <div class="bg-gray-100 rounded-3 p-4 font-monospace fs-8 text-gray-900 border">
                        <pre class="mb-0"><code>{
  "status": "success",
  "data": {
    "type": "whatsapp",
    "content": "https://wa.me/6281234567890?text=Halo%2C+saya+tertarik+dengan+layanan+Anda",
    "size": 400,
    "foreground_color": "#1e40af",
    "background_color": "#ffffff",
    "svg": "&lt;svg xmlns=\"http://www.w3.org/2000/svg\" ...&gt;...&lt;/svg&gt;",
    "data_uri": "data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHZlcnNpb249..."
  }
}</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= ENDPOINT: GET /qr-codes ================= -->
        <div class="card card-flush shadow-sm border-0 mb-8" id="endpoint-qr-list">
            <div class="card-header pt-6 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge badge-primary fw-bolder fs-7 px-3 py-2">GET</span>
                    <h3 class="fw-bold text-gray-900 fs-4 mb-0">/qr-codes</h3>
                </div>
                <div class="card-toolbar">
                    <span class="text-muted fs-8">Daftar QR Code Tersimpan (List Saved QR Codes)</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <p class="text-gray-700 fs-7">Mengambil daftar seluruh QR Code yang tersimpan di akun Anda dengan pagination dan pencarian.</p>
                <div class="bg-dark rounded-3 p-4 text-white font-monospace fs-8">
                    <pre class="text-info mb-0"><code>curl -X GET "{{ url('/api/v1/qr-codes?per_page=15') }}" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Accept: application/json"</code></pre>
                </div>
            </div>
        </div>

        <!-- ================= ENDPOINT: POST /qr-codes ================= -->
        <div class="card card-flush shadow-sm border-0 mb-8" id="endpoint-qr-create">
            <div class="card-header pt-6 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge badge-success fw-bolder fs-7 px-3 py-2">POST</span>
                    <h3 class="fw-bold text-gray-900 fs-4 mb-0">/qr-codes</h3>
                </div>
                <div class="card-toolbar">
                    <span class="text-muted fs-8">Simpan QR Code Baru (Create & Save)</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <p class="text-gray-700 fs-7">Membuat dan menyimpan desain QR Code baru ke akun Anda agar dapat diakses atau diperbarui kembali di kemudian hari.</p>
                <div class="bg-dark rounded-3 p-4 text-white font-monospace fs-8">
                    <pre class="text-success mb-0"><code>curl -X POST "{{ url('/api/v1/qr-codes') }}" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "name": "Brosur Event 2026",
    "type": "url",
    "content": "https://event.newlink.test/registrasi",
    "foreground_color": "#0f172a",
    "size": 500,
    "project_id": 1
  }'</code></pre>
                </div>
            </div>
        </div>

        <!-- ================= ENDPOINT: GET /qr-codes/{id} ================= -->
        <div class="card card-flush shadow-sm border-0 mb-8" id="endpoint-qr-get">
            <div class="card-header pt-6 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge badge-primary fw-bolder fs-7 px-3 py-2">GET</span>
                    <h3 class="fw-bold text-gray-900 fs-4 mb-0">/qr-codes/{id}</h3>
                </div>
                <div class="card-toolbar">
                    <span class="text-muted fs-8">Detail QR Code</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <p class="text-gray-700 fs-7">Mengambil rincian data serta gambar vektor QR code tersimpan berdasarkan ID.</p>
                <div class="bg-dark rounded-3 p-4 text-white font-monospace fs-8">
                    <pre class="text-info mb-0"><code>curl -X GET "{{ url('/api/v1/qr-codes/1') }}" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Accept: application/json"</code></pre>
                </div>
            </div>
        </div>

        <!-- ================= ENDPOINT: POST /links ================= -->
        <div class="card card-flush shadow-sm border-0 mb-8" id="endpoint-links-create">
            <div class="card-header pt-6 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge badge-success fw-bolder fs-7 px-3 py-2">POST</span>
                    <h3 class="fw-bold text-gray-900 fs-4 mb-0">/links</h3>
                </div>
                <div class="card-toolbar">
                    <span class="text-muted fs-8">Buat Tautan Pendek (Create Shortlink)</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <p class="text-gray-700 fs-7">Membuat tautan singkat baru yang mengarah ke URL tujuan Anda.</p>

                <!-- Parameter Table -->
                <div class="table-responsive mb-4">
                    <table class="table table-bordered align-middle fs-7">
                        <thead class="bg-light">
                            <tr class="fw-bold text-gray-900">
                                <th>Parameter (Body JSON)</th>
                                <th>Tipe</th>
                                <th>Wajib?</th>
                                <th>Deskripsi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="font-monospace fw-bold text-primary">location_url</td>
                                <td><code>string (url)</code></td>
                                <td><span class="badge badge-light-danger fw-bold fs-9">Wajib</span></td>
                                <td>URL target tujuan lengkap (misal: <code>https://mywebsite.com/promo</code>)</td>
                            </tr>
                            <tr>
                                <td class="font-monospace fw-bold">url</td>
                                <td><code>string (slug)</code></td>
                                <td><span class="badge badge-light-secondary fw-bold fs-9">Opsional</span></td>
                                <td>Alias kustom yang diinginkan (misal: <code>promo2026</code>). Dibuat otomatis jika kosong.</td>
                            </tr>
                            <tr>
                                <td class="font-monospace fw-bold">domain_id</td>
                                <td><code>integer</code></td>
                                <td><span class="badge badge-light-secondary fw-bold fs-9">Opsional</span></td>
                                <td>ID Custom Domain yang ingin digunakan (0 = domain default sistem).</td>
                            </tr>
                            <tr>
                                <td class="font-monospace fw-bold">project_id</td>
                                <td><code>integer</code></td>
                                <td><span class="badge badge-light-secondary fw-bold fs-9">Opsional</span></td>
                                <td>ID Folder Proyek untuk mengelompokkan tautan.</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Code Example -->
                <div class="bg-dark rounded-3 p-4 text-white font-monospace fs-8">
                    <pre class="text-success mb-0"><code>curl -X POST "{{ url('/api/v1/links') }}" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "location_url": "https://tokopedia.com/product/123",
    "url": "diskon-merdeka",
    "project_id": 1
  }'</code></pre>
                </div>

                <!-- Response Example -->
                <div class="mt-4">
                    <span class="fs-8 fw-bold text-muted text-uppercase d-block mb-2">Contoh Respon 201 Created:</span>
                    <div class="bg-gray-100 rounded-3 p-4 font-monospace fs-8 text-gray-900 border">
                        <pre class="mb-0"><code>{
  "status": "success",
  "message": "Shortlink created successfully.",
  "data": {
    "id": 142,
    "user_id": 1,
    "type": "link",
    "url": "diskon-merdeka",
    "location_url": "https://tokopedia.com/product/123",
    "full_url": "{{ url('/diskon-merdeka') }}",
    "clicks": 0,
    "is_enabled": 1,
    "created_at": "2026-08-31T13:00:00.000000Z"
  }
}</code></pre>
                    </div>
                </div>
            </div>
        </div>

        <!-- ================= ENDPOINT: GET /links ================= -->
        <div class="card card-flush shadow-sm border-0 mb-8" id="endpoint-links-list">
            <div class="card-header pt-6 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge badge-primary fw-bolder fs-7 px-3 py-2">GET</span>
                    <h3 class="fw-bold text-gray-900 fs-4 mb-0">/links</h3>
                </div>
                <div class="card-toolbar">
                    <span class="text-muted fs-8">Daftar Tautan (List Links)</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <p class="text-gray-700 fs-7">Mengambil daftar tautan pendek milik pengguna dengan pagination dan filter pencarian.</p>
                <div class="bg-dark rounded-3 p-4 text-white font-monospace fs-8">
                    <pre class="text-info mb-0"><code>curl -X GET "{{ url('/api/v1/links?search=promo&per_page=10') }}" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Accept: application/json"</code></pre>
                </div>
            </div>
        </div>

        <!-- ================= ENDPOINT: POST /biolinks ================= -->
        <div class="card card-flush shadow-sm border-0 mb-8" id="endpoint-biolinks-create">
            <div class="card-header pt-6 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge badge-success fw-bolder fs-7 px-3 py-2">POST</span>
                    <h3 class="fw-bold text-gray-900 fs-4 mb-0">/biolinks</h3>
                </div>
                <div class="card-toolbar">
                    <span class="text-muted fs-8">Buat Halaman Biolink Baru</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <p class="text-gray-700 fs-7">Membuat halaman biolink baru dengan tema dan konfigurasi default.</p>
                <div class="bg-dark rounded-3 p-4 text-white font-monospace fs-8">
                    <pre class="text-success mb-0"><code>curl -X POST "{{ url('/api/v1/biolinks') }}" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "url": "official-store",
    "title": "Toko Resmi Saya"
  }'</code></pre>
                </div>
            </div>
        </div>

        <!-- ================= ENDPOINT: GET /statistics ================= -->
        <div class="card card-flush shadow-sm border-0 mb-8" id="endpoint-statistics">
            <div class="card-header pt-6 pb-2">
                <div class="d-flex align-items-center gap-3">
                    <span class="badge badge-primary fw-bolder fs-7 px-3 py-2">GET</span>
                    <h3 class="fw-bold text-gray-900 fs-4 mb-0">/statistics</h3>
                </div>
                <div class="card-toolbar">
                    <span class="text-muted fs-8">Ringkasan Analitik Klik</span>
                </div>
            </div>
            <div class="card-body pt-0">
                <p class="text-gray-700 fs-7">Mengambil ringkasan total klik, klik hari ini, top 5 negara asal pengunjung, dan distribusi perangkat.</p>
                <div class="bg-dark rounded-3 p-4 text-white font-monospace fs-8">
                    <pre class="text-info mb-0"><code>curl -X GET "{{ url('/api/v1/statistics') }}" \
  -H "Authorization: Bearer YOUR_API_KEY" \
  -H "Accept: application/json"</code></pre>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
