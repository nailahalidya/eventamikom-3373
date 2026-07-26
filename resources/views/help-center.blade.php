@extends('layouts.app')

@section('title', 'Pusat Bantuan & FAQ — AmikomEventHub')

@section('content')
<main class="min-h-screen py-16 px-6 max-w-7xl mx-auto">

    <!-- Header Section -->
    <div class="text-center max-w-3xl mx-auto mb-16 space-y-4">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-black uppercase tracking-wider">
            💬 Customer Care & Support
        </span>

        <h1 class="text-4xl md:text-6xl font-black text-slate-900 tracking-tight leading-tight">
            Pusat Bantuan <span class="text-indigo-600">AmikomEventHub</span>
        </h1>

        <p class="text-slate-500 text-base md:text-lg leading-relaxed font-medium">
            Temukan jawaban atas pertanyaan yang sering diajukan atau terhubung langsung dengan Tim Support WhatsApp kami.
        </p>

        <!-- Live FAQ Search Bar -->
        <div class="pt-4 max-w-xl mx-auto">
            <div class="relative">
                <input type="text" 
                       id="faq-search" 
                       placeholder="Cari pertanyaan... (contoh: cara pesan, e-ticket, refund, midtrans)"
                       class="w-full pl-12 pr-4 py-4 rounded-2xl border border-slate-200 shadow-lg shadow-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent text-sm font-medium transition">
                <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">
                    🔍
                </div>
            </div>
        </div>
    </div>

    <!-- Direct Contact Options Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 max-w-4xl mx-auto mb-16">
        <!-- WhatsApp CS Card -->
        <div class="bg-gradient-to-br from-emerald-600 to-green-700 text-white rounded-3xl p-8 shadow-xl shadow-green-600/20 relative overflow-hidden flex flex-col justify-between group">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            
            <div>
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center text-2xl mb-6 backdrop-blur border border-white/20">
                    💬
                </div>

                <h3 class="text-2xl font-black mb-2">Bantuan WhatsApp CS</h3>
                <p class="text-emerald-100 text-sm leading-relaxed mb-6 font-medium">
                    Ada pertanyaan atau butuh kendala transaksi segera diselesaikan? Obrolkan langsung dengan Customer Service kami via WhatsApp.
                </p>
            </div>

            <a href="https://wa.me/6281234567890?text=Halo%20Admin%20AmikomEventHub,%20saya%20butuh%20bantuan%20terkait%20transaksi%20event."
               target="_blank"
               class="inline-flex items-center justify-center gap-3 px-6 py-4 bg-white text-emerald-800 rounded-2xl font-black text-sm hover:bg-emerald-50 transition shadow-lg">
                <span>Chat CS via WhatsApp</span>
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-1.155 4.22 4.298-1.127z"/>
                </svg>
            </a>
        </div>

        <!-- Find Ticket Search Card -->
        <div class="bg-gradient-to-br from-indigo-600 to-indigo-800 text-white rounded-3xl p-8 shadow-xl shadow-indigo-600/20 relative overflow-hidden flex flex-col justify-between group">
            <div class="absolute -right-10 -bottom-10 w-40 h-40 bg-white/10 rounded-full blur-2xl group-hover:scale-150 transition-transform"></div>
            
            <div>
                <div class="w-14 h-14 bg-white/20 rounded-2xl flex items-center justify-center text-2xl mb-6 backdrop-blur border border-white/20">
                    🎟️
                </div>

                <h3 class="text-2xl font-black mb-2">Temukan Tiket Saya</h3>
                <p class="text-indigo-100 text-sm leading-relaxed mb-6 font-medium">
                    Email E-Ticket terhapus atau lupa simpan Kode QR? Masukkan Email atau Order ID kamu untuk mengambil tiket terpesan.
                </p>
            </div>

            <a href="{{ route('tickets.index') }}"
               class="inline-flex items-center justify-center gap-2 px-6 py-4 bg-white text-indigo-900 rounded-2xl font-black text-sm hover:bg-indigo-50 transition shadow-lg">
                <span>Cari Tiket Terpesan</span>
                <span>→</span>
            </a>
        </div>
    </div>

    <!-- FAQ Accordion Section -->
    <div class="max-w-4xl mx-auto space-y-10">
        <div class="text-center mb-8">
            <h2 class="text-3xl font-black text-slate-900">
                Pertanyaan yang Sering Diajukan (FAQ)
            </h2>
            <p class="text-slate-500 text-sm font-medium mt-1">
                Jelajahi panduan dan rincian teknis seputar layanan kami
            </p>
        </div>

        @foreach($faqs as $catIndex => $category)
        <div class="bg-white rounded-3xl p-8 border border-slate-100 shadow-sm space-y-6 faq-category">
            <div class="flex items-center gap-3 border-b border-slate-100 pb-4">
                <span class="text-2xl">{{ $category['icon'] }}</span>
                <h3 class="text-xl font-black text-slate-800">
                    {{ $category['category'] }}
                </h3>
            </div>

            <div class="space-y-4">
                @foreach($category['questions'] as $qIndex => $item)
                <div class="border border-slate-100 rounded-2xl overflow-hidden faq-item transition">
                    <button type="button" 
                            onclick="toggleFaq('faq-{{ $catIndex }}-{{ $qIndex }}')"
                            class="w-full p-5 text-left font-bold text-slate-800 flex justify-between items-center bg-slate-50/50 hover:bg-indigo-50/50 transition">
                        <span class="faq-question text-base">{{ $item['q'] }}</span>
                        <span id="icon-faq-{{ $catIndex }}-{{ $qIndex }}" class="text-xl font-light text-slate-400 transition-transform duration-300">
                            +
                        </span>
                    </button>
                    <div id="faq-{{ $catIndex }}-{{ $qIndex }}" class="hidden p-5 bg-white text-slate-600 text-sm leading-relaxed border-t border-slate-100 faq-answer">
                        {{ $item['a'] }}
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endforeach
    </div>

</main>

@push('scripts')
<script>
    function toggleFaq(id) {
        const content = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        if (content.classList.contains('hidden')) {
            content.classList.remove('hidden');
            icon.innerText = '−';
            icon.classList.add('text-indigo-600');
        } else {
            content.classList.add('hidden');
            icon.innerText = '+';
            icon.classList.remove('text-indigo-600');
        }
    }

    // Live search script
    document.getElementById('faq-search').addEventListener('input', function (e) {
        const query = e.target.value.toLowerCase().trim();
        const items = document.querySelectorAll('.faq-item');

        items.forEach(item => {
            const text = item.innerText.toLowerCase();
            if (text.includes(query)) {
                item.classList.remove('hidden');
            } else {
                item.classList.add('hidden');
            }
        });
    });
</script>
@endpush
@endsection
