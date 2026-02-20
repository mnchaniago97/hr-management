<?php

namespace App\Services\Hr;

use App\Models\Hr\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class MemberService
{
    /**
     * Get all members with filtering and pagination.
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $query = Member::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('position', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($department = $request->get('department')) {
            $query->where('department', $department);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    /**
     * Create a new member.
     */
    public function create(array $data): Member
    {
        if (isset($data['photo'])) {
            $data['photo'] = $this->handlePhotoUpload($data['photo']);
        }

        return Member::create($data);
    }

    /**
     * Update an existing member.
     */
    public function update(Member $member, array $data): Member
    {
        if (isset($data['photo'])) {
            // Delete old photo
            if ($member->photo) {
                Storage::delete('public/members/' . $member->photo);
            }
            $data['photo'] = $this->handlePhotoUpload($data['photo']);
        }

        $member->update($data);
        return $member;
    }

    /**
     * Delete a member.
     */
    public function delete(Member $member): void
    {
        if ($member->photo) {
            Storage::delete('public/members/' . $member->photo);
        }
        $member->delete();
    }

    /**
     * Get member statistics.
     */
    public function getStats(): array
    {
        return [
            'total' => Member::count(),
            'active' => Member::where('status', 'active')->count(),
            'inactive' => Member::where('status', 'inactive')->count(),
            'on_leave' => Member::where('status', 'on_leave')->count(),
        ];
    }

    /**
     * Handle photo upload.
     */
    private function handlePhotoUpload($photo): string
    {
        $filename = time() . '_' . $photo->getClientOriginalName();
        $photo->storeAs('public/members', $filename);
        return $filename;
    }
}
