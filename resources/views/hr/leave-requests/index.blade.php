@extends('layouts.app')

@section('title', 'Cuti')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Daftar Pengajuan Cuti</h2>
          <p class="text-sm text-gray-500 mt-1">Kelola pengajuan cuti anggota.</p>
        </div>
        <a href="{{ route('hr.leave-requests.create') }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
          <svg class="mr-2" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 4V20M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
          Ajukan Cuti
        </a>
      </div>

      <!-- Filters -->
      <form method="GET" action="{{ route('hr.leave-requests.index') }}" class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pencarian</label>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari anggota...">
          </div>
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status</label>
            <select name="status">
              <option value="">Semua Status</option>
              <option value="pending" {{ ($status ?? '') == 'pending' ? 'selected' : '' }}>Menunggu</option>
              <option value="approved" {{ ($status ?? '') == 'approved' ? 'selected' : '' }}>Disetujui</option>
              <option value="rejected" {{ ($status ?? '') == 'rejected' ? 'selected' : '' }}>Ditolak</option>
            </select>
          </div>
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tipe</label>
            <select name="type">
              <option value="">Semua Tipe</option>
              <option value="annual" {{ ($type ?? '') == 'annual' ? 'selected' : '' }}>Cuti Tahunan</option>
              <option value="sick" {{ ($type ?? '') == 'sick' ? 'selected' : '' }}>Cuti Sakit</option>
              <option value="personal" {{ ($type ?? '') == 'personal' ? 'selected' : '' }}>Cuti Pribadi</option>
              <option value="maternity" {{ ($type ?? '') == 'maternity' ? 'selected' : '' }}>Cuti Melahirkan</option>
              <option value="paternity" {{ ($type ?? '') == 'paternity' ? 'selected' : '' }}>Cuti Ayah</option>
              <option value="unpaid" {{ ($type ?? '') == 'unpaid' ? 'selected' : '' }}>Cuti Tanpa Gaji</option>
            </select>
          </div>
        </div>
        <div class="mt-4 flex flex-wrap items-center justify-end gap-3">
          <a href="{{ route('hr.leave-requests.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Reset
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">Cari</button>
        </div>
      </form>

      <!-- Table -->
      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
          <table class="w-full min-w-[1000px]">
            <thead>
              <tr class="border-b border-gray-100 dark:border-gray-800">
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Anggota</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tipe Cuti</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Mulai</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Selesai</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Durasi</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p>
                </th>
                <th class="px-5 py-3 text-center sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aksi</p>
                </th>
              </tr>
            </thead>
            <tbody>
              @forelse($leaveRequests ?? [] as $index => $request)
              <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $index + 1 }}</td>
                <td class="px-5 py-4 text-theme-sm font-medium text-gray-800 dark:text-white/90 sm:px-6">
                  <a href="{{ route('hr.members.show', $request->member->id) }}" class="hover:text-primary">{{ $request->member->name }}</a>
                </td>
                <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">
                  @switch($request->type)
                    @case('annual')
                      Cuti Tahunan
                      @break
                    @case('sick')
                      Cuti Sakit
                      @break
                    @case('personal')
                      Cuti Pribadi
                      @break
                    @case('maternity')
                      Cuti Melahirkan
                      @break
                    @case('paternity')
                      Cuti Ayah
                      @break
                    @case('unpaid')
                      Cuti Tanpa Gaji
                      @break
                    @default
                      {{ $request->type }}
                  @endswitch
                </td>
                <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $request->start_date }}</td>
                <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $request->end_date }}</td>
                <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">
                  {{ \Carbon\Carbon::parse($request->start_date)->diffInDays(\Carbon\Carbon::parse($request->end_date)) + 1 }} hari
                </td>
                <td class="px-5 py-4 text-theme-sm sm:px-6">
                  @switch($request->status)
                    @case('pending')
                      <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400">Menunggu</span>
                      @break
                    @case('approved')
                      <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500">Disetujui</span>
                      @break
                    @case('rejected')
                      <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500">Ditolak</span>
                      @break
                  @endswitch
                </td>
                <td class="px-5 py-4 text-center sm:px-6">
                  <div class="flex items-center justify-center gap-2">
                    <a href="{{ route('hr.leave-requests.show', $request->id) }}" class="text-blue-600 hover:text-blue-800">
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                    </a>
                    @if($request->status == 'pending')
                    <form action="{{ route('hr.leave-requests.approve', $request->id) }}" method="POST" class="inline">
                      @csrf
                      <button type="submit" class="text-green-600 hover:text-green-800" title="Setuju">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                      </button>
                    </form>
                    @endif
                  </div>
                </td>
              </tr>
              @empty
              <tr class="border-b border-gray-100 dark:border-gray-800">
                <td colspan="8" class="px-5 py-8 text-center text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">
                  Data tidak tersedia. Silakan tambahkan data cuti terlebih dahulu.
                </td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      @if(isset($leaveRequests) && $leaveRequests->hasPages())
      <div class="mt-4 flex items-center justify-between">
        <p class="text-sm text-gray-500">Menampilkan {{ $leaveRequests->firstItem() }} - {{ $leaveRequests->lastItem() }} dari {{ $leaveRequests->total() }} data</p>
        <div class="flex gap-2">
          {{ $leaveRequests->links('pagination::tailwind') }}
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection


