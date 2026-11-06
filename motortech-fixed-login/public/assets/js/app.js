document.querySelectorAll('input[type="date"]').forEach(el => {
  if (!el.min) el.min = new Date().toISOString().slice(0, 10);
});

const passwordToggle = document.getElementById('togglePassword');
if (passwordToggle) {
  passwordToggle.addEventListener('click', () => {
    const field = document.getElementById('password');
    if (!field) return;
    const visible = field.type === 'password';
    field.type = visible ? 'text' : 'password';
    passwordToggle.textContent = visible ? 'Sembunyikan' : 'Lihat';
  });
}

const sidebar = document.getElementById('adminSidebar');
const backdrop = document.getElementById('sidebarBackdrop');
const menuToggle = document.getElementById('adminMenuToggle');

function setSidebar(open) {
  if (!sidebar || !backdrop || !menuToggle) return;
  sidebar.classList.toggle('open', open);
  backdrop.classList.toggle('show', open);
  menuToggle.setAttribute('aria-expanded', open ? 'true' : 'false');
  document.body.style.overflow = open ? 'hidden' : '';
}

if (menuToggle && sidebar) {
  menuToggle.addEventListener('click', () => setSidebar(!sidebar.classList.contains('open')));
}
if (backdrop) backdrop.addEventListener('click', () => setSidebar(false));
if (sidebar) {
  sidebar.querySelectorAll('a').forEach(link => link.addEventListener('click', () => setSidebar(false)));
}
document.addEventListener('keydown', event => {
  if (event.key === 'Escape') setSidebar(false);
});
window.addEventListener('resize', () => {
  if (window.innerWidth >= 992) setSidebar(false);
});

document.querySelectorAll('#siteNavMenu .nav-link').forEach(link => {
  link.addEventListener('click', () => {
    const menu = document.getElementById('siteNavMenu');
    if (menu && menu.classList.contains('show') && window.bootstrap) {
      bootstrap.Collapse.getOrCreateInstance(menu).hide();
    }
  });
});
