<?php

namespace App\Services\Asset;

use App\Models\Asset\AssetItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Pagination\LengthAwarePaginator;

class AssetItemService
{
    /**
     * Get all asset items with filtering and pagination.
     */
    public function getAll(Request $request): LengthAwarePaginator
    {
        $query = AssetItem::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category = $request->get('category')) {
            $query->where('category', $category);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        if ($condition = $request->get('condition')) {
            $query->where('condition', $condition);
        }

        if ($location = $request->get('location')) {
            $query->where('location', 'like', "%{$location}%");
        }

        return $query->orderBy('created_at', 'desc')->paginate(15);
    }

    /**
     * Create a new asset item.
     */
    public function create(array $data): AssetItem
    {
        if (isset($data['image'])) {
            $data['image'] = $this->handleImageUpload($data['image']);
        }

        return AssetItem::create($data);
    }

    /**
     * Update an existing asset item.
     */
    public function update(AssetItem $item, array $data): AssetItem
    {
        if (isset($data['image'])) {
            if ($item->image) {
                Storage::delete('public/items/' . $item->image);
            }
            $data['image'] = $this->handleImageUpload($data['image']);
        }

        $item->update($data);
        return $item;
    }

    /**
     * Delete an asset item.
     */
    public function delete(AssetItem $item): bool
    {
        if ($item->assetAssignments()->where('status', 'borrowed')->count() > 0) {
            return false;
        }

        if ($item->image) {
            Storage::delete('public/items/' . $item->image);
        }

        $item->delete();
        return true;
    }

    /**
     * Get available items for assignment.
     */
    public function getAvailable(): \Illuminate\Database\Eloquent\Collection
    {
        return AssetItem::where('status', 'available')
            ->where('condition', '!=', 'poor')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get asset statistics.
     */
    public function getStats(): array
    {
        return [
            'total' => AssetItem::count(),
            'available' => AssetItem::where('status', 'available')->count(),
            'borrowed' => AssetItem::where('status', 'borrowed')->count(),
            'maintenance' => AssetItem::where('status', 'maintenance')->count(),
            'retired' => AssetItem::where('status', 'retired')->count(),
        ];
    }

    /**
     * Handle image upload.
     */
    private function handleImageUpload($image): string
    {
        $filename = time() . '_' . $image->getClientOriginalName();
        $image->storeAs('public/items', $filename);
        return $filename;
    }
}
