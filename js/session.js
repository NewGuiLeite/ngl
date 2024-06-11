function checkUserStatus(redirectIfNotLoggedIn = true, callback) {
    fetch('/ngl/backend/session_status.php') // Caminho absoluto para session_status.php
        .then(response => response.json())
        .then(data => {
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
                window.location.href = '/ngl/frontend/login.html'; // Caminho absoluto para login.html
            }
        })
        .catch(error => console.error('Erro ao verificar status do usuário:', error));
}
