// Simple helpers usable across pages

// demo toast
function showToast(msg){
  const t = document.createElement('div');
  t.className = 'position-fixed bottom-0 end-0 p-3';
  t.innerHTML = `
    <div class="toast align-items-center text-bg-dark border-0 show">
      <div class="d-flex">
        <div class="toast-body">${msg}</div>
        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
      </div>
    </div>`;
  document.body.appendChild(t);
  setTimeout(()=>t.remove(), 3500);
}

// active link highlighter (runs on every page)
(function(){
  const here = location.pathname.split('/').pop();
  document.querySelectorAll('.sidebar .nav-link').forEach(a=>{
    const href = (a.getAttribute('href')||'').split('/').pop();
    if(href===here) a.classList.add('active');
  });
})();
