@extends('layouts.app')

@section('title', 'Lihat File')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-8">
    <h1 class="text-xl font-semibold mb-4">📄 Pratinjau Dokumen</h1>

    <div class="mb-4 flex space-x-3">
        <a href="{{ url()->previous() }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 transition">
            ← Kembali
        </a>
    </div>

    @php
        $isLocal = str_contains(request()->getHost(), '127.0.0.1') || str_contains(request()->getHost(), 'localhost');
    @endphp

    <div class="border rounded-lg overflow-hidden" style="height:80vh;">
        @if ($isLocal)
            <iframe src="{{ $fileUrl }}" width="100%" height="100%" frameborder="0"></iframe>
        @else
            <iframe src="https://view.officeapps.live.com/op/embed.aspx?src={{ urlencode($fileUrl) }}" width="100%" height="100%" frameborder="0"></iframe>
        @endif
    </div>
</div>
@endsection
