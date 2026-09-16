<template>
  <div>
    <div class="page-header">
      <h2>Admin Users</h2>
    </div>

    <div v-if="loading" class="loading-msg">Loading users…</div>
    <div v-else-if="loadError" class="alert alert-error">{{ loadError }}</div>
    <template v-else>
      <div class="users-card">
        <div class="users-list">
          <div v-if="!users.length" class="empty-msg">No admin users found.</div>
          <div v-for="u in users" :key="u.email" class="user-row">
            <div class="user-info">
              <span class="user-email">{{ u.email }}</span>
              <span class="user-since">Added {{ formatDate(u.created_at) }}</span>
            </div>
            <button
              class="remove-btn"
              @click="remove(u.email)"
              :disabled="removing === u.email"
            >
              {{ removing === u.email ? 'Removing…' : 'Remove' }}
            </button>
          </div>
        </div>

        <div class="add-section">
          <h3 class="add-title">Add Admin User</h3>
          <div class="add-row">
            <input
              v-model="newEmail"
              type="email"
              placeholder="new-admin@example.com"
              class="add-input"
              @keydown.enter.prevent="add"
            />
            <button class="btn-admin btn-admin-primary" @click="add" :disabled="!newEmail || adding">
              <span v-if="adding" class="spinner"></span>
              {{ adding ? 'Adding…' : 'Add User' }}
            </button>
          </div>
          <div v-if="addError"    class="alert alert-error">{{ addError }}</div>
          <div v-if="addSuccess"  class="alert alert-success">User added successfully.</div>
          <div v-if="removeError" class="alert alert-error">{{ removeError }}</div>
        </div>
      </div>
    </template>
  </div>
</template>

<script setup>
definePageMeta({ layout: 'admin', middleware: 'admin-auth' })

const { public: { apiBaseUrl } } = useRuntimeConfig()
const base = apiBaseUrl.replace(/\/$/, '')

const users     = ref([])
const loading   = ref(true)
const loadError = ref('')
const newEmail  = ref('')
const adding    = ref(false)
const addError  = ref('')
const addSuccess = ref(false)
const removing  = ref('')
const removeError = ref('')

async function fetchUsers() {
  loading.value = true
  try {
    users.value = await useAdminFetch(`${base}/admin/get-admin-users.php`)
  } catch (e) {
    if (e.status === 401) { navigateTo('/admin/login'); return }
    loadError.value = 'Failed to load users.'
  } finally {
    loading.value = false
  }
}

async function add() {
  adding.value = true
  addError.value = ''
  addSuccess.value = false
  try {
    await useAdminFetch(`${base}/admin/add-admin-user.php`, {
      method: 'POST', body: { email: newEmail.value }
    })
    addSuccess.value = true
    newEmail.value = ''
    await fetchUsers()
  } catch (e) {
    addError.value = e?.data?.error || 'Failed to add user.'
  } finally {
    adding.value = false
  }
}

async function remove(email) {
  if (!confirm(`Remove ${email} from admin list?`)) return
  removing.value = email
  removeError.value = ''
  try {
    await useAdminFetch(`${base}/admin/remove-admin-user.php`, {
      method: 'POST', body: { email }
    })
    await fetchUsers()
  } catch (e) {
    removeError.value = e?.data?.error || 'Failed to remove user.'
  } finally {
    removing.value = ''
  }
}

function formatDate(dt) {
  return new Date(dt).toLocaleDateString('en-GB', { day: 'numeric', month: 'short', year: 'numeric' })
}

onMounted(fetchUsers)
</script>

<style scoped>
.page-header { margin-bottom: 28px; }
.page-header h2 { font-family: var(--admin-font-family); font-size: 26px; font-weight: 700; margin: 0; color: #1A1A1A; }
.loading-msg { font-size: 14px; color: #6B7280; }
.users-card { background: #fff; border-radius: 14px; box-shadow: 0 1px 4px rgba(0,0,0,0.06); overflow: hidden; }
.users-list { border-bottom: 1px solid #F3F4F6; }
.empty-msg { padding: 24px 24px; font-size: 14px; color: #9CA3AF; }
.user-row {
  display: flex; align-items: center; justify-content: space-between;
  padding: 16px 24px; border-bottom: 1px solid #F3F4F6;
}
.user-row:last-child { border-bottom: none; }
.user-info { display: flex; flex-direction: column; gap: 3px; }
.user-email { font-size: 15px; font-weight: 600; color: #1A1A1A; }
.user-since { font-size: 12px; color: #9CA3AF; }
.remove-btn {
  padding: 6px 14px; border: 1px solid #EF4444; border-radius: 6px;
  background: none; color: #EF4444; font-size: 12px; font-weight: 600;
  font-family: 'Manrope', sans-serif; cursor: pointer; transition: all 0.15s;
  flex-shrink: 0;
}
.remove-btn:hover:not(:disabled) { background: #FEE2E2; }
.remove-btn:disabled { opacity: 0.5; cursor: not-allowed; }
.add-section { padding: 24px; }
.add-title { font-size: 14px; font-weight: 700; color: #374151; margin: 0 0 16px; }
.add-row { display: flex; gap: 12px; align-items: center; }
.add-input {
  flex: 1; padding: 10px 14px; border: 1.5px solid #E5E7EB;
  border-radius: 8px; font-size: 14px; font-family: 'Manrope', sans-serif;
  outline: none; transition: border-color 0.2s;
}
.add-input:focus { border-color: #B91C1C; }
.btn-admin { padding: 10px 22px; border-radius: 8px; border: none; font-size: 14px; font-weight: 600; font-family: 'Manrope', sans-serif; cursor: pointer; transition: all 0.15s; display: inline-flex; align-items: center; gap: 8px; white-space: nowrap; }
.btn-admin-primary { background: #B91C1C; color: #fff; }
.btn-admin-primary:hover:not(:disabled) { background: #991B1B; }
.btn-admin-primary:disabled { opacity: 0.6; cursor: not-allowed; }
.alert { padding: 12px 16px; border-radius: 8px; font-size: 13px; margin-top: 12px; }
.alert-error { background: #FEE2E2; color: #991B1B; }
.alert-success { background: #D1FAE5; color: #065F46; }
.spinner { width: 14px; height: 14px; border: 2px solid rgba(255,255,255,0.4); border-top-color: #fff; border-radius: 50%; animation: spin 0.7s linear infinite; }
@keyframes spin { to { transform: rotate(360deg); } }
@media (max-width: 600px) { .add-row { flex-direction: column; align-items: stretch; } }
</style>
