# Event Tickets Refactor Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the global `ticket-categories.json` system with a per-event-key `event-tickets.json` map, where each key (e.g. `utsava_2026`) owns a complete set of member + nonMember ticket rows (eligibility embedded in member rows).

**Architecture:** A single `php/data/event-tickets.json` file maps event keys → `{ member, nonMember }`. `utsava-tickets.php` reads `ticketKey` from the utsava event in `events.json` and looks up that key. The admin manages this map via a new Event Tickets page. The event editor gets a plain `ticketKey` text field replacing the old checkbox picker.

**Tech Stack:** PHP 8, Nuxt 3 / Vue 3 Composition API, JSON file storage

**Spec:** Conversation design — no separate spec file.

## Global Constraints

- PHP 8, no TypeScript in PHP files
- Every PHP admin endpoint: `require_once __DIR__ . '/../_cors.php'` as very first include, then `require_once __DIR__ . '/_auth.php'`
- GET endpoints: set `$corsMethod = 'GET';` BEFORE `require_once _cors.php`
- All admin Vue pages: use `useAdminFetch` (never bare `$fetch`) for admin API calls
- All admin Vue pages: `definePageMeta({ layout: 'admin', middleware: 'admin-auth' })`
- `_cors.php` already sets `Content-Type: application/json` — do not add redundant header calls
- Core event slugs: `utsava`, `ugadi`, `food-festival`

---

### Task 1: Create `event-tickets.json` and delete legacy files

**Files:**
- Create: `php/data/event-tickets.json`
- Delete: `php/data/ticket-categories.json`
- Delete: `php/data/utsava-tickets.json`

**Interfaces:**
- Produces: `php/data/event-tickets.json` with shape:
  ```json
  {
    "utsava_2026": {
      "member": [
        { "id": "adult_member", "label": "Adult (Member)", "price": 15, "currency": "EUR", "memberPlanEligibility": ["single_adult", "family"] },
        { "id": "child_member", "label": "Child (Member)", "price": 5, "currency": "EUR", "memberPlanEligibility": ["family"] }
      ],
      "nonMember": [
        { "id": "early_bird", "label": "Early Bird", "price": 18, "currency": "EUR" },
        { "id": "adult", "label": "Adult", "price": 22, "currency": "EUR" },
        { "id": "student", "label": "Student", "price": 12, "currency": "EUR" },
        { "id": "family", "label": "Family (2 adults + 2 children)", "price": 55, "currency": "EUR" }
      ]
    }
  }
  ```

- [ ] **Step 1: Create `php/data/event-tickets.json`** with the seed data above (migrate values from `utsava-tickets.json`). Member rows get `memberPlanEligibility` from the old `memberEligibility` map: `single_adult` → `adult_member`, `family` → `adult_member` + `child_member`. nonMember rows are flat (no eligibility field).

- [ ] **Step 2: Delete `php/data/ticket-categories.json`**

- [ ] **Step 3: Delete `php/data/utsava-tickets.json`**

- [ ] **Step 4: Commit**
  ```bash
  git add php/data/event-tickets.json
  git rm php/data/ticket-categories.json php/data/utsava-tickets.json
  git commit -m "feat: introduce event-tickets.json, remove legacy ticket data files"
  ```

---

### Task 2: New PHP admin endpoints for event-tickets

**Files:**
- Create: `php/admin/get-event-tickets.php`
- Create: `php/admin/save-event-tickets.php`
- Delete: `php/admin/get-ticket-categories.php`
- Delete: `php/admin/save-ticket-categories.php`

**Interfaces:**
- `get-event-tickets.php` GET → `{ tickets: { [key]: { member: [...], nonMember: [...] } } }`
- `save-event-tickets.php` POST body: `{ tickets: { [key]: { member: [...], nonMember: [...] } } }` → `{ success: true }`

- [ ] **Step 1: Create `php/admin/get-event-tickets.php`**
  ```php
  <?php
  $corsMethod = 'GET';
  require_once __DIR__ . '/../_cors.php';
  require_once __DIR__ . '/_auth.php';
  
  $file = __DIR__ . '/../data/event-tickets.json';
  $tickets = file_exists($file) ? (json_decode(file_get_contents($file), true) ?? []) : [];
  echo json_encode(['tickets' => $tickets]);
  ```

- [ ] **Step 2: Create `php/admin/save-event-tickets.php`**
  ```php
  <?php
  require_once __DIR__ . '/../_cors.php';
  require_once __DIR__ . '/_auth.php';
  
  $body   = json_decode(file_get_contents('php://input'), true) ?? [];
  $input  = $body['tickets'] ?? null;
  
  if (!is_array($input)) {
      http_response_code(422);
      echo json_encode(['error' => 'tickets object required']);
      exit;
  }
  
  $clean = [];
  foreach ($input as $key => $set) {
      $key = preg_replace('/[^a-z0-9_\-]/', '', strtolower(trim((string)$key)));
      if (!$key) continue;
  
      $member    = [];
      $nonMember = [];
  
      foreach ((array)($set['member'] ?? []) as $row) {
          $id    = preg_replace('/[^a-z0-9_\-]/', '', strtolower(trim($row['id'] ?? '')));
          $label = trim($row['label'] ?? '');
          if (!$id || !$label) continue;
          $eligibility = array_values(array_filter(array_map('trim', (array)($row['memberPlanEligibility'] ?? [])), fn($v) => $v !== ''));
          $member[] = ['id' => $id, 'label' => $label, 'price' => max(0, (float)($row['price'] ?? 0)), 'currency' => substr(trim($row['currency'] ?? 'EUR'), 0, 10), 'memberPlanEligibility' => $eligibility];
      }
  
      foreach ((array)($set['nonMember'] ?? []) as $row) {
          $id    = preg_replace('/[^a-z0-9_\-]/', '', strtolower(trim($row['id'] ?? '')));
          $label = trim($row['label'] ?? '');
          if (!$id || !$label) continue;
          $nonMember[] = ['id' => $id, 'label' => $label, 'price' => max(0, (float)($row['price'] ?? 0)), 'currency' => substr(trim($row['currency'] ?? 'EUR'), 0, 10)];
      }
  
      $clean[$key] = ['member' => $member, 'nonMember' => $nonMember];
  }
  
  $file = __DIR__ . '/../data/event-tickets.json';
  file_put_contents($file, json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE), LOCK_EX);
  echo json_encode(['success' => true]);
  ```

- [ ] **Step 3: Delete old endpoints**
  ```bash
  git rm php/admin/get-ticket-categories.php php/admin/save-ticket-categories.php
  ```

- [ ] **Step 4: Commit**
  ```bash
  git add php/admin/get-event-tickets.php php/admin/save-event-tickets.php
  git commit -m "feat: add get/save-event-tickets.php admin endpoints, remove ticket-categories endpoints"
  ```

---

### Task 3: New admin Vue page `event-tickets.vue`

**Files:**
- Create: `app/pages/admin/event-tickets.vue`
- Delete: `app/pages/admin/ticket-categories.vue`
- Modify: `app/layouts/admin.vue` — swap nav link

**Interfaces:**
- Calls `GET .../admin/get-event-tickets.php` → `{ tickets: { [key]: { member, nonMember } } }`
- Calls `POST .../admin/save-event-tickets.php` with `{ tickets: { ... } }`
- Member row shape on screen: `{ id, label, price, currency, memberPlanEligibility }` (eligibility as comma-separated string in the input, split/join on read/write)
- nonMember row shape: `{ id, label, price, currency }`

**UI layout:**
- Left panel: list of event keys (e.g. `utsava_2026`). "+ Add Event Key" button. Clicking a key selects it.
- Right panel: two tables — Member Tickets and Non-Member Tickets — each with add/delete row. `memberPlanEligibility` column only on member table (comma-separated plan IDs input).
- "Save All" button at bottom saves the full map.

- [ ] **Step 1: Create `app/pages/admin/event-tickets.vue`**

  Full component:
  ```vue
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
  ```

- [ ] **Step 2: Delete `app/pages/admin/ticket-categories.vue`**
  ```bash
  git rm app/pages/admin/ticket-categories.vue
  ```

- [ ] **Step 3: Update nav link in `app/layouts/admin.vue`** — change the Ticket Categories link to Event Tickets:
  ```html
  <NuxtLink to="/admin/event-tickets" class="nav-item" active-class="active">
    <span class="nav-icon">🎟</span> Event Tickets
  </NuxtLink>
  ```

- [ ] **Step 4: Commit**
  ```bash
  git add app/pages/admin/event-tickets.vue app/layouts/admin.vue
  git commit -m "feat: event-tickets admin page, remove ticket-categories page, update nav"
  ```

---

### Task 4: Update event editor — replace checkbox picker with `ticketKey` field

**Files:**
- Modify: `app/pages/admin/events/[slug].vue`

**What to change** (all inside the existing file — do not rewrite the whole component):

1. Remove from `form` reactive object: `ticketCategoryIds: []`  
   Add instead: `ticketKey: ''`

2. Remove refs: `allCategories`, `categoriesLoadError`  
   Remove computed: `memberCategories`, `nonMemberCategories`  
   Remove the `useAdminFetch` call for ticket categories in `onMounted`

3. In the template, replace the entire `<div v-if="isCoreEvent" class="form-section">` Ticket Categories block with:
   ```html
   <div v-if="isCoreEvent" class="form-section">
     <h3 class="section-title">Ticket Key</h3>
     <div class="form-group">
       <label>Ticket Key</label>
       <input v-model="form.ticketKey" type="text" placeholder="utsava_2026" class="tc-id-input" />
       <span class="help-text">Key from the <NuxtLink to="/admin/event-tickets">Event Tickets</NuxtLink> page. Leave blank for no ticket sales.</span>
     </div>
   </div>
   ```

4. Remove CSS: `.tc-group`, `.tc-group-label`, `.tc-check-label`, `.tc-id-badge`  
   Add CSS: `.tc-id-input { font-family: monospace; max-width: 240px; padding: 7px 10px; border: 1px solid #ddd; border-radius: 6px; font-size: 14px; }`

- [ ] **Step 1: Update `form` reactive object** — remove `ticketCategoryIds: []`, add `ticketKey: ''`

- [ ] **Step 2: Remove dead refs/computed/fetch** — `allCategories`, `categoriesLoadError`, `memberCategories`, `nonMemberCategories`, the `useAdminFetch` categories fetch in `onMounted`

- [ ] **Step 3: Replace Ticket Categories template section** with the `ticketKey` text field shown above

- [ ] **Step 4: Replace old CSS, add new CSS**

- [ ] **Step 5: Commit**
  ```bash
  git add "app/pages/admin/events/[slug].vue"
  git commit -m "feat: replace ticket-category checkbox picker with ticketKey text field in event editor"
  ```

---

### Task 5: Rewrite `utsava-tickets.php`

**Files:**
- Modify: `php/utsava-tickets.php`

**Logic:**
1. Read `events.json`, find utsava event, get `ticketKey` (string field, was `ticketCategoryIds` array before)
2. If `ticketKey` is empty/missing → return `{ member: [], nonMember: [], memberEligibility: {} }`
3. Read `event-tickets.json`, look up `tickets[$ticketKey]`
4. If key not found → return `{ member: [], nonMember: [], memberEligibility: {} }`
5. Build `memberEligibility` map from member rows' `memberPlanEligibility` arrays
6. Return `{ member: [...], nonMember: [...], memberEligibility: {...} }`

Member rows in response: strip `memberPlanEligibility` (it's internal), return only `{ id, label, price, currency }`.

- [ ] **Step 1: Rewrite `php/utsava-tickets.php`**
  ```php
  <?php
  $corsMethod = 'GET';
  require_once __DIR__ . '/_cors.php';

  // Find utsava event's ticketKey
  $eventsFile  = __DIR__ . '/data/events.json';
  $events      = file_exists($eventsFile) ? (json_decode(file_get_contents($eventsFile), true) ?? []) : [];
  $ticketKey   = '';
  foreach ($events as $e) {
      if (($e['slug'] ?? '') === 'utsava') { $ticketKey = $e['ticketKey'] ?? ''; break; }
  }

  if (!$ticketKey) {
      echo json_encode(['member' => [], 'nonMember' => [], 'memberEligibility' => new stdClass()]);
      exit;
  }

  // Look up the ticket set by key
  $etFile  = __DIR__ . '/data/event-tickets.json';
  $allSets = file_exists($etFile) ? (json_decode(file_get_contents($etFile), true) ?? []) : [];
  $set     = $allSets[$ticketKey] ?? null;

  if (!$set) {
      echo json_encode(['member' => [], 'nonMember' => [], 'memberEligibility' => new stdClass()]);
      exit;
  }

  // Build response — strip memberPlanEligibility from output rows, build eligibility map
  $member            = [];
  $memberEligibility = [];
  foreach ($set['member'] ?? [] as $row) {
      $member[] = ['id' => $row['id'], 'label' => $row['label'], 'price' => $row['price'], 'currency' => $row['currency']];
      foreach ($row['memberPlanEligibility'] ?? [] as $plan) {
          $memberEligibility[$plan][] = $row['id'];
      }
  }

  $nonMember = [];
  foreach ($set['nonMember'] ?? [] as $row) {
      $nonMember[] = ['id' => $row['id'], 'label' => $row['label'], 'price' => $row['price'], 'currency' => $row['currency']];
  }

  echo json_encode([
      'member'            => $member,
      'nonMember'         => $nonMember,
      'memberEligibility' => $memberEligibility ?: new stdClass(),
  ]);
  ```

- [ ] **Step 2: Commit**
  ```bash
  git add php/utsava-tickets.php
  git commit -m "feat: utsava-tickets.php reads ticketKey from event, looks up event-tickets.json"
  ```
