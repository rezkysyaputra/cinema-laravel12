<x-mail::message>
    # Tiket Anda Berhasil Dibayar!

    Halo, {{ $booking->user->name }}

    Terima kasih telah melakukan pembayaran. Berikut detail tiket Anda:

    - **Film:** {{ $booking->screening->movie->title }}
    - **Studio:** {{ $booking->screening->studio->name }}
    - **Jadwal:** {{ $booking->screening->start_time->format('d M Y H:i') }}
    - **Jumlah Tiket:** {{ $booking->tickets->count() }}
    - **Kode Booking:** {{ $booking->id }}

    Silakan tunjukkan email ini saat masuk ke studio.

    <x-mail::button :url="url('/')">
        Lihat Tiket di Website
    </x-mail::button>

    Terima kasih,
    {{ config('app.name') }}
</x-mail::message>
