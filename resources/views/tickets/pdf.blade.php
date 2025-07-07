<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Tiket - {{ $ticket->booking->screening->movie->title }}</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 24px;
            background-color: #f3f4f6;
        }

        .ticket {
            background: #fff;
            border-radius: 18px;
            max-width: 420px;
            margin: 0 auto;
            overflow: hidden;
            /* box-shadow: 0 6px 24px rgba(239, 68, 68, 0.10), 0 1.5px 4px rgba(17, 24, 39, 0.08); */
            border: 1.5px solid #ffe0b2;
        }

        .ticket-header {
            background: #ff9800;
            color: #fff;
            padding: 24px 20px 16px 20px;
            text-align: center;
            position: relative;
        }

        .ticket-header .logo {
            width: 48px;
            height: 48px;
            object-fit: contain;
            margin-bottom: 8px;
        }

        .movie-title {
            font-size: 22px;
            font-weight: bold;
            margin: 10px 0 0 0;
            color: #fff;
            letter-spacing: 0.5px;
        }

        .ticket-body {
            padding: 28px 24px 18px 24px;
        }

        .cinema-info {
            text-align: center;
            margin-bottom: 20px;
        }

        .cinema-name {
            font-size: 19px;
            font-weight: 700;
            color: #ff9800;
            margin-bottom: 2px;
            letter-spacing: 0.5px;
        }

        .screening-info {
            color: #6b7280;
            font-size: 15px;
            margin-bottom: 6px;
        }

        .seat-info {
            text-align: center;
            margin-bottom: 20px;
        }

        .seat-number {
            display: inline-block;
            background: #ffe0b2;
            color: #ff9800;
            padding: 8px 16px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 18px;
            margin: 0 4px;
            letter-spacing: 1px;
        }

        .booking-details {
            border-top: 2px dashed #ffcc80;
            border-bottom: 2px dashed #ffcc80;
            padding: 18px 0 18px 0;
            margin-bottom: 20px;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            color: #374151;
            font-size: 15px;
        }

        .detail-value {
            font-family: monospace;
            color: #111827;
            font-weight: 600;
        }

        .qr-section {
            text-align: center;
            padding: 18px 0 0 0;
            background: #f9fafb;
            border-radius: 0 0 18px 18px;
        }

        .qr-code {
            display: inline-block;
            padding: 12px;
            background: #fff;
            border-radius: 10px;
            margin-bottom: 8px;
            /* box-shadow: 0 2px 8px rgba(239, 68, 68, 0.07); */
        }

        .qr-text {
            color: #6b7280;
            font-size: 13px;
            margin-top: 4px;
        }

        .footer-text {
            text-align: center;
            color: #6b7280;
            font-size: 12px;
            margin-top: 16px;
            letter-spacing: 0.2px;
        }

        .brand {
            text-align: center;
            margin-top: 10px;
            color: #ff9800;
            font-weight: 700;
            font-size: 13px;
            letter-spacing: 1px;
        }

    </style>
</head>
<body>
    <div class="ticket">
        <div class="ticket-header">
            <img src="{{ public_path('assets/images/cinetix_logo.png') }}" class="logo" alt="Cinetix Logo">
            <h1 style="margin:0; font-size: 20px; letter-spacing: 2px; font-weight: 700;">CINEMA TICKET</h1>
            <div class="movie-title">{{ $ticket->booking->screening->movie->title }}</div>
        </div>

        <div class="ticket-body">
            <div class="cinema-info">
                <div class="cinema-name">{{ $ticket->booking->screening->studio->name }}</div>
                <div class="screening-info">
                    {{ Carbon\Carbon::parse($ticket->booking->screening->start_time)->format('d M Y • H:i') }}
                </div>
            </div>

            <div class="seat-info">
                <div class="seat-number">{{ $ticket->seat->code }}</div>
            </div>

            <div class="booking-details">
                <div class="detail-row">
                    <span>Kode Tiket</span>
                    <span class="detail-value">{{ $ticket->ticket_code }}</span>
                </div>
                <div class="detail-row">
                    <span>Nama</span>
                    <span class="detail-value">{{ $ticket->booking->user->name }}</span>
                </div>
                <div class="detail-row">
                    <span>Harga</span>
                    <span class="detail-value">Rp {{ number_format($ticket->price, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="qr-section">
                <div class="qr-code">
                    <img src="data:image/png;base64,{{ $qrCode }}" style="width: 150px; height: 150px;">
                    </img>
                    <div class="qr-text">Scan QR code ini saat memasuki studio</div>
                </div>
            </div>

            <div class="footer-text">
                <p>Tiket ini sah & terdaftar di sistem <span style="color:#ff9800;font-weight:600;">Cinetix</span>.<br>Selamat menonton!</p>
            </div>
            <div class="brand">CINETIX.ID</div>
        </div>
    </div>
</body>
</html>
