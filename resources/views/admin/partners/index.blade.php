@extends('layouts.admin')
@section('page_title', 'Kelola Partner')
@section('content')

    <form method="GET" action="{{ route('admin.partners.index') }}" class="mb-6">
        <input type="text" name="search" value="{{ $search ?? '' }}" placeholder="Ketik nama partner..."
            class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl outline-none font-medium shadow-sm">
    </form>

    <div class="mb-4 text-right">
        <a href="{{ route('admin.partners.create') }}"
            class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition">+
            Tambah Partner</a>
    </div>

    @foreach($partners as $partner)
        <div class="flex items-center justify-between p-4 bg-white rounded-xl mb-4 shadow-sm">
            <div class="flex items-center gap-4">
                <img src="{{ $partner->logo_url }}" class="w-16 h-16 rounded-xl">
                <p class="font-bold text-slate-800">{{ $partner->name }}</p>
            </div>
            <div class="flex gap-2">
                <a href="{{ route('admin.partners.edit', $partner->id) }}"
                    class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 00-2 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                        </path>
                    </svg>

                </a>
                <form action="{{ route('admin.partners.destroy', $partner->id) }}" method="POST"
                    onsubmit="return confirm('Yakin mau dihapus?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                </form>
            </div>
        </div>
    @endforeach
@endsection