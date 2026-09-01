<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\QrCode;
use App\Services\QrCodeService;

class QrCodeController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = QrCode::where('user_id', $user->id);

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                  ->orWhere('content', 'like', "%{$s}%");
            });
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('project_id')) {
            $query->where('project_id', $request->project_id);
        }

        $perPage = min((int)($request->per_page ?? 15), 100);
        $qrCodes = $query->orderBy('created_at', 'DESC')->paginate($perPage);

        // Attach live vector data URI to each item
        $qrCodes->getCollection()->transform(function ($qr) {
            $qr->data_uri = QrCodeService::generateDataUri(
                $qr->content,
                $qr->size ?: 300,
                $qr->foreground_color ?: '#000000',
                $qr->background_color ?: '#ffffff',
                $qr->margin ?: 2
            );
            return $qr;
        });

        return response()->json([
            'status' => 'success',
            'data' => $qrCodes->items(),
            'pagination' => [
                'current_page' => $qrCodes->currentPage(),
                'per_page' => $qrCodes->perPage(),
                'total' => $qrCodes->total(),
                'last_page' => $qrCodes->lastPage(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'name' => 'required|string|max:128',
            'type' => 'required|string|in:url,text,email,phone,sms,whatsapp,wifi,vcard',
            'content' => 'required', // string or object depending on type
            'foreground_color' => 'nullable|string|max:32',
            'background_color' => 'nullable|string|max:32',
            'size' => 'nullable|integer|min:100|max:2000',
            'margin' => 'nullable|integer|min:0|max:10',
            'project_id' => 'nullable|integer',
        ]);

        $formattedContent = QrCodeService::formatContent($request->type, $request->content);
        $fgColor = $request->foreground_color ?: '#000000';
        $bgColor = $request->background_color ?: '#ffffff';
        $size = (int)($request->size ?: 300);
        $margin = (int)($request->margin ?? 2);

        $qrCode = QrCode::create([
            'user_id' => $user->id,
            'project_id' => $request->project_id ?: null,
            'name' => $request->name,
            'type' => $request->type,
            'content' => $formattedContent,
            'foreground_color' => $fgColor,
            'background_color' => $bgColor,
            'size' => $size,
            'margin' => $margin,
            'settings' => is_array($request->content) ? $request->content : ['raw' => $request->content],
        ]);

        $svg = QrCodeService::generateSvg($formattedContent, $size, $fgColor, $bgColor, $margin);
        $dataUri = QrCodeService::generateDataUri($formattedContent, $size, $fgColor, $bgColor, $margin);

        return response()->json([
            'status' => 'success',
            'message' => 'QR Code created successfully.',
            'data' => array_merge($qrCode->toArray(), [
                'svg' => $svg,
                'data_uri' => $dataUri,
            ])
        ], 201);
    }

    public function show(Request $request, $id)
    {
        $user = $request->user();
        $qrCode = QrCode::where('user_id', $user->id)->findOrFail($id);

        $svg = QrCodeService::generateSvg(
            $qrCode->content,
            $qrCode->size ?: 300,
            $qrCode->foreground_color ?: '#000000',
            $qrCode->background_color ?: '#ffffff',
            $qrCode->margin ?: 2
        );

        $dataUri = QrCodeService::generateDataUri(
            $qrCode->content,
            $qrCode->size ?: 300,
            $qrCode->foreground_color ?: '#000000',
            $qrCode->background_color ?: '#ffffff',
            $qrCode->margin ?: 2
        );

        return response()->json([
            'status' => 'success',
            'data' => array_merge($qrCode->toArray(), [
                'svg' => $svg,
                'data_uri' => $dataUri,
            ])
        ]);
    }

    public function update(Request $request, $id)
    {
        $user = $request->user();
        $qrCode = QrCode::where('user_id', $user->id)->findOrFail($id);

        $request->validate([
            'name' => 'nullable|string|max:128',
            'content' => 'nullable',
            'foreground_color' => 'nullable|string|max:32',
            'background_color' => 'nullable|string|max:32',
            'size' => 'nullable|integer|min:100|max:2000',
            'margin' => 'nullable|integer|min:0|max:10',
            'project_id' => 'nullable|integer',
        ]);

        if ($request->filled('name')) {
            $qrCode->name = $request->name;
        }

        if ($request->has('content')) {
            $type = $request->type ?: $qrCode->type;
            $qrCode->content = QrCodeService::formatContent($type, $request->content);
        }

        if ($request->filled('foreground_color')) {
            $qrCode->foreground_color = $request->foreground_color;
        }

        if ($request->filled('background_color')) {
            $qrCode->background_color = $request->background_color;
        }

        if ($request->filled('size')) {
            $qrCode->size = (int)$request->size;
        }

        if ($request->has('margin')) {
            $qrCode->margin = (int)$request->margin;
        }

        if ($request->has('project_id')) {
            $qrCode->project_id = $request->project_id ?: null;
        }

        $qrCode->save();

        $dataUri = QrCodeService::generateDataUri(
            $qrCode->content,
            $qrCode->size ?: 300,
            $qrCode->foreground_color ?: '#000000',
            $qrCode->background_color ?: '#ffffff',
            $qrCode->margin ?: 2
        );

        return response()->json([
            'status' => 'success',
            'message' => 'QR Code updated successfully.',
            'data' => array_merge($qrCode->toArray(), [
                'data_uri' => $dataUri,
            ])
        ]);
    }

    public function destroy(Request $request, $id)
    {
        $user = $request->user();
        $qrCode = QrCode::where('user_id', $user->id)->findOrFail($id);

        $qrCode->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'QR Code deleted successfully.'
        ]);
    }

    /**
     * Instant on-the-fly QR code generator (Stateless)
     */
    public function generate(Request $request)
    {
        $request->validate([
            'type' => 'nullable|string|in:url,text,email,phone,sms,whatsapp,wifi,vcard',
            'content' => 'required',
            'foreground_color' => 'nullable|string|max:32',
            'background_color' => 'nullable|string|max:32',
            'size' => 'nullable|integer|min:100|max:2000',
            'margin' => 'nullable|integer|min:0|max:10',
            'format' => 'nullable|string|in:json,svg,data-uri',
        ]);

        $type = $request->type ?: 'url';
        $formattedContent = QrCodeService::formatContent($type, $request->content);
        $fgColor = $request->foreground_color ?: '#000000';
        $bgColor = $request->background_color ?: '#ffffff';
        $size = (int)($request->size ?: 300);
        $margin = (int)($request->margin ?? 2);

        $svg = QrCodeService::generateSvg($formattedContent, $size, $fgColor, $bgColor, $margin);
        $dataUri = QrCodeService::generateDataUri($formattedContent, $size, $fgColor, $bgColor, $margin);

        // Return raw SVG if format=svg
        if ($request->get('format') === 'svg') {
            return response($svg, 200, [
                'Content-Type' => 'image/svg+xml',
                'Content-Disposition' => 'inline; filename="qrcode.svg"',
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'type' => $type,
                'content' => $formattedContent,
                'size' => $size,
                'foreground_color' => $fgColor,
                'background_color' => $bgColor,
                'svg' => $svg,
                'data_uri' => $dataUri,
            ]
        ]);
    }
}
