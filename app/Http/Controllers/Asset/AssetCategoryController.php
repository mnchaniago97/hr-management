<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\Asset\AssetCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AssetCategoryController extends Controller
{
    /**
     * Display a listing of the asset categories.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');

        $query = AssetCategory::with('parent');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $categories = $query->orderBy('name')->paginate(15);
        $parentCategories = AssetCategory::whereNull('parent_id')->orderBy('name')->get();

        return view('asset.categories.index', compact('categories', 'search', 'parentCategories'));
    }

    /**
     * Show the form for creating a new category.
     */
    public function create(): View
    {
        $parentCategories = AssetCategory::whereNull('parent_id')->orderBy('name')->get();

        return view('asset.categories.create', compact('parentCategories'));
    }

    /**
     * Store a newly created category in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:asset_categories,name',
            'code' => 'required|string|max:50|unique:asset_categories,code',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:asset_categories,id',
            'is_active' => 'boolean',
        ]);

        AssetCategory::create($validated);

        return redirect()->route('asset.categories.index')
            ->with('success', 'Category created successfully.');
    }

    /**
     * Display the specified category.
     */
    public function show(int $id): View
    {
        $category = AssetCategory::with(['parent', 'subcategories', 'assetItems'])->findOrFail($id);

        return view('asset.categories.show', compact('category'));
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit(int $id): View
    {
        $category = AssetCategory::findOrFail($id);
        $parentCategories = AssetCategory::whereNull('parent_id')
            ->where('id', '!=', $id)
            ->orderBy('name')
            ->get();

        return view('asset.categories.edit', compact('category', 'parentCategories'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $category = AssetCategory::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:asset_categories,name,' . $id,
            'code' => 'required|string|max:50|unique:asset_categories,code,' . $id,
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:asset_categories,id',
            'is_active' => 'boolean',
        ]);

        $category->update($validated);

        return redirect()->route('asset.categories.show', $id)
            ->with('success', 'Category updated successfully.');
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $category = AssetCategory::findOrFail($id);

        // Check if category has subcategories
        if ($category->subcategories()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with subcategories.');
        }

        // Check if category has items
        if ($category->assetItems()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Cannot delete category with asset items.');
        }

        $category->delete();

        return redirect()->route('asset.categories.index')
            ->with('success', 'Category deleted successfully.');
    }
}
