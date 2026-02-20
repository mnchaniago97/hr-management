@extends('layouts.app')

@section('title', 'Detail Jabatan')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <h2 class="text-xl font-semibold text-black dark:text-white">Detail Jabatan</h2>
        <div class="flex flex-wrap gap-2">
          <a href="{{ route('hr.positions.edit', $position->id) }}" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            <svg class="mr-2" width="16" height="16" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
          </a>
          <a href="{{ route('hr.positions.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Kembali
          </a>
        </div>
      </div>

      <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        <!-- Detail Card -->
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Informasi Jabatan</h3>
          
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Nama Jabatan</span>
              <span class="font-medium text-black dark:text-white">{{ $position->name }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Departemen</span>
              <span class="font-medium text-black dark:text-white">{{ $position->department }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Level</span>
              <span class="inline-block rounded-full bg-blue-50 px-2 py-0.5 text-xs font-medium text-blue-700 dark:bg-blue-500/15 dark:text-blue-400">Level {{ $position->level }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Status</span>
              <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium {{ $position->is_active ? 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500' : 'bg-red-50 text-red-700 dark:bg-red-500/15 dark:text-red-500' }}">
                {{ $position->is_active ? 'Aktif' : 'Tidak Aktif' }}
              </span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Deskripsi</span>
              <span class="text-right font-medium text-black dark:text-white">{{ $position->description ?? '-' }}</span>
            </div>
          </div>
        </div>

        <!-- Statistics Card -->
        <div class="rounded-lg border border-gray-200 p-4 dark:border-gray-700">
          <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Statistik</h3>
          
          <div class="space-y-3">
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Jumlah Anggota</span>
              <span class="font-medium text-black dark:text-white">{{ $position->members->count() }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Jumlah Penempatan</span>
              <span class="font-medium text-black dark:text-white">{{ $position->divisionAssignments->count() }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Dibuat pada</span>
              <span class="font-medium text-black dark:text-white">{{ $position->created_at->format('d M Y') }}</span>
            </div>
            <div class="flex justify-between">
              <span class="text-sm text-gray-500 dark:text-gray-400">Diupdate pada</span>
              <span class="font-medium text-black dark:text-white">{{ $position->updated_at->format('d M Y') }}</span>
            </div>
          </div>
        </div>
      </div>

      <!-- Members List -->
      @if($position->members->count() > 0)
      <div class="mt-6">
        <h3 class="mb-4 text-lg font-semibold text-black dark:text-white">Anggota dengan Jabatan Ini</h3>
        <div class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
          <div class="max-w-full overflow-x-auto custom-scrollbar">
            <table class="w-full min-w-[600px]">
              <thead>
                <tr class="border-b border-gray-100 dark:border-gray-800">
                  <th class="px-5 py-3 text-left sm:px-6">
                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Nama</p>
                  </th>
                  <th class="px-5 py-3 text-left sm:px-6">
                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Email</p>
                  </th>
                  <th class="px-5 py-3 text-left sm:px-6">
                    <p class="font-medium text-gray-500 text-theme-xs dark:text-gray-400">Status</p>
                  </th>
                </tr>
              </thead>
              <tbody>
                @foreach($position->members as $member)
                <tr class="border-b border-gray-100 dark:border-gray-800 hover:bg-gray-50 dark:hover:bg-white/[0.03]">
                  <td class="px-5 py-4 text-theme-sm font-medium text-gray-800 dark:text-white/90 sm:px-6">
                    <a href="{{ route('hr.members.show', $member->id) }}" class="hover:text-primary">{{ $member->name }}</a>
                  </td>
                  <td class="px-5 py-4 text-theme-sm text-gray-500 dark:text-gray-400 sm:px-6">{{ $member->email }}</td>
                  <td class="px-5 py-4 text-theme-sm sm:px-6">
                    <span class="text-theme-xs inline-block rounded-full px-2 py-0.5 font-medium {{ $member->status === 'active' ? 'bg-green-50 text-green-700 dark:bg-green-500/15 dark:text-green-500' : 'bg-gray-100 text-gray-700 dark:bg-gray-800 dark:text-gray-300' }}">
                      {{ $member->status === 'active' ? 'Aktif' : $member->status }}
                    </span>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      @endif
    </div>
  </div>
</div>
@endsection


