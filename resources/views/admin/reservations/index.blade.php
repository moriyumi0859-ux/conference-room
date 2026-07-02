<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>予約管理</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <header class="bg-gray-800 text-white px-6 py-4 flex justify-between items-center">
        <h1 class="text-lg font-bold">会議室予約システム［管理者］</h1>
        <div class="text-sm flex gap-6 items-center">
            <a href="{{ route('admin.dashboard') }}" class="hover:underline">ダッシュボード</a>
            <a href="{{ route('admin.conference-rooms.index') }}" class="hover:underline">会議室管理</a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="hover:underline">ログアウト</button>
            </form>
        </div>
    </header>

    <main class="max-w-4xl mx-auto py-8 px-4">
        <h2 class="text-xl font-bold mb-6">予約管理</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">利用者名</th>
                        <th class="px-4 py-3 text-left">会議室名</th>
                        <th class="px-4 py-3 text-center">予約日</th>
                        <th class="px-4 py-3 text-center">時間</th>
                        <th class="px-4 py-3 text-center">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($reservations as $reservation)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $reservation->user->name }}</td>
                            <td class="px-4 py-3">{{ $reservation->conferenceRoom->name }}</td>
                            <td class="px-4 py-3 text-center">
                                {{ $reservation->reservation_date->format('Y/m/d') }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                {{ $reservation->start_time }} 〜 {{ $reservation->end_time }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <form method="POST"
                                    action="{{ route('admin.reservations.destroy', $reservation) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="bg-red-500 text-white px-3 py-1 rounded text-xs hover:bg-red-400"
                                        onclick="return confirm('予約をキャンセルしますか？')">
                                        キャンセル
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-6 text-center text-gray-400">
                                予約はありません。
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="mt-4">{{ $reservations->links() }}</div>
    </main>
</body>
</html>