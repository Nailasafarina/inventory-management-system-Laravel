{{-- Flash Success Alert --}}
@if (session('success'))
<div id="alert-success" class="flex items-start p-4 mb-4 text-emerald-800 rounded-2xl bg-emerald-50 border border-emerald-200/70 shadow-soft transition-all duration-300">
    <div class="flex-shrink-0 mt-0.5">
        <i class="fa-solid fa-circle-check text-emerald-600 text-lg"></i>
    </div>
    <div class="ml-3 text-sm font-medium flex-1">
        <p class="font-semibold text-emerald-900">Operasi Berhasil</p>
        <p class="mt-0.5 text-emerald-700">{{ session('success') }}</p>
    </div>
    <button type="button" onclick="document.getElementById('alert-success').remove()" class="ml-auto -mx-1.5 -my-1.5 bg-emerald-50 text-emerald-500 rounded-lg p-1.5 hover:bg-emerald-100 focus:outline-none transition">
        <span class="sr-only">Tutup</span>
        <i class="fa-solid fa-xmark text-sm"></i>
    </button>
</div>
@endif

{{-- Flash Error Alert --}}
@if (session('error'))
<div id="alert-error" class="flex items-start p-4 mb-4 text-rose-800 rounded-2xl bg-rose-50 border border-rose-200/70 shadow-soft transition-all duration-300">
    <div class="flex-shrink-0 mt-0.5">
        <i class="fa-solid fa-circle-exclamation text-rose-600 text-lg"></i>
    </div>
    <div class="ml-3 text-sm font-medium flex-1">
        <p class="font-semibold text-rose-900">Terjadi Kesalahan</p>
        <p class="mt-0.5 text-rose-700">{{ session('error') }}</p>
    </div>
    <button type="button" onclick="document.getElementById('alert-error').remove()" class="ml-auto -mx-1.5 -my-1.5 bg-rose-50 text-rose-500 rounded-lg p-1.5 hover:bg-rose-100 focus:outline-none transition">
        <span class="sr-only">Tutup</span>
        <i class="fa-solid fa-xmark text-sm"></i>
    </button>
</div>
@endif

{{-- Form Validation Errors --}}
@if (isset($errors) && $errors->any())
<div id="alert-validation" class="flex items-start p-4 mb-4 text-amber-900 rounded-2xl bg-amber-50 border border-amber-200/80 shadow-soft transition-all duration-300">
    <div class="flex-shrink-0 mt-0.5">
        <i class="fa-solid fa-triangle-exclamation text-amber-600 text-lg"></i>
    </div>
    <div class="ml-3 text-sm font-medium flex-1">
        <p class="font-semibold text-amber-900">Terdapat kesalahan pada input formulir:</p>
        <ul class="mt-1.5 list-disc list-inside space-y-1 text-amber-800 text-xs">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    <button type="button" onclick="document.getElementById('alert-validation').remove()" class="ml-auto -mx-1.5 -my-1.5 bg-amber-50 text-amber-500 rounded-lg p-1.5 hover:bg-amber-100 focus:outline-none transition">
        <span class="sr-only">Tutup</span>
        <i class="fa-solid fa-xmark text-sm"></i>
    </button>
</div>
@endif
