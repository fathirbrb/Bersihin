@extends('layouts.admin')

@section('title', 'Profil · Admin Bersihin')

@section('content')
    <div class="kepala">
        <h1>Profil</h1>
        <p class="peran">Admin sejak {{ Auth::user()->created_at?->translatedFormat('d F Y') ?? '-' }}</p>
    </div>

    <div class="dua-kolom">
        <section>
            <h2 class="bagian">Data diri</h2>
            <form action="{{ route('admin.profil.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="profil-foto">
                    <span class="avatar avatar-besar" id="pratinjau-foto">
                        @if(Auth::user()->foto)
                            <img src="{{ asset('storage/' . Auth::user()->foto) }}" alt="Foto {{ Auth::user()->nama }}">
                        @else
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8.5" r="3.5"/><path d="M5 19.5a7 7 0 0 1 14 0"/></svg>
                        @endif
                    </span>
                    <div>
                        <div class="aksi">
                            <label class="btn btn-kedua btn-kecil pilih-berkas">Ganti foto<input type="file" id="foto" name="foto" accept="image/jpeg,image/png,image/jpg"></label>
                            <button type="button" class="btn btn-teks bahaya" id="hapus-foto" @if(!Auth::user()->foto) hidden @endif>Hapus foto</button>
                        </div>
                        <p class="sub-baris">JPG atau PNG, maks. 2 MB.</p>
                        <input type="hidden" name="hapus_foto" id="hapus_foto" value="0">
                        @error('foto') <p class="galat" style="margin-top:4px;">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="isian @error('nama') isian-salah @enderror">
                    <label for="nama">Nama</label>
                    <input type="text" id="nama" name="nama" value="{{ old('nama', Auth::user()->nama) }}" autocomplete="name" required>
                    @error('nama') <p class="galat">{{ $message }}</p> @enderror
                </div>

                <!--<div class="isian @error('email') isian-salah @enderror">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email', Auth::user()->email) }}" autocomplete="email" required readonly>
                    @error('email') <p class="galat">{{ $message }}</p> @enderror
                </div>-->

                <div class="isian @error('no_hp') isian-salah @enderror">
                    <label for="no_hp">Nomor HP</label>
                    <input type="tel" id="no_hp" name="no_hp" value="{{ old('no_hp', Auth::user()->no_hp) }}" autocomplete="tel" inputmode="tel" required>
                    @error('no_hp') <p class="galat">{{ $message }}</p> @enderror
                </div>

                <button type="submit" class="btn btn-utama btn-penuh">Simpan</button>
            </form>
        </section>

        <section>
            <h2 class="bagian">Kata sandi</h2>
            <form action="{{ route('admin.profil.password') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="isian @error('current_password') isian-salah @enderror">
                    <label for="current_password">Kata sandi sekarang</label>
                    <input type="password" id="current_password" name="current_password" autocomplete="current-password" required>
                    @error('current_password') <p class="galat">{{ $message }}</p> @enderror
                </div>

                <div class="isian @error('password') isian-salah @enderror">
                    <label for="password">Kata sandi baru</label>
                    <input type="password" id="password" name="password" autocomplete="new-password" minlength="8" required>
                    <p class="bantu">Minimal 8 karakter.</p>
                    @error('password') <p class="galat">{{ $message }}</p> @enderror
                </div>

                <div class="isian">
                    <label for="password_confirmation">Ulangi kata sandi baru</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="new-password" required>
                </div>

                <button type="submit" class="btn btn-kedua btn-penuh">Ganti kata sandi</button>
            </form>

            @if(Route::has('logout'))
                <form action="{{ route('logout') }}" method="POST" style="margin-top:40px;">
                    @csrf
                    <button type="submit" class="btn btn-bahaya btn-penuh">Keluar</button>
                </form>
            @endif
        </section>
    </div>
@endsection

@push('scripts')
<script>
    const inputFoto = document.getElementById('foto');
    if (inputFoto) {
        const pratinjau = document.getElementById('pratinjau-foto');
        const hapus = document.getElementById('hapus-foto');
        const ikonDefault = `<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="8.5" r="3.5"/><path d="M5 19.5a7 7 0 0 1 14 0"/></svg>`;
        const hapusFlag = document.getElementById('hapus_foto');

        inputFoto.addEventListener('change', () => {
            const berkas = inputFoto.files[0];
            if (!berkas) return;
            pratinjau.innerHTML = `<img src="${URL.createObjectURL(berkas)}" alt="Foto profil baru">`;
            if (hapus) hapus.hidden = false;
            if (hapusFlag) hapusFlag.value = '0';
        });

        if (hapus) {
            hapus.addEventListener('click', () => {
                pratinjau.innerHTML = ikonDefault;
                inputFoto.value = '';
                hapus.hidden = true;
                if (hapusFlag) hapusFlag.value = '1';
            });
        }
    }
</script>
@endpush
