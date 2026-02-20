@extends('layouts.app')

@section('title', 'Keanggotaan')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Data Keanggotaan</h2>
        <div class="flex flex-wrap items-center gap-2">
          @if(auth()->user()?->hasRole('Super Admin'))
            <span class="inline-flex items-center rounded-full bg-green-50 px-3 py-1 text-xs font-medium text-green-700 dark:bg-green-500/15 dark:text-green-400">
              Akses: Super Admin
            </span>
          @endif
          @if(auth()->user()?->hasPermission('hr.access'))
            <span class="inline-flex items-center rounded-full bg-blue-50 px-3 py-1 text-xs font-medium text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">
              HR Access
            </span>
          @endif
          <a href="{{ route('hr.members.import') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Import Anggota
          </a>
          <a href="{{ route('hr.members.create') }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Tambah Anggota
          </a>
        </div>
      </div>

      <!-- Filters -->
      <form class="mb-6" method="GET" action="{{ route('hr.members.index') }}">
        <div class="grid grid-cols-1 gap-4 lg:grid-cols-3">
          <div class="lg:col-span-1">
            <label for="filterSearch" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Pencarian</label>
            <input
              id="filterSearch"
              name="search"
              type="text"
              value="{{ $search ?? '' }}"
              placeholder="Cari nama, NIA, atau jabatan..."
              class="w-full rounded border border-gray-300 bg-white px-4 py-2 text-sm text-gray-800 focus:border-brand-500 focus:outline-none focus:ring-1 focus:ring-brand-500/20 dark:border-gray-700 dark:bg-boxdark dark:text-white"
            />
          </div>
          <div>
            <label for="filterStatus" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Status</label>
            <select id="filterStatus" name="status">
              <option value="">Semua Status</option>
              <option value="active" @selected(($status ?? '') === 'active')>Aktif</option>
              <option value="inactive" @selected(($status ?? '') === 'inactive')>Tidak Aktif</option>
              <option value="terminated" @selected(($status ?? '') === 'terminated')>Mengundurkan Diri</option>
            </select>
          </div>
          <div>
            <label for="filterType" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">Tipe Anggota</label>
            <select id="filterType" name="member_type">
              <option value="">Semua Tipe</option>
              <option value="AM" @selected(($memberType ?? '') === 'AM')>Anggota Muda (AM)</option>
              <option value="AT" @selected(($memberType ?? '') === 'AT')>Anggota Tetap (AT)</option>
              <option value="Alumni" @selected(($memberType ?? '') === 'Alumni')>Alumni</option>
            </select>
          </div>
        </div>
        <div class="mt-4 flex flex-wrap items-center justify-end gap-3">
          <a href="{{ route('hr.members.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Reset
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Terapkan Filter
          </button>
        </div>
      </form>

      <!-- Table -->
      <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="max-w-full overflow-x-auto custom-scrollbar">
          <table class="w-full min-w-[900px] table-fixed">
            <thead>
              <tr class="border-b border-gray-100 dark:border-gray-800">
                <th class="px-4 py-3 text-left sm:px-5 w-[60px]">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">No</p>
                </th>
                <th class="px-4 py-3 text-left sm:px-5 w-[220px]">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama</p>
                </th>
                <th class="px-4 py-3 text-left sm:px-5 w-[140px]">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">NIA</p>
                </th>
                <th class="px-4 py-3 text-left sm:px-5 w-[160px]">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Telepon</p>
                </th>
                <th class="px-4 py-3 text-left sm:px-5 w-[110px]">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tipe</p>
                </th>
                <th class="px-4 py-3 text-left sm:px-5 w-[160px]">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Jabatan</p>
                </th>
                <th class="px-4 py-3 text-left sm:px-5 w-[110px]">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p>
                </th>
                <th class="px-4 py-3 text-center sm:px-5 w-[120px]">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aksi</p>
                </th>
              </tr>
            </thead>
            <tbody>
              @forelse ($members as $index => $member)
                <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                  <td class="px-4 py-3 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-5">
                    {{ $members->firstItem() + $index }}
                  </td>
                  <td class="px-4 py-3 text-theme-sm font-medium text-gray-800 dark:text-white/90 sm:px-5 truncate">
                    {{ $member->name }}
                  </td>
                  <td class="px-4 py-3 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-5">
                    {{ $member->profile->nia ?? '-' }}
                  </td>
                  <td class="px-4 py-3 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-5">
                    {{ $member->phone ?? '-' }}
                  </td>
                  <td class="px-4 py-3 text-theme-sm sm:px-5">
                    @php
                      $type = $member->member_type ?? 'AM';
                    @endphp
                    <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium
                      {{ $type === 'AT' ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400' :
                          ($type === 'AM' ? 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400' :
                          'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300') }}">
                      {{ $type }}
                    </span>
                  </td>
                  <td class="px-4 py-3 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-5 truncate">
                    {{ $member->position ?? '-' }}
                  </td>
                  <td class="px-4 py-3 text-theme-sm sm:px-5">
                    @php
                      $status = $member->status ?? 'active';
                    @endphp
                    <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium
                      {{ $status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500' :
                          ($status === 'inactive' ? 'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400' :
                          'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500') }}">
                      {{ $status === 'active' ? 'Aktif' : ($status === 'inactive' ? 'Tidak Aktif' : 'Mengundurkan Diri') }}
                    </span>
                  </td>
                  <td class="px-4 py-3 text-center sm:px-5">
                    <div class="flex items-center justify-center gap-2">
                      <a href="{{ route('hr.members.show', $member->id) }}" class="text-gray-600 hover:text-gray-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                      </a>
                      <a href="{{ route('hr.members.edit', $member->id) }}" class="text-blue-600 hover:text-blue-800">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                      </a>
                      <form action="{{ route('hr.members.destroy', $member->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Apakah Anda yakin ingin menghapus anggota ini?')">
                          <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                          </svg>
                        </button>
                      </form>
                    </div>
                  </td>
                </tr>
              @empty
                <tr class="border-b border-gray-100 dark:border-gray-800">
                  <td colspan="8" class="px-5 py-8 text-center text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">
                    Data tidak tersedia. Silakan tambahkan data anggota terlebih dahulu.
                  </td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div class="mt-4 flex items-center justify-between">
        <p class="text-sm text-gray-500">
          Menampilkan {{ $members->count() }} dari {{ $members->total() }} data
        </p>
        <div class="text-sm">
          {{ $members->links() }}
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
