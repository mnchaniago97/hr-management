@extends('layouts.app')

@section('title', 'Unggah Dokumen')

@section('content')
<div class="grid grid-cols-12 gap-4 md:gap-6">
  <div class="col-span-12">
    <div class="rounded-sm border border-stroke bg-white px-5 py-6 shadow-default dark:border-strokedark dark:bg-boxdark">
      <div class="flex flex-wrap items-center justify-between gap-3 mb-6">
        <div>
          <h2 class="text-xl font-semibold text-black dark:text-white">Unggah Dokumen</h2>
          <p class="text-sm text-gray-500 mt-1">Tambahkan file proposal, LPJ, atau surat tugas.</p>
        </div>
        <a href="{{ route('program.documents.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
          Kembali
        </a>
      </div>

      <form action="{{ route('program.documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
        @csrf

        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
          <div>
            <label for="type" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Jenis Dokumen <span class="text-error-500">*</span>
            </label>
            <select id="type" name="type" required>
              <option value="">Pilih Jenis</option>
              @foreach(\App\Models\Program\Document::getAvailableTypes() as $type)
                <option value="{{ $type }}" @selected(old('type') == $type)>{{ \App\Models\Program\Document::getTypeLabel($type) }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="program_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Program
            </label>
            <select id="program_id" name="program_id">
              <option value="">Pilih Program</option>
              @foreach(\App\Models\Program\Program::orderBy('name')->get() as $program)
                <option value="{{ $program->id }}" @selected(old('program_id') == $program->id)>{{ $program->name }}</option>
              @endforeach
            </select>
          </div>

          <div>
            <label for="activity_id" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              Kegiatan
            </label>
            <select id="activity_id" name="activity_id">
              <option value="">Pilih Kegiatan</option>
              @foreach(\App\Models\Program\Activity::orderBy('start_date', 'desc')->get() as $activity)
                <option value="{{ $activity->id }}" @selected(old('activity_id') == $activity->id)>{{ $activity->name }}</option>
              @endforeach
            </select>
          </div>

          <div class="lg:col-span-2">
            <label for="file" class="mb-1.5 block text-sm font-medium text-gray-700 dark:text-gray-400">
              File <span class="text-error-500">*</span>
            </label>
            <input type="file" id="file" name="file" required>
            <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">Maksimal 10MB. Format bebas.</p>
          </div>
        </div>

        <div class="flex flex-wrap justify-end gap-3">
          <a href="{{ route('program.documents.index') }}" class="inline-flex items-center rounded-lg border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-theme-xs hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-white/[0.03]">
            Batal
          </a>
          <button type="submit" class="inline-flex items-center rounded-lg bg-brand-500 px-4 py-2 text-sm font-medium text-white shadow-theme-xs hover:bg-brand-600">
            Upload Dokumen
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
