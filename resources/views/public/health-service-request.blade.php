@extends('layouts.fullscreen-layout')

@section('content')
<div class="relative min-h-screen overflow-hidden bg-[#f4f6fb] text-gray-900">
  <div class="pointer-events-none absolute -left-28 -top-28 h-72 w-72 rounded-full bg-[#c7b9ff]/40 blur-3xl"></div>
  <div class="pointer-events-none absolute -right-24 top-20 h-80 w-80 rounded-full bg-blue-200/40 blur-3xl"></div>

  <div class="mx-auto flex min-h-screen w-full max-w-6xl flex-col px-6 py-10">
    <div class="mb-6 flex items-center justify-between">
      <div class="flex items-center gap-3">
        <img src="/images/logo/logoksr.png" alt="KSR PMI UNP" class="h-9 w-auto" />
        <div>
          <p class="text-sm font-semibold tracking-wide">KSR PMI UNP</p>
          <p class="text-xs text-gray-500">Loyalitas untuk kemanusiaan</p>
        </div>
      </div>
      <a href="{{ route('public.home') }}"
        class="rounded-full border border-gray-300 px-4 py-2 text-sm font-medium text-gray-700 hover:border-gray-400 hover:text-gray-900">
        Beranda
      </a>
    </div>

    <div class="grid gap-8 lg:grid-cols-[1.05fr_0.95fr]">
      <div class="rounded-[28px] border border-white/70 bg-white/90 p-6 shadow-sm backdrop-blur sm:p-8">
        <div class="mb-6">
          <span class="inline-flex items-center rounded-full bg-[#c7b9ff] px-3 py-1 text-xs font-semibold text-[#1f2140]">
            Form Publik
          </span>
          <h1 class="mt-3 text-2xl font-semibold text-gray-800">Permohonan Layanan Tim Kesehatan</h1>
          <p class="mt-1 text-sm text-gray-500">Isi form ini untuk meminta dukungan tim kesehatan dari KSR PMI UNP.</p>
        </div>

        @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700">
          {{ session('success') }}
        </div>
        @endif

        @if ($errors->any())
        <div class="mb-4 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
          <ul class="list-disc space-y-1 pl-4">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('health.public.store') }}" class="space-y-6">
          @csrf
          <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">Nama Pemohon</label>
              <input type="text" name="requester_name" value="{{ old('requester_name') }}" required
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60"
                placeholder="Nama lengkap pemohon">
            </div>
            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">Instansi</label>
              <input type="text" name="institution" value="{{ old('institution') }}" required
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60"
                placeholder="Nama instansi/organisasi">
            </div>
            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">No. HP</label>
              <input type="text" name="phone" value="{{ old('phone') }}" required
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60"
                placeholder="08xxxxxxxxxx">
            </div>
            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">Email (Opsional)</label>
              <input type="email" name="email" value="{{ old('email') }}"
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60"
                placeholder="email@contoh.com">
            </div>
            <div class="lg:col-span-2">
              <label class="mb-2 block text-sm font-medium text-gray-700">Lokasi Kegiatan</label>
              <input type="text" name="location" value="{{ old('location') }}" required
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60"
                placeholder="Alamat kegiatan">
            </div>
            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">Tanggal Kegiatan</label>
              <input type="date" name="event_date" value="{{ old('event_date') }}" required
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60">
            </div>
            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">Perkiraan Peserta</label>
              <input type="number" name="participants" value="{{ old('participants') }}" min="1"
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60"
                placeholder="Jumlah peserta">
            </div>
            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">Mulai</label>
              <input type="time" name="start_time" value="{{ old('start_time') }}"
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60">
            </div>
            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">Selesai</label>
              <input type="time" name="end_time" value="{{ old('end_time') }}"
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60">
            </div>
            <div class="lg:col-span-2">
              <label class="mb-2 block text-sm font-medium text-gray-700">Catatan Tambahan</label>
              <textarea name="notes" rows="4"
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60">{{ old('notes') }}</textarea>
            </div>
          </div>

          <div class="flex flex-wrap justify-end gap-3">
            <button type="submit" class="inline-flex items-center rounded-full bg-[#2f64f6] px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-[#2554d4]">
              Kirim Permohonan
            </button>
          </div>
        </form>

        <div class="mt-6 rounded-2xl border border-white/70 bg-gray-50/70 p-4 text-xs text-gray-500">
          Catatan: Tim KSR akan menghubungi Anda setelah permohonan diterima.
        </div>
      </div>

      <div class="rounded-[28px] bg-[#c7b9ff] p-6 text-[#1f2140] shadow-sm">
        <h2 class="text-xl font-semibold">Syarat Umum</h2>
        <p class="mt-2 text-sm text-[#2f3166]">
          Mohon siapkan informasi kegiatan agar tim dapat menyiapkan personel dan perlengkapan.
        </p>
        <div class="mt-6 grid gap-3 text-sm">
          <div class="rounded-2xl bg-white/80 p-4">
            <p class="text-xs text-[#6a6fa7]">Dokumen</p>
            <p class="mt-1 font-semibold">Rincian kegiatan & kebutuhan</p>
          </div>
          <div class="rounded-2xl bg-white/80 p-4">
            <p class="text-xs text-[#6a6fa7]">Koordinasi</p>
            <p class="mt-1 font-semibold">Nomor kontak aktif</p>
          </div>
          <div class="rounded-2xl bg-white/80 p-4">
            <p class="text-xs text-[#6a6fa7]">Lokasi</p>
            <p class="mt-1 font-semibold">Alamat jelas & akses</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
