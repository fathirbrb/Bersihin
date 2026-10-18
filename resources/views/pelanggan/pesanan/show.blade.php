@extends('layouts.pelanggan')

@section('title', 'Detail Pesanan')

@section('content')
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.25rem;">
        <h1 class="page-title" style="margin-bottom:0;">Detail Pesanan #{{ $pesanan->id }}</h1>
        <a href="{{ route('pelanggan.pesanan.index') }}" class="btn btn-secondary">← Kembali</a>
    </div>

    <div class="card">
        <table style="border-collapse:collapse; width:100%;">
            <tr>
                <td style="padding:.5rem .75rem; font-weight:600; width:200px;">Paket Layanan</td>
                <td style="padding:.5rem .75rem;">{{ $pesanan->paketLayanan->nama_paket ?? '-' }}</td>
            </tr>
            <tr>
                <td style="padding:.5rem .75rem; font-weight:600;">Alamat</td>
                <td style="padding:.5rem .75rem;">{{ $pesanan->alamat }}</td>
            </tr>
            <tr>
                <td style="padding:.5rem .75rem; font-weight:600;">Tanggal & Jam</td>
                <td style="padding:.5rem .75rem;">{{ $pesanan->tanggal->format('d M Y') }} — {{ $pesanan->jam_mulai }}</td>
            </tr>
            <tr>
                <td style="padding:.5rem .75rem; font-weight:600;">Catatan</td>
                <td style="padding:.5rem .75rem;">{{ $pesanan->catatan ?: '—' }}</td>
            </tr>
            <tr>
                <td style="padding:.5rem .75rem; font-weight:600;">Total Harga</td>
                <td style="padding:.5rem .75rem; font-weight:700;">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td style="padding:.5rem .75rem; font-weight:600;">Status</td>
                <td style="padding:.5rem .75rem;"><span class="badge badge-{{ $pesanan->status }}">{{ $pesanan->status }}</span></td>
            </tr>
            <tr>
                <td style="padding:.5rem .75rem; font-weight:600;">Petugas</td>
                <td style="padding:.5rem .75rem;">{{ $pesanan->petugas->nama ?? 'Belum ada petugas' }}</td>
            </tr>
        </table>
    </div>

    {{-- Tombol Batalkan --}}
    @if($pesanan->status === 'menunggu')
        <div class="card">
            <form action="{{ route('pelanggan.pesanan.batalkan', $pesanan->id) }}" method="POST"
                  onsubmit="return confirm('Yakin ingin membatalkan pesanan ini?')">
                @csrf
                <button type="submit" class="btn btn-danger">Batalkan Pesanan</button>
            </form>
        </div>
    @endif

    {{-- Ulasan --}}
    @if($pesanan->status === 'selesai')
        <div class="card">
            <h2 style="font-size:1.15rem; font-weight:700; margin-bottom:.75rem;">Ulasan</h2>

            @if($pesanan->ulasan)
                <p>
                    <span class="star">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $pesanan->ulasan->rating ? '★' : '☆' }}
                        @endfor
                    </span>
                    ({{ $pesanan->ulasan->rating }}/5)
                </p>
                <p style="margin-top:.4rem;">{{ $pesanan->ulasan->komentar ?: '—' }}</p>
            @else
                <p style="margin-bottom:.75rem; color:#6b7280;">Belum ada ulasan untuk pesanan ini.</p>
                <a href="{{ route('pelanggan.ulasan.create', $pesanan->id) }}" class="btn btn-warning">Tulis Ulasan</a>
            @endif
        </div>
    @endif

    {{-- Pembayaran --}}
    @if($pesanan->pembayaran)
        {{-- ── Info Pembayaran yang sudah ada ── --}}
        <div class="card">
            <h2 style="font-size:1.15rem; font-weight:700; margin-bottom:.75rem;">💳 Pembayaran</h2>
            <table style="border-collapse:collapse; width:100%;">
                <tr>
                    <td style="padding:.5rem .75rem; font-weight:600; width:200px;">Metode</td>
                    <td style="padding:.5rem .75rem; text-transform:capitalize;">{{ $pesanan->pembayaran->metode }}</td>
                </tr>
                <tr>
                    <td style="padding:.5rem .75rem; font-weight:600;">Status</td>
                    <td style="padding:.5rem .75rem;">
                        <span class="badge badge-{{ $pesanan->pembayaran->status === 'lunas' ? 'selesai' : ($pesanan->pembayaran->status === 'ditolak' ? 'dibatalkan' : 'menunggu') }}">
                            {{ $pesanan->pembayaran->status }}
                        </span>
                    </td>
                </tr>
                @if($pesanan->pembayaran->bukti_bayar)
                <tr>
                    <td style="padding:.5rem .75rem; font-weight:600;">Bukti Pembayaran</td>
                    <td style="padding:.5rem .75rem;">
                        <img src="{{ asset('storage/' . $pesanan->pembayaran->bukti_bayar) }}"
                             alt="Bukti Pembayaran"
                             style="max-width:320px; border-radius:8px; border:1px solid #e5e7eb; cursor:pointer;"
                             onclick="window.open(this.src, '_blank')">
                    </td>
                </tr>
                @endif
            </table>
        </div>
    @elseif($pesanan->status !== 'dibatalkan')
        {{-- ── Form Pembayaran Baru ── --}}
        <div class="card">
            <h2 style="font-size:1.15rem; font-weight:700; margin-bottom:.75rem;">💳 Pembayaran</h2>

            {{-- Ringkasan total harga --}}
            <div style="background:linear-gradient(135deg,#ecfdf5,#d1fae5); border-radius:10px; padding:1rem 1.25rem; margin-bottom:1.25rem; display:flex; justify-content:space-between; align-items:center;">
                <div>
                    <p style="font-size:.85rem; color:#065f46; margin-bottom:.15rem;">Total yang harus dibayar</p>
                    <p style="font-size:1.5rem; font-weight:800; color:#047857;">Rp{{ number_format($pesanan->total_harga, 0, ',', '.') }}</p>
                </div>
                <span style="font-size:2rem;">🧾</span>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    <ul style="margin:0; padding-left:1.25rem;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('pelanggan.pembayaran.store', $pesanan->id) }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- Pilihan Metode --}}
                <div class="form-group">
                    <label>Metode Pembayaran</label>
                    <div style="display:flex; gap:.75rem; margin-top:.35rem;">
                        {{-- Tombol Transfer --}}
                        <label id="label-transfer" style="flex:1; cursor:pointer;">
                            <input type="radio" name="metode" value="transfer" {{ old('metode', 'transfer') === 'transfer' ? 'checked' : '' }}
                                   style="display:none;" onchange="toggleMetode()">
                            <div class="metode-option" id="opt-transfer">
                                <span style="font-size:1.35rem;">🏦</span>
                                <span style="font-weight:600;">Transfer</span>
                                <span style="font-size:.75rem; color:#6b7280;">Upload bukti bayar</span>
                            </div>
                        </label>
                        {{-- Tombol Tunai --}}
                        <label id="label-tunai" style="flex:1; cursor:pointer;">
                            <input type="radio" name="metode" value="tunai" {{ old('metode') === 'tunai' ? 'checked' : '' }}
                                   style="display:none;" onchange="toggleMetode()">
                            <div class="metode-option" id="opt-tunai">
                                <span style="font-size:1.35rem;">💵</span>
                                <span style="font-weight:600;">Tunai</span>
                                <span style="font-size:.75rem; color:#6b7280;">Bayar langsung</span>
                            </div>
                        </label>
                    </div>
                    @error('metode')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Area Upload Bukti Bayar (hanya muncul jika transfer) --}}
                <div class="form-group" id="upload-area">
                    <label for="bukti_bayar">Upload Bukti Pembayaran</label>
                    <div id="dropzone" style="border:2px dashed #d1d5db; border-radius:10px; padding:2rem 1rem; text-align:center; cursor:pointer; transition:border-color .2s, background .2s;"
                         onclick="document.getElementById('bukti_bayar').click()">
                        <input type="file" name="bukti_bayar" id="bukti_bayar" accept="image/jpeg,image/png,image/jpg"
                               style="display:none;" onchange="previewImage(event)">
                        <div id="upload-placeholder">
                            <span style="font-size:2.5rem; display:block; margin-bottom:.5rem;">📤</span>
                            <p style="font-weight:600; color:#374151;">Klik atau seret file ke sini</p>
                            <p style="font-size:.8rem; color:#9ca3af;">Format: JPG, JPEG, PNG — Maks 2 MB</p>
                        </div>
                        <div id="upload-preview" style="display:none;">
                            <img id="preview-img" src="" alt="Preview"
                                 style="max-width:280px; max-height:200px; border-radius:8px; border:1px solid #e5e7eb;">
                            <p style="margin-top:.5rem; font-size:.85rem; color:#059669; font-weight:600;" id="file-name"></p>
                        </div>
                    </div>
                    @error('bukti_bayar')
                        <p class="text-danger">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary" style="width:100%; padding:.75rem; font-size:1rem; margin-top:.5rem;">
                    Kirim Pembayaran
                </button>
            </form>
        </div>
    @endif
@endsection

@push('styles')
<style>
    .metode-option {
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: .25rem;
        padding: 1rem .75rem;
        border: 2px solid #e5e7eb;
        border-radius: 10px;
        transition: all .2s;
        background: #fff;
    }
    .metode-option.active {
        border-color: #0f9b58;
        background: #ecfdf5;
        box-shadow: 0 0 0 3px rgba(15,155,88,.15);
    }
    .metode-option:hover {
        border-color: #a7f3d0;
    }
    #dropzone:hover, #dropzone.dragover {
        border-color: #0f9b58;
        background: #f0fdf4;
    }
</style>
@endpush

@push('scripts')
<script>
    function toggleMetode() {
        const metode = document.querySelector('input[name="metode"]:checked')?.value;
        const uploadArea = document.getElementById('upload-area');
        const optTransfer = document.getElementById('opt-transfer');
        const optTunai = document.getElementById('opt-tunai');

        // Toggle active class
        optTransfer.classList.toggle('active', metode === 'transfer');
        optTunai.classList.toggle('active', metode === 'tunai');

        // Show/hide upload area
        uploadArea.style.display = metode === 'transfer' ? 'block' : 'none';

        // Clear file input jika pindah ke tunai
        if (metode === 'tunai') {
            document.getElementById('bukti_bayar').value = '';
            document.getElementById('upload-placeholder').style.display = 'block';
            document.getElementById('upload-preview').style.display = 'none';
        }
    }

    function previewImage(event) {
        const file = event.target.files[0];
        if (!file) return;

        // Validasi ukuran di client-side
        if (file.size > 2048 * 1024) {
            alert('Ukuran file melebihi 2 MB. Silakan pilih file yang lebih kecil.');
            event.target.value = '';
            return;
        }

        const reader = new FileReader();
        reader.onload = function (e) {
            document.getElementById('preview-img').src = e.target.result;
            document.getElementById('file-name').textContent = file.name;
            document.getElementById('upload-placeholder').style.display = 'none';
            document.getElementById('upload-preview').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }

    // Drag-and-drop support
    const dropzone = document.getElementById('dropzone');
    if (dropzone) {
        ['dragenter', 'dragover'].forEach(evt =>
            dropzone.addEventListener(evt, e => { e.preventDefault(); dropzone.classList.add('dragover'); })
        );
        ['dragleave', 'drop'].forEach(evt =>
            dropzone.addEventListener(evt, e => { e.preventDefault(); dropzone.classList.remove('dragover'); })
        );
        dropzone.addEventListener('drop', e => {
            const files = e.dataTransfer.files;
            if (files.length) {
                document.getElementById('bukti_bayar').files = files;
                previewImage({ target: { files: files } });
            }
        });
    }

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', toggleMetode);
</script>
@endpush
