@extends('index')

@section('title', 'Dashboard')


@section('content')
<h1>Dashboard Page</h1>
@endsection

<body class="flex bg-gray-100">
    <x-sidemenu title="Admin Panel" />

    <main class="flex-1 p-6">
        {{ $slot ?? '' }}
    </main>

</body>