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
          <h1 class="mt-3 text-2xl font-semibold text-gray-800">Peminjaman Aset / Inventaris</h1>
          <p class="mt-1 text-sm text-gray-500">Isi form berikut untuk mengajukan peminjaman aset.</p>
        </div>

        @if (session('success'))
        <div class="mb-4 rounded-lg border border-green-100 bg-green-50 px-4 py-3 text-sm text-green-700">
          {{ session('success') }}
        </div>
        @endif

        @if (session('error'))
        <div class="mb-4 rounded-lg border border-red-100 bg-red-50 px-4 py-3 text-sm text-red-700">
          {{ session('error') }}
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

        <form method="POST" action="{{ route('asset.public.loan.store') }}" class="space-y-6" enctype="multipart/form-data" x-data="{ itemQuery: '' }">
          @csrf
          <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">Nama Peminjam</label>
              <input
                type="text"
                name="borrower_name"
                value="{{ old('borrower_name') }}"
                placeholder="Nama lengkap peminjam..."
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60"
                required
              >
            </div>

            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">Aset / Inventaris</label>
              <input
                type="text"
                placeholder="Cari aset..."
                class="mb-2 w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60"
                x-model="itemQuery"
              >
              <select name="item_id" required
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60">
                <option value="">Pilih aset</option>
                @foreach($availableItems ?? [] as $item)
                  <option
                    value="{{ $item->id }}"
                    x-show="{{ json_encode($item->name . ' ' . ($item->code ?? '')) }}.toLowerCase().includes(itemQuery.toLowerCase())"
                    {{ old('item_id') == $item->id ? 'selected' : '' }}
                  >
                    {{ $item->name }}{{ $item->code ? ' (' . $item->code . ')' : '' }}
                  </option>
                @endforeach
              </select>
            </div>

            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">Instansi</label>
              <input type="text" name="borrower_institution" value="{{ old('borrower_institution') }}" required
                placeholder="Nama instansi/organisasi..."
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60">
            </div>

            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">No. HP</label>
              <input type="text" name="borrower_phone" value="{{ old('borrower_phone') }}" required
                placeholder="Contoh: 08xxxxxxxxxx"
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60">
            </div>

            <div class="lg:col-span-2">
              <label class="mb-2 block text-sm font-medium text-gray-700">Alamat</label>
              <input type="text" name="borrower_address" value="{{ old('borrower_address') }}" required
                placeholder="Alamat lengkap peminjam..."
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60">
            </div>

            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">Tanggal Pinjam</label>
              <input type="date" name="assignment_date" value="{{ old('assignment_date') }}" required
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60">
            </div>

            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">Tanggal Kembali</label>
              <input type="date" name="return_date" value="{{ old('return_date') }}" required
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60">
            </div>

            <div class="lg:col-span-2">
              <label class="mb-2 block text-sm font-medium text-gray-700">Catatan</label>
              <textarea name="notes" rows="4"
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm focus:border-[#5a4cfe] focus:outline-none focus:ring-2 focus:ring-[#c7b9ff]/60">{{ old('notes') }}</textarea>
            </div>

            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">Surat Permohonan (Opsional)</label>
              <input type="file" name="request_letter" accept=".pdf,.jpg,.jpeg,.png"
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm">
              <p class="mt-1 text-xs text-gray-500">PDF/JPG/PNG maks 4MB.</p>
            </div>

            <div>
              <label class="mb-2 block text-sm font-medium text-gray-700">KTP (Opsional)</label>
              <input type="file" name="id_card" accept=".pdf,.jpg,.jpeg,.png"
                class="w-full rounded-xl border border-gray-200 bg-white px-3 py-2 text-sm shadow-sm">
              <p class="mt-1 text-xs text-gray-500">PDF/JPG/PNG maks 4MB.</p>
            </div>
          </div>

          <div class="flex flex-wrap justify-end gap-3">
            <button type="submit" class="inline-flex items-center rounded-full bg-[#2f64f6] px-5 py-2.5 text-sm font-medium text-white shadow-sm hover:bg-[#2554d4]">
              Kirim Peminjaman
            </button>
          </div>
        </form>

        <div class="mt-6 rounded-2xl border border-white/70 bg-gray-50/70 p-4 text-xs text-gray-500">
          Catatan: Pengajuan akan langsung tercatat sebagai peminjaman aktif. Hubungi admin jika ada perubahan.
        </div>
      </div>

      <div class="rounded-[28px] bg-[#c7b9ff] p-6 text-[#1f2140] shadow-sm">
        <h2 class="text-xl font-semibold">Tips Peminjaman</h2>
        <p class="mt-2 text-sm text-[#2f3166]">
          Pastikan data anggota dan aset sesuai sebelum mengirimkan form.
        </p>
        <div class="mt-6 grid gap-3 text-sm">
          <div class="rounded-2xl bg-white/80 p-4">
            <p class="text-xs text-[#6a6fa7]">Durasi</p>
            <p class="mt-1 font-semibold">Tentukan tanggal kembali</p>
          </div>
          <div class="rounded-2xl bg-white/80 p-4">
            <p class="text-xs text-[#6a6fa7]">Catatan</p>
            <p class="mt-1 font-semibold">Tambahkan keperluan peminjaman</p>
          </div>
          <div class="rounded-2xl bg-white/80 p-4">
            <p class="text-xs text-[#6a6fa7]">Verifikasi</p>
            <p class="mt-1 font-semibold">Admin akan konfirmasi</p>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
