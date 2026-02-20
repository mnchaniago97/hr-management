<?php

namespace App\Http\Controllers;

use App\Models\HealthServiceRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HealthServiceRequestController extends Controller
{
    public function publicForm(): View
    {
        return view('public.health-service-request', ['title' => 'Permohonan Layanan Kesehatan']);
    }

    public function publicStore(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'requester_name' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'phone' => 'required|string|max:30',
            'email' => 'nullable|email|max:255',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'start_time' => 'nullable|date_format:H:i',
            'end_time' => 'nullable|date_format:H:i|after:start_time',
            'participants' => 'nullable|integer|min:1',
            'notes' => 'nullable|string',
        ]);

        HealthServiceRequest::create($validated + ['status' => 'pending']);

        return redirect()->back()->with('success', 'Permohonan layanan kesehatan berhasil dikirim.');
    }

    public function index(): View
    {
        $requests = HealthServiceRequest::orderByDesc('created_at')->paginate(20);
        $statuses = ['pending', 'approved', 'rejected', 'completed'];

        return view('health.requests.index', compact('requests', 'statuses'));
    }

    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,approved,rejected,completed',
        ]);

        $requestRecord = HealthServiceRequest::findOrFail($id);
        $requestRecord->update(['status' => $validated['status']]);

        return redirect()->back()->with('success', 'Status permohonan diperbarui.');
    }
}
