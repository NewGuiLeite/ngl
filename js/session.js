function checkUserStatus(redirectIfNotLoggedIn = true, callback) {
    console.log('Verificando status do usuário...');
    fetch('/ngl/backend/session_status.php')
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Dados recebidos:', data);
            if (data.is_logged_in) {
                document.body.classList.add('logged-in');
                if (data.username && data.username.toLowerCase() === 'admin') {
                    document.body.classList.add('admin');
                    const adminLink = document.getElementById('admin-link');
                    if (adminLink) {
                        adminLink.style.display = 'block';
                    }
                }
                if (callback && typeof callback === 'function') {
                    callback(data);
                }
            } else if (redirectIfNotLoggedIn) {
                const currentPath = window.location.pathname;
                console.log('Redirecionando para login. Caminho atual:', currentPath);
                if (currentPath !== '/ngl/frontend/login.html') {
                    window.location.href = '/ngl/frontend/login.html';
                }
            }
        })
        .catch(error => console.error('Erro ao verificar status do usuário:', error));
}
