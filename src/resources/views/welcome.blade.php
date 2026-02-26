@extends('layouts.app')
@section('content')
<div class="min-h-screen flex flex-col">
    <section class="relative h-screen flex flex-col items-center justify-center px-6 overflow-hidden">
        <div class="text-center z-10">
            <h1 class="text-7xl md:text-9xl font-serif font-bold leading-[0.8] mb-6">
                Kopi <br> <span class="italic text-[#5A5A40]">Brader.</span>
            </h1>
            <p class="text-lg text-black/60 mb-10 max-w-md mx-auto">Sistem pemesanan kafe modern. Scan, pesan, dan bayar langsung dari meja Anda.</p>
            <div class="flex gap-4 justify-center">
                <a href="/s/01" class="bg-[#5A5A40] text-white px-8 py-4 rounded-2xl font-bold shadow-xl">Pesan Sekarang</a>
                <a href="/login" class="px-8 py-4 rounded-2xl font-bold border border-black/10">Admin Portal</a>
            </div>
        </div>
    </section>
</div>
@endsection