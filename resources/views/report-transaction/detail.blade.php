@extends('layouts.main')

@section('content-dashboard')
    <div class="row me-lg-4 pb-4 d-none d-md-inline-block" style="margin-top: 32px;">
        <div class="col-12 pe-lg-0">
            @if(session()->has('failed'))
                <div class="alert alert-danger w-100 mb-4" role="alert">
                    {{ session('failed') }}
                </div>
            @endif
        </div>
        <div class="col-12 pe-lg-0">
            <form class="row row-cols-1 row-cols-md-2">
                <div class="col mb-3">
                    <label for="customers_id" class="form-label">Nama Pelanggan</label>
                    <input readonly type="text" class="form-control" id="customers_id" value="{{ $transaction->customer->name }}">
                </div>
                <div class="col mb-3">
                    <label for="resellers_id" class="form-label">Nama Karyawan</label>
                    <input readonly type="text" class="form-control" id="resellers_id" value="{{ $transaction->reseller->name }}">
                </div>
                <div class="col mb-3">
                    <label for="products_id" class="form-label">Nama Produk</label>
                    <input readonly type="text" class="form-control" id="products_id" value="{{ $transaction->product->name }}">
                </div>
                <div class="col mb-3">
                    <label for="quantity" class="form-label">Kuantitas Dibeli</label>
                    <input readonly type="number" class="form-control" id="quantity" value="{{ $transaction->quantity }}">
                </div>
                <div class="col mb-3">
                    <label for="price_per_unit" class="form-label">Harga Satuan</label>
                    <input readonly type="text" class="form-control" id="price_per_unit" value="Rp. {{ number_format($transaction->product->selling_price, 2, ",", ".") }}">
                </div>
                <div class="col mb-3">
                    <label for="total" class="form-label">Total</label>
                    <input readonly type="text" class="form-control" id="total" value="Rp. {{ number_format($transaction->total, 2, ",", ".") }}">
                </div>
                <div class="col mb-3">
                    <label for="total_payment" class="form-label">Total Bayar</label>
                    <input readonly type="text" class="form-control" id="total_payment" value="Rp. {{ number_format($transaction->total_payment, 2, ",", ".") }}">
                </div>
                <div class="col mb-3">
                    <label for="created_at" class="form-label">Transaksi Dilakukan</label>
                    <input readonly type="text" class="form-control" id="created_at" value="{{ Carbon\Carbon::parse($transaction->created_at)->format('l, d F Y') }}">
                </div>
                <div class="col">
                    <button type="button" class="button-dark" onClick="history_back()">Kembali ke Halaman</button>
                </div>
            </form>
        </div>
    </div>

    @push('js')
        <script>
            function history_back() {
                window.history.back();
            }
        </script>
    @endpush
@endsection
