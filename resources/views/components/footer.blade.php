<!-- Footer -->
<footer class="border-t border-gray-800">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 sm:gap-8">
            <!-- Logo & Description -->
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center gap-2 mb-3 sm:mb-4">
                    <img src="{{ asset('assets/images/cinetix_logo.png') }}" alt="CINETIX Logo" class="w-8 h-8">
                    <h2 class="text-xl font-bold text-orange-500">CINETIX</h2>
                </div>
                <p class="text-sm sm:text-base text-gray-400 mb-3 sm:mb-4 leading-relaxed">
                    Your ultimate destination for movie tickets. Book your favorite movies, choose the best seats, and enjoy an unforgettable cinematic experience with premium comfort and sound.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="text-white font-semibold mb-3 sm:mb-4 text-base sm:text-lg">Quick Links</h3>
                <ul class="space-y-1.5 sm:space-y-2">
                    <li>
                        <a href="{{ route('home') }}" class="text-gray-400 hover:text-orange-500 transition flex items-center gap-1.5 sm:gap-2 group text-sm sm:text-base">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Home
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('movies.index') }}" class="text-gray-400 hover:text-orange-500 transition flex items-center gap-1.5 sm:gap-2 group text-sm sm:text-base">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            Movies
                        </a>
                    </li>
                    @auth
                    <li>
                        <a href="{{ route('tickets.index') }}" class="text-gray-400 hover:text-orange-500 transition flex items-center gap-1.5 sm:gap-2 group text-sm sm:text-base">
                            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"></path>
                            </svg>
                            My Tickets
                        </a>
                    </li>
                    @endauth
                </ul>
            </div>

            <!-- Contact -->
            <div>
                <h3 class="text-white font-semibold mb-3 sm:mb-4 text-base sm:text-lg">Contact</h3>
                <ul class="space-y-2 sm:space-y-3">
                    <li class="flex items-center gap-1.5 sm:gap-2 text-gray-400 hover:text-orange-500 transition group text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>support@movieapp.com</span>
                    </li>
                    <li class="flex items-center gap-1.5 sm:gap-2 text-gray-400 hover:text-orange-500 transition group text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>+1 234 567 890</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Copyright -->
        <div class="border-t border-gray-800 mt-6 sm:mt-8 pt-6 sm:pt-8 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} CINETIX. All rights reserved.</p>
        </div>
    </div>
</footer>
