<template>
  <div class="admin-shell">
    <aside class="admin-sidebar">
      <div class="sidebar-logo">
        <span class="kannada-text">ಮ್ಯೂನಿಕ್</span>
        <span>Admin</span>
      </div>
      <nav class="sidebar-nav">
        <NuxtLink to="/admin/events" class="nav-item" active-class="active">
          <span class="nav-icon">📅</span> Events
        </NuxtLink>
        <NuxtLink to="/admin/users" class="nav-item" active-class="active">
          <span class="nav-icon">👤</span> Admin Users
        </NuxtLink>
        <NuxtLink to="/admin/event-tickets" class="nav-item" active-class="active">
          <span class="nav-icon">🎟</span> Event Tickets
        </NuxtLink>
      </nav>
      <div class="sidebar-footer">
        <span class="sidebar-user">{{ adminEmail }}</span>
        <button class="logout-btn" @click="logout">Log out</button>
      </div>
    </aside>

    <main class="admin-main">
      <slot />
    </main>
  </div>
</template>

<script setup>
const { public: { apiBaseUrl } } = useRuntimeConfig()
const base = apiBaseUrl.replace(/\/$/, '')
const adminEmail = useCookie('admin_email_hint')

async function logout() {
  await useAdminFetch(`${base}/admin/logout.php`, { method: 'POST' }).catch(() => {})
  const hint = useCookie('admin_session_hint', { path: '/' })
  hint.value = null
  navigateTo('/admin/login')
}
</script>

<style>
/* Reset admin area */
.admin-shell * { box-sizing: border-box; }
.admin-shell {
  --admin-font-family: 'Manrope', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
  display: flex;
  min-height: 100vh;
  font-family: var(--admin-font-family);
  background: #F7F4F0;
}
.admin-shell button,
.admin-shell input,
.admin-shell select,
.admin-shell textarea {
  font-family: var(--admin-font-family);
}
.admin-sidebar {
  width: 220px;
  flex-shrink: 0;
  background: #1A1A1A;
  color: #fff;
  display: flex;
  flex-direction: column;
  position: sticky;
  top: 0;
  height: 100vh;
}
.sidebar-logo {
  padding: 24px 20px 20px;
  border-bottom: 1px solid rgba(255,255,255,0.1);
  font-size: 14px;
  font-weight: 700;
  display: flex;
  flex-direction: column;
  gap: 2px;
}
.sidebar-logo .kannada-text { color: #F59E0B; font-size: 12px; font-weight: 500; }
.sidebar-nav { flex: 1; padding: 16px 12px; display: flex; flex-direction: column; gap: 4px; }
.nav-item {
  display: flex; align-items: center; gap: 10px;
  padding: 10px 12px; border-radius: 8px;
  color: rgba(255,255,255,0.7); text-decoration: none;
  font-size: 14px; font-weight: 500; transition: all 0.15s;
}
.nav-item:hover { background: rgba(255,255,255,0.08); color: #fff; }
.nav-item.active { background: #B91C1C; color: #fff; }
.nav-icon { font-size: 16px; }
.sidebar-footer {
  padding: 16px 20px;
  border-top: 1px solid rgba(255,255,255,0.1);
  display: flex; flex-direction: column; gap: 8px;
}
.sidebar-user { font-size: 11px; color: rgba(255,255,255,0.5); word-break: break-all; }
.logout-btn {
  background: none; border: 1px solid rgba(255,255,255,0.2);
  color: rgba(255,255,255,0.7); padding: 7px 12px;
  border-radius: 6px; cursor: pointer; font-size: 13px;
  font-family: var(--admin-font-family); transition: all 0.15s;
}
.logout-btn:hover { border-color: #fff; color: #fff; }
.admin-main { flex: 1; padding: 36px 40px; min-width: 0; }
@media (max-width: 768px) {
  .admin-shell { flex-direction: column; }
  .admin-sidebar { width: 100%; height: auto; position: static; flex-direction: row; flex-wrap: wrap; }
  .sidebar-logo { border-bottom: none; border-right: 1px solid rgba(255,255,255,0.1); }
  .sidebar-nav { flex-direction: row; padding: 12px; }
  .admin-main { padding: 20px; }
}
</style>
