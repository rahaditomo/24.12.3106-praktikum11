@extends('layouts.admin')
@section('page_title', 'Edit Partner')
@section('content')
<div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm max-w-3xl">
    <form action="{{ route('admin.partners.update', $partner->id) }}" method="POST" class="space-y-6">
        @csrf
        @method('PUT')
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Nama Partner</label>
            <input type="text" name="name" value="{{ old('name', $partner->name) }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none font-medium" required>
        </div>
        <div>
            <label class="block text-sm font-bold text-slate-700 mb-2">Logo URL</label>
            <input type="text" name="logo_url" value="{{ old('logo_url', $partner->logo_url) }}" class="w-full px-5 py-4 bg-slate-50 border-2 border-slate-100 rounded-2xl outline-none font-medium" required>
        </div>
        <div class="flex justify-end gap-4 pt-4 border-t border-slate-100">
            <a href="{{ route('admin.partners.index') }}" class="px-6 py-4 text-slate-500 font-bold">Batal</a>
            <button type="submit" class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection