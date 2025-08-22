document.getElementById('loginForm').addEventListener('submit', async function(e) 
{
    e.preventDefault();

    const email = document.getElementById('email').value;
    const password = document.getElementById('password').value;
    const recaptcha = grecaptcha.getResponse();

    if (!recaptcha) 
    {
        document.getElementById('errorMessage').innerText = "Por favor, complete o reCAPTCHA.";
        return;
    }

    try 
    {
        const res = await fetch('api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ email, password, 'g-recaptcha-response': recaptcha })
        });

        const data = await res.json();

        if (res.ok) 
        {
            localStorage.setItem('token', data.token);
            window.location.href = '/users';
        } 
        else 
        {
            document.getElementById('errorMessage').innerText = data.message || "Erro ao logar.";
            grecaptcha.reset(); 
        }
    } 
    catch (err) 
    {
        document.getElementById('errorMessage').innerText = "Erro de conexão.";
        grecaptcha.reset();
    }
});
