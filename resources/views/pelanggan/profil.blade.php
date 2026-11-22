@extends('layouts.pelanggan')

@section('title', 'Profil')

@section('content')
<div class="kepala">
    <h1>Profil</h1>
    <p class="peran">Pelanggan sejak {{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : '-' }}</p>
</div>

<div class="dua-kolom">
    <section>
        <h2 class="bagian">Data diri</h2>
        <form action="{{ route('pelanggan.profil.update') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="profil-foto">
                <span class="avatar avatar-besar" id="pratinjau-foto">
                    @if($user->foto)
                        <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->nama }}" id="img-avatar">
                    @else
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" id="svg-avatar"><circle cx="12" cy="8.5" r="3.5"/><path d="M5 19.5a7 7 0 0 1 14 0"/></svg>
                    @endif
                </span>
                <div>
                    <div class="aksi">
                        <label class="btn btn-kedua btn-kecil pilih-berkas">
                            Ganti foto
                            <input type="file" id="foto" name="foto" accept="image/jpeg,image/png" onchange="previewAvatar(this)">
                        </label>
                        <button type="button" class="btn btn-teks bahaya" id="hapus-foto" onclick="hapusAvatar()" {{ $user->foto ? '' : 'hidden' }}>Hapus foto</button>
                    </div>
                    <p class="sub-baris">JPG atau PNG, maks. 2 MB.</p>
                    <input type="hidden" name="hapus_foto" id="input-hapus-foto" value="0">
                </div>
            </div>

            <div class="isian {{ $errors->has('nama') ? 'isian-salah' : '' }}">
                <label for="nama">Nama</label>
                <input type="text" id="nama" name="nama" value="{{ old('nama', $user->nama) }}" autocomplete="name" required>
                @error('nama')
                    <p class="galat">{{ $message }}</p>
                @enderror
            </div>

            <div class="isian {{ $errors->has('email') ? 'isian-salah' : '' }}">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" autocomplete="email" required>
                @error('email')
                    <p class="galat" id="galat-email">{{ $message }}</p>
                @enderror
            </div>

            <div class="isian {{ $errors->has('no_hp') ? 'isian-salah' : '' }}">
                <label for="no_hp">Nomor HP</label>
                <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp', $user->no_hp) }}" autocomplete="tel" inputmode="tel" required>
                @error('no_hp')
                    <p class="galat">{{ $message }}</p>
                @enderror
            </div>

            <button type="submit" class="btn btn-utama btn-penuh">Simpan</button>
        </form>
    </section>

    <section>
        <h2 class="bagian">Kata sandi</h2>
        <form action="{{ route('pelanggan.profil.password') }}" method="POST">
            @csrf
            @method('PUT')

            <input type="text" name="username" value="{{ $user->email }}" autocomplete="username" hidden>

            <div class="isian {{ $errors->has('current_password') ? 'isian-salah' : '' }}">
                <label for="current_password">Kata sandi sekarang</label>
                <input type="password" id="current_password" name="current_password" autocomplete="current-password" required>
                @error('current_password')
                    <p class="galat">{{ $message }}</p>
                @enderror
            </div>

            <div class="isian {{ $errors->has('password') ? 'isian-salah' : '' }}">
                <label for="password">Kata sandi baru</label>
                <input type="password" id="password" name="password" autocomplete="new-password" minlength="8" required>
                <p class="bantu">Minimal 8 karakter.</p>
                @error('password')
                    <p class="galat">{{ $message }}</p>
                @enderror
            </div>

            <div class="isian">
                <label for="password_confirmation">Ulangi kata sandi baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
            </div>

            <button type="submit" class="btn btn-kedua btn-penuh">Ganti kata sandi</button>
        </form>

        <form action="{{ route('logout') }}" method="POST" style="margin-top:40px;">
            @csrf
            <button type="submit" class="btn btn-bahaya btn-penuh">Keluar</button>
        </form>
    </section>
</div>

@push('scripts')
<script>
    function previewAvatar(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                const container = document.getElementById('pratinjau-foto');
                container.innerHTML = '<img src="' + e.target.result + '" alt="Pratinjau">';
                document.getElementById('hapus-foto').removeAttribute('hidden');
                document.getElementById('input-hapus-foto').value = '0';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function hapusAvatar() {
        const container = document.getElementById('pratinjau-foto');
        container.innerHTML = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8.5" r="3.5"/><path d="M5 19.5a7 7 0 0 1 14 0"/></svg>';
        document.getElementById('foto').value = '';
        document.getElementById('hapus-foto').setAttribute('hidden', 'true');
        document.getElementById('input-hapus-foto').value = '1';
    }
</script>
@endpush
@endsection

