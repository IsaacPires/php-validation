const API_URL = '/api';
const token = localStorage.getItem('auth_token');

document?.getElementById('loginForm')?.addEventListener('submit', async e => {
    e.preventDefault();
    const form = e.target;
    const email = form.email.value;
    const password = form.password.value;
    const captcha = grecaptcha.getResponse();

    const res = await fetch(`${API_URL}/login`, {
        method: 'POST',
        headers: {'Content-Type':'application/json'},
        body: JSON.stringify({email, password, 'g-recaptcha-response': captcha})
    });
    const data = await res.json();
    if(data.token){
        localStorage.setItem('auth_token', data.token);
        window.location.href = '/users';
    } else {
        document.getElementById('loginError').innerText = data.message;
        grecaptcha.reset();
    }
});

// ===== Logout =====
function logout() {
    localStorage.removeItem('auth_token');
    window.location.href = '/login';
}

// ===== Users CRUD =====
async function fetchUsers(){
    const res = await fetch(`${API_URL}/users`, {
        headers: {Authorization: `Bearer ${token}`}
    });
    const data = await res.json();
    const tbody = document.querySelector('#usersTable tbody');
    tbody.innerHTML = '';
    data.data.forEach(user => {
        tbody.innerHTML += `
            <tr>
                <td><input type="checkbox" data-id="${user.id}"></td>
                <td>${user.id}</td>
                <td>${user.name}</td>
                <td>${user.email}</td>
                <td>${user.is_active ? 'Sim' : 'Não'}</td>
                <td>
                    <button onclick="editUser(${user.id})" class="bg-yellow-500 text-white px-2 rounded">Editar</button>
                    <button onclick="deleteUser(${user.id})" class="bg-red-600 text-white px-2 rounded">Excluir</button>
                </td>
            </tr>
        `;
    });
}

async function deleteUser(id){
    if(!confirm('Deseja excluir esse usuário?')) return;
    await fetch(`${API_URL}/users/${id}`, {
        method:'DELETE',
        headers:{Authorization: `Bearer ${token}`}
    });
    fetchUsers();
}

async function deleteSelected(){
    const checked = document.querySelectorAll('#usersTable tbody input:checked');
    if(!checked.length) return alert('Selecione ao menos um usuário.');
    if(!confirm(`Deseja excluir ${checked.length} usuários?`)) return;
    for(const box of checked){
        await fetch(`${API_URL}/users/${box.dataset.id}`, {
            method:'DELETE',
            headers:{Authorization: `Bearer ${token}`}
        });
    }
    fetchUsers();
}

if(document.querySelector('#usersTable tbody')) fetchUsers();
