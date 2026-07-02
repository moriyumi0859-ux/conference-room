<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会議室登録</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">
    <header class="bg-gray-800 text-white px-6 py-4 flex justify-between items-center">
        <h1 class="text-lg font-bold">会議室予約システム［管理者］</h1>
    </header>

    <main class="max-w-2xl mx-auto py-8 px-4">
        <a href="{{ route('admin.conference-rooms.index') }}"
            class="text-sm text-blue-600 hover:underline">
            ← 会議室管理に戻る
        </a>

        <div class="bg-white rounded shadow p-6 mt-4">
            <h2 class="text-xl font-bold mb-6">会議室登録</h2>

            @if ($errors->any())
                <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm">
                    @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.conference-rooms.store') }}">
                @csrf
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">会議室名</label>
                    <input type="text" name="name" value="{{ old('name') }}"
                        class="w-full border rounded px-3 py-2 text-sm" required>
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-1">定員</label>
                    <input type="number" name="capacity" value="{{ old('capacity', 1) }}"
                        min="1" class="w-full border rounded px-3 py-2 text-sm" required>
                </div>
                <div class="mb-6">
                    <label class="block text-sm font-medium mb-1">設備（任意）</label>
                    <input type="text" name="equipment" value="{{ old('equipment') }}"
                        placeholder="例：プロジェクター・ホワイトボード"
                        class="w-full border rounded px-3 py-2 text-sm">
                </div>
                <button type="submit"
                    class="bg-blue-600 text-white px-6 py-2 rounded text-sm hover:bg-blue-500">
                    登録する
                </button>
            </form>
        </div>
    </main>
</body>
</html>