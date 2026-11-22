// Khusus mockup, tidak perlu dibawa ke Blade

// Varian keadaan lewat #hash, contoh #kosong
function tampilkanVarian() {
    const varian = location.hash.slice(1) || 'awal';
    document.querySelectorAll('[data-saat]').forEach((el) => {
        el.hidden = !el.dataset.saat.split(' ').includes(varian);
        el.querySelectorAll('input, textarea, select').forEach((c) => { c.disabled = el.hidden; });
    });
}
tampilkanVarian();
window.addEventListener('hashchange', tampilkanVarian);

// Tombol kirim berubah jadi "sedang proses"
document.querySelectorAll('form[data-proses]').forEach((form) => {
    form.addEventListener('submit', () => {
        const btn = form.querySelector('button[type=submit]');
        if (!btn) return;
        btn.disabled = true;
        btn.textContent = form.dataset.proses;
    });
});

// Buka dan tutup dialog
document.querySelectorAll('[data-buka]').forEach((btn) => {
    btn.addEventListener('click', () => document.getElementById(btn.dataset.buka).showModal());
});
document.querySelectorAll('[data-tutup]').forEach((btn) => {
    btn.addEventListener('click', () => btn.closest('dialog').close());
});

// Isi dialog ubah paket dari baris tabel
document.querySelectorAll('[data-paket]').forEach((btn) => {
    btn.addEventListener('click', () => {
        const p = JSON.parse(btn.dataset.paket);
        const f = document.getElementById('form-paket');
        Object.entries(p).forEach(([k, v]) => { if (f.elements[k]) f.elements[k].value = v; });
    });
});

// Saring baris tabel per status
const saring = document.querySelector('.saring');
if (saring) {
    saring.addEventListener('click', (e) => {
        const btn = e.target.closest('button');
        if (!btn) return;
        saring.querySelectorAll('button').forEach((b) => b.setAttribute('aria-pressed', b === btn));
        let ada = 0;
        document.querySelectorAll('tr[data-status]').forEach((tr) => {
            tr.hidden = btn.dataset.status !== 'semua' && tr.dataset.status !== btn.dataset.status;
            if (!tr.hidden) ada++;
        });
        document.getElementById('tabel-isi').hidden = ada === 0;
        document.getElementById('tabel-kosong').hidden = ada > 0;
    });
}

// Bukti transfer hanya untuk metode transfer
const bukti = document.getElementById('isian-bukti');
if (bukti) {
    const atur = () => {
        const metode = document.querySelector('input[name=metode]:checked');
        bukti.hidden = !metode || metode.value !== 'transfer';
    };
    document.querySelectorAll('input[name=metode]').forEach((r) => r.addEventListener('change', atur));
    atur();
}

// Nama file bukti transfer yang dipilih
const inputBukti = document.getElementById('bukti_bayar');
if (inputBukti) {
    inputBukti.addEventListener('change', () => {
        const berkas = inputBukti.files[0];
        if (berkas) document.getElementById('nama-bukti').textContent = berkas.name;
    });
}

// Total di tombol pesan ikut paket
const tombolPesan = document.getElementById('tombol-pesan');
if (tombolPesan) {
    const pilihPaket = new URLSearchParams(location.search).get('paket');
    if (pilihPaket) {
        const r = document.querySelector(`input[name=paket_layanan_id][value="${pilihPaket}"]`);
        if (r) r.checked = true;
    }
    const atur = () => {
        const r = document.querySelector('input[name=paket_layanan_id]:checked');
        tombolPesan.textContent = r ? `Pesan · ${r.dataset.harga}` : 'Pesan';
    };
    document.querySelectorAll('input[name=paket_layanan_id]').forEach((r) => r.addEventListener('change', atur));
    atur();
}

// Keterangan bintang yang dipilih
const ratingLabel = document.getElementById('rating-label');
if (ratingLabel) {
    document.querySelectorAll('input[name=rating]').forEach((r) => {
        r.addEventListener('change', () => { ratingLabel.textContent = r.dataset.label; });
    });
}

// Pratinjau foto profil sebelum disimpan
const inputFoto = document.getElementById('foto');
if (inputFoto) {
    const pratinjau = document.getElementById('pratinjau-foto');
    const hapus = document.getElementById('hapus-foto');
    const ikonAsli = pratinjau.innerHTML;
    const hapusFlag = document.querySelector('input[name=hapus_foto]');
    inputFoto.addEventListener('change', () => {
        const berkas = inputFoto.files[0];
        if (!berkas) return;
        pratinjau.innerHTML = `<img src="${URL.createObjectURL(berkas)}" alt="Foto profil baru">`;
        hapus.hidden = false;
        hapusFlag.value = '0';
    });
    hapus.addEventListener('click', () => {
        pratinjau.innerHTML = ikonAsli;
        inputFoto.value = '';
        hapus.hidden = true;
        hapusFlag.value = '1';
    });
}

// Pilih peran dari #petugas di halaman daftar
const peran = document.getElementById('peran-petugas');
if (peran) {
    const atur = () => { document.getElementById('catatan-petugas').hidden = !peran.checked; };
    const dariHash = () => { if (location.hash === '#petugas') peran.checked = true; atur(); };
    document.querySelectorAll('input[name=role]').forEach((r) => r.addEventListener('change', atur));
    window.addEventListener('hashchange', dariHash);
    dariHash();
}
