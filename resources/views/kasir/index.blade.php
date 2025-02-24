@extends('layouts.main')

@section('title', 'Kasir')
@section('header')
    Kasir
@endsection



@section('content')
    <div class="container">
        <div class="row">
            <!-- Form Pilihan Barang -->
            <div class="col-md-5">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Form Input Barang</h4>
                    </div>
                    <div class="card-body">
                        <div class="form-group">
                            <label for="pengguna">Pilih Pelanggan:</label>
                            <select id="pengguna" class="form-control">
                                <option value="">-- Pilih --</option>
                                @foreach ($users as $user)
                                    <option value="{{ $user->id }}" data-poin="{{ $user->membership_poin ?? 0 }}">
                                        {{ $user->name }} (Tipe Pelanggan: {{ $user->tipe_pelanggan ?? 'Tanpa Tipe' }})
                                    </option>
                                @endforeach
                            </select>

                        </div>

                        <div class="form-group">
                            <label for="barang">Pilih Barang:</label>
                            <select id="barang" class="form-control">
                                <option value="">-- Pilih Barang --</option>
                                @foreach ($barang as $b)
                                    <option value="{{ $b->id }}" data-harga="{{ $b->harga_jual }}"
                                        data-stok="{{ $b->stock_barang }}">
                                        {{ $b->nama_barang }} - Rp {{ number_format($b->harga_jual, 2) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="quantity">Jumlah:</label>
                            <input type="number" id="quantity" class="form-control" placeholder="Masukkan jumlah"
                                min="1">
                        </div>

                        <button class="btn btn-primary btn-block" id="addItem">Tambah Barang</button>
                    </div>
                </div>
            </div>

            <!-- Nota Belanja -->
            <div class="col-md-7">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4>Nota Belanja</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="bg-primary text-white">
                                    <tr>
                                        <th>Barang</th>
                                        <th>Harga</th>
                                        <th>Qty</th>
                                        <th>Subtotal</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="cart"></tbody>
                            </table>
                        </div>

                        <div class="bg-light p-3 rounded">
                            <p>Subtotal: <strong id="subtotalAwal">Rp 0</strong></p>

                            <!-- Pilihan Diskon -->
                            <select id="diskon" class="form-control" disabled>
                                <option value="">-- Pilih Diskon --</option>
                                @foreach ($diskon as $d)
                                    <option value="{{ $d->id }}" data-diskon-persen="{{ $d->diskon_persen }}"
                                        data-min-pembelanjaan="{{ $d->min_pembelanjaan }}"
                                        data-status="{{ $d->status }}">
                                        {{ $d->diskon_persen }}% (Min. Rp {{ number_format($d->min_pembelanjaan, 2) }})
                                    </option>
                                @endforeach
                            </select>


                            <p>Potongan Diskon: <strong id="potonganDiskon">Rp 0</strong></p>
                            <p>Total Setelah Diskon: <strong id="totalSetelahDiskon">Rp 0</strong></p>

                            <!-- Pilihan Poin -->
                            <div class="form-group mt-2">
                                <p>Poin Pelanggan: <strong id="poin_pengguna">0</strong></p>

                                <label for="poin">Gunakan Poin (1 Poin = Rp 1):</label>
                                <input type="number" id="poin" class="form-control" placeholder="Masukkan jumlah poin"
                                    min="0" disabled>
                            </div>

                            <p>Potongan Poin: <strong id="potonganPoin">Rp 0</strong></p>

                            <p>PPN (12%): <strong id="ppn">Rp 0</strong></p>

                            <h4>Total Akhir: <strong id="totalAkhir">Rp 0</strong></h4>

                            <div class="form-group mt-2">
                                <label for="uangMasuk">Masukkan Nominal Uang Masuk:</label>
                                <input type="number" id="uangMasuk" class="form-control"
                                    placeholder="Masukkan nominal dalam rupiah (contoh: 200000)">
                            </div>

                            <h4>Kembalian: <strong id="uangKembalian">Rp 0</strong></h4>


                            <button class="btn btn-success btn-block mt-3" id="checkout">Selesaikan Transaksi</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {
            let cart = [];

            function formatRupiah(angka) {
                if (isNaN(angka) || angka === null || angka === undefined) {
                    return 'Rp 0';
                }
                return 'Rp ' + new Intl.NumberFormat('id-ID', {
                    maximumFractionDigits: 0
                }).format(angka);
            }


            function updateCart() {
                let total = 0;
                $('#cart').html('');
                cart.forEach((item, index) => {
                    let subtotal = item.harga_jual * item.quantity;
                    total += subtotal;
                    $('#cart').append(`
            <tr>
                <td>${item.nama_barang}</td>
                <td>${formatRupiah(item.harga_jual)}</td>
                <td class="text-center">
                    <div class="d-flex justify-content-center align-items-center gap-2">
                        <button class="btn btn-sm btn-primary" onclick="increaseQty(${index})">
                            <i class="fas fa-plus"></i>
                        </button>
                        <span>${item.quantity}</span>
                        <button class="btn btn-sm btn-danger" onclick="decreaseQty(${index})">
                            <i class="fas fa-minus"></i>
                        </button>
                    </div>
                </td>
                <td>${formatRupiah(subtotal)}</td>
                <td>
                    <button class="btn btn-danger btn-sm" onclick="removeItem(${index})">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `);
                });

                $('#subtotalAwal').text(formatRupiah(total));
                updateDiscountOptions(total);
                calculateTotal();
            }




            function updateDiscountOptions(total) {
                let hasValidDiscount = false;
                let penggunaDipilih = $('#pengguna').val(); // Cek apakah pengguna dipilih

                $('#diskon option').each(function() {
                    let minPembelanjaan = parseFloat($(this).data('min-pembelanjaan')) || 0;
                    let statusDiskon = parseInt($(this).data('status')) || 0;

                    let isDisabled = !penggunaDipilih || total < minPembelanjaan || statusDiskon === 0;
                    $(this).prop('disabled', isDisabled);

                    if (!isDisabled && $(this).val() !== "") {
                        hasValidDiscount = true;
                    }
                });

                $('#diskon').prop('disabled', !hasValidDiscount);
                calculateTotal();
            }

            // Jalankan fungsi saat halaman dimuat
            updateDiscountOptions(0);


            function calculateTotal() {
    let subtotal = parseFloat($('#subtotalAwal').text().replace(/[^0-9]/g, '')) || 0;
    let diskonPersen = parseFloat($('#diskon option:selected').attr('data-diskon-persen')) || 0;
    let poinDigunakan = parseFloat($('#poin').val().replace(/[^0-9]/g, '')) || 0;
    let maxPoin = parseFloat($('#poin').attr('max')) || 0;

    let potonganDiskon = Math.round(subtotal * (diskonPersen / 100));
    let totalSetelahDiskon = subtotal - potonganDiskon;
    let batasPoin = Math.floor(totalSetelahDiskon * 0.5);

    if (poinDigunakan > batasPoin) {
        poinDigunakan = batasPoin;
        $('#poin').val(batasPoin);
        alert(`Maksimal poin yang bisa digunakan adalah Rp ${batasPoin.toLocaleString()}`);
    }

    let nilaiPoin = Math.min(poinDigunakan, maxPoin);
    let totalSetelahPoin = Math.max(totalSetelahDiskon - nilaiPoin, 0);
    let ppn = Math.round(totalSetelahPoin * 0.12);
    let totalAkhir = Math.round(totalSetelahPoin + ppn);

    $('#potonganDiskon').text(formatRupiah(potonganDiskon));
    $('#totalSetelahDiskon').text(formatRupiah(totalSetelahDiskon));
    $('#potonganPoin').text(formatRupiah(nilaiPoin));
    $('#totalSetelahPoin').text(formatRupiah(totalSetelahPoin));
    $('#ppn').text(formatRupiah(ppn));
    $('#totalAkhir').text(formatRupiah(totalAkhir));

    calculateChange();
}

function calculateChange() {
    let uangMasuk = parseFloat($('#uangMasuk').val().replace(/[^\d]/g, '')) || 0;
    let totalAkhir = parseFloat($('#totalAkhir').text().replace(/[^\d]/g, '')) || 0;
    let uangKembalian = uangMasuk - totalAkhir;

    if (uangMasuk < totalAkhir) {
        $('#uangKembalian').text('Uang tidak cukup!').css('color', 'red');
    } else {
        $('#uangKembalian').text(formatRupiah(uangKembalian)).css('color', 'black');
    }
}

            window.increaseQty = function(index) {
                if (cart[index].quantity < cart[index].stock_barang) {
                    cart[index].quantity++;
                    updateCart();
                } else {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Stok Tidak Mencukupi!',
                        text: `Maksimal stok tersedia hanya ${cart[index].stock_barang}`,
                    });
                }
            };

            window.decreaseQty = function(index) {
                if (cart[index].quantity > 1) {
                    cart[index].quantity--;
                    updateCart();
                } else {
                    removeItem(index);
                }
            };

            window.removeItem = function(index) {
                cart.splice(index, 1);
                updateCart();
            };


            $('#pengguna').change(function() {
                let penggunaId = $(this).val();
                let maxPoin = $(this).find(':selected').data('poin') || 0;

                if (penggunaId) {
                    $('#poin').prop('disabled', maxPoin === 0).attr('max', maxPoin);
                    $('#poin_pengguna').text(formatRupiah(maxPoin));
                } else {
                    $('#poin').prop('disabled', true).val('');
                    $('#diskon').prop('disabled', true).val('');
                    $('#poin_pengguna').text(formatRupiah(0));
                }

                updateDiscountOptions(parseFloat($('#subtotalAwal').text().replace(/[^0-9]/g, '')) || 0);
            });

            $('#poin').on('input', function() {
                let maxPoin = parseInt($(this).attr('max')) || 0;
                let inputVal = parseInt($(this).val()) || 0;

                if (inputVal > maxPoin) {
                    $(this).val(maxPoin); // Set input ke maksimal jika melebihi batas
                }

                calculateTotal(); // Perbarui total setelah poin diubah
            });
            $('#addItem').click(function() {
                let id_barang = $('#barang').val();
                let id_pengguna = $('#pengguna').val() || null; // Pengguna bisa null
                let quantity = parseInt($('#quantity').val()) || 0;

                if (!id_barang || quantity < 1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Silakan pilih barang dan pastikan jumlah sesuai!'
                    });
                    return;
                }

                $.ajax({
                    url: '/kasir/addItem',
                    method: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id_barang,
                        id_pengguna,
                        quantity
                    },
                    success: function(response) {
                        if (!response || !response.id_barang) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error!',
                                text: 'Data yang dikembalikan tidak valid.'
                            });
                            return;
                        }

                        // Cek apakah barang sudah kedaluwarsa
                        let expiredDate = response.tanggal_kedaluarsa_terdekat;
                        if (expiredDate && new Date(expiredDate) < new Date()) {
                            Swal.fire({
                                icon: 'error',
                                title: 'Barang Kadaluarsa!',
                                text: 'Barang ini sudah melewati tanggal kedaluwarsa dan tidak bisa ditransaksikan.'
                            });
                            return;
                        }

                        // Mengecek apakah barang yang ada di keranjang sudah ada
                        let existingItem = cart.find(item => item.id_barang === response
                            .id_barang);
                        if (existingItem) {
                            // Jika barang sudah ada, periksa apakah jumlah stok cukup
                            if (existingItem.quantity + quantity > response.stock_barang) {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Stok Tidak Mencukupi!',
                                    text: `Maksimal stok tersedia hanya ${response.stock_barang}`
                                });
                                return;
                            }
                            // Menambahkan jumlah barang yang ada di keranjang
                            existingItem.quantity += quantity;
                        } else {
                            // Jika barang belum ada di keranjang, tambahkan baru
                            response.quantity = quantity;
                            cart.push(response);
                        }

                        // Beri peringatan jika barang mendekati kadaluwarsa
                        let today = new Date();
                        let expiredWarningDate = new Date();
                        expiredWarningDate.setDate(today.getDate() +
                        7); // Barang kedaluwarsa dalam 7 hari

                        if (expiredDate && new Date(expiredDate) < expiredWarningDate) {
                            // Format tanggal dalam dd/mm/yyyy
                            let formattedExpiredDate = new Date(expiredDate).toLocaleDateString(
                                'en-GB'); // 'en-GB' format -> dd/mm/yyyy

                            Swal.fire({
                                icon: 'warning',
                                title: 'Perhatian!',
                                text: `Barang ini akan kadaluwarsa dalam waktu dekat (${formattedExpiredDate}). Pastikan segera digunakan!`
                            });
                        }

                        // Update keranjang setelah perubahan
                        updateCart();
                    },
                    error: function(xhr) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON?.error ||
                                'Terjadi kesalahan saat menambahkan barang.'
                        });
                    }
                });
            });







            $('#diskon, #uangMasuk').on('input change', function() {
                calculateTotal();
            });

            $('#checkout').click(function() {
                let id_pengguna = $('#pengguna').val() ? parseInt($('#pengguna').val()) :
                    null; // Pengguna bisa null
                let total_pembelanjaan = parseFloat($('#totalAkhir').text().replace(/[^0-9.]/g, '')) || 0;
                total_pembelanjaan = total_pembelanjaan.toFixed(2);
                let id_diskon = $('#diskon').val() ? parseInt($('#diskon').val()) : null;
                let poin_digunakan = parseInt($('#poin').val()) || 0;
                let uang_masuk = parseFloat($('#uangMasuk').val()) || 0;

                if (cart.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Oops...',
                        text: 'Tambahkan barang terlebih dahulu!'
                    });
                    return;
                }

                $.ajax({
                    url: '/kasir/checkout',
                    method: 'POST',
                    contentType: "application/json",
                    data: JSON.stringify({
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id_pengguna,
                        total_pembelanjaan,
                        id_diskon,
                        poin_digunakan,
                        uang_masuk,
                        items: cart
                    }),
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: response.message
                        }).then(() => {
                            window.location.href = response.redirect_url;
                        });
                    },
                    error: function(xhr) {
                        console.error("Error Response:", xhr);
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: xhr.responseJSON?.error ||
                                'Gagal menyelesaikan transaksi.'
                        });
                    }
                });
            });



        });
    </script>
@endpush
