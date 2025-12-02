@extends('layouts.app')
@section('content')
    <!-- Hero -->
    <section class="pt-12 lg:pt-20">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-center">
            <div>
                <h1 class="text-4xl md:text-5xl font-extrabold leading-tight tracking-tight">
                    Belajar Matematika jadi <span class="text-indigo-600">seru</span> & <span
                        class="text-yellow-500">interaktif</span>
                </h1>
                <p class="mt-4 text-slate-600 max-w-xl">Movato membantu siswa SD memahami konsep matematika lewat latihan
                    interaktif, AI grader, dan pengalaman mirip permainan — bukan cuma soal jawaban.</p>
                <div class="mt-6 flex gap-3">
                    <a id="cta" href="#"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-indigo-600 text-white font-medium shadow hover:bg-indigo-700">Coba
                        Gratis</a>
                    <a href="#how"
                        class="inline-flex items-center gap-2 px-5 py-3 rounded-full bg-white shadow text-indigo-600 border">Lihat
                        Cara Kerja</a>
                </div>
                <div class="mt-8 grid grid-cols-3 gap-3">
                    <div class="p-3 bg-white rounded-xl shadow text-center">
                        <div class="text-xs text-slate-500">Untuk</div>
                        <div class="font-bold">Siswa SD</div>
                    </div>
                    <div class="p-3 bg-white rounded-xl shadow text-center">
                        <div class="text-xs text-slate-500">Fitur</div>
                        <div class="font-bold">AI Grader</div>
                    </div>
                    <div class="p-3 bg-white rounded-xl shadow text-center">
                        <div class="text-xs text-slate-500">Mode</div>
                        <div class="font-bold">Game-like</div>
                    </div>
                </div>
            </div>

            <div class="relative">
                
                <div
                    class="h-72 md:h-96 rounded-3xl bg-linear-to-br from-indigo-100 to-yellow-100 shadow-lg flex items-center justify-center">
                    <div class="text-center">
                        <div class="text-6xl">🎒📐</div>
                        <div class="mt-4 text-slate-600">Ilustrasi Anak Belajar (ganti nanti dengan asset kamu)</div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Features -->
    <section id="features" class="mt-16">
        <h2 class="text-2xl font-bold">Fitur Utama</h2>
        <p class="mt-2 text-slate-600 max-w-2xl">Dirancang untuk membuat latihan matematika menjadi pengalaman yang
            menyenangkan dan bermakna.</p>
        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="p-5 bg-white rounded-2xl shadow">
                <h3 class="font-semibold">AI Grader</h3>
                <p class="mt-2 text-sm text-slate-600">Memberi umpan balik kontekstual dan penjelasan sehingga siswa paham
                    kenapa jawabannya benar/salah.</p>
            </div>
            <div class="p-5 bg-white rounded-2xl shadow">
                <h3 class="font-semibold">Pembelajaran Interaktif</h3>
                <p class="mt-2 text-sm text-slate-600">Latihan dengan permainan, level, dan reward untuk memotivasi belajar.
                </p>
            </div>
            <div class="p-5 bg-white rounded-2xl shadow">
                <h3 class="font-semibold">Dashboard Guru</h3>
                <p class="mt-2 text-sm text-slate-600">Pantau kemajuan kelas dan beri intervensi tepat waktu.</p>
            </div>
        </div>
    </section>
    
    <section id="how" class="mt-16">
        <h2 class="text-2xl font-bold">Cara Kerja</h2>
        <ol class="mt-4 space-y-4 list-decimal list-inside text-slate-600">
            <li>Siswa memilih topik dan mulai latihan interaktif.</li>
            <li>AI menilai jawaban dan memberi penjelasan langkah demi langkah.</li>
            <li>Siswa dapat mengulang level atau naik ke latihan berikutnya.</li>
        </ol>
    </section>

    <section id="testimonials" class="mt-16">
        <h2 class="text-2xl font-bold">Testimoni</h2>
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="p-5 bg-white rounded-2xl shadow">
                <p class="italic">"Movato bikin anakku jadi lebih pede sama matematika!"</p>
                <div class="mt-3 text-sm font-semibold">— Ibu Siti, orang tua</div>
            </div>
            <div class="p-5 bg-white rounded-2xl shadow">
                <p class="italic">"Fitur AI-nya membantu siswa paham konsep, bukan cuma jawab soal."</p>
                <div class="mt-3 text-sm font-semibold">— Pak Andi, guru</div>
            </div>
        </div>
    </section>


    <!-- CTA -->
    <section class="mt-16 mb-24">
        <div class="bg-indigo-600/5 p-6 rounded-2xl flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-xl font-bold">Siap bikin belajar jadi seru?</h3>
                <p class="text-slate-600">Daftar sekarang dan coba Movato gratis untuk kelasmu.</p>
            </div>
            <div>
                <a href="#" class="px-6 py-3 rounded-full bg-indigo-600 text-white font-medium shadow">Daftar Sekarang</a>
            </div>
        </div>
    </section>


@endsection