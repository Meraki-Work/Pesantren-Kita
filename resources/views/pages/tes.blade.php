@extends('index')

@section('tes')

dd($columns, $rows);

    <h1 class="text-xl font-bold mb-4">Data Keuangan</h1>

    <x-dynamic-table :columns="$columns" :rows="$rows" />
@endsection
