@extends('layouts.app')

@section('title', 'Detail Penempatan')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Detail Penempatan</h2>
        <div class="flex flex-wrap gap-2">
          <a href="{{ route('hr.assignments.edit', $assignment->id) }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            <svg class="mr-2" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
          </a>
          <a href="{{ route('hr.assignments.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Kembali
          </a>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Detail Card -->
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Informasi Penempatan</h3>
          
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Anggota</span>
              <a href="{{ route('hr.members.show', $assignment->member->id) }}" class="font-medium text-primary hover:underline">{{ $assignment->member->name }}</a>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Email</span>
              <span class="font-medium text-black dark:text-white">{{ $assignment->member->email }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Divisi</span>
              <span class="font-medium text-black dark:text-white">{{ $assignment->division->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Jabatan</span>
              <span class="font-medium text-black dark:text-white">{{ $assignment->position->name ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal Penempatan</span>
              <span class="font-medium text-black dark:text-white">{{ $assignment->assigned_date }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Tanggal Selesai</span>
              <span class="font-medium text-black dark:text-white">{{ $assignment->end_date ?? '-' }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
              @switch($assignment->status)
                @case('active')
                  <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500">Aktif</span>
                  @break
                @case('completed')
                  <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">Selesai</span>
                  @break
                @case('transferred')
                  <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400">Dipindahkan</span>
                  @break
              @endswitch
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Catatan</span>
              <span class="text-right font-medium text-black dark:text-white">{{ $assignment->notes ?? '-' }}</span>
            </div>
          </div>
        </div>

        <!-- Info Card -->
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Informasi Tambahan</h3>
          
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Dibuat pada</span>
              <span class="font-medium text-black dark:text-white">{{ $assignment->created_at->format('d M Y') }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Diupdate pada</span>
              <span class="font-medium text-black dark:text-white">{{ $assignment->updated_at->format('d M Y') }}</span>
            </div>
          </div>

          @if($assignment->status == 'active')
          <div class="mt-6 border-t border-gray-200 pt-4 dark:border-gray-700">
            <h4 class="mb-3 text-sm font-semibold text-black dark:text-white">Aksi Cepat</h4>
            <form action="{{ route('hr.assignments.destroy', $assignment->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menyelesaikan penempatan ini?')">
              @csrf
              @method('DELETE')
              <button type="submit" class="w-full rounded-lg bg-green-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-green-600">
                Tandai Selesai
              </button>
            </form>
          </div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection


