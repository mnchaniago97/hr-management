@extends('layouts.app')

@section('content')
<div class="rounded-[28px] border border-white/70 bg-white/90 p-6 shadow-sm backdrop-blur">
  <div class="mb-6 flex flex-wrap items-center justify-between gap-3">
    <div>
      <h1 class="text-xl font-semibold text-gray-900">Permohonan Layanan Tim Kesehatan</h1>
      <p class="mt-1 text-sm text-gray-500">Kelola permintaan layanan kesehatan yang masuk.</p>
    </div>
    <span class="inline-flex items-center rounded-full bg-[#c7b9ff] px-3 py-1 text-xs font-semibold text-[#1f2140]">
      Admin
    </span>
  </div>

  @if (session('success'))
    <div class="mb-4 rounded-lg border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700">
      {{ session('success') }}
    </div>
  @endif

  <div class="overflow-x-auto">
    <table class="w-full text-left text-sm">
      <thead>
        <tr class="border-b border-gray-200 text-xs font-semibold uppercase tracking-wide text-gray-500">
          <th class="px-4 py-3">Pemohon</th>
          <th class="px-4 py-3">Instansi</th>
          <th class="px-4 py-3">Tanggal</th>
          <th class="px-4 py-3">Lokasi</th>
          <th class="px-4 py-3">Status</th>
          <th class="px-4 py-3 text-right">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($requests as $request)
          <tr class="border-b border-gray-100">
            <td class="px-4 py-3">
              <div class="font-medium text-gray-900">{{ $request->requester_name }}</div>
              <div class="text-xs text-gray-500">{{ $request->phone }}</div>
            </td>
            <td class="px-4 py-3 text-gray-700">{{ $request->institution }}</td>
            <td class="px-4 py-3 text-gray-700">
              {{ optional($request->event_date)->format('d M Y') ?? '-' }}
            </td>
            <td class="px-4 py-3 text-gray-700">{{ $request->location }}</td>
            <td class="px-4 py-3">
              <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-1 text-xs font-semibold text-gray-700">
                {{ ucfirst($request->status) }}
              </span>
            </td>
            <td class="px-4 py-3 text-right">
              <form method="POST" action="{{ route('health.requests.update-status', $request->id) }}" class="inline-flex items-center gap-2">
                @csrf
                <select name="status" class="rounded-full border border-gray-200 bg-white px-3 py-1.5 text-xs text-gray-700">
                  @foreach ($statuses as $status)
                    <option value="{{ $status }}" @selected($request->status === $status)>{{ ucfirst($status) }}</option>
                  @endforeach
                </select>
                <button type="submit" class="rounded-full bg-[#2f64f6] px-3 py-1.5 text-xs font-medium text-white hover:bg-[#2554d4]">
                  Simpan
                </button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="px-4 py-6 text-center text-gray-500">Belum ada permohonan.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-4">
    {{ $requests->links('pagination::tailwind') }}
  </div>
</div>
@endsection
