<template>
  <div>
    <div class="page-header">
      <h2>Event Tickets</h2>
    </div>
    <p class="section-desc">Define ticket sets per event key (e.g. <code>utsava_2026</code>). Each event references its key in the Event editor.</p>

    <div v-if="loadError" class="alert alert-error">{{ loadError }}</div>
    <div v-else-if="loading" class="loading-msg">Loading…</div>
    <template v-else>
      <div class="et-layout">
        <!-- Left: key list -->
        <div class="et-sidebar">
          <div
            v-for="key in eventKeys"
            :key="key"
            class="et-key-item"
            :class="{ active: selectedKey === key }"
            @click="selectedKey = key"
          >
            {{ key }}
          </div>
          <div class="et-add-key">
            <input v-model="newKeyInput" type="text" class="tc-input" placeholder="utsava_2027" @keydown.enter="addKey" />
            <button class="btn-admin btn-admin-secondary" @click="addKey">+ Add</button>
          </div>
        </div>

        <!-- Right: ticket editor for selected key -->
        <div class="et-main" v-if="selectedKey">
          <div class="et-section-header">
            <h3>{{ selectedKey }}</h3>
            <button class="action-btn action-btn--danger" @click="removeKey(selectedKey)">Delete key</button>
          </div>

          <!-- Member tickets -->
          <h4 class="et-table-title">Member Tickets</h4>
          <table class="tc-table">
            <thead>
              <tr>
                <th>ID</th><th>Label</th><th>Price</th><th>Currency</th>
                <th>Member Plan Eligibility <span class="th-note">(comma-separated)</span></th>
                <th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, i) in currentSet.member" :key="row.id || i">
                <td><input v-model="row.id" type="text" class="tc-input tc-id" /></td>
                <td><input v-model="row.label" type="text" class="tc-input" /></td>
                <td><input v-model.number="row.price" type="number" min="0" step="0.01" class="tc-input tc-price" /></td>
                <td><input v-model="row.currency" type="text" class="tc-input tc-currency" maxlength="10" /></td>
                <td>
                  <input
                    :value="(row.memberPlanEligibility || []).join(', ')"
                    type="text"
                    class="tc-input"
                    placeholder="single_adult, family"
                    @input="row.memberPlanEligibility = $event.target.value.split(',').map(s => s.trim()).filter(Boolean)"
                  />
                </td>
                <td><button class="action-btn action-btn--danger" @click="currentSet.member.splice(i,1)">✕</button></td>
              </tr>
              <tr v-if="!currentSet.member.length">
                <td colspan="6" class="empty-msg">No member tickets. Click "+ Add Row" to add one.</td>
              </tr>
            </tbody>
          </table>
          <button class="btn-admin btn-admin-secondary btn-sm" @click="currentSet.member.push({ id:'', label:'', price:0, currency:'EUR', memberPlanEligibility:[] })">+ Add Row</button>

          <!-- Non-member tickets -->
          <h4 class="et-table-title" style="margin-top:24px">Non-Member Tickets</h4>
          <table class="tc-table">
            <thead>
              <tr>
                <th>ID</th><th>Label</th><th>Price</th><th>Currency</th><th></th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(row, i) in currentSet.nonMember" :key="row.id || i">
                <td><input v-model="row.id" type="text" class="tc-input tc-id" /></td>
                <td><input v-model="row.label" type="text" class="tc-input" /></td>
                <td><input v-model.number="row.price" type="number" min="0" step="0.01" class="tc-input tc-price" /></td>
                <td><input v-model="row.currency" type="text" class="tc-input tc-currency" maxlength="10" /></td>
                <td><button class="action-btn action-btn--danger" @click="currentSet.nonMember.splice(i,1)">✕</button></td>
              </tr>
              <tr v-if="!currentSet.nonMember.length">
                <td colspan="5" class="empty-msg">No non-member tickets. Click "+ Add Row" to add one.</td>
              </tr>
            </tbody>
          </table>
          <button class="btn-admin btn-admin-secondary btn-sm" @click="currentSet.nonMember.push({ id:'', label:'', price:0, currency:'EUR' })">+ Add Row</button>
        </div>
        <div class="et-main et-empty-state" v-else>
          <p>Select an event key on the left, or add a new one.</p>
        </div>
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

const tickets     = ref({})   // { [key]: { member: [], nonMember: [] } }
const loading     = ref(true)
const loadError   = ref('')
const saving      = ref(false)
const saveError   = ref('')
const saveSuccess = ref(false)
const selectedKey = ref('')
const newKeyInput = ref('')

const eventKeys  = computed(() => Object.keys(tickets.value))
const currentSet = computed(() => tickets.value[selectedKey.value] ?? { member: [], nonMember: [] })

onMounted(async () => {
  try {
    const res = await useAdminFetch(`${base}/admin/get-event-tickets.php`)
    tickets.value = res?.tickets ?? {}
    if (eventKeys.value.length) selectedKey.value = eventKeys.value[0]
  } catch (e) {
    loadError.value = e?.data?.error ?? 'Failed to load event tickets.'
  } finally {
    loading.value = false
  }
})

function addKey() {
  const k = newKeyInput.value.trim().toLowerCase().replace(/[^a-z0-9_\-]/g, '')
  if (!k || tickets.value[k]) return
  tickets.value[k] = { member: [], nonMember: [] }
  selectedKey.value = k
  newKeyInput.value = ''
}

function removeKey(k) {
  delete tickets.value[k]
  selectedKey.value = eventKeys.value[0] ?? ''
}

async function saveAll() {
  saveError.value   = ''
  saveSuccess.value = false
  saving.value      = true
  try {
    await useAdminFetch(`${base}/admin/save-event-tickets.php`, {
      method: 'POST',
      body:   { tickets: tickets.value },
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
.page-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px; }
.section-desc { color: #666; font-size: 14px; margin-bottom: 20px; }
.et-layout { display: flex; gap: 24px; align-items: flex-start; }
.et-sidebar { width: 200px; flex-shrink: 0; border: 1px solid #e0e0e0; border-radius: 8px; overflow: hidden; }
.et-key-item { padding: 10px 14px; font-size: 14px; cursor: pointer; border-bottom: 1px solid #ececec; font-family: monospace; }
.et-key-item:last-of-type { border-bottom: none; }
.et-key-item:hover { background: #f5f5f5; }
.et-key-item.active { background: #fdecea; font-weight: 600; color: #c41e3a; }
.et-add-key { padding: 10px; display: flex; gap: 6px; border-top: 1px solid #e0e0e0; background: #fafafa; }
.et-add-key .tc-input { flex: 1; }
.et-main { flex: 1; min-width: 0; }
.et-section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 16px; }
.et-section-header h3 { font-family: monospace; font-size: 18px; margin: 0; }
.et-table-title { font-size: 13px; font-weight: 600; text-transform: uppercase; letter-spacing: 0.05em; color: #888; margin: 0 0 8px; }
.et-empty-state { color: #999; font-size: 14px; padding: 40px 0; }
.btn-sm { font-size: 12px; padding: 5px 12px; margin-top: 8px; }
.tc-table { width: 100%; border-collapse: collapse; font-size: 14px; }
.tc-table th { text-align: left; padding: 8px 10px; background: #f5f5f5; border-bottom: 2px solid #e0e0e0; white-space: nowrap; }
.tc-table td { padding: 6px 8px; border-bottom: 1px solid #ececec; vertical-align: middle; }
.th-note { font-weight: 400; font-size: 11px; color: #999; }
.tc-input { width: 100%; padding: 5px 8px; border: 1px solid #ddd; border-radius: 4px; font-size: 13px; box-sizing: border-box; }
.tc-input:focus { outline: none; border-color: #c41e3a; }
.tc-id { min-width: 120px; font-family: monospace; }
.tc-price { max-width: 80px; }
.tc-currency { max-width: 70px; }
.empty-msg { text-align: center; color: #999; padding: 16px; }
.action-btn--danger { background: none; border: none; color: #c41e3a; cursor: pointer; font-size: 16px; padding: 2px 6px; border-radius: 4px; }
.action-btn--danger:hover { background: #fdecea; }
.form-footer { margin-top: 24px; display: flex; justify-content: flex-end; }
.alert-error   { background: #fdecea; color: #c00; padding: 10px 14px; border-radius: 6px; margin: 12px 0; font-size: 14px; }
.alert-success { background: #e6f4ea; color: #1a7a3a; padding: 10px 14px; border-radius: 6px; margin: 12px 0; font-size: 14px; }
.loading-msg { color: #888; padding: 20px 0; }
.spinner { display: inline-block; width: 12px; height: 12px; border: 2px solid #fff; border-top-color: transparent; border-radius: 50%; animation: spin 0.6s linear infinite; margin-right: 6px; vertical-align: middle; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>
