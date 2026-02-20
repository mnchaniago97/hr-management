<?php

namespace App\Http\Controllers\Asset;

use App\Http\Controllers\Controller;
use App\Models\Asset\MaintenanceRecord;
use App\Models\Asset\AssetItem;
use App\Models\Asset\Vendor;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Carbon\Carbon;

class MaintenanceController extends Controller
{
    /**
     * Display a listing of the maintenance records.
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status');
        $type = $request->get('type');
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');

        $query = MaintenanceRecord::with(['item', 'vendor']);

        if ($search) {
            $query->whereHas('item', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('code', 'like', "%{$search}%");
            });
        }

        if ($status) {
            $query->where('status', $status);
        }

        if ($type) {
            $query->where('type', $type);
        }

        if ($dateFrom) {
            $query->whereDate('maintenance_date', '>=', $dateFrom);
        }

        if ($dateTo) {
            $query->whereDate('maintenance_date', '<=', $dateTo);
        }

        $records = $query->orderBy('maintenance_date', 'desc')->paginate(15);

        $items = AssetItem::orderBy('name')->get();
        $vendors = Vendor::where('is_active', true)->orderBy('name')->get();
        $statuses = ['pending', 'in_progress', 'completed', 'cancelled'];
        $types = ['preventive', 'corrective', 'inspection', 'upgrade'];

        return view('asset.maintenance.index', compact('records', 'search', 'status', 'type', 'dateFrom', 'dateTo', 'items', 'vendors', 'statuses', 'types'));
    }

    /**
     * Show the form for creating a new maintenance record.
     */
    public function create(): View
    {
        $items = AssetItem::orderBy('name')->get();
        $vendors = Vendor::where('is_active', true)->orderBy('name')->get();
        $statuses = ['pending', 'in_progress', 'completed', 'cancelled'];
        $types = ['preventive', 'corrective', 'inspection', 'upgrade'];

        return view('asset.maintenance.create', compact('items', 'vendors', 'statuses', 'types'));
    }

    /**
     * Store a newly created maintenance record in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'item_id' => 'required|exists:asset_items,id',
            'type' => 'required|in:preventive,corrective,inspection,upgrade',
            'maintenance_date' => 'required|date',
            'scheduled_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'cost' => 'nullable|numeric|min:0',
            'vendor_id' => 'nullable|exists:asset_vendors,id',
            'description' => 'required|string',
            'performed_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $record = MaintenanceRecord::create($validated);

        // Update item status to maintenance if needed
        if ($validated['status'] === 'in_progress' || $validated['status'] === 'pending') {
            AssetItem::findOrFail($validated['item_id'])->update(['status' => 'maintenance']);
        }

        return redirect()->route('asset.maintenance.show', $record->id)
            ->with('success', 'Maintenance record created successfully.');
    }

    /**
     * Display the specified maintenance record.
     */
    public function show(int $id): View
    {
        $record = MaintenanceRecord::with(['item', 'vendor'])->findOrFail($id);

        return view('asset.maintenance.show', compact('record'));
    }

    /**
     * Show the form for editing the specified maintenance record.
     */
    public function edit(int $id): View
    {
        $record = MaintenanceRecord::findOrFail($id);
        $items = AssetItem::orderBy('name')->get();
        $vendors = Vendor::where('is_active', true)->orderBy('name')->get();
        $statuses = ['pending', 'in_progress', 'completed', 'cancelled'];
        $types = ['preventive', 'corrective', 'inspection', 'upgrade'];

        return view('asset.maintenance.edit', compact('record', 'items', 'vendors', 'statuses', 'types'));
    }

    /**
     * Update the specified maintenance record in storage.
     */
    public function update(Request $request, int $id): RedirectResponse
    {
        $record = MaintenanceRecord::findOrFail($id);

        $validated = $request->validate([
            'item_id' => 'required|exists:asset_items,id',
            'type' => 'required|in:preventive,corrective,inspection,upgrade',
            'maintenance_date' => 'required|date',
            'scheduled_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,cancelled',
            'cost' => 'nullable|numeric|min:0',
            'vendor_id' => 'nullable|exists:asset_vendors,id',
            'description' => 'required|string',
            'performed_by' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);

        $record->update($validated);

        // Update item status based on maintenance completion
        if ($validated['status'] === 'completed') {
            $record->item->update(['status' => 'available']);
        } elseif ($validated['status'] === 'in_progress' || $validated['status'] === 'pending') {
            $record->item->update(['status' => 'maintenance']);
        }

        return redirect()->route('asset.maintenance.show', $id)
            ->with('success', 'Maintenance record updated successfully.');
    }

    /**
     * Remove the specified maintenance record from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        $record = MaintenanceRecord::findOrFail($id);

        // Restore item status if needed
        if ($record->item->status === 'maintenance') {
            $record->item->update(['status' => 'available']);
        }

        $record->delete();

        return redirect()->route('asset.maintenance.index')
            ->with('success', 'Maintenance record deleted successfully.');
    }

    /**
     * Display upcoming maintenance.
     */
    public function upcoming(): View
    {
        $records = MaintenanceRecord::with('item')
            ->where('status', 'pending')
            ->whereDate('scheduled_date', '>=', Carbon::now()->toDateString())
            ->orderBy('scheduled_date', 'asc')
            ->paginate(15);

        return view('asset.maintenance.upcoming', compact('records'));
    }

    /**
     * Display maintenance history for an item.
     */
    public function itemHistory(int $itemId): View
    {
        $item = AssetItem::findOrFail($itemId);
        $records = MaintenanceRecord::where('item_id', $itemId)
            ->orderBy('maintenance_date', 'desc')
            ->paginate(15);

        return view('asset.maintenance.item-history', compact('records', 'item'));
    }
}
