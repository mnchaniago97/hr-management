@extends('layouts.app')

@section('title', 'Detail Cuti')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Detail Pengajuan Cuti</h2>
        <a href="{{ route('hr.leave-requests.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          Kembali
        </a>
      </div>

      <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Detail Card -->
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Informasi Cuti</h3>
          
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Anggota</span>
              <a href="{{ route('hr.members.show', $leaveRequest->member->id) }}" class="font-medium text-primary hover:underline">{{ $leaveRequest->member->name }}</a>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Email</span>
              <span class="font-medium text-black dark:text-white">{{ $leaveRequest->member->email }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Tipe Cuti</span>
              <span class="font-medium text-black dark:text-white">
                @switch($leaveRequest->type)
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
                    {{ $leaveRequest->type }}
                @endswitch
              </span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal Mulai</span>
              <span class="font-medium text-black dark:text-white">{{ $leaveRequest->start_date }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal Selesai</span>
              <span class="font-medium text-black dark:text-white">{{ $leaveRequest->end_date }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Durasi</span>
              <span class="font-medium text-black dark:text-white">
                {{ \Carbon\Carbon::parse($leaveRequest->start_date)->diffInDays(\Carbon\Carbon::parse($leaveRequest->end_date)) + 1 }} hari
              </span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
              @switch($leaveRequest->status)
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
            </div>
          </div>
        </div>

        <!-- Reason Card -->
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Alasan & Catatan</h3>
          
          <div class="space-y-3">
            <div>
              <span class="text-sm text-gray-500 dark:text-gray-400">Alasan</span>
              <p class="mt-1 font-medium text-black dark:text-white">{{ $leaveRequest->reason }}</p>
            </div>
            @if($leaveRequest->notes)
            <div>
              <span class="text-sm text-gray-500 dark:text-gray-400">Catatan Penolakan</span>
              <p class="mt-1 font-medium text-red-600 dark:text-red-400">{{ $leaveRequest->notes }}</p>
            </div>
            @endif
            <div class="border-t border-gray-200 pt-3 dark:border-gray-700">
              <span class="text-sm text-gray-500 dark:text-gray-400">Dibuat pada</span>
              <p class="font-medium text-black dark:text-white">{{ $leaveRequest->created_at->format('d M Y H:i') }}</p>
            </div>
          </div>

          @if($leaveRequest->status == 'pending')
          <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">
            <div class="flex gap-2">
              <form action="{{ route('hr.leave-requests.approve', $leaveRequest->id) }}" method="POST" class="flex-1">
                @csrf
                <button type="submit" class="w-full rounded-lg bg-green-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-green-600">
                  Setuju
                </button>
              </form>
              <button type="button" onclick="document.getElementById('rejectModal').classList.remove('hidden')" class="flex-1 rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-red-600">
                Tolak
              </button>
            </div>
          </div>
          @endif
        </div>
      </div>

      <!-- Reject Modal -->
      @if($leaveRequest->status == 'pending')
      <div id="rejectModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-50">
        <div class="w-full max-w-md rounded-lg bg-white p-6 dark:bg-boxdark">
          <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Tolak Permintaan Cuti</h3>
          <form action="{{ route('hr.leave-requests.reject', $leaveRequest->id) }}" method="POST">
            @csrf
            <div class="mb-4">
              <label for="rejection_reason" class="mb-2 block text-sm font-medium text-black dark:text-white">
                Alasan Penolakan <span class="text-red-500">*</span>
              </label>
              <textarea 
                id="rejection_reason" 
                name="rejection_reason" 
                rows="3"
                class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-black focus:border-primary focus:outline-none focus:ring-1 focus:ring-primary dark:border-gray-600 dark:bg-boxdark dark:text-white"
                required
              ></textarea>
            </div>
            <div class="flex justify-end gap-2">
              <button type="button" onclick="document.getElementById('rejectModal').classList.add('hidden')" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
                Batal
              </button>
              <button type="submit" class="inline-flex items-center rounded-lg bg-red-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-red-600">
                Tolak
              </button>
            </div>
          </form>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection


