<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会議室管理</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <header class="bg-gray-800 text-white px-6 py-4 flex justify-between items-center">
        <h1 class="text-lg font-bold">会議室予約システム［管理者］</h1>
        <div class="text-sm flex gap-6 items-center">
            <a href="{{ route('admin.dashboard') }}" class="hover:underline">ダッシュボード</a>
            <a href="{{ route('admin.reservations.index') }}" class="hover:underline">予約管理</a>
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="hover:underline">ログアウト</button>
            </form>
        </div>
    </header>

    <main class="max-w-4xl mx-auto py-8 px-4">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold">会議室管理</h2>
            <a href="{{ route('admin.conference-rooms.create') }}"
                class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-500">
                ＋ 新規登録
            </a>
        </div>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded shadow overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">会議室名</th>
                        <th class="px-4 py-3 text-center">定員</th>
                        <th class="px-4 py-3 text-left">設備</th>
                        <th class="px-4 py-3 text-center">操作</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($rooms as $room)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $room->name }}</td>
                            <td class="px-4 py-3 text-center">{{ $room->capacity }}名</td>
                            <td class="px-4 py-3">{{ $room->equipment ?? '−' }}</td>
                            <td class="px-4 py-3 text-center flex gap-3 justify-center">
                                <a href="{{ route('admin.conference-rooms.edit', $room) }}"
                                    class="text-blue-600 hover:underline">編集</a>
                                <form method="POST"
                                    action="{{ route('admin.conference-rooms.destroy', $room) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:underline"
                                        onclick="return confirm('削除しますか？')">削除</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-4 py-6 text-center text-gray-400">
                                会議室が登録されていません。
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>