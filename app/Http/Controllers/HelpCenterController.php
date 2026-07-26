<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HelpCenterController extends Controller
{
    /**
     * Halaman Pusat Bantuan & FAQ
     */
    public function index()
    {
        $faqs = [
            [
                'category' => 'Pembelian & Pembayaran',
                'icon' => '💳',
                'questions' => [
                    [
                        'q' => 'Bagaimana cara membeli tiket di AmikomEventHub?',
                        'a' => 'Pilih event yang ingin Anda ikuti di Halaman Beranda, klik tombol "Beli Tiket", isi form pemesanan (nama, email, no. WhatsApp), lalu ikuti instruksi pembayaran via Midtrans (Gopay, ShopeePay, Bank Transfer, QRIS) atau gratis untuk event tanpa biaya.'
                    ],
                    [
                        'q' => 'Metode pembayaran apa saja yang didukung?',
                        'a' => 'AmikomEventHub menggunakan Midtrans Payment Gateway resmi. Anda bisa membayar via QRIS, Bank Transfer (BCA, Mandiri, BNI, BRI, Permata), GoPay, ShopeePay, dan Credit/Debit Card.'
                    ],
                    [
                        'q' => 'Apakah pembayaran tiket aman?',
                        'a' => 'Sangat aman! Seluruh transaksi diproses terenkripsi 256-bit oleh Midtrans Payment Gateway resmi.'
                    ]
                ]
            ],
            [
                'category' => 'E-Ticket & QR Code',
                'icon' => '🎟️',
                'questions' => [
                    [
                        'q' => 'Ke mana E-Ticket dikirimkan setelah pembayaran berhasil?',
                        'a' => 'E-Ticket beserta Kode QR unik akan otomatis dikirimkan ke Email dan WhatsApp pembeli secara instan setelah status pembayaran berhasil/settlement.'
                    ],
                    [
                        'q' => 'Bagaimana jika saya tidak menerima email atau WhatsApp E-Ticket?',
                        'a' => 'Buka menu "Temukan Tiket Saya" di bagian atas website, masukkan Kode Order (TRX-...) atau alamat Email yang Anda gunakan saat membeli tiket. E-Ticket beserta QR code akan langsung muncul.'
                    ],
                    [
                        'q' => 'Bagaimana cara menunjukkan tiket saat di lokasi event?',
                        'a' => 'Tunjukkan QR Code di E-Ticket (dari email, WhatsApp, atau halaman Temukan Tiket) kepada panitia di pintu masuk untuk di-scan menggunakan Check-in Scanner resmi.'
                    ]
                ]
            ],
            [
                'category' => 'Bantuan & Customer Service',
                'icon' => '💬',
                'questions' => [
                    [
                        'q' => 'Bagaimana jika terjadi kendala pembayaran atau tiket tidak muncul?',
                        'a' => 'Tim Customer Support AmikomEventHub siap membantu 24/7. Anda dapat klik tombol "Hubungi via WhatsApp" di bawah halaman ini untuk terhubung langsung dengan tim kami.'
                    ],
                    [
                        'q' => 'Apakah tiket yang sudah dibeli bisa dibatalkan / di-refund?',
                        'a' => 'Kebijakan refund bergantung pada ketentuan Penyelenggara Event (Organizer). Silakan hubungi panitia penyelenggara event atau CS AmikomEventHub dengan melampirkan Order ID.'
                    ]
                ]
            ]
        ];

        return view('help-center', compact('faqs'));
    }
}
