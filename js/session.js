function checkUserStatus(redirectIfNotLoggedIn = true, callback) {
  fetch('/ngl/backend/session_status.php')
    .then(r => r.ok ? r.json() : { is_logged_in:false })
    .then(data => {
      if (data.is_logged_in) {
        if (data.username && data.username.toLowerCase() === 'admin') {
          document.getElementById('admin-link')?.style && (document.getElementById('admin-link').style.display = 'block');
        }
        if (typeof callback === 'function') callback(data);
      } else if (redirectIfNotLoggedIn) {
        if (location.pathname !== '/ngl/frontend/login.html') location.href = '/ngl/frontend/login.html';
      } else {
        if (typeof callback === 'function') callback({ is_logged_in:false });
      }
    })
    .catch(() => { if (redirectIfNotLoggedIn) location.href='/ngl/frontend/login.html'; });
}
