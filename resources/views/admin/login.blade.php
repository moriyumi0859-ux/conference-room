<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理者ログイン</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow w-full max-w-md">
        <h1 class="text-xl font-bold mb-6 text-center">管理者ログイン</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('admin.login') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border rounded px-3 py-2 text-sm" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">パスワード</label>
                <input type="password" name="password"
                    class="w-full border rounded px-3 py-2 text-sm" required>
            </div>
            <button type="submit"
                class="w-full bg-gray-800 text-white py-2 rounded text-sm hover:bg-gray-700">
                ログイン
            </button>
        </form>
    </div>
</body>
</html>