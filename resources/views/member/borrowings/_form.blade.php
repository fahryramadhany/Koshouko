<div class="gradient-card rounded-2xl p-8">
    @php
        // defensive defaults: partial can be included standalone (preview, modal, tests)
        $availableBooks = $availableBooks ?? collect();
        $user = $user ?? auth()->user() ?? (object) ['name' => 'Pengguna', 'email' => '', 'member_id' => '-', 'status' => 'active'];
    @endphp

    <form action="{{ route('borrowings.store') }}" method="POST" class="space-y-6" id="borrowingForm"@if($errors->any()) aria-describedby="borrowingFormErrors" @endif>
        @csrf
        <input type="hidden" name="from" value="{{ ($renderedIn ?? '') === 'modal' ? 'modal' : old('from') }}">

        {{-- Server-side validation summary (aria-live) --}}
        @if($errors->any())
            <div id="borrowingFormErrors" role="alert" aria-live="assertive" tabindex="-1" class="mb-4 p-3 border-2 border-red-100 bg-red-50 text-red-700 rounded">
                <p class="font-semibold">Ada beberapa kesalahan:</p>
                <ul class="mt-2 list-disc list-inside text-sm">
                    @foreach($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @else
            <div id="borrowingFormErrors" role="status" aria-live="polite" class="sr-only"></div>
        @endif

        <!-- Book Selection Section -->
        <div class="bg-koshouko-cream/50 border-2 border-koshouko-border rounded-xl p-6">
            <h2 class="text-lg font-bold text-koshouko-text mb-4">📚 Pilih Buku</h2>

            <div>
                <label for="book_id" class="block text-sm font-semibold text-koshouko-text mb-3">
                    Buku <span class="text-red-600">*</span>
                </label>
                <select name="book_id" id="book_id" required
                    class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg bg-white text-koshouko-text focus:outline-none focus:border-koshouko-wood transition @error('book_id') border-red-500 @enderror"
                    onchange="updateBookInfo()">
                    <option value="">-- Pilih Buku --</option>
                    @foreach($availableBooks as $b)
                        <option value="{{ $b->id }}" data-title="{{ $b->title }}" data-author="{{ $b->author }}" data-isbn="{{ $b->isbn }}" data-available="{{ $b->available_copies }}" {{ (string) old('book_id', $selectedBookId ?? '') === (string) $b->id ? 'selected' : '' }}>
                            {{ $b->title }} - {{ $b->author }} ({{ $b->available_copies }} tersedia)
                        </option>
                    @endforeach
                </select>
                @error('book_id')
                    <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                @enderror
            </div>

            <!-- Book Info Display -->
            <div id="bookInfo" class="mt-6 p-4 bg-white rounded-lg border border-koshouko-border hidden">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-koshouko-text-muted text-sm">Judul Buku</p>
                        <p id="infoTitle" class="font-semibold text-koshouko-text">-</p>
                    </div>
                    <div>
                        <p class="text-koshouko-text-muted text-sm">Pengarang</p>
                        <p id="infoAuthor" class="font-semibold text-koshouko-text">-</p>
                    </div>
                    <div>
                        <p class="text-koshouko-text-muted text-sm">ISBN</p>
                        <p id="infoISBN" class="font-semibold text-koshouko-text font-mono">-</p>
                    </div>
                    <div>
                        <p class="text-koshouko-text-muted text-sm">Salinan Tersedia</p>
                        <p id="infoAvailable" class="font-semibold text-green-700">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Borrowing Duration Section -->
        <div class="bg-koshouko-cream/50 border-2 border-koshouko-border rounded-xl p-6">
            <h2 class="text-lg font-bold text-koshouko-text mb-4">📅 Durasi Peminjaman</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Borrow Date -->
                <div>
                    <label for="borrowed_at" class="block text-sm font-semibold text-koshouko-text mb-3">
                        Tanggal Pinjam <span class="text-red-600">*</span>
                    </label>
                    <input type="date" name="borrowed_at" id="borrowed_at"
                        value="{{ date('Y-m-d') }}" required
                        class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg bg-white text-koshouko-text focus:outline-none focus:border-koshouko-wood transition @error('borrowed_at') border-red-500 @enderror"
                        readonly>
                    <p class="text-koshouko-text-muted text-xs mt-2">Otomatis diisi dengan tanggal hari ini</p>
                    @error('borrowed_at')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Duration Selection -->
                <div>
                    <label class="block text-sm font-semibold text-koshouko-text mb-4">
                        Durasi Peminjaman <span class="text-red-600">*</span>
                    </label>
                    <div class="grid grid-cols-2 gap-3" id="duration_group">
                        <label class="relative flex items-center p-4 border-2 border-koshouko-border rounded-lg cursor-pointer hover:bg-koshouko-cream/30 transition"
                               onclick="selectDuration(7)">
                            <input type="radio" name="duration_days" id="duration_7" value="7" required
                                   class="w-4 h-4 accent-koshouko-wood"
                                   onchange="updateDueDate()">
                            <span class="ml-3 font-semibold text-koshouko-text">
                                <span class="block">7 Hari</span>
                                <span class="text-xs text-koshouko-text-muted">1 Minggu</span>
                            </span>
                        </label>
                        <label class="relative flex items-center p-4 border-2 border-koshouko-border rounded-lg cursor-pointer hover:bg-koshouko-cream/30 transition"
                               onclick="selectDuration(14)">
                            <input type="radio" name="duration_days" id="duration_14" value="14" required
                                   class="w-4 h-4 accent-koshouko-wood"
                                   onchange="updateDueDate()">
                            <span class="ml-3 font-semibold text-koshouko-text">
                                <span class="block">14 Hari</span>
                                <span class="text-xs text-koshouko-text-muted">2 Minggu</span>
                            </span>
                        </label>
                        <label class="relative flex items-center p-4 border-2 border-koshouko-border rounded-lg cursor-pointer hover:bg-koshouko-cream/30 transition"
                               onclick="selectDuration(21)">
                            <input type="radio" name="duration_days" id="duration_21" value="21" required
                                   class="w-4 h-4 accent-koshouko-wood"
                                   onchange="updateDueDate()">
                            <span class="ml-3 font-semibold text-koshouko-text">
                                <span class="block">21 Hari</span>
                                <span class="text-xs text-koshouko-text-muted">3 Minggu</span>
                            </span>
                        </label>
                        <label class="relative flex items-center p-4 border-2 border-koshouko-border rounded-lg cursor-pointer hover:bg-koshouko-cream/30 transition"
                               onclick="selectDuration(30)">
                            <input type="radio" name="duration_days" id="duration_30" value="30" required
                                   class="w-4 h-4 accent-koshouko-wood"
                                   onchange="updateDueDate()">
                            <span class="ml-3 font-semibold text-koshouko-text">
                                <span class="block">30 Hari</span>
                                <span class="text-xs text-koshouko-text-muted">1 Bulan</span>
                            </span>
                        </label>
                    </div>
                    @error('duration_days')
                        <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <!-- Due Date Display -->
            <div class="mt-6 p-6 bg-gradient-to-r from-koshouko-cream to-white rounded-lg border-2 border-koshouko-wood/20 shadow-md">
                <p class="text-koshouko-text-muted text-sm font-semibold mb-4">📅 Rincian Peminjaman</p>
                <div class="grid grid-cols-3 gap-4">
                    <div class="text-center">
                        <p class="text-koshouko-text-muted text-xs mb-2">Tanggal Pinjam</p>
                        <p id="borrowDateDisplay" class="font-bold text-koshouko-wood text-base">
                            @php
                                echo date('d M Y');
                            @endphp
                        </p>
                    </div>
                    <div class="flex items-center justify-center">
                        <div class="text-center">
                            <p class="text-koshouko-text-muted text-xs mb-2">Durasi</p>
                            <p id="daysCount" class="font-bold text-koshouko-red text-2xl">-</p>
                            <p class="text-koshouko-text-muted text-xs">hari</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-koshouko-text-muted text-xs mb-2">Tanggal Kembali</p>
                        <p id="dueDateDisplay" class="font-bold text-koshouko-wood text-base">-</p>
                    </div>
                </div>
            </div>

            <!-- Hidden input for due_date -->
            <input type="hidden" name="due_date" id="due_date_hidden">
        </div>

        <!-- Personal Info Section -->
        <div class="bg-koshouko-cream/50 border-2 border-koshouko-border rounded-xl p-6">
            <h2 class="text-lg font-bold text-koshouko-text mb-4">👤 Data Pribadi</h2>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div>
                    <label for="user_name" class="block text-sm font-semibold text-koshouko-text mb-3">
                        Nama Lengkap
                    </label>
                    <input type="text" name="user_name" id="user_name"
                        value="{{ $user->name }}"
                        disabled
                        class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text-muted cursor-not-allowed">
                    <p class="text-koshouko-text-muted text-xs mt-2">Berdasarkan data akun Anda</p>
                </div>

                <!-- Email -->
                <div>
                    <label for="user_email" class="block text-sm font-semibold text-koshouko-text mb-3">
                        Email
                    </label>
                    <input type="email" name="user_email" id="user_email"
                        value="{{ $user->email }}"
                        disabled
                        class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text-muted cursor-not-allowed">
                    <p class="text-koshouko-text-muted text-xs mt-2">Berdasarkan data akun Anda</p>
                </div>

                <!-- Nomor Member -->
                <div>
                    <label for="member_id" class="block text-sm font-semibold text-koshouko-text mb-3">
                        Nomor Member
                    </label>
                    <input type="text" name="member_id" id="member_id"
                        value="{{ $user->member_id }}"
                        disabled
                        class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text-muted cursor-not-allowed">
                    <p class="text-koshouko-text-muted text-xs mt-2">ID member Anda</p>
                </div>

                <!-- Status Keanggotaan -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-koshouko-text mb-3">
                        Status Keanggotaan
                    </label>
                    <input type="text" name="status" id="status"
                        value="{{ ucfirst($user->status) }}"
                        disabled
                        class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg bg-koshouko-cream text-koshouko-text-muted cursor-not-allowed">
                </div>
            </div>
        </div>

        <!-- Special Request Section -->
        <div class="bg-koshouko-cream/50 border-2 border-koshouko-border rounded-xl p-6">
            <h2 class="text-lg font-bold text-koshouko-text mb-4">📝 Catatan Khusus (Opsional)</h2>

            <textarea name="special_request" id="special_request" rows="4" placeholder="Tuliskan catatan atau permintaan khusus Anda (opsional)..."
                class="w-full px-4 py-3 border-2 border-koshouko-border rounded-lg bg-white text-koshouko-text focus:outline-none focus:border-koshouko-wood transition placeholder-koshouko-text-muted @error('special_request') border-red-500 @enderror"></textarea>
            <p class="text-koshouko-text-muted text-xs mt-2">Contoh: Dimohon untuk dikirim secepatnya atau Permintaan khusus lainnya</p>
            @error('special_request')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Terms & Conditions -->
        <div class="bg-yellow-50 border-2 border-yellow-300 rounded-xl p-6">
            <h2 class="text-lg font-bold text-yellow-800 mb-4">⚠️ Syarat & Ketentuan</h2>

            <div class="space-y-3 text-sm text-yellow-900">
                <div class="flex gap-3">
                    <input type="checkbox" name="agree_terms" id="agree_terms" required
                        class="mt-1 w-4 h-4 rounded border-2 border-yellow-400 text-yellow-600 focus:outline-none">
                    <label for="agree_terms" class="leading-relaxed">
                        Saya menyetujui untuk mengembalikan buku tepat pada tanggal yang ditentukan
                    </label>
                </div>
                <div class="flex gap-3">
                    <input type="checkbox" name="agree_condition" id="agree_condition" required
                        class="mt-1 w-4 h-4 rounded border-2 border-yellow-400 text-yellow-600 focus:outline-none">
                    <label for="agree_condition" class="leading-relaxed">
                        Saya bertanggung jawab atas kondisi buku yang dipinjam
                    </label>
                </div>
                <div class="flex gap-3">
                    <input type="checkbox" name="agree_loss" id="agree_loss" required
                        class="mt-1 w-4 h-4 rounded border-2 border-yellow-400 text-yellow-600 focus:outline-none">
                    <label for="agree_loss" class="leading-relaxed">
                        Saya akan membayar ganti rugi jika buku hilang atau rusak
                    </label>
                </div>
            </div>

            @error('agree_terms')
                <p class="text-red-600 text-sm mt-2">{{ $message }}</p>
            @enderror
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-4 pt-6 border-t-2 border-koshouko-border">
            <a href="{{ route('books.index') }}" class="px-6 py-3 border-2 border-koshouko-border text-koshouko-wood rounded-lg font-semibold hover:bg-koshouko-cream transition">
                ← Batal
            </a>
            <button type="submit" class="flex-1 px-6 py-3 bg-gradient-to-r from-koshouko-wood to-koshouko-red text-white rounded-lg font-semibold hover:shadow-lg transition btn-koshouko">
                ✓ Ajukan Peminjaman
            </button>
        </div>
    </form>
</div>

<!-- Info Box -->
<div class="mt-8 bg-blue-50 border-2 border-blue-300 rounded-xl p-6">
    <h3 class="font-bold text-blue-900 mb-3">ℹ️ Informasi Penting</h3>
    <ul class="text-sm text-blue-800 space-y-2">
        <li>✓ Peminjaman akan diproses dalam 1x24 jam</li>
        <li>✓ Anda akan menerima notifikasi melalui email saat permintaan disetujui atau ditolak</li>
        <li>✓ Denda keterlambatan adalah Rp 5.000 per hari</li>
        <li>✓ Maksimal peminjaman 5 buku sekaligus</li>
        <li>✓ Buku dapat diperpanjang 1 kali jika tidak ada yang memesan</li>
    </ul>
</div>

<script>
    function updateBookInfo() {
        const select = document.getElementById('book_id');
        const selected = select.options[select.selectedIndex];
        const bookInfo = document.getElementById('bookInfo');

        if (selected.value) {
            document.getElementById('infoTitle').textContent = selected.dataset.title;
            document.getElementById('infoAuthor').textContent = selected.dataset.author;
            document.getElementById('infoISBN').textContent = selected.dataset.isbn;
            document.getElementById('infoAvailable').textContent = selected.dataset.available + ' salinan';
            bookInfo.classList.remove('hidden');
        } else {
            bookInfo.classList.add('hidden');
        }
    }

    function updateDueDate() {
        const checkedRadio = document.querySelector('input[name="duration_days"]:checked');
        const duration = checkedRadio ? parseInt(checkedRadio.value) : 0;
        const dueDateDisplay = document.getElementById('dueDateDisplay');
        const daysCount = document.getElementById('daysCount');
        const dueDateHidden = document.getElementById('due_date_hidden');

        if (duration > 0) {
            const today = new Date();
            const dueDate = new Date(today.getTime() + duration * 24 * 60 * 60 * 1000);

            // Format display date
            const options = { year: 'numeric', month: 'short', day: 'numeric' };
            dueDateDisplay.textContent = dueDate.toLocaleDateString('id-ID', options);
            daysCount.textContent = duration;

            // Set hidden input value (YYYY-MM-DD format)
            const year = dueDate.getFullYear();
            const month = String(dueDate.getMonth() + 1).padStart(2, '0');
            const day = String(dueDate.getDate()).padStart(2, '0');
            dueDateHidden.value = `${year}-${month}-${day}`;

            // Add visual feedback to selected radio button
            document.querySelectorAll('input[name="duration_days"]').forEach(radio => {
                radio.closest('label').classList.remove('border-koshouko-wood', 'bg-koshouko-cream');
                radio.closest('label').classList.add('border-koshouko-border');
            });
            checkedRadio.closest('label').classList.remove('border-koshouko-border');
            checkedRadio.closest('label').classList.add('border-koshouko-wood', 'bg-koshouko-cream');
        } else {
            dueDateDisplay.textContent = '-';
            daysCount.textContent = '-';
            dueDateHidden.value = '';
        }
    }

    function selectDuration(days) {
        const radio = document.getElementById(`duration_${days}`);
        if (radio) {
            radio.checked = true;
            updateDueDate();
        }
    }

    // Initialize with today's date
    window.addEventListener('DOMContentLoaded', function() {
        const borrowedAt = document.getElementById('borrowed_at');
        if (borrowedAt) borrowedAt.value = new Date().toISOString().split('T')[0];

        // If form exists on the page (dashboard modal), ensure borrowDateDisplay is populated
        const borrowDateDisplay = document.getElementById('borrowDateDisplay');
        if (borrowDateDisplay) {
            borrowDateDisplay.textContent = new Date().toLocaleDateString('id-ID', { day: '2-digit', month: 'short', year: 'numeric' });
        }

        // If server preselected a book (e.g. /books/{id}/borrow) or old input present, initialise UI
        const preselected = @json(old('book_id', $selectedBookId ?? null));
        if (preselected) {
            const sel = document.getElementById('book_id');
            if (sel) {
                sel.value = preselected;
                updateBookInfo();
            }
        }

        // If duration was previously chosen (old input), restore and calculate due date
        const oldDuration = @json(old('duration_days'));
        if (oldDuration) {
            const radio = document.querySelector(`input[name="duration_days"][value="${oldDuration}"]`);
            if (radio) {
                radio.checked = true;
                updateDueDate();
            }
        }
    });
</script>