@extends('layouts.admin')

@section('title', 'Paket · Admin Bersihin')

@section('content')
    <div class="kepala">
        <h1>Paket layanan<span class="sr"> - Kelola Paket Layanan</span></h1>
        <p>Harga yang dilihat pelanggan saat pesan.</p>
    </div>

    @if($paketLayanan->isEmpty())
        <div class="kosong">
            <b>Belum ada paket</b>
            <span>Paket layanan akan muncul di sini.</span>
        </div>
    @else
        <div class="tabel-wadah">
            <table class="tabel">
                <thead>
                    <tr><th>Paket</th><th>Durasi</th><th class="kanan">Harga</th><th class="kanan">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($paketLayanan as $paket)
                        <tr>
                            <td data-label="Paket">
                                <span class="judul-baris">{{ $paket->nama_paket }}</span>
                                <span class="sub-baris">{{ $paket->deskripsi }}</span>
                            </td>
                            <td data-label="Durasi" class="angka">± {{ $paket->estimasi_durasi_jam }} jam</td>
                            <td data-label="Harga" class="kanan harga">Rp{{ number_format($paket->harga, 0, ',', '.') }}</td>
                            <td data-label="">
                                <div class="aksi">
                                    <button type="button" class="btn btn-kedua btn-kecil" data-buka="dialog-paket"
                                        data-paket="{{ json_encode([
                                            'id' => $paket->id,
                                            'nama_paket' => $paket->nama_paket,
                                            'deskripsi' => $paket->deskripsi,
                                            'harga' => (int) $paket->harga,
                                            'estimasi_durasi_jam' => (int) $paket->estimasi_durasi_jam,
                                            'update_url' => route('admin.paket.update', $paket->id)
                                        ]) }}">Ubah</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <dialog id="dialog-paket" aria-labelledby="judul-paket">
        <h2 id="judul-paket">Ubah paket</h2>
        <p>Harga baru berlaku untuk pesanan baru.</p>
        <form action="" method="POST" id="form-paket">
            @csrf
            @method('PUT')
            <div class="isian">
                <label for="nama_paket">Nama paket</label>
                <input type="text" id="nama_paket" name="nama_paket" required>
            </div>
            <div class="isian">
                <label for="deskripsi">Deskripsi</label>
                <textarea id="deskripsi" name="deskripsi" rows="3" required></textarea>
            </div>
            <div class="isian-dua">
                <div class="isian">
                    <label for="harga">Harga (Rp)</label>
                    <input type="number" id="harga" name="harga" min="0" step="1000" inputmode="numeric" required>
                </div>
                <div class="isian">
                    <label for="estimasi_durasi_jam">Durasi (jam)</label>
                    <input type="number" id="estimasi_durasi_jam" name="estimasi_durasi_jam" min="1" inputmode="numeric" required>
                </div>
            </div>
            <div class="aksi">
                <button type="button" class="btn btn-teks" data-tutup>Batal</button>
                <button type="submit" class="btn btn-utama btn-kecil">Simpan</button>
            </div>
        </form>
    </dialog>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-buka]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const dialog = document.getElementById(btn.dataset.buka);
            if (dialog) dialog.showModal();
        });
    });

    document.querySelectorAll('[data-tutup]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const dialog = btn.closest('dialog');
            if (dialog) dialog.close();
        });
    });

    document.querySelectorAll('[data-paket]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const p = JSON.parse(btn.dataset.paket);
            const f = document.getElementById('form-paket');
            if (f) {
                f.action = p.update_url;
                if (f.elements['nama_paket']) f.elements['nama_paket'].value = p.nama_paket;
                if (f.elements['deskripsi']) f.elements['deskripsi'].value = p.deskripsi;
                if (f.elements['harga']) f.elements['harga'].value = p.harga;
                if (f.elements['estimasi_durasi_jam']) f.elements['estimasi_durasi_jam'].value = p.estimasi_durasi_jam;
            }
        });
    });

    // Close dialog when clicking backdrop
    const dialogPaket = document.getElementById('dialog-paket');
    if (dialogPaket) {
        dialogPaket.addEventListener('click', (e) => {
            const rect = dialogPaket.getBoundingClientRect();
            if (
                e.clientX < rect.left ||
                e.clientX > rect.right ||
                e.clientY < rect.top ||
                e.clientY > rect.bottom
            ) {
                dialogPaket.close();
            }
        });
    }
</script>
@endpush
