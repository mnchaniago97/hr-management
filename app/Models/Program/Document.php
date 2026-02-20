<?php

namespace App\Models\Program;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Document extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'program_documents';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'activity_id',
        'program_id',
        'type',
        'file_path',
        'file_name',
        'uploaded_by',
        'uploaded_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'activity_id' => 'integer',
        'program_id' => 'integer',
        'uploaded_by' => 'integer',
        'uploaded_at' => 'datetime',
    ];

    /**
     * Document types.
     */
    public const TYPE_PROPOSAL = 'proposal';
    public const TYPE_LPJ = 'lpj';           // Laporan Pertanggung Jawaban
    public const TYPE_TOR = 'tor';           // Term of Reference
    public const TYPE_ATTENDANCE = 'attendance';
    public const TYPE_PHOTO = 'photo';
    public const TYPE_REPORT = 'report';

    /**
     * Get the activity that owns this document.
     */
    public function activity(): BelongsTo
    {
        return $this->belongsTo(Activity::class, 'activity_id');
    }

    /**
     * Get the program that owns this document.
     */
    public function program(): BelongsTo
    {
        return $this->belongsTo(Program::class, 'program_id');
    }

    /**
     * Get the user who uploaded this document.
     */
    public function uploader(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'uploaded_by');
    }

    /**
     * Scope to get documents by type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope to get documents for an activity.
     */
    public function scopeForActivity($query, int $activityId)
    {
        return $query->where('activity_id', $activityId);
    }

    /**
     * Scope to get documents for a program.
     */
    public function scopeForProgram($query, int $programId)
    {
        return $query->where('program_id', $programId);
    }

    /**
     * Get the file extension.
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    /**
     * Get the file size in human readable format.
     */
    public function getFileSizeFormattedAttribute(): string
    {
        $path = \Storage::disk('public')->path($this->file_path);
        if (!file_exists($path)) {
            return 'File not found';
        }

        $bytes = filesize($path);

        if ($bytes >= 1073741824) {
            return number_format($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return number_format($bytes / 1024, 2) . ' KB';
        }

        return $bytes . ' B';
    }

    /**
     * Check if document is an image.
     */
    public function isImage(): bool
    {
        return in_array($this->extension, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
    }

    /**
     * Check if document is a PDF.
     */
    public function isPdf(): bool
    {
        return strtolower($this->extension) === 'pdf';
    }

    /**
     * Get available types.
     */
    public static function getAvailableTypes(): array
    {
        return [
            self::TYPE_PROPOSAL,
            self::TYPE_LPJ,
            self::TYPE_TOR,
            self::TYPE_ATTENDANCE,
            self::TYPE_PHOTO,
            self::TYPE_REPORT,
        ];
    }

    /**
     * Get document type label.
     */
    public static function getTypeLabel(string $type): string
    {
        return match ($type) {
            self::TYPE_PROPOSAL => 'Proposal',
            self::TYPE_LPJ => 'LPJ',
            self::TYPE_TOR => 'TOR',
            self::TYPE_ATTENDANCE => 'Absensi',
            self::TYPE_PHOTO => 'Foto',
            self::TYPE_REPORT => 'Laporan',
            default => $type,
        };
    }
}
