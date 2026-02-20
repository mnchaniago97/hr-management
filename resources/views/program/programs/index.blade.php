@extends('layouts.app')

@section('title', 'Program Kerja')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex items-center justify-between mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Data Program Kerja</h2>
        <a href="{{ route('program.programs.create') }}" class="inline-flex items-center rounded-md bg-primary px-4 py-2 text-white hover:bg-opacity-90">
          <svg class="mr-2" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M12 4V20M4 12H20" stroke="currentColor" stroke-width="2" stroke-linecap="round"/>
          </svg>
          Tambah Program
        </a>
      </div>

      <!-- Stats Summary -->
      <div class="grid grid-cols-4 gap-4 mb-6">
        <div class="rounded border border-gray-200 p-4 dark:border-gray-700">
          <p class="text-sm text-gray-500">Total Program</p>
          <p class="text-2xl font-bold text-black dark:text-white" id="totalPrograms">-</p>
        </div>
        <div class="rounded border border-gray-200 p-4 dark:border-gray-700">
          <p class="text-sm text-gray-500">Aktif</p>
          <p class="text-2xl font-bold text-success" id="activePrograms">-</p>
        </div>
        <div class="rounded border border-gray-200 p-4 dark:border-gray-700">
          <p class="text-sm text-gray-500">Terprogram</p>
          <p class="text-2xl font-bold text-primary" id="terprogramPrograms">-</p>
        </div>
        <div class="rounded border border-gray-200 p-4 dark:border-gray-700">
          <p class="text-sm text-gray-500">Insidentil</p>
          <p class="text-2xl font-bold text-warning" id="insidentilPrograms">-</p>
        </div>
      </div>

      <!-- Filters -->
      <div class="mb-4 flex flex-wrap gap-4">
        <select id="filterStatus" class="rounded border border-gray-300 px-3 py-2 dark:border-gray-600 dark:bg-boxdark">
          <option value="">Semua Status</option>
          <option value="draft">Draft</option>
          <option value="active">Aktif</option>
          <option value="completed">Selesai</option>
          <option value="cancelled">Dibatalkan</option>
        </select>
        <select id="filterType" class="rounded border border-gray-300 px-3 py-2 dark:border-gray-600 dark:bg-boxdark">
          <option value="">Semua Tipe</option>
          <option value="terprogram">Terprogram</option>
          <option value="insidentil">Insidentil</option>
        </select>
        <select id="filterPeriod" class="rounded border border-gray-300 px-3 py-2 dark:border-gray-600 dark:bg-boxdark">
          <option value="">Semua Periode</option>
          @if(isset($periods))
          @foreach($periods as $period)
          <option value="{{ $period->id }}">{{ $period->name }}</option>
          @endforeach
          @endif
        </select>
      </div>

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
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama Program</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Periode</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Bidang</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Tipe</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p>
                </th>
                <th class="px-5 py-3 text-left sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aktivitas</p>
                </th>
                <th class="px-5 py-3 text-center sm:px-6">
                  <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Aksi</p>
                </th>
              </tr>
            </thead>
            <tbody id="programsTableBody">
              <tr class="border-b border-gray-100 dark:border-gray-800">
                <td colspan="8" class="px-5 py-8 text-center text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">
                  Data tidak tersedia. Silakan tambahkan data program terlebih dahulu.
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Pagination -->
      <div class="mt-4 flex items-center justify-between">
        <p class="text-sm text-gray-500">Menampilkan data program kerja</p>
        <div class="flex gap-2">
          <button class="rounded border border-gray-300 px-3 py-1 text-sm hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-800">Previous</button>
          <button class="rounded border border-gray-300 px-3 py-1 text-sm hover:bg-gray-100 dark:border-gray-600 dark:hover:bg-gray-800">Next</button>
        </div>
      </div>
    </div>
  </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Fetch programs data
  fetch('{{ route("program.programs.index") }}?json=1')
    .then(response => response.json())
    .then(data => {
      const tbody = document.getElementById('programsTableBody');
      
      // Update stats
      document.getElementById('totalPrograms').textContent = data.stats.total;
      document.getElementById('activePrograms').textContent = data.stats.active;
      document.getElementById('terprogramPrograms').textContent = data.stats.terprogram;
      document.getElementById('insidentilPrograms').textContent = data.stats.insidentil;
      
      if (data.programs.length > 0) {
        tbody.innerHTML = data.programs.map((program, index) => `
          <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.03]">
            <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">${index + 1}</td>
            <td class="px-5 py-4 text-theme-sm font-medium text-gray-800 dark:text-white/90 sm:px-6">${program.name}</td>
            <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">${program.period || '-'}</td>
            <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">${program.field || '-'}</td>
            <td class="px-5 py-4 text-theme-sm sm:px-6">
              <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium
                ${program.type === 'terprogram' ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400' :
                  'bg-yellow-50 text-yellow-700 dark:bg-yellow-500/15 dark:text-yellow-400'}">
                ${program.type === 'terprogram' ? 'Terprogram' : 'Insidentil'}
              </span>
            </td>
            <td class="px-5 py-4 text-theme-sm sm:px-6">
              <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium
                ${program.status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500' :
                  program.status === 'draft' ? 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' :
                  program.status === 'completed' ? 'bg-blue-50 text-blue-700 dark:bg-blue-500/15 dark:text-blue-400' :
                  'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500'}">
                ${program.status === 'active' ? 'Aktif' :
                  program.status === 'draft' ? 'Draft' :
                  program.status === 'completed' ? 'Selesai' :
                  'Dibatalkan'}
              </span>
            </td>
            <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">${program.activity_count || 0}</td>
            <td class="px-5 py-4 text-center sm:px-6">
              <div class="flex items-center justify-center gap-2">
                <a href="/program/programs/${program.id}" class="text-blue-600 hover:text-blue-800">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                  </svg>
                </a>
                <a href="/program/programs/${program.id}/edit" class="text-blue-600 hover:text-blue-800">
                  <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                  </svg>
                </a>
                <form action="/program/programs/${program.id}" method="POST" class="inline">
                  @csrf
                  @method('DELETE')
                  <button type="submit" class="text-red-600 hover:text-red-800" onclick="return confirm('Apakah Anda yakin ingin menghapus program ini?')">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                  </button>
                </form>
              </div>
            </td>
          </tr>
        `).join('');
      }
    })
    .catch(error => console.error('Error loading programs:', error));
});
</script>
@endpush
@endsection
