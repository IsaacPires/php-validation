<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Uhuu</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="w-full max-w-md bg-white rounded shadow-md p-8">
        <h2 class="text-2xl font-bold mb-6 text-center">Uhuu</h2>

        <form id="loginForm" class="space-y-4">
             @csrf
            <div>
                <label class="block text-gray-700">Email</label>
                <input type="email" name="email" id="email" class="w-full px-3 py-2 border rounded" required>
            </div>
            <div>
                <label class="block text-gray-700">Senha</label>
                <input type="password" name="password" id="password" class="w-full px-3 py-2 border rounded" required>
            </div>

            <div class="g-recaptcha" data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"></div>

            <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded hover:bg-blue-700 transition">
                Entrar
            </button>

            <p id="errorMessage" class="text-red-500 text-sm mt-2"></p>
        </form>
    </div>
    <script src="{{ asset('Js/Login/script.js') }}"></script>
</body>
</html>
