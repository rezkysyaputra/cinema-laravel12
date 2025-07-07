 <x-app-layout>
     <!-- Simple Movie Header -->
     <div class="bg-dark-bg py-8">
         <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
             <div class="flex flex-col sm:flex-row items-start gap-6">
                 <!-- Small Poster -->
                 <div class="flex-shrink-0">
                     <img src="{{ $movie->poster_url }}" alt="{{ $movie->title }}" class="w-48 h-72 sm:w-56 sm:h-80 object-cover rounded-lg shadow-lg">
                 </div>

                 <!-- Movie Info -->
                 <div class="flex-1 text-white">
                     <h1 class="text-2xl sm:text-3xl font-bold mb-3">{{ $movie->title }}</h1>

                     <!-- Movie Meta -->
                     <div class="flex items-center gap-4 mb-6">
                         <span class="text-orange-400 font-medium">{{ $movie->duration }} menit</span>
                         <span class="text-gray-400">{{ $movie->genre }}</span>
                     </div>

                     <!-- Tab Buttons -->
                     <div class="flex bg-gray-800 rounded-lg p-1 mb-6">
                         <button onclick="showSchedule()" id="schedule-tab" class="flex-1 py-2 px-4 rounded-md font-medium transition-all duration-200 bg-orange-500 text-white">
                             Jadwal Tayang
                         </button>
                         <button onclick="showDetails()" id="details-tab" class="flex-1 py-2 px-4 rounded-md font-medium transition-all duration-200 text-gray-300 hover:text-white">
                             Detail Film
                         </button>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     <!-- Content Sections -->
     <div class="">
         <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
             <!-- Schedule Section -->
             <div id="schedule-section">
                 <h2 class="text-xl sm:text-2xl font-bold text-white mb-6">Jadwal Tayang</h2>

                 @php
                 // Ambil tanggal unik dari screenings dan urutkan
                 $dates = $movie->screenings->map(function($screening) {
                 return \Carbon\Carbon::parse($screening->start_time)->format('Y-m-d');
                 })->unique()->sort()->values();
                 @endphp

                 @if($dates->count() > 0)
                 <!-- Tanggal Bar -->
                 <div class="flex overflow-x-auto gap-2 mb-6 pb-2" id="date-bar">
                     @foreach($dates as $i => $date)
                     <button class="date-btn flex-shrink-0 px-4 py-2 rounded-lg border border-gray-700 bg-gray-800 text-white font-semibold transition-all duration-200 @if($i === 0) bg-orange-500 border-orange-500 @endif" data-date="{{ $date }}" @if($i===0) id="active-date-btn" @endif>
                         {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                     </button>
                     @endforeach
                 </div>

                 <!-- Jam Tayang -->
                 <div id="screening-times">
                     @foreach($dates as $i => $date)
                     <div class="screening-list" id="screening-list-{{ $date }}" @if($i !==0) style="display:none" @endif>
                         @php
                         // Ambil studio unik yang ada screening di tanggal ini
                         $studios = $movie->screenings->where(fn($s) => \Carbon\Carbon::parse($s->start_time)->format('Y-m-d') === $date)
                         ->map(fn($s) => $s->studio)
                         ->unique('id')
                         ->values();
                         @endphp
                         @if($studios->count() > 0)
                         <div class="space-y-6">
                             @foreach($studios as $studio)
                             <div class="bg-dark-bg rounded-lg p-4 border border-gray-700">
                                 <h3 class="text-white font-medium mb-3">{{ $studio->name }}</h3>
                                 <div class="flex flex-wrap gap-3">
                                     @foreach($movie->screenings->where(fn($s) => \Carbon\Carbon::parse($s->start_time)->format('Y-m-d') === $date && $s->studio->id === $studio->id) as $screening)
                                     <a href="{{ route('booking.create', $screening) }}" class="px-4 py-2 rounded-lg bg-orange-500 text-white font-semibold hover:bg-orange-600 transition text-center min-w-[80px]">
                                         {{ \Carbon\Carbon::parse($screening->start_time)->format('H:i') }} WIB
                                     </a>
                                     @endforeach
                                 </div>
                             </div>
                             @endforeach
                         </div>
                         @else
                         <div class="text-center py-8">
                             <p class="text-gray-400">Tidak ada jadwal tayang tersedia.</p>
                         </div>
                         @endif
                     </div>
                     @endforeach
                 </div>
                 @else
                 <div class="text-center py-8">
                     <p class="text-gray-400">Tidak ada jadwal tayang tersedia.</p>
                 </div>
                 @endif
             </div>

             <!-- Details Section -->
             <div id="details-section" class="hidden">
                 <h2 class="text-xl sm:text-2xl font-bold text-white mb-6">Detail Film</h2>

                 <div class="bg-dark-bg rounded-lg p-6">
                     <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                         <div>
                             <h3 class="text-white font-semibold mb-3">Informasi Film</h3>
                             <div class="space-y-4">
                                 <div class="grid grid-cols-3 gap-2 items-center">
                                     <span class="text-sm text-gray-400 font-semibold col-span-1">Judul</span>
                                     <span class="col-span-2 text-white text-base font-bold">{{ $movie->title }}</span>
                                 </div>
                                 <div class="grid grid-cols-3 gap-2 items-center">
                                     <span class="text-sm text-gray-400 font-semibold col-span-1">Tanggal Rilis</span>
                                     <span class="col-span-2 text-white text-base">{{ \Carbon\Carbon::parse($movie->release_date)->format('d M Y') }}</span>
                                 </div>
                                 <div class="grid grid-cols-3 gap-2 items-center">
                                     <span class="text-sm text-gray-400 font-semibold col-span-1">Durasi</span>
                                     <span class="col-span-2 text-white text-base">{{ $movie->duration }} menit</span>
                                 </div>
                                 <div class="grid grid-cols-3 gap-2 items-center">
                                     <span class="text-sm text-gray-400 font-semibold col-span-1">Genre</span>
                                     <span class="col-span-2 text-white text-base">{{ $movie->genre }}</span>
                                 </div>
                             </div>
                         </div>
                         <div>
                             <h3 class="text-white font-semibold mb-3">Sinopsis</h3>
                             <p class="text-gray-300 leading-relaxed">{{ $movie->synopsis }}</p>
                         </div>
                     </div>
                 </div>
             </div>
         </div>
     </div>

     <x-footer />

     <script>
         function showSchedule() {
             document.getElementById('schedule-section').classList.remove('hidden');
             document.getElementById('details-section').classList.add('hidden');

             // Update tab styles
             document.getElementById('schedule-tab').classList.add('bg-orange-500', 'text-white');
             document.getElementById('schedule-tab').classList.remove('text-gray-300', 'hover:text-white');
             document.getElementById('details-tab').classList.remove('bg-orange-500', 'text-white');
             document.getElementById('details-tab').classList.add('text-gray-300', 'hover:text-white');
         }

         function showDetails() {
             document.getElementById('details-section').classList.remove('hidden');
             document.getElementById('schedule-section').classList.add('hidden');

             // Update tab styles
             document.getElementById('details-tab').classList.add('bg-orange-500', 'text-white');
             document.getElementById('details-tab').classList.remove('text-gray-300', 'hover:text-white');
             document.getElementById('schedule-tab').classList.remove('bg-orange-500', 'text-white');
             document.getElementById('schedule-tab').classList.add('text-gray-300', 'hover:text-white');
         }

         // Date bar logic
         document.addEventListener('DOMContentLoaded', function() {
             const dateBtns = document.querySelectorAll('.date-btn');
             dateBtns.forEach(btn => {
                 btn.addEventListener('click', function() {
                     // Remove highlight from all
                     dateBtns.forEach(b => b.classList.remove('bg-orange-500', 'border-orange-500'));
                     dateBtns.forEach(b => b.classList.add('bg-gray-800'));
                     // Highlight selected
                     this.classList.add('bg-orange-500', 'border-orange-500');
                     this.classList.remove('bg-gray-800');

                     // Show corresponding screening list
                     document.querySelectorAll('.screening-list').forEach(list => list.style.display = 'none');
                     const date = this.getAttribute('data-date');
                     const list = document.getElementById('screening-list-' + date);
                     if (list) list.style.display = '';
                 });
             });
         });

     </script>
 </x-app-layout>
