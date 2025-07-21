<x-app-layout>
    <div class="max-w-5xl mx-auto py-10 px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar Navigation -->
            <aside class="w-full lg:w-1/4">
                <nav class="bg-dark-card rounded-lg shadow p-2 lg:p-4 flex flex-row lg:flex-col gap-2 overflow-x-auto lg:overflow-visible">
                    @auth
                    <a href="#profile" class="px-4 py-2 rounded text-gray-300 hover:bg-orange-500 hover:text-white transition whitespace-nowrap">Profil Akun</a>
                    @endauth
                    <a href="#guide" class="px-4 py-2 rounded text-gray-300 hover:bg-orange-500 hover:text-white transition whitespace-nowrap">Panduan Pengguna</a>
                    <a href="#faq" class="px-4 py-2 rounded text-gray-300 hover:bg-orange-500 hover:text-white transition whitespace-nowrap">FAQ</a>
                    <a href="#terms" class="px-4 py-2 rounded text-gray-300 hover:bg-orange-500 hover:text-white transition whitespace-nowrap">Syarat & Ketentuan</a>
                    <a href="#privacy" class="px-4 py-2 rounded text-gray-300 hover:bg-orange-500 hover:text-white transition whitespace-nowrap">Kebijakan Privasi</a>
                    <a href="#about" class="px-4 py-2 rounded text-gray-300 hover:bg-orange-500 hover:text-white transition whitespace-nowrap">Tentang Aplikasi</a>
                    <a href="#contact" class="px-4 py-2 rounded text-gray-300 hover:bg-orange-500 hover:text-white transition whitespace-nowrap">Kontak & Bantuan</a>
                    <a href="#payment" class="px-4 py-2 rounded text-gray-300 hover:bg-orange-500 hover:text-white transition whitespace-nowrap">Petunjuk Pembayaran</a>
                </nav>
            </aside>
            <!-- Main Content -->
            <main class="flex-1 space-y-12">
                @auth
                @php $user = Auth::user(); @endphp
                <!-- Profile Section -->
                <section id="profile">
                    <h2 class="text-2xl font-bold text-orange-500 mb-4">Profil Akun</h2>
                    <div class="space-y-6">
                        @include('profile.partials.update-profile-information-form', ['user' => $user])
                        @include('profile.partials.update-password-form')
                        @include('profile.partials.delete-user-form')
                    </div>
                </section>
                @else
                <!-- Login Prompt for Guest -->
                <section id="profile">
                    <div class="bg-dark-card rounded-lg p-6 text-center">
                        <h2 class="text-2xl font-bold text-orange-500 mb-4">Profil Akun</h2>
                        <p class="text-gray-300 mb-4">Silakan login untuk mengakses pengaturan akun Anda.</p>
                        <a href="{{ route('login') }}" class="inline-block px-6 py-2 rounded-lg bg-orange-500 text-white font-semibold hover:bg-orange-600 transition">Login</a>
                    </div>
                </section>
                @endauth
                <!-- Guide Section -->
                <section id="guide">
                    <h2 class="text-2xl font-bold text-orange-500 mb-4">Panduan Pengguna</h2>
                    <div class="bg-dark-card rounded-lg p-6 space-y-4">
                        <ol class="list-decimal list-inside text-gray-300 space-y-2">
                            <li><b>Cara membuat akun:</b> Klik tombol "Register", isi data diri, lalu submit.</li>
                            <li><b>Cara login:</b> Klik "Login", masukkan email & password.</li>
                            <li><b>Cara mencari film yang tersedia:</b> Buka menu "Movies".</li>
                            <li><b>Cara melihat jadwal tayang:</b> Klik film, lihat jadwal di halaman detail.</li>
                            <li><b>Cara memesan tiket:</b> Pilih film & jadwal, klik "Pesan Tiket".</li>
                            <li><b>Cara memilih kursi:</b> Pilih kursi pada tampilan pemilihan kursi.</li>
                            <li><b>Cara melakukan pembayaran:</b> Ikuti instruksi pembayaran setelah memilih kursi.</li>
                            <li><b>Cara melihat e-ticket:</b> Setelah pembayaran, e-ticket dapat dilihat di menu "My Tickets".</li>
                        </ol>
                    </div>
                </section>
                <!-- FAQ Section -->
                <section id="faq">
                    <h2 class="text-2xl font-bold text-orange-500 mb-4">FAQ (Frequently Asked Questions)</h2>
                    <div class="bg-dark-card rounded-lg p-6 space-y-4">
                        <ul class="list-disc list-inside text-gray-300 space-y-2">
                            <li><b>Bagaimana jika saya tidak menerima e-ticket?</b> Cek email/spam, atau hubungi support.</li>
                            <li><b>Bisakah saya membatalkan tiket?</b> Tiket yang sudah dibayar tidak dapat dibatalkan, kecuali ada kebijakan khusus.</li>
                            <li><b>Metode pembayaran apa saja yang tersedia?</b> QRIS, transfer bank, e-wallet.</li>
                            <li><b>Apakah saya perlu mencetak tiket?</b> Tidak perlu, cukup tunjukkan e-ticket di aplikasi.</li>
                        </ul>
                    </div>
                </section>
                <!-- Terms Section -->
                <section id="terms">
                    <h2 class="text-2xl font-bold text-orange-500 mb-4">Syarat & Ketentuan</h2>
                    <div class="bg-dark-card rounded-lg p-6 space-y-4">
                        <ul class="list-disc list-inside text-gray-300 space-y-2">
                            <li><b>Ketentuan pemesanan tiket:</b> Tiket hanya berlaku untuk jadwal & kursi yang dipilih.</li>
                            <li><b>Kebijakan pembatalan:</b> Tiket yang sudah dibayar tidak dapat dibatalkan, kecuali force majeure.</li>
                            <li><b>Aturan penggunaan aplikasi:</b> Pengguna wajib menjaga kerahasiaan akun dan data pribadi.</li>
                        </ul>
                    </div>
                </section>
                <!-- Privacy Section -->
                <section id="privacy">
                    <h2 class="text-2xl font-bold text-orange-500 mb-4">Kebijakan Privasi</h2>
                    <div class="bg-dark-card rounded-lg p-6 space-y-4">
                        <ul class="list-disc list-inside text-gray-300 space-y-2">
                            <li><b>Bagaimana data pengguna disimpan:</b> Data disimpan secara aman di server.</li>
                            <li><b>Apa yang dilakukan dengan data transaksi:</b> Hanya digunakan untuk keperluan transaksi & analitik internal.</li>
                            <li><b>Perlindungan terhadap data pribadi:</b> Data tidak dibagikan ke pihak ketiga tanpa izin.</li>
                        </ul>
                    </div>
                </section>
                <!-- About Section -->
                <section id="about">
                    <h2 class="text-2xl font-bold text-orange-500 mb-4">Tentang Aplikasi</h2>
                    <div class="bg-dark-card rounded-lg p-6 space-y-4">
                        <ul class="list-disc list-inside text-gray-300 space-y-2">
                            <li><b>Deskripsi:</b> Cinetix adalah aplikasi pemesanan tiket bioskop online.</li>
                            <li><b>Tujuan:</b> Memudahkan pengguna memesan tiket & memilih kursi secara online.</li>
                            <li><b>Versi:</b> 1.0.0</li>
                            <li><b>Kontak pengembang:</b> support@cinetix.com</li>
                        </ul>
                    </div>
                </section>
                <!-- Contact Section -->
                <section id="contact">
                    <h2 class="text-2xl font-bold text-orange-500 mb-4">Kontak & Bantuan</h2>
                    <div class="bg-dark-card rounded-lg p-6 space-y-4">
                        <ul class="list-disc list-inside text-gray-300 space-y-2">
                            <li><b>Email:</b> support@cinetix.com</li>
                            <li><b>Formulir bantuan:</b> <span class="text-orange-400">(Coming Soon)</span></li>
                            <li><b>WhatsApp Support:</b> <span class="text-orange-400">(Coming Soon)</span></li>
                        </ul>
                    </div>
                </section>
                <!-- Payment Guide Section -->
                <section id="payment">
                    <h2 class="text-2xl font-bold text-orange-500 mb-4">Petunjuk Pembayaran</h2>
                    <div class="bg-dark-card rounded-lg p-6 space-y-4">
                        <ul class="list-disc list-inside text-gray-300 space-y-2">
                            <li><b>Jenis pembayaran:</b> QRIS, transfer bank, e-wallet.</li>
                            <li><b>Instruksi:</b> Pilih metode pembayaran, ikuti langkah di layar, dan konfirmasi pembayaran.</li>
                        </ul>
                    </div>
                </section>
            </main>
        </div>
    </div>
</x-app-layout>
