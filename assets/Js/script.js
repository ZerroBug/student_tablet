// Utility: show an input as invalid + display message
function markInvalid(el, message) {
  el.style.borderColor = 'var(--danger)';
  el.focus();

  // Find or create error message container
  let msgEl = el.parentElement.querySelector('.error-msg');
  if (!msgEl) {
    msgEl = document.createElement('small');
    msgEl.className = 'error-msg';
    msgEl.style.color = 'var(--danger)';
    msgEl.style.display = 'block';
    msgEl.style.marginTop = '4px';
    el.parentElement.appendChild(msgEl);
  }
  msgEl.textContent = message;

  // Remove the red border after 1.2s but keep the tip until corrected
  setTimeout(() => {
    el.style.borderColor = 'var(--border)';
  }, 1200);
}

const form = document.getElementById('loginForm');
const btn  = document.getElementById('loginBtn');

form.addEventListener('submit', function (e) {
  e.preventDefault();

  const username = document.getElementById('username');
  const password = document.getElementById('password');

  // Clear old messages
  form.querySelectorAll('.error-msg').forEach(el => el.remove());

  if (!username.value.trim()) return markInvalid(username, "Username is required");
  if (!password.value.trim()) return markInvalid(password, "Password is required");

  if (username.value.length < 3) {
    return markInvalid(username, "Username must be at least 3 characters long");
  }

  if (password.value.length < 6) {
    return markInvalid(password, "Password must be at least 6 characters long");
  }

  if (!/^[a-zA-Z0-9]+$/.test(username.value)) {
    return markInvalid(username, "Username must be alphanumeric only");
  }

  if (!/\d/.test(password.value)) {
    return markInvalid(password, "Password must contain at least one number");
  }

  // Passed validation → loading state
  btn.disabled = true;
  btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i><span> Signing in…</span>';

  // Simulate API request
  setTimeout(() => {
    window.location.href = 'dashboard.html';
  }, 800);
});
