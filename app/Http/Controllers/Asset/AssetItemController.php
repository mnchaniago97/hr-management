<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Exports\AssetItemsExport;
use App\Imports\AssetItemsImport;
use App\Models\Asset\AssetItem;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;

class AssetItemController extends Controller
{
    /**
     * Display a listing of the asset items.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $status = $request->get('status');
        $condition = $request->get('condition');
        $location = $request->get('location');

        $query = $this->buildFilteredQuery($search, $category, $status, $condition, $location);

        if ($request->query('json')) {
            $items = $query->orderBy('created_at', 'desc')->get();

            $stats = [
                'total' => AssetItem::count(),
                'available' => AssetItem::where('status', 'available')->count(),
                'borrowed' => AssetItem::where('status', 'borrowed')->count(),
                'maintenance' => AssetItem::where('status', 'maintenance')->count(),
            ];

            return response()->json([
                'stats' => $stats,
                'items' => $items,
            ]);
        }

        $stats = [
            'total' => AssetItem::count(),
            'available' => AssetItem::where('status', 'available')->count(),
            'borrowed' => AssetItem::where('status', 'borrowed')->count(),
            'maintenance' => AssetItem::where('status', 'maintenance')->count(),
        ];

        $items = $query->orderBy('created_at', 'desc')->paginate(15);

        $categories = AssetItem::distinct()->pluck('category')->filter()->values();
        $locations = AssetItem::distinct()->pluck('location')->filter()->values();
        $conditions = ['new', 'good', 'fair', 'poor'];
        $statuses = ['available', 'borrowed', 'maintenance', 'retired'];

        return view('asset.items.index', compact('items', 'search', 'category', 'status', 'condition', 'location', 'categories', 'locations', 'conditions', 'statuses', 'stats'));
    }

    /**
     * Show the form for creating a new asset item.
     */
    public function create(): View
    {
        $categories = ['computer', 'furniture', 'vehicle', 'equipment', 'electronics', 'other'];
        $conditions = ['new', 'good', 'fair', 'poor'];
        $statuses = ['available', 'borrowed', 'maintenance', 'retired'];

        return view('asset.items.create', compact('categories', 'conditions', 'statuses'));
    }

    /**
     * Store a newly created asset item in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:asset_items,code',
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'condition' => 'required|in:new,good,fair,poor',
            'status' => 'required|in:available,borrowed,maintenance,retired',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/items', $filename);
            $validated['image'] = $filename;
        }

        AssetItem::create($validated);

        return redirect()->route('asset.items.index')
            ->with('success', 'Asset item created successfully.');
    }

    /**
     * Display the specified asset item.
     */
    public function show(int|string $id): View
    {
        $item = AssetItem::with('assetAssignments.member')->findOrFail((int) $id);

        return view('asset.items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified asset item.
     */
    public function edit(int|string $id): View
    {
        $item = AssetItem::findOrFail((int) $id);
        $categories = ['computer', 'furniture', 'vehicle', 'equipment', 'electronics', 'other'];
        $conditions = ['new', 'good', 'fair', 'poor'];
        $statuses = ['available', 'borrowed', 'maintenance', 'retired'];

        return view('asset.items.edit', compact('item', 'categories', 'conditions', 'statuses'));
    }

    /**
     * Update the specified asset item in storage.
     */
    public function update(Request $request, int|string $id): RedirectResponse
    {
        $item = AssetItem::findOrFail((int) $id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:asset_items,code,' . (int) $id,
            'category' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'purchase_date' => 'nullable|date',
            'purchase_price' => 'nullable|numeric|min:0',
            'condition' => 'required|in:new,good,fair,poor',
            'status' => 'required|in:available,borrowed,maintenance,retired',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($item->image) {
                \Storage::delete('public/items/' . $item->image);
            }
            $image = $request->file('image');
            $filename = time() . '_' . $image->getClientOriginalName();
            $image->storeAs('public/items', $filename);
            $validated['image'] = $filename;
        }

        $item->update($validated);

        return redirect()->route('asset.items.show', (int) $id)
            ->with('success', 'Asset item updated successfully.');
    }

    /**
     * Remove the specified asset item from storage.
     */
    public function destroy(int|string $id): RedirectResponse
    {
        $item = AssetItem::findOrFail((int) $id);

        // Check if item has active assignments
        $activeAssignments = $item->assetAssignments()->where('status', 'borrowed')->count();
        if ($activeAssignments > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete item with active assignments.');
        }

        // Delete image if exists
        if ($item->image) {
            \Storage::delete('public/items/' . $item->image);
        }

        $item->delete();

        return redirect()->route('asset.items.index')
            ->with('success', 'Asset item deleted successfully.');
    }

    /**
     * Import asset items from Excel.
     */
    public function import(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'file' => 'required|file|mimes:xlsx,xls,csv|max:5120',
        ]);

        $import = new AssetItemsImport();
        Excel::import($import, $validated['file']);

        $failures = $import->failures();
        if ($failures->isNotEmpty()) {
            $messages = $failures->map(function ($failure) {
                return 'Baris ' . $failure->row() . ': ' . implode(', ', $failure->errors());
            })->values();

            return redirect()->back()
                ->with('error', 'Sebagian data gagal diimport.')
                ->with('importFailures', $messages);
        }

        return redirect()->back()
            ->with('success', 'Import data aset berhasil.');
    }

    /**
     * Export asset items to Excel.
     */
    public function export(Request $request)
    {
        $query = $this->buildFilteredQuery(
            $request->get('search'),
            $request->get('category'),
            $request->get('status'),
            $request->get('condition'),
            $request->get('location')
        );

        $filename = 'asset-items-' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new AssetItemsExport($query), $filename);
    }

    /**
     * Get available items for assignment.
     */
    public function getAvailable(): \Illuminate\Http\JsonResponse
    {
        $items = AssetItem::where('status', 'available')
            ->where('condition', '!=', 'poor')
            ->orderBy('name')
            ->get();

        return response()->json($items);
    }

    private function buildFilteredQuery(?string $search, ?string $category, ?string $status, ?string $condition, ?string $location)
    {
        $query = AssetItem::query();

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('category', $category);
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($condition) {
            $query->where('condition', $condition);
        }

        if ($location) {
            $query->where('location', 'like', "%{$location}%");
        }

        return $query;
    }
}
