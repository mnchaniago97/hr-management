<?php

namespace App\Services\Program;

use App\Models\Program\Activity;
use App\Models\Program\Document;
use App\Models\Program\Program;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentService
{
    /**
     * Get all documents with optional filtering.
     */
    public function getAll(array $filters = []): Collection|LengthAwarePaginator
    {
        $query = Document::query()->with(['activity', 'program', 'uploader']);

        if (isset($filters['activity_id'])) {
            $query->forActivity($filters['activity_id']);
        }

        if (isset($filters['program_id'])) {
            $query->forProgram($filters['program_id']);
        }

        if (isset($filters['type'])) {
            $query->ofType($filters['type']);
        }

        if (isset($filters['search'])) {
            $query->where('file_name', 'like', '%' . $filters['search'] . '%');
        }

        $sortBy = $filters['sort_by'] ?? 'uploaded_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortBy, $sortOrder);

        if (isset($filters['per_page'])) {
            return $query->paginate($filters['per_page']);
        }

        return $query->get();
    }

    /**
     * Get document by ID.
     */
    public function getById(int $id): ?Document
    {
        return Document::with(['activity', 'program', 'uploader'])->find($id);
    }

    /**
     * Upload a new document.
     */
    public function upload(array $data, $file): Document
    {
        $originalName = $file->getClientOriginalName();
        $extension = $file->getClientOriginalExtension();
        
        // Generate unique filename
        $fileName = Str::uuid() . '.' . $extension;
        
        // Store file
        $path = $file->storeAs('program-documents', $fileName, 'public');

        $document = Document::create([
            'activity_id' => $data['activity_id'] ?? null,
            'program_id' => $data['program_id'] ?? null,
            'type' => $data['type'],
            'file_path' => $path,
            'file_name' => $originalName,
            'uploaded_by' => $data['uploaded_by'] ?? auth()->id(),
            'uploaded_at' => now(),
        ]);

        return $document->load(['activity', 'program', 'uploader']);
    }

    /**
     * Update document metadata.
     */
    public function update(Document $document, array $data): Document
    {
        $document->update($data);

        return $document->fresh(['activity', 'program', 'uploader']);
    }

    /**
     * Delete a document.
     */
    public function delete(Document $document): bool
    {
        // Delete the file from storage
        if (Storage::disk('public')->exists($document->file_path)) {
            Storage::disk('public')->delete($document->file_path);
        }

        return $document->delete();
    }

    /**
     * Get documents for an activity.
     */
    public function getByActivity(Activity $activity): Collection
    {
        return Document::forActivity($activity->id)
            ->with(['uploader'])
            ->orderBy('uploaded_at', 'desc')
            ->get();
    }

    /**
     * Get documents for a program.
     */
    public function getByProgram(Program $program): Collection
    {
        return Document::forProgram($program->id)
            ->with(['uploader'])
            ->orderBy('uploaded_at', 'desc')
            ->get();
    }

    /**
     * Get documents by type.
     */
    public function getByType(string $type): Collection
    {
        return Document::ofType($type)
            ->with(['activity', 'program', 'uploader'])
            ->orderBy('uploaded_at', 'desc')
            ->get();
    }

    /**
     * Get proposal documents.
     */
    public function getProposals(?int $activityId = null, ?int $programId = null): Collection
    {
        $query = Document::ofType(Document::TYPE_PROPOSAL)->with(['uploader']);

        if ($activityId) {
            $query->forActivity($activityId);
        }

        if ($programId) {
            $query->forProgram($programId);
        }

        return $query->orderBy('uploaded_at', 'desc')->get();
    }

    /**
     * Get LPJ documents.
     */
    public function getLpjs(?int $activityId = null, ?int $programId = null): Collection
    {
        $query = Document::ofType(Document::TYPE_LPJ)->with(['uploader']);

        if ($activityId) {
            $query->forActivity($activityId);
        }

        if ($programId) {
            $query->forProgram($programId);
        }

        return $query->orderBy('uploaded_at', 'desc')->get();
    }

    /**
     * Get TOR documents.
     */
    public function getTors(?int $activityId = null, ?int $programId = null): Collection
    {
        $query = Document::ofType(Document::TYPE_TOR)->with(['uploader']);

        if ($activityId) {
            $query->forActivity($activityId);
        }

        if ($programId) {
            $query->forProgram($programId);
        }

        return $query->orderBy('uploaded_at', 'desc')->get();
    }

    /**
     * Get attendance documents.
     */
    public function getAttendances(?int $activityId = null): Collection
    {
        $query = Document::ofType(Document::TYPE_ATTENDANCE)->with(['uploader']);

        if ($activityId) {
            $query->forActivity($activityId);
        }

        return $query->orderBy('uploaded_at', 'desc')->get();
    }

    /**
     * Get photo documents.
     */
    public function getPhotos(?int $activityId = null): Collection
    {
        $query = Document::ofType(Document::TYPE_PHOTO)->with(['uploader']);

        if ($activityId) {
            $query->forActivity($activityId);
        }

        return $query->orderBy('uploaded_at', 'desc')->get();
    }

    /**
     * Generate download URL for a document.
     */
    public function getDownloadUrl(Document $document): ?string
    {
        if (!Storage::disk('public')->exists($document->file_path)) {
            return null;
        }

        return Storage::disk('public')->url($document->file_path);
    }

    /**
     * Check if document file exists.
     */
    public function fileExists(Document $document): bool
    {
        return Storage::disk('public')->exists($document->file_path);
    }

    /**
     * Get document statistics.
     */
    public function getStatistics(?int $activityId = null, ?int $programId = null): array
    {
        $query = Document::query();

        if ($activityId) {
            $query->forActivity($activityId);
        }

        if ($programId) {
            $query->forProgram($programId);
        }

        $typeCounts = [];
        foreach (Document::getAvailableTypes() as $type) {
            $typeCounts[$type] = $query->clone()->ofType($type)->count();
        }

        return [
            'total' => $query->count(),
            'by_type' => $typeCounts,
        ];
    }
}
