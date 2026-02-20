@extends('layouts.app')

@section('title', 'Sertifikasi')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Daftar Sertifikasi</h2>
          <p class="text-sm text-gray-500 mt-1">Kelola data sertifikasi anggota.</p>
        </div>
        <a href="{{ route('hr.certifications.create') }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
          Tambah Sertifikasi
        </a>
      </div>

      <form method="GET" action="{{ route('hr.certifications.index') }}" class="mb-6">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pencarian</label>
            <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Cari sertifikasi...">
          </div>
          <div>
            <label class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status</label>
            <select name="status">
          <option value="">Semua Status</option>
          <option value="active" {{ ($status ?? '') == 'active' ? 'selected' : '' }}>Aktif</option>
          <option value="expired" {{ ($status ?? '') == 'expired' ? 'selected' : '' }}>Kedaluwarsa</option>
          <option value="revoked" {{ ($status ?? '') == 'revoked' ? 'selected' : '' }}>Dicabut</option>
        </select>
          </div>
        </div>
        <div class="mt-4 flex flex-wrap items-center justify-end gap-3">
          <a href="{{ route('hr.certifications.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Reset
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">Cari</button>
        </div>
      </form>

      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
          <table class="w-full min-w-[900px]">
            <thead>
              <tr class="border-b border-gray-100 dark:border-gray-800">
                <th class="px-5 py-3 text-left sm:px-6"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</p></th>
                <th class="px-5 py-3 text-left sm:px-6"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama Sertifikasi</p></th>
                <th class="px-5 py-3 text-left sm:px-6"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Anggota</p></th>
                <th class="px-5 py-3 text-left sm:px-6"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Penyedia</p></th>
                <th class="px-5 py-3 text-left sm:px-6"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tanggal Terbit</p></th>
                <th class="px-5 py-3 text-left sm:px-6"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tanggal Expired</p></th>
                <th class="px-5 py-3 text-left sm:px-6"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p></th>
                <th class="px-5 py-3 text-center sm:px-6"><p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aksi</p></th>
              </tr>
            </thead>
            <tbody>
              @forelse($certifications ?? [] as $index => $cert)
              <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $index + 1 }}</td>
                <td class="px-5 py-4 text-theme-sm font-medium text-gray-800 dark:text-white/90 sm:px-6">{{ $cert->name }}</td>
                <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $cert->member->name ?? '-' }}</td>
                <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $cert->provider }}</td>
                <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $cert->issue_date }}</td>
                <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $cert->expiry_date ?? '-' }}</td>
                <td class="px-5 py-4 text-theme-sm sm:px-6">
                  @switch($cert->status)
                    @case('active')<span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-green-50 text-green-700">Aktif</span>@break
                    @case('expired')<span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-red-50 text-red-700">Kedaluwarsa</span>@break
                    @case('revoked')<span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium bg-gray-100 text-gray-700">Dicabut</span>@break
                  @endswitch
                </td>
                <td class="px-5 py-4 text-center sm:px-6">
                  <div class="flex items-center justify-center gap-2">
                    <a href="{{ route('hr.certifications.show', $cert->id) }}" class="text-blue-600 hover:text-blue-800">
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                    </a>
                    <a href="{{ route('hr.certifications.edit', $cert->id) }}" class="text-yellow-600 hover:text-yellow-800">
                      <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                    </a>
                  </div>
                </td>
              </tr>
              @empty
              <tr class="border-b border-gray-100 dark:border-gray-800">
                <td colspan="8" class="px-5 py-8 text-center text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">Data tidak tersedia.</td>
              </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      @if(isset($certifications) && $certifications->hasPages())
      <div class="mt-4 flex items-center justify-between">
        <p class="text-sm text-gray-500">Menampilkan {{ $certifications->firstItem() }} - {{ $certifications->lastItem() }} dari {{ $certifications->total() }} data</p>
        {{ $certifications->links('pagination::tailwind') }}
      </div>
      @endif
    </div>
  </div>
</div>
@endsection
