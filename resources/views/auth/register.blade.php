<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>会員登録</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-8 rounded shadow w-full max-w-md">
        <h1 class="text-xl font-bold mb-6 text-center">会員登録</h1>

        @if ($errors->any())
            <div class="bg-red-100 text-red-600 p-3 rounded mb-4 text-sm">
                @foreach ($errors->all() as $error)
                    <p>{{ $error }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">名前</label>
                <input type="text" name="name" value="{{ old('name') }}"
                    class="w-full border rounded px-3 py-2 text-sm" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">メールアドレス</label>
                <input type="email" name="email" value="{{ old('email') }}"
                    class="w-full border rounded px-3 py-2 text-sm" required>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium mb-1">パスワード</label>
                <input type="password" name="password"
                    class="w-full border rounded px-3 py-2 text-sm" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium mb-1">パスワード確認</label>
                <input type="password" name="password_confirmation"
                    class="w-full border rounded px-3 py-2 text-sm" required>
            </div>
            <button type="submit"
                class="w-full bg-blue-600 text-white py-2 rounded text-sm hover:bg-blue-500">
                登録する
            </button>
        </form>
        <p class="text-sm text-center mt-4">
            すでにアカウントをお持ちの方は
            <a href="{{ route('login') }}" class="text-blue-600 hover:underline">ログイン</a>
        </p>
    </div>
</body>
</html>