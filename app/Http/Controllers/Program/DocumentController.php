<?php

namespace App\Http\Controllers\Program;

use App\Http\Controllers\Controller;
use App\Models\Program\Activity;
use App\Models\Program\Document;
use App\Models\Program\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DocumentController extends Controller
{
    /**
     * Display a listing of the documents.
     */
    public function index(Request $request): View|JsonResponse
    {
        $query = Document::query()->with(['activity', 'program', 'uploader']);

        if ($request->has('activity_id')) {
            $query->forActivity($request->integer('activity_id'));
        }

        if ($request->has('program_id')) {
            $query->forProgram($request->integer('program_id'));
        }

        if ($request->has('type')) {
            $query->ofType($request->input('type'));
        }

        $documents = $query->orderBy('uploaded_at', 'desc')->get();

        if ($request->wantsJson() || $request->query('json')) {
            return response()->json([
                'success' => true,
                'data' => $documents,
            ]);
        }

        return view('program.documents.index', compact('documents'));
    }

    /**
     * Show the form for uploading a document.
     */
    public function create(): View
    {
        return view('program.documents.upload');
    }

    /**
     * Store a newly uploaded document.
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'activity_id' => 'nullable|exists:program_activities,id',
            'program_id' => 'nullable|exists:programs,id',
            'type' => 'required|string|in:' . implode(',', Document::getAvailableTypes()),
            'file' => 'required|file|max:10240', // 10MB max
        ]);

        // At least one of activity_id or program_id must be provided
        if (!$validated['activity_id'] && !$validated['program_id']) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal salah satu dari activity_id atau program_id harus diisi',
            ], 422);
        }

        $file = $request->file('file');
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        
        // Generate unique filename
        $fileName = Str::uuid() . '.' . $extension;
        
        // Store file
        $path = $file->storeAs('program-documents', $fileName, 'public');

        $document = Document::create([
            'activity_id' => $validated['activity_id'] ?? null,
            'program_id' => $validated['program_id'] ?? null,
            'type' => $validated['type'],
            'file_path' => $path,
            'file_name' => $originalName,
            'uploaded_by' => auth()->id(),
            'uploaded_at' => now(),
        ]);

        if ($request->wantsJson() || $request->query('json')) {
            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diupload',
                'data' => $document->load(['activity', 'program', 'uploader']),
            ], 201);
        }

        return redirect()->route('program.documents.index')
            ->with('success', 'Dokumen berhasil diupload');
    }

    /**
     * Display the specified document.
     */
    public function show(Document $document): View|JsonResponse
    {
        $document->load(['activity', 'program', 'uploader']);

        if (request()->wantsJson() || request()->query('json')) {
            return response()->json([
                'success' => true,
                'data' => $document,
            ]);
        }

        return view('program.documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified document.
     */
    public function edit(Document $document): View
    {
        return view('program.documents.edit', compact('document'));
    }

    /**
     * Update the specified document metadata.
     */
    public function update(Request $request, Document $document): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'type' => 'sometimes|string|in:' . implode(',', Document::getAvailableTypes()),
        ]);

        $document->update($validated);

        if ($request->wantsJson() || $request->query('json')) {
            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil diperbarui',
                'data' => $document->fresh(['activity', 'program', 'uploader']),
            ]);
        }

        return redirect()->route('program.documents.show', $document->id)
            ->with('success', 'Dokumen berhasil diperbarui');
    }

    /**
     * Remove the specified document.
     */
    public function destroy(Document $document): JsonResponse|RedirectResponse
    {
        // Delete the file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        $document->delete();

        if (request()->wantsJson() || request()->query('json')) {
            return response()->json([
                'success' => true,
                'message' => 'Dokumen berhasil dihapus',
            ]);
        }

        return redirect()->route('program.documents.index')
            ->with('success', 'Dokumen berhasil dihapus');
    }

    /**
     * Download the specified document.
     */
    public function download(Document $document): JsonResponse|RedirectResponse
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            if (request()->wantsJson() || request()->query('json')) {
                return response()->json([
                    'success' => false,
                    'message' => 'File tidak ditemukan',
                ], 404);
            }

            return redirect()->back()
                ->with('error', 'File tidak ditemukan');
        }

        if (request()->wantsJson() || request()->query('json')) {
            return response()->json([
                'success' => true,
                'download_url' => Storage::disk('public')->url($document->file_path),
                'file_name' => $document->file_name,
            ]);
        }

        return redirect()->away(Storage::disk('public')->url($document->file_path));
    }

    /**
     * Get documents for an activity.
     */
    public function byActivity(Activity $activity): JsonResponse
    {
        $documents = Document::forActivity($activity->id)
            ->with(['uploader'])
            ->orderBy('uploaded_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $documents,
        ]);
    }

    /**
     * Get documents for a program.
     */
    public function byProgram(Program $program): JsonResponse
    {
        $documents = Document::forProgram($program->id)
            ->with(['uploader'])
            ->orderBy('uploaded_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $documents,
        ]);
    }

    /**
     * Get documents by type.
     */
    public function byType(Request $request): JsonResponse
    {
        $type = $request->input('type');
        
        $documents = Document::ofType($type)
            ->with(['activity', 'program', 'uploader'])
            ->orderBy('uploaded_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $documents,
        ]);
    }
}
