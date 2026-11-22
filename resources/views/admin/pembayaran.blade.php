@extends('layouts.admin')

@section('title', 'Pembayaran · Admin Bersihin')

@section('content')
    <div class="kepala">
        <h1>Pembayaran<span class="sr"> - Konfirmasi Pembayaran</span></h1>
        @if($pembayaranMenungguCount > 0)
            <p>{{ $pembayaranMenungguCount }} nunggu konfirmasi</p>
        @else
            <p>Semua sudah dikonfirmasi</p>
        @endif
    </div>

    @if($pembayaran->isEmpty())
        <div class="kosong">
            <b>Belum ada pembayaran</b>
            <span>Pembayaran pelanggan muncul di sini.</span>
        </div>
    @else
        <div class="tabel-wadah">
            <table class="tabel">
                <thead>
                    <tr><th>Pesanan</th><th>Pelanggan</th><th>Metode</th><th>Bukti</th><th class="kanan">Jumlah</th><th>Status</th><th class="kanan">Aksi</th></tr>
                </thead>
                <tbody>
                    @foreach($pembayaran as $item)
                        <tr @if($item->status === 'menunggu') class="sorot" @endif>
                            <td data-label="Pesanan">
                                <span class="judul-baris">#{{ $item->pesanan_id }} {{ $item->pesanan->paketLayanan->nama_paket ?? '-' }}</span>
                                <span class="sub-baris">
                                    {{ $item->pesanan->tanggal ? $item->pesanan->tanggal->translatedFormat('d M') : '-' }} · {{ str_replace(':', '.', substr($item->pesanan->jam_mulai, 0, 5)) }}
                                </span>
                            </td>
                            <td data-label="Pelanggan">{{ $item->pesanan->pelanggan->nama ?? '-' }}</td>
                            <td data-label="Metode" style="text-transform:capitalize;">{{ $item->metode }}</td>
                            <td data-label="Bukti">
                                @if($item->bukti_bayar)
                                    <button type="button" class="btn btn-teks" data-buka="dialog-bukti"
                                        data-bukti="{{ asset('storage/' . $item->bukti_bayar) }}"
                                        data-pesanan="#{{ $item->pesanan_id }}"
                                        data-pelanggan="{{ $item->pesanan->pelanggan->nama ?? '-' }}"
                                        data-total="Rp{{ number_format($item->pesanan->total_harga ?? 0, 0, ',', '.') }}"
                                        style="margin-left:-10px;">Lihat bukti</button>
                                @else
                                    <span style="color:var(--tinta-2);">{{ $item->metode === 'tunai' ? 'Dibayar di lokasi' : '-' }}</span>
                                @endif
                            </td>
                            <td data-label="Jumlah" class="kanan harga">Rp{{ number_format($item->pesanan->total_harga ?? 0, 0, ',', '.') }}</td>
                            <td data-label="Status">
                                @if($item->status === 'menunggu')
                                    <span class="status s-tunggu">Menunggu</span>
                                @elseif($item->status === 'lunas')
                                    <span class="status s-beres">Lunas</span>
                                @elseif($item->status === 'ditolak')
                                    <span class="status s-batal">Ditolak</span>
                                @else
                                    <span class="status s-netral">{{ ucfirst($item->status) }}</span>
                                @endif
                            </td>
                            <td data-label="">
                                @if($item->status === 'menunggu')
                                    <form action="{{ route('admin.pembayaran.status', $item->id) }}" method="POST" class="aksi">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" name="status" value="ditolak" class="btn btn-teks bahaya">Tolak</button>
                                        <button type="submit" name="status" value="lunas" class="btn btn-utama btn-kecil">Lunas</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    <dialog id="dialog-bukti" aria-labelledby="judul-bukti">
        <h2 id="judul-bukti">Bukti transfer <span id="bukti-pesanan"></span></h2>
        <p><span id="bukti-pelanggan"></span> · <span id="bukti-total"></span></p>
        <div style="aspect-ratio:3/4;max-height:56vh;background:var(--lantai);border-radius:var(--r-kontrol);display:grid;place-items:center;color:var(--tinta-2);font-size:15px;overflow:hidden;">
            <img id="bukti-img" src="" alt="Bukti transfer" style="width:100%;height:100%;object-fit:contain;display:none;">
            <span id="bukti-kosong" style="display:none;">[Foto bukti transfer tidak tersedia]</span>
        </div>
        <div class="aksi"><button type="button" class="btn btn-kedua btn-kecil" data-tutup>Tutup</button></div>
    </dialog>
@endsection

@push('scripts')
<script>
    document.querySelectorAll('[data-buka]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const dialogId = btn.dataset.buka;
            const dialog = document.getElementById(dialogId);
            if (!dialog) return;

            if (dialogId === 'dialog-bukti') {
                const img = document.getElementById('bukti-img');
                const kosong = document.getElementById('bukti-kosong');
                document.getElementById('bukti-pesanan').textContent = btn.dataset.pesanan || '';
                document.getElementById('bukti-pelanggan').textContent = btn.dataset.pelanggan || '';
                document.getElementById('bukti-total').textContent = btn.dataset.total || '';

                if (btn.dataset.bukti) {
                    img.src = btn.dataset.bukti;
                    img.style.display = 'block';
                    kosong.style.display = 'none';
                } else {
                    img.style.display = 'none';
                    kosong.style.display = 'block';
                }
            }

            dialog.showModal();
        });
    });

    document.querySelectorAll('[data-tutup]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const dialog = btn.closest('dialog');
            if (dialog) dialog.close();
        });
    });

    const dialogBukti = document.getElementById('dialog-bukti');
    if (dialogBukti) {
        dialogBukti.addEventListener('click', (e) => {
            const rect = dialogBukti.getBoundingClientRect();
            if (
                e.clientX < rect.left ||
                e.clientX > rect.right ||
                e.clientY < rect.top ||
                e.clientY > rect.bottom
            ) {
                dialogBukti.close();
            }
        });
    }
</script>
@endpush
