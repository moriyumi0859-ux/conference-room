<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会議室一覧・予約</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <header class="bg-white shadow px-6 py-4 flex justify-between items-center">
        <h1 class="text-lg font-bold">会議室予約システム</h1>
        <div class="text-sm flex gap-4 items-center">
            <a href="{{ route('mypage') }}" class="text-blue-600">マイページ</a>
            <span>{{ Auth::user()->name }} さん</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-gray-500 hover:underline">ログアウト</button>
            </form>
        </div>
    </header>

    <main class="max-w-4xl mx-auto py-8 px-4">
        <h2 class="text-xl font-bold mb-6">会議室一覧・予約</h2>

        @if (session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-3 rounded mb-4 text-sm">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="bg-red-100 text-red-600 px-4 py-3 rounded mb-4 text-sm">
                {{ session('error') }}
            </div>
        @endif

        {{-- 会議室一覧 --}}
        <div class="bg-white rounded shadow overflow-hidden mb-8">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-600">
                    <tr>
                        <th class="px-4 py-3 text-left">会議室名</th>
                        <th class="px-4 py-3 text-center">定員</th>
                        <th class="px-4 py-3 text-left">設備</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse ($rooms as $room)
                        <tr class="hover:bg-gray-50">
                            <td class="px-4 py-3">{{ $room->name }}</td>
                            <td class="px-4 py-3 text-center">{{ $room->capacity }}名</td>
                            <td class="px-4 py-3">{{ $room->equipment ?? '−' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-6 text-center text-gray-400">
                                会議室が登録されていません。
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- 予約フォーム --}}
        <div class="bg-white rounded shadow p-6">
            <h3 class="font-bold mb-4">予約する</h3>
            <form method="POST" action="{{ route('reservations.store') }}" class="flex gap-3 flex-wrap">
                @csrf
                <select name="conference_room_id" class="border rounded px-3 py-2 text-sm" required>
                    <option value="">会議室を選択</option>
                    @foreach ($rooms as $room)
                        <option value="{{ $room->id }}">{{ $room->name }}（定員{{ $room->capacity }}名）</option>
                    @endforeach
                </select>
                <input type="date" name="reservation_date"
                    min="{{ now()->toDateString() }}"
                    class="border rounded px-3 py-2 text-sm" required>
                <input type="time" name="start_time" class="border rounded px-3 py-2 text-sm" required>
                <span class="flex items-center text-sm">〜</span>
                <input type="time" name="end_time" class="border rounded px-3 py-2 text-sm" required>
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-500">
                    予約する
                </button>
            </form>
        </div>
    </main>
</body>
</html>