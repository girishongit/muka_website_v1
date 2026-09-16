# Ticket Categories Admin Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Add a ticket categories management UI to the admin panel, and allow default events (utsava, ugadi, food-festival) to reference those categories.

**Architecture:** Ticket categories live in `php/data/ticket-categories.json`. Two new admin PHP endpoints (GET list, POST save-all). A new Vue admin page `/admin/ticket-categories` lets admins CRUD categories. The default-event edit form (`/admin/events/[slug].vue`) gains a multi-select "Ticket Categories" section visible only for core events. `php/utsava-tickets.php` is updated to read from the JSON file filtered by IDs on the utsava event entry.

**Tech Stack:** Nuxt 3 / Vue 3 Composition API, PHP 8, JSON file storage, existing admin session auth (`php/admin/_auth.php`), existing admin layout.

**Spec:** No separate spec file — requirements are captured in this plan.

## Global Constraints

- Auth: every `php/admin/*.php` endpoint must `require_once __DIR__ . '/_auth.php'` before any logic.
- CORS: every PHP endpoint must `require_once __DIR__ . '/../_cors.php'` (admin endpoints) or `require_once __DIR__ . '/_cors.php'` (public endpoints) as the very first require.
- JSON file paths: `php/data/ticket-categories.json`, `php/data/events.json` — read/write with `file_get_contents` / `file_put_contents` + `json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)`.
- Admin Vue pages: use `definePageMeta({ layout: 'admin' })`, fetch admin endpoints with `useRuntimeConfig().public.apiBaseUrl` + path, follow existing pattern in `app/pages/admin/events/index.vue`.
- Ticket category shape: `{ id: string, label: string, price: number, currency: string, type: 'member'|'nonMember', memberPlanEligibility: string[] }`. `memberPlanEligibility` is only meaningful when `type === 'member'`; for `nonMember` it is an empty array.
- Event `ticketCategoryIds` field: string array on each event object in `events.json`, only set/used for core events (slugs: `utsava`, `ugadi`, `food-festival`). Non-core events ignore this field.
- `php/utsava-tickets.php`: must return `{ member: [...], nonMember: [...], memberEligibility: {...} }` built dynamically from `ticket-categories.json` filtered by the utsava event's `ticketCategoryIds`. Fall back to returning the full `utsava-tickets.json` contents if the utsava event has no `ticketCategoryIds` set (backward compat).
- No new npm packages. No TypeScript — plain JS only.
- Do not modify `nuxt.config.ts` or any other critical config file.

---

### Task 1: Data layer — ticket-categories.json + two PHP admin endpoints

**Files:**
- Create: `php/data/ticket-categories.json`
- Create: `php/admin/get-ticket-categories.php`
- Create: `php/admin/save-ticket-categories.php`

**Interfaces:**
- Produces: `GET /php/admin/get-ticket-categories.php` → `{ categories: TicketCategory[] }`
- Produces: `POST /php/admin/save-ticket-categories.php` body `{ categories: TicketCategory[] }` → `{ success: true }` or `{ error: string }`

- [ ] **Step 1: Create `php/data/ticket-categories.json` with seed data**

Seed the file with the same categories currently in `php/data/utsava-tickets.json`, converted to the unified shape:

```json
[
  { "id": "adult_member",  "label": "Adult (Member)",              "price": 15, "currency": "EUR", "type": "member",    "memberPlanEligibility": ["single_adult", "family"] },
  { "id": "child_member",  "label": "Child (Member)",              "price": 5,  "currency": "EUR", "type": "member",    "memberPlanEligibility": ["family"] },
  { "id": "early_bird",    "label": "Early Bird",                  "price": 18, "currency": "EUR", "type": "nonMember", "memberPlanEligibility": [] },
  { "id": "adult",         "label": "Adult",                       "price": 22, "currency": "EUR", "type": "nonMember", "memberPlanEligibility": [] },
  { "id": "student",       "label": "Student",                     "price": 12, "currency": "EUR", "type": "nonMember", "memberPlanEligibility": [] },
  { "id": "family",        "label": "Family (2 adults + 2 children)", "price": 55, "currency": "EUR", "type": "nonMember", "memberPlanEligibility": [] }
]
```

- [ ] **Step 2: Create `php/admin/get-ticket-categories.php`**

```php
<?php
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';

$file = __DIR__ . '/../data/ticket-categories.json';
$categories = file_exists($file) ? (json_decode(file_get_contents($file), true) ?? []) : [];
echo json_encode(['categories' => $categories]);
```

- [ ] **Step 3: Create `php/admin/save-ticket-categories.php`**

```php
<?php
require_once __DIR__ . '/../_cors.php';
require_once __DIR__ . '/_auth.php';

$body = json_decode(file_get_contents('php://input'), true) ?? [];
$categories = $body['categories'] ?? null;

if (!is_array($categories)) {
    http_response_code(422);
    echo json_encode(['error' => 'categories array required']);
    exit;
}

// Validate and sanitise each category
$clean = [];
foreach ($categories as $cat) {
    $id    = preg_replace('/[^a-z0-9_\-]/', '', strtolower(trim($cat['id'] ?? '')));
    $label = trim($cat['label'] ?? '');
    $price = max(0, (float)($cat['price'] ?? 0));
    $currency = substr(trim($cat['currency'] ?? 'EUR'), 0, 10);
    $type  = in_array($cat['type'] ?? '', ['member', 'nonMember'], true) ? $cat['type'] : 'nonMember';
    $eligibility = array_values(array_filter(array_map('trim', (array)($cat['memberPlanEligibility'] ?? [])), fn($v) => $v !== ''));

    if (!$id || !$label) continue;

    $clean[] = [
        'id'                   => $id,
        'label'                => $label,
        'price'                => $price,
        'currency'             => $currency,
        'type'                 => $type,
        'memberPlanEligibility'=> $eligibility,
    ];
}

$file = __DIR__ . '/../data/ticket-categories.json';
file_put_contents($file, json_encode($clean, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo json_encode(['success' => true]);
```

- [ ] **Step 4: Commit**

```bash
git add php/data/ticket-categories.json php/admin/get-ticket-categories.php php/admin/save-ticket-categories.php
git commit -m "feat: ticket-categories data file and admin PHP endpoints"
```

---

### Task 2: Admin UI — /admin/ticket-categories page

**Files:**
- Create: `app/pages/admin/ticket-categories.vue`

**Interfaces:**
- Consumes: `GET /php/admin/get-ticket-categories.php` → `{ categories: TicketCategory[] }`
- Consumes: `POST /php/admin/save-ticket-categories.php` body `{ categories: TicketCategory[] }`

The page renders a table of all categories. Each row is editable inline (all fields). The user can add a new row at the bottom and delete rows. One "Save All" button persists the full list.

- [ ] **Step 1: Create `app/pages/admin/ticket-categories.vue`**

Full component:

```vue
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
            <tr v-for="(cat, i) in categories" :key="i" :class="{ 'row-member': cat.type === 'member' }">
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
definePageMeta({ layout: 'admin' })

const { public: { apiBaseUrl } } = useRuntimeConfig()
const base = apiBaseUrl.replace(/\/$/, '')

const categories = ref([])
const loading    = ref(true)
const loadError  = ref('')
const saving     = ref(false)
const saveError  = ref('')
const saveSuccess = ref(false)

onMounted(async () => {
  try {
    const res = await $fetch(`${base}/admin/get-ticket-categories.php`)
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
  saveError.value = ''
  saveSuccess.value = false
  saving.value = true
  try {
    await $fetch(`${base}/admin/save-ticket-categories.php`, {
      method: 'POST',
      body: { categories: categories.value },
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
.alert-error  { background: #fdecea; color: #c00; padding: 10px 14px; border-radius: 6px; margin-bottom: 12px; font-size: 14px; }
.alert-success { background: #e6f4ea; color: #1a7a3a; padding: 10px 14px; border-radius: 6px; margin-bottom: 12px; font-size: 14px; }
.loading-msg { color: #888; padding: 20px 0; }
.spinner { display: inline-block; width: 12px; height: 12px; border: 2px solid #fff; border-top-color: transparent; border-radius: 50%; animation: spin 0.6s linear infinite; margin-right: 6px; vertical-align: middle; }
@keyframes spin { to { transform: rotate(360deg); } }
</style>
```

- [ ] **Step 2: Commit**

```bash
git add app/pages/admin/ticket-categories.vue
git commit -m "feat: admin ticket categories management page"
```

---

### Task 3: Add nav link for Ticket Categories in admin layout + default-event editor section

**Files:**
- Modify: `app/layouts/admin.vue` — add "Ticket Categories" nav link
- Modify: `app/pages/admin/events/[slug].vue` — add ticket category multi-select section for core events

**Interfaces:**
- Consumes (admin layout): just add a `<NuxtLink to="/admin/ticket-categories">` entry in the existing nav list.
- Consumes (event editor): `GET /php/admin/get-ticket-categories.php` → used to populate the category picker; `ticketCategoryIds: string[]` added to the form model and persisted via the existing save-event endpoint.

For the event editor: read the admin layout nav first to understand the pattern, then add a "Ticket Categories" section inside the `<form>` — **only rendered when `isCoreEvent` is true** — showing a list of checkboxes, one per defined category, grouped by type (Member / Non-member). The section sits after the Content section and before Display Settings. `form.ticketCategoryIds` is a string array, initialized from the loaded event data (default `[]`). It is included in the save payload automatically because it's on `form`.

- [ ] **Step 1: Read `app/layouts/admin.vue` to find the nav link list**

Read the file fully before editing.

- [ ] **Step 2: Add "Ticket Categories" nav link in admin layout**

Find the nav list in `app/layouts/admin.vue` and add a link. Pattern follows existing entries like `/admin/events` and `/admin/users`.

- [ ] **Step 3: Read `app/pages/admin/events/[slug].vue` script section**

Read the full script setup section to understand: how `form` is defined, how the event is loaded, what `isCoreEvent` is, and where the save payload is constructed.

- [ ] **Step 4: Add `ticketCategoryIds` to form and fetch categories**

In the script setup:
- Add `ticketCategoryIds: []` to the initial form object.
- Add `allCategories = ref([])` and fetch from `get-ticket-categories.php` on mount (non-blocking, ignore errors gracefully — if it fails, the section shows an error message but the rest of the form still works).
- When the event data is loaded and merged into `form`, also populate `form.ticketCategoryIds` from the loaded data (default `[]`).

- [ ] **Step 5: Add Ticket Categories section in template**

Inside the form grid, after the Content section and before Display Settings, add:

```html
<!-- Ticket Categories (core events only) -->
<div v-if="isCoreEvent" class="form-section">
  <h3 class="section-title">Ticket Categories</h3>
  <p class="section-desc">Select which ticket categories apply to this event. Only categories defined in <a href="/admin/ticket-categories" target="_blank">Ticket Categories</a> appear here.</p>
  <div v-if="categoriesLoadError" class="alert alert-error">{{ categoriesLoadError }}</div>
  <div v-else-if="!allCategories.length" class="info-box">
    <span class="info-icon">ℹ️</span> No ticket categories defined yet. <a href="/admin/ticket-categories" target="_blank">Create some first.</a>
  </div>
  <template v-else>
    <div class="tc-group" v-if="memberCategories.length">
      <p class="tc-group-label">Member tickets</p>
      <label v-for="cat in memberCategories" :key="cat.id" class="tc-check-label">
        <input type="checkbox" :value="cat.id" v-model="form.ticketCategoryIds" />
        <span>{{ cat.label }} — €{{ cat.price }} <span class="tc-id-badge">{{ cat.id }}</span></span>
      </label>
    </div>
    <div class="tc-group" v-if="nonMemberCategories.length">
      <p class="tc-group-label">Non-member tickets</p>
      <label v-for="cat in nonMemberCategories" :key="cat.id" class="tc-check-label">
        <input type="checkbox" :value="cat.id" v-model="form.ticketCategoryIds" />
        <span>{{ cat.label }} — €{{ cat.price }} <span class="tc-id-badge">{{ cat.id }}</span></span>
      </label>
    </div>
  </template>
</div>
```

Add computed helpers (in script setup):
```js
const memberCategories    = computed(() => allCategories.value.filter(c => c.type === 'member'))
const nonMemberCategories = computed(() => allCategories.value.filter(c => c.type === 'nonMember'))
```

Add these styles to the scoped `<style>` block:
```css
.tc-group { margin-bottom: 16px; }
.tc-group-label { font-size: 12px; font-weight: 600; text-transform: uppercase; color: #888; margin: 0 0 8px; letter-spacing: 0.05em; }
.tc-check-label { display: flex; align-items: center; gap: 8px; padding: 6px 0; font-size: 14px; cursor: pointer; }
.tc-check-label input[type="checkbox"] { width: 16px; height: 16px; flex-shrink: 0; }
.tc-id-badge { font-family: monospace; font-size: 11px; background: #f0f0f0; padding: 1px 5px; border-radius: 3px; color: #666; }
.section-desc { color: var(--text-light, #666); font-size: 14px; margin: -8px 0 16px; }
```

- [ ] **Step 6: Commit**

```bash
git add app/layouts/admin.vue app/pages/admin/events/[slug].vue
git commit -m "feat: ticket categories nav link and core-event category picker"
```

---

### Task 4: Update php/utsava-tickets.php to serve from ticket-categories.json

**Files:**
- Modify: `php/utsava-tickets.php`

**Interfaces:**
- Still returns `{ member: [...], nonMember: [...], memberEligibility: {...} }`
- Logic change: read `ticket-categories.json`; read utsava entry from `events.json`; filter by `ticketCategoryIds` if set; build the response shape. Fall back to reading `utsava-tickets.json` directly if utsava has no `ticketCategoryIds`.

- [ ] **Step 1: Read the current `php/utsava-tickets.php`**

- [ ] **Step 2: Rewrite `php/utsava-tickets.php`**

```php
<?php
/**
 * GET /php/utsava-tickets.php
 * Returns { member: [...], nonMember: [...], memberEligibility: {...} }
 * built from ticket-categories.json filtered by the utsava event's ticketCategoryIds.
 * Falls back to utsava-tickets.json if no ticketCategoryIds are configured.
 */
require_once __DIR__ . '/_cors.php';

// Load utsava event to get configured category IDs
$eventsFile = __DIR__ . '/data/events.json';
$events     = file_exists($eventsFile) ? (json_decode(file_get_contents($eventsFile), true) ?? []) : [];
$utsavaEvent = null;
foreach ($events as $e) {
    if (($e['slug'] ?? '') === 'utsava') { $utsavaEvent = $e; break; }
}

$configuredIds = $utsavaEvent['ticketCategoryIds'] ?? [];

// Fallback: no IDs configured — serve legacy utsava-tickets.json
if (empty($configuredIds)) {
    $legacyFile = __DIR__ . '/data/utsava-tickets.json';
    if (file_exists($legacyFile)) {
        header('Content-Type: application/json');
        echo file_get_contents($legacyFile);
    } else {
        echo json_encode(['member' => [], 'nonMember' => [], 'memberEligibility' => new stdClass()]);
    }
    exit;
}

// Load all categories and filter
$catFile    = __DIR__ . '/data/ticket-categories.json';
$allCats    = file_exists($catFile) ? (json_decode(file_get_contents($catFile), true) ?? []) : [];
$idSet      = array_flip($configuredIds);
$filtered   = array_filter($allCats, fn($c) => isset($idSet[$c['id']]));

$member    = [];
$nonMember = [];
$memberEligibility = [];

foreach ($filtered as $cat) {
    $entry = [
        'id'       => $cat['id'],
        'label'    => $cat['label'],
        'price'    => $cat['price'],
        'currency' => $cat['currency'],
    ];
    if ($cat['type'] === 'member') {
        $member[] = $entry;
        // Build memberEligibility map: plan -> [categoryIds]
        foreach ($cat['memberPlanEligibility'] ?? [] as $plan) {
            $memberEligibility[$plan][] = $cat['id'];
        }
    } else {
        $nonMember[] = $entry;
    }
}

echo json_encode([
    'member'            => $member,
    'nonMember'         => $nonMember,
    'memberEligibility' => $memberEligibility ?: new stdClass(),
]);
```

- [ ] **Step 3: Commit**

```bash
git add php/utsava-tickets.php
git commit -m "feat: utsava-tickets.php serves from ticket-categories.json filtered by event config"
```
