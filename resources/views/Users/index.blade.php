<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Usuários</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">

    <nav class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="font-bold text-xl">Painel de Usuários</h1>
        <button id="logoutBtn" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Sair</button>
    </nav>

    <div class="p-6">
        <div class="flex justify-between items-center mb-4">
            <div>
                <button id="deleteSelectedBtn" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 hidden">Excluir Selecionados</button>
            </div>
            <div class="flex items-center space-x-4">
                 <div class="flex items-center space-x-2">
                    <label for="perPageSelect" class="text-sm text-gray-700">Itens por página:</label>
                    <select id="perPageSelect" class="border rounded px-2 py-1 text-sm">
                        <option value="20">20</option>
                        <option value="50">50</option>
                        <option value="100">100</option>
                    </select>
                </div>
                <button id="newUserBtn" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Novo Usuário</button>
            </div>
            </div>

        <table class="w-full bg-white shadow rounded overflow-hidden">
            <thead class="bg-gray-200">
                <tr>
                    <th class="p-2 border w-1"><input type="checkbox" id="selectAllCheckbox"></th>
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Nome</th>
                    <th class="p-2 border">Email</th>
                    <th class="p-2 border">Ativo</th>
                    <th class="p-2 border">Ações</th>
                </tr>
            </thead>
            <tbody id="userTableBody"></tbody>
        </table>

        <div id="paginationControls" class="mt-4 flex justify-end items-center space-x-2"></div>
        </div>

    <div id="userModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded p-6 w-full max-w-md">
            <h3 class="text-xl font-bold mb-4">Novo Usuário</h3>
            <form id="userForm" class="space-y-3">
                @csrf
                <div>
                    <label class="block text-gray-700">Nome</label>
                    <input type="text" name="name" id="name" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="email" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700">Senha</label>
                    <input type="password" name="password" id="password" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700">Confirmar Senha</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div class="g-recaptcha" data-sitekey="{{ env('NOCAPTCHA_SITEKEY') }}"></div>
                <p id="formError" class="text-red-500 text-sm mt-2"></p>
                <div class="flex justify-end mt-4 space-x-2">
                    <button type="button" id="closeModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">Criar</button>
                </div>
            </form>
        </div>
    </div>
    
    <div id="editUserModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white rounded p-6 w-full max-w-md">
            <h3 class="text-xl font-bold mb-4">Editar Usuário</h3>
            <form id="editUserForm" class="space-y-3">
                <input type="hidden" id="edit-id" name="id">
                <div>
                    <label class="block text-gray-700">Nome</label>
                    <input type="text" name="name" id="edit-name" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div>
                    <label class="block text-gray-700">Email</label>
                    <input type="email" name="email" id="edit-email" class="w-full px-3 py-2 border rounded" required>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" name="active" id="edit-active" class="mr-2">
                    <label for="edit-active" class="text-gray-700">Ativo</label>
                </div>
                 <p id="editFormError" class="text-red-500 text-sm mt-2"></p>
                <div class="flex justify-end mt-4 space-x-2">
                    <button type="button" id="closeEditModal" class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">Cancelar</button>
                    <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
    <script src="{{ asset('Js/User/script.js') }}"></script>
</body>
</html>