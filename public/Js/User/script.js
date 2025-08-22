let currentPage = 1;
let perPage = 20;
const selectedUserIds = new Set();
const token = localStorage.getItem('token');

if (!token) 
    window.location.href = '/login';

const modal = document.getElementById('userModal');
const editModal = document.getElementById('editUserModal');
const selectAllCheckbox = document.getElementById('selectAllCheckbox');
const deleteSelectedBtn = document.getElementById('deleteSelectedBtn');
const perPageSelect = document.getElementById('perPageSelect');
const userTableBody = document.getElementById('userTableBody');

async function loadUsers() 
{
    const token = localStorage.getItem('token');
    const url = `api/users?page=${currentPage}&per_page=${perPage}`;

    try 
    {
        const res = await fetch(url, {
            headers: { 'Authorization': 'Bearer ' + token }
        });

        if (!res.ok) 
        {
            console.error("Falha ao carregar usuários. Status:", res.status);
            userTableBody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-red-500">Não foi possível carregar os dados.</td></tr>`;
            return;
        }

        const responseData = await res.json();
        
        userTableBody.innerHTML = '';
        
        selectedUserIds.clear();
        updateDeleteButtonState();
        selectAllCheckbox.checked = false;

        responseData.data.forEach(user => 
        {
            userTableBody.innerHTML += `
                <tr>
                    <td class="p-2 border text-center"><input type="checkbox" class="user-checkbox" data-id="${user.id}"></td>
                    <td class="p-2 border">${user.id}</td>
                    <td class="p-2 border">${user.name}</td>
                    <td class="p-2 border">${user.email}</td>
                    <td class="p-2 border">${user.active == 1 ? 'Sim' : 'Não'}</td>
                    <td class="p-2 border space-x-2">
                        <button data-id="${user.id}" class="editBtn px-2 py-1 bg-green-500 text-white rounded hover:bg-green-600">Editar</button>
                        <button data-id="${user.id}" class="deleteBtn px-2 py-1 bg-red-500 text-white rounded hover:bg-red-600">Excluir</button>
                    </td>
                </tr>
            `;
        });
        
        attachActionEvents();

        renderPagination(responseData);
    } 
    catch (error) 
    {
        console.error("Erro de conexão:", error);
        userTableBody.innerHTML = `<tr><td colspan="6" class="p-4 text-center text-red-500">Erro de conexão ao buscar dados.</td></tr>`;
    }
}

function renderPagination(data) 
{
    const paginationContainer = document.getElementById('paginationControls');
    paginationContainer.innerHTML = '';

    if (!data.links || data.links.length === 0) return;

    const pageInfo = document.createElement('span');
    pageInfo.className = 'text-sm text-gray-700';
    pageInfo.innerText = `Página ${data.current_page} de ${data.last_page}`;
    paginationContainer.appendChild(pageInfo);

    const buttonsWrapper = document.createElement('div');
    data.links.forEach(link => {
        const button = document.createElement('button');
        button.innerHTML = link.label;
        button.disabled = !link.url;
        button.className = `px-3 py-1 rounded text-sm ${link.active ? 'bg-blue-600 text-white' : 'bg-white'} ${!link.url ? 'text-gray-400 cursor-not-allowed' : 'hover:bg-gray-200'}`;
        
        if (link.url) 
        {
            button.addEventListener('click', () => 
            {
                try {
                    const urlObject = new URL(link.url, window.location.href);
                    const pageQuery = urlObject.searchParams.get('page');
                    
                    if (pageQuery) {
                        currentPage = parseInt(pageQuery);
                        loadUsers();
                    }
                } catch (error) {
                    console.error("Não foi possível parsear a URL de paginação:", link.url, error);
                }
            });
        }
        buttonsWrapper.appendChild(button);
    });
    paginationContainer.appendChild(buttonsWrapper);
}

document.getElementById('logoutBtn').addEventListener('click', () => {
    localStorage.removeItem('token');
    window.location.href = '/login';
});

document.getElementById('newUserBtn').addEventListener('click', () => modal.classList.remove('hidden'));
document.getElementById('closeModal').addEventListener('click', () => modal.classList.add('hidden'));
document.getElementById('closeEditModal').addEventListener('click', () => editModal.classList.add('hidden'));

perPageSelect.addEventListener('change', (e) => 
{
    perPage = parseInt(e.target.value);
    currentPage = 1;
    loadUsers();
});

function updateDeleteButtonState() 
{
    deleteSelectedBtn.classList.toggle('hidden', selectedUserIds.size === 0);
}

function attachSelectionEvents() 
{
    selectAllCheckbox.addEventListener('change', (e) => {
        const isChecked = e.target.checked;
        const userCheckboxes = document.querySelectorAll('.user-checkbox');
        userCheckboxes.forEach(checkbox => {
            checkbox.checked = isChecked;
            const id = parseInt(checkbox.dataset.id);
            if (isChecked) {
                selectedUserIds.add(id);
            } else {
                selectedUserIds.delete(id);
            }
        });
        updateDeleteButtonState();
    });

    userTableBody.addEventListener('change', e => 
    {
        if (e.target.classList.contains('user-checkbox')) 
            {
            const id = parseInt(e.target.dataset.id);
            if (e.target.checked) 
                selectedUserIds.add(id);
            else
                selectedUserIds.delete(id);
            
            const allCheckboxes = document.querySelectorAll('.user-checkbox');
            selectAllCheckbox.checked = allCheckboxes.length > 0 && Array.from(allCheckboxes).every(cb => cb.checked);
            updateDeleteButtonState();
        }
    });
}

deleteSelectedBtn.addEventListener('click', async () => 
{
    const token = localStorage.getItem('token');

    if (selectedUserIds.size === 0) return;

    if (confirm(`Tem certeza que deseja excluir ${selectedUserIds.size} usuário(s) selecionado(s)?`)) 
    {
        try {
            const res = await fetch('api/users/bulk-delete', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'Authorization': 'Bearer ' + token,
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify({ ids: Array.from(selectedUserIds) })
            });

            if (res.ok) 
                loadUsers();
            else 
                alert('Erro ao excluir usuários.');
            
        } catch (err) 
        {
            alert('Erro de conexão ao tentar excluir.');
        }
    }
});

document.getElementById('userForm').addEventListener('submit', async function(e) 
{
    const token = localStorage.getItem('token');

    e.preventDefault();

    const form = e.target;
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    data['g-recaptcha-response'] = grecaptcha.getResponse();
    
    const formError = document.getElementById('formError');
    formError.textContent = ''; 

    try {
        const res = await fetch('api/users', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + token,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify(data)
        });

        if (res.ok) {
            modal.classList.add('hidden');
            form.reset();
            grecaptcha.reset();
            loadUsers(); 
        } else {
            const errorData = await res.json();
            if (errorData.errors) {
                const errorMessages = Object.values(errorData.errors).flat().join(' ');
                formError.textContent = errorMessages;
            } else {
                formError.textContent = 'Ocorreu um erro ao criar o usuário.';
            }
        }
    } catch (err) {
        console.error("Erro na requisição de criação:", err);
        formError.textContent = 'Erro de conexão. Tente novamente.';
    }
});


document.getElementById('editUserForm').addEventListener('submit', async function(e) 
{
    const token = localStorage.getItem('token');

    e.preventDefault();

    //const form = e.target;
    const id = document.getElementById('edit-id').value;
    const name = document.getElementById('edit-name').value;
    const email = document.getElementById('edit-email').value;
    const active = document.getElementById('edit-active').checked ? 1 : 0;
    
    const editFormError = document.getElementById('editFormError');
    editFormError.textContent = ''; 
    try {
        const res = await fetch(`api/users/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'Authorization': 'Bearer ' + token,
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ name, email, active })
        });

        if (res.ok) {
            editModal.classList.add('hidden');
            loadUsers();
        } else {
            const errorData = await res.json();
            if (errorData.errors) {
                const errorMessages = Object.values(errorData.errors).flat().join(' ');
                editFormError.textContent = errorMessages;
            } else {
                editFormError.textContent = 'Ocorreu um erro ao atualizar o usuário.';
            }
        }
    } catch (err) {
        console.error("Erro na requisição de edição:", err);
        editFormError.textContent = 'Erro de conexão. Tente novamente.';
    }
});

function attachActionEvents() {
    const token = localStorage.getItem('token');

    document.querySelectorAll('.deleteBtn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            if (!confirm('Tem certeza que deseja excluir este usuário?')) return;
            await fetch('api/users/' + id, {
                method: 'DELETE',
                headers: { 
                    'Authorization': 'Bearer ' + token, 
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            loadUsers();
        });
    });

    document.querySelectorAll('.editBtn').forEach(btn => {
        btn.addEventListener('click', () => {
            const id = btn.dataset.id;
            openEditModal(id);
        });
    });

    
}

async function openEditModal(id) 
{
    const token = localStorage.getItem('token');

    try {

        const res = await fetch(`api/users/${id}`, {
            headers: { 'Authorization': 'Bearer ' + token }
        });

        if (!res.ok) 
        {
            const errorText = await res.text();
            console.error('A API retornou um erro:', res.status, res.statusText, errorText);
            throw new Error(`Erro ${res.status}: ${res.statusText}`);
        }
        
        const data = await res.json();
        const user = data.data || data;

        if (!user || typeof user.id === 'undefined') 
        {
            console.error("Dados do usuário recebidos em formato inesperado:", data);
            throw new Error("Formato de dados do usuário inválido.");
        }

        document.getElementById('edit-id').value = user.id;
        document.getElementById('edit-name').value = user.name;
        document.getElementById('edit-email').value = user.email;
        document.getElementById('edit-active').checked = user.active == 1;
        
        editModal.classList.remove('hidden');

    } catch(err) {
        console.error("ERRO DETALHADO ao carregar usuário:", err);
        alert('Não foi possível carregar os dados. Verifique o console para mais detalhes (F12).');
    }
}

attachSelectionEvents(); 
loadUsers();           
