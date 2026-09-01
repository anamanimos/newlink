<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ToolController extends Controller
{
    /**
     * Display the main tools directory with search and categorization.
     */
    public function index()
    {
        $categories = [
            'generator' => [
                'title' => 'Generator Tools',
                'description' => 'Koleksi tools generator berguna untuk mempercepat pembuatan tautan dan data.',
                'color' => '#009ef7',
                'bg_class' => 'bg-primary',
                'icon' => 'ki-element-plus',
                'tools' => [
                    [
                        'id' => 'whatsapp-link-generator',
                        'title' => 'WhatsApp Link Generator',
                        'description' => 'Buat tautan pesan WhatsApp langsung dengan nomor dan template pesan otomatis.',
                        'icon' => 'ki-whatsapp',
                        'badge' => 'Populer',
                        'badge_class' => 'badge-light-success',
                        'route' => route('tools.whatsapp-link-generator'),
                    ],
                    [
                        'id' => 'utm-link-generator',
                        'title' => 'UTM Link Generator',
                        'description' => 'Tambahkan parameter UTM campaign (source, medium, campaign) ke tautan Anda.',
                        'icon' => 'ki-filter-search',
                        'badge' => 'Marketing',
                        'badge_class' => 'badge-light-info',
                        'route' => route('tools.utm-link-generator'),
                    ],
                    [
                        'id' => 'slug-generator',
                        'title' => 'Slug Generator',
                        'description' => 'Ubah teks judul apa saja menjadi URL slug ramah SEO secara instan.',
                        'icon' => 'ki-text-align-left',
                        'route' => route('tools.slug-generator'),
                    ],
                    [
                        'id' => 'password-generator',
                        'title' => 'Password Generator',
                        'description' => 'Generate kata sandi acak yang aman dan kuat dengan panjang serta karakter kustom.',
                        'icon' => 'ki-shield-tick',
                        'route' => route('tools.password-generator'),
                    ],
                    [
                        'id' => 'uuid-generator',
                        'title' => 'UUID v4 Generator',
                        'description' => 'Buat Universally Unique Identifier (UUID v4) acak untuk kebutuhan sistem/API.',
                        'icon' => 'ki-fingerprint-scanning',
                        'route' => route('tools.uuid-generator'),
                    ],
                    [
                        'id' => 'lorem-ipsum-generator',
                        'title' => 'Lorem Ipsum Generator',
                        'description' => 'Generate teks dummy placeholder Lorem Ipsum dengan jumlah paragraf fleksibel.',
                        'icon' => 'ki-document',
                        'route' => route('tools.lorem-ipsum-generator'),
                    ],
                ]
            ],
            'checker' => [
                'title' => 'Checker & Network Tools',
                'description' => 'Tools untuk memeriksa status domain, DNS, IP, dan koneksi.',
                'color' => '#7239ea',
                'bg_class' => 'bg-purple',
                'icon' => 'ki-security-check',
                'tools' => [
                    [
                        'id' => 'dns-lookup',
                        'title' => 'DNS Lookup Checker',
                        'description' => 'Periksa record DNS (A, AAAA, CNAME, MX, TXT, NS) dari domain mana saja.',
                        'icon' => 'ki-geolocation',
                        'route' => '#',
                        'action' => 'modal',
                        'modal_target' => '#dnsLookupModal'
                    ],
                    [
                        'id' => 'ip-lookup',
                        'title' => 'IP Address Lookup',
                        'description' => 'Cek informasi lokasi, negara, ISP, dan ASN dari alamat IP publik.',
                        'icon' => 'ki-map',
                        'route' => '#',
                        'action' => 'modal',
                        'modal_target' => '#ipLookupModal'
                    ]
                ]
            ],
            'converter' => [
                'title' => 'Converter & Encoder Tools',
                'description' => 'Tools konversi data, encoding, decoding, dan format teks.',
                'color' => '#50cd89',
                'bg_class' => 'bg-success',
                'icon' => 'ki-arrows-circle',
                'tools' => [
                    [
                        'id' => 'base64-converter',
                        'title' => 'Base64 Encoder / Decoder',
                        'description' => 'Encode teks biasa ke format Base64 atau decode string Base64 kembali ke teks.',
                        'icon' => 'ki-code',
                        'route' => '#',
                        'action' => 'modal',
                        'modal_target' => '#base64Modal'
                    ],
                    [
                        'id' => 'url-encoder',
                        'title' => 'URL Encoder / Decoder',
                        'description' => 'Encode string ke format URL-encoded atau decode parameter URL.',
                        'icon' => 'ki-disconnect',
                        'route' => '#',
                        'action' => 'modal',
                        'modal_target' => '#urlEncoderModal'
                    ]
                ]
            ]
        ];

        return view('tools.index', compact('categories'));
    }

    /**
     * WhatsApp Link Generator Tool
     */
    public function whatsappLinkGenerator()
    {
        return view('tools.whatsapp_link_generator');
    }

    /**
     * UTM Link Generator Tool
     */
    public function utmLinkGenerator()
    {
        return view('tools.utm_link_generator');
    }

    /**
     * Slug Generator Tool
     */
    public function slugGenerator()
    {
        return view('tools.slug_generator');
    }

    /**
     * Password Generator Tool
     */
    public function passwordGenerator()
    {
        return view('tools.password_generator');
    }

    /**
     * UUID Generator Tool
     */
    public function uuidGenerator()
    {
        return view('tools.uuid_generator');
    }

    /**
     * Lorem Ipsum Generator Tool
     */
    public function loremIpsumGenerator()
    {
        return view('tools.lorem_ipsum_generator');
    }
}