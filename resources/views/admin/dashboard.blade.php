<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ダッシュボード</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <header class="bg-gray-800 text-white px-6 py-4 flex justify-between items-center">
        <h1 class="text-lg font-bold">会議室予約システム［管理者］</h1>
        <div class="text-sm flex gap-6 items-center">
            <a href="{{ route('admin.conference-rooms.index') }}" class="hover:underline">会議室管理</a>
            <a href="{{ route('admin.reservations.index') }}" class="hover:underline">予約管理</a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="hover:underline">ログアウト</button>
            </form>
        </div>
    </header>

    <main class="max-w-4xl mx-auto py-8 px-4">
        <h2 class="text-xl font-bold mb-6">ダッシュボード</h2>

        <div class="grid grid-cols-3 gap-4">
            <div class="bg-white rounded shadow p-6 text-center">
                <p class="text-sm text-gray-500 mb-2">会議室総数</p>
                <p class="text-3xl font-bold text-gray-800">{{ $roomCount }}</p>
                <p class="text-sm text-gray-400 mt-1">室</p>
            </div>
            <div class="bg-white rounded shadow p-6 text-center">
                <p class="text-sm text-gray-500 mb-2">当日の予約件数</p>
                <p class="text-3xl font-bold text-blue-600">{{ $todayReservationCount }}</p>
                <p class="text-sm text-gray-400 mt-1">件</p>
            </div>
            <div class="bg-white rounded shadow p-6 text-center">
                <p class="text-sm text-gray-500 mb-2">当日空き会議室</p>
                <p class="text-3xl font-bold text-green-600">{{ $availableRoomCount }}</p>
                <p class="text-sm text-gray-400 mt-1">室</p>
            </div>
        </div>
    </main>
</body>
</html>