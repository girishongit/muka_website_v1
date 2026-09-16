<template>
  <div>
    <div class="page-header">
      <h2>Ticket Categories</h2>
      <button class="btn-admin btn-admin-primary" @click="addRow">+ Add Category</button>
    </div>

    <p class="section-desc">Define ticket categories used by default events (Utsava, Ugadi, Food Festival). Each category has a unique ID referenced by events.</p>

    <div v-if="loadError" class="alert alert-error">{{ loadError }}</div>
    <div v-else-if="loading" class="loading-msg">Loading…</div>
    <template v-else>
      <div class="tc-table-wrap">
        <table class="tc-table">
          <thead>
            <tr>
              <th>ID <span class="th-note">(unique, lowercase)</span></th>
              <th>Label</th>
              <th>Price</th>
              <th>Currency</th>
              <th>Type</th>
              <th>Member Plan Eligibility <span class="th-note">(comma-separated plan IDs)</span></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(cat, i) in categories" :key="cat.id || i" :class="{ 'row-member': cat.type === 'member' }">
              <td><input v-model="cat.id" type="text" class="tc-input tc-id" placeholder="adult_member" /></td>
              <td><input v-model="cat.label" type="text" class="tc-input" placeholder="Adult (Member)" /></td>
              <td><input v-model.number="cat.price" type="number" min="0" step="0.01" class="tc-input tc-price" /></td>
              <td><input v-model="cat.currency" type="text" class="tc-input tc-currency" placeholder="EUR" maxlength="10" /></td>
              <td>
                <select v-model="cat.type" class="tc-select">
                  <option value="nonMember">Non-member</option>
                  <option value="member">Member</option>
                </select>
              </td>
              <td>
                <input
                  v-if="cat.type === 'member'"
                  :value="(cat.memberPlanEligibility || []).join(', ')"
                  type="text"
                  class="tc-input"
                  placeholder="single_adult, family"
                  @input="cat.memberPlanEligibility = $event.target.value.split(',').map(s => s.trim()).filter(Boolean)"
                />
                <span v-else class="tc-na">—</span>
              </td>
              <td>
                <button class="action-btn action-btn--danger" @click="removeRow(i)" title="Delete">✕</button>
              </td>
            </tr>
            <tr v-if="!categories.length">
              <td colspan="7" class="empty-msg">No categories yet. Click "+ Add Category" to create one.</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div v-if="saveError" class="alert alert-error">{{ saveError }}</div>
      <div v-if="saveSuccess" class="alert alert-success">Saved successfully.</div>

      <div class="form-footer">
        <button class="btn-admin btn-admin-primary" :disabled="saving" @click="saveAll">
          <span v-if="saving" class="spinner"></span>
          {{ saving ? 'Saving…' : 'Save All' }}
        </button>
      </div>
    </template>
  </div>
</template>

<script setup>
definePageMeta({ layout: 'admin', middleware: 'admin-auth' })

const { public: { apiBaseUrl } } = useRuntimeConfig()
const base = apiBaseUrl.replace(/\/$/, '')

const categories  = ref([])
const loading     = ref(true)
const loadError   = ref('')
const saving      = ref(false)
const saveError   = ref('')
const saveSuccess = ref(false)

onMounted(async () => {
  try {
    const res = await useAdminFetch(`${base}/admin/get-ticket-categories.php`)
    categories.value = res.categories ?? []
  } catch (e) {
    loadError.value = e?.data?.error ?? 'Failed to load ticket categories.'
  } finally {
    loading.value = false
  }
})

function addRow() {
  categories.value.push({ id: '', label: '', price: 0, currency: 'EUR', type: 'nonMember', memberPlanEligibility: [] })
}

function removeRow(i) {
  categories.value.splice(i, 1)
}

async function saveAll() {
  saveError.value   = ''
  saveSuccess.value = false
  saving.value      = true
  try {
    await useAdminFetch(`${base}/admin/save-ticket-categories.php`, {
      method: 'POST',
      body:   { categories: categories.value },
    })
    saveSuccess.value = true
    setTimeout(() => { saveSuccess.value = false }, 3000)
  } catch (e) {
    saveError.value = e?.data?.error ?? 'Failed to save. Please try again.'
  } finally {
    saving.value = false
  }
}
</script>

<style scoped>
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
.section-desc { color: var(--text-light, #666); font-size: 14px; margin-bottom: 24px; }
.tc-table-wrap { overflow-x: auto; }
.tc-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.tc-table th { text-align: left; padding: 8px 10px; background: #f5f5f5; border-bottom: 2px solid #e0e0e0; white-space: nowrap; }
.tc-table td { padding: 6px 8px; border-bottom: 1px solid #ececec; vertical-align: middle; }
.tc-table .row-member td { background: #f0faf5; }
.th-note { font-weight: 400; font-size: 11px; color: #999; }
.tc-input { width: 100%; padding: 5px 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; box-sizing: border-box; }
.tc-input:focus { outline: none; border-color: #c41e3a; }
.tc-id { min-width: 130px; font-family: monospace; }
.tc-price { max-width: 80px; }
.tc-currency { max-width: 70px; }
.tc-select { padding: 5px 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; }
.tc-na { color: #bbb; font-size: 13px; }
.empty-msg { text-align: center; color: #999; padding: 20px; }
.action-btn--danger { background: none; border: none; color: #c41e3a; cursor: pointer; font-size: 16px; padding: 2px 6px; border-radius: 4px; }
.action-btn--danger:hover { background: #fdecea; }
.form-footer { margin-top: 20px; display: flex; justify-content: flex-end; }
.alert-error   { background: #fdecea; color: #c00; padding: 10px 14px; border-radius: 6px; margin-bottom: 12px; font-size: 14px; }
.alert-success { background: #e6f4ea; color: #1a7a3a; padding: 10px 14px; border-radius: 6px; margin-bottom: 12px; font-size: 14px; }
.loading-msg { color: #888; padding: 20px 0; }
.spinner { display: inline-block; width: 12px; height: 12px; border: 2px solid #fff; border-top-color: transparent; border-radius: 50%; animation: spin 0.6s linear infinite; margin-right: 6px; vertical-align: middle; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>
