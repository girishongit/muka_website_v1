# Utsava Ticket Registration Form — Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Replace the basic Utsava registration form with a ticket-selection form that fetches configurable ticket categories from the backend, handles member/non-member pricing, and submits a full booking payload.

**Architecture:** Three focused changes — (1) a new read-only PHP tickets endpoint backed by a JSON data file, (2) a replacement PHP registration handler with the new payload/schema, (3) the updated `utsava.vue` dialog with membership toggle, ticket quantity steppers, and live total. All changes are self-contained; no new Vue components, no new composables.

**Tech Stack:** PHP 8, MySQL/MariaDB (JSON column), Vue 3 Composition API, Nuxt 3, NuxtTurnstile

**Spec:** `docs/superpowers/specs/2026-09-15-utsava-ticket-registration-design.md`

## Global Constraints

- PHP endpoints follow the pattern in `php/_cors.php` and `php/events.php` — set `$corsMethod` before including `_cors.php`, require `_cors.php` / `_turnstile.php` / `Database.php` at the top
- Read-only endpoints: set `$corsMethod = 'GET'` — no Turnstile, no DB write
- All monetary values in EUR; currency stored as string in DB
- `totalAmount` must be server-recalculated and validated (reject on mismatch)
- `membershipId` required when `isMember === true`; stored even when empty for non-members
- Vue reactive state shape must match spec exactly — `form`, `ticketConfig`, `quantities`, `ticketsLoading`, `ticketsError`
- No new files outside the four listed in the spec; no new composables or components

---

## Task 1: Ticket data file + read endpoint

**Files:**
- Create: `php/data/utsava-tickets.json`
- Create: `php/utsava-tickets.php`

**Interfaces:**
- Produces: `GET /php/utsava-tickets.php` → `{ member: TicketCategory[], nonMember: TicketCategory[] }` where `TicketCategory = { id: string, label: string, price: number, currency: string }`

- [ ] **Step 1: Create the data file**

`php/data/utsava-tickets.json`:
```json
{
  "member": [
    { "id": "adult_member", "label": "Adult (Member)", "price": 15, "currency": "EUR" },
    { "id": "child_member", "label": "Child (Member)", "price": 5,  "currency": "EUR" }
  ],
  "nonMember": [
    { "id": "early_bird", "label": "Early Bird",                     "price": 18, "currency": "EUR" },
    { "id": "adult",      "label": "Adult",                          "price": 22, "currency": "EUR" },
    { "id": "student",    "label": "Student",                        "price": 12, "currency": "EUR" },
    { "id": "family",     "label": "Family (2 adults + 2 children)", "price": 55, "currency": "EUR" }
  ]
}
```

- [ ] **Step 2: Create `php/utsava-tickets.php`**

```php
<?php
/**
 * GET /php/utsava-tickets.php
 * Returns ticket categories split by member / non-member status.
 * Data lives in php/data/utsava-tickets.json.
 */

$corsMethod = 'GET';
require __DIR__ . '/_cors.php';

$file = __DIR__ . '/data/utsava-tickets.json';

if (!file_exists($file)) {
    http_response_code(500);
    echo json_encode(['error' => 'Ticket data unavailable']);
    exit;
}

$data = json_decode(file_get_contents($file), true);

if (!isset($data['member'], $data['nonMember'])) {
    http_response_code(500);
    echo json_encode(['error' => 'Ticket data malformed']);
    exit;
}

echo json_encode($data);
```

- [ ] **Step 3: Verify the endpoint manually**

Open `http://localhost:3000/php/utsava-tickets.php` (or curl it) and confirm it returns the JSON object with `member` and `nonMember` arrays.

Expected response:
```json
{
  "member": [{"id":"adult_member","label":"Adult (Member)","price":15,"currency":"EUR"},{"id":"child_member","label":"Child (Member)","price":5,"currency":"EUR"}],
  "nonMember": [{"id":"early_bird","label":"Early Bird","price":18,"currency":"EUR"},...]
}
```

- [ ] **Step 4: Commit**

```bash
git add php/data/utsava-tickets.json php/utsava-tickets.php
git commit -m "feat: add utsava tickets endpoint backed by JSON data file"
```

---

## Task 2: Replace `utsava-register.php`

**Files:**
- Modify: `php/utsava-register.php` (full replacement)

**Interfaces:**
- Consumes: POST body `{ firstName, lastName, email, phone, isMember, membershipId, tickets: [{categoryId, label, quantity, priceEach}], totalAmount, currency, turnstileToken }`
- Produces: `{ success: true }` on 200, `{ error: string }` on 400/422/500

- [ ] **Step 1: Create the new DB table**

Run this SQL against the production/dev database. The old `utsava_registrations` table can stay (it stores past data); we add a new table for ticket-based registrations:

```sql
CREATE TABLE utsava_ticket_registrations (
  id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  first_name     VARCHAR(80)  NOT NULL,
  last_name      VARCHAR(80)  NOT NULL,
  email          VARCHAR(200) NOT NULL,
  phone          VARCHAR(30)  NOT NULL DEFAULT '',
  is_member      TINYINT(1)   NOT NULL DEFAULT 0,
  membership_id  VARCHAR(50)  NOT NULL DEFAULT '',
  tickets_json   JSON         NOT NULL,
  total_amount   DECIMAL(8,2) NOT NULL DEFAULT 0,
  currency       VARCHAR(10)  NOT NULL DEFAULT 'EUR',
  created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

- [ ] **Step 2: Replace `php/utsava-register.php` with the new handler**

```php
<?php
/**
 * POST /php/utsava-register.php
 * Accepts Utsava ticket registrations.
 *
 * Expected JSON body:
 *   {
 *     "firstName": string, "lastName": string,
 *     "email": string, "phone": string,
 *     "isMember": bool,
 *     "membershipId": string,
 *     "tickets": [{ "categoryId": string, "label": string, "quantity": int, "priceEach": float }],
 *     "totalAmount": float,
 *     "currency": string,
 *     "turnstileToken": string
 *   }
 *
 * ─── DB schema ───────────────────────────────────────────────────────────────
 * CREATE TABLE utsava_ticket_registrations (
 *   id             INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 *   first_name     VARCHAR(80)  NOT NULL,
 *   last_name      VARCHAR(80)  NOT NULL,
 *   email          VARCHAR(200) NOT NULL,
 *   phone          VARCHAR(30)  NOT NULL DEFAULT '',
 *   is_member      TINYINT(1)   NOT NULL DEFAULT 0,
 *   membership_id  VARCHAR(50)  NOT NULL DEFAULT '',
 *   tickets_json   JSON         NOT NULL,
 *   total_amount   DECIMAL(8,2) NOT NULL DEFAULT 0,
 *   currency       VARCHAR(10)  NOT NULL DEFAULT 'EUR',
 *   created_at     DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
 * ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
 * ─────────────────────────────────────────────────────────────────────────────
 */

require_once __DIR__ . '/_cors.php';
require_once __DIR__ . '/_turnstile.php';
require_once __DIR__ . '/Database.php';

$body = json_decode(file_get_contents('php://input'), true) ?? [];

// 1. Turnstile
if (!verifyTurnstile($body['turnstileToken'] ?? '')) {
    http_response_code(400);
    echo json_encode(['error' => 'Security check failed. Please refresh and try again.']);
    exit;
}

// 2. Required personal fields
$firstName = trim($body['firstName'] ?? '');
$lastName  = trim($body['lastName']  ?? '');
$email     = trim($body['email']     ?? '');
$phone     = trim($body['phone']     ?? '');

if (empty($firstName) || empty($lastName) || empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['error' => 'First name, last name, and a valid email are required.']);
    exit;
}

// 3. Membership
$isMember    = !empty($body['isMember']);
$membershipId = trim($body['membershipId'] ?? '');

if ($isMember && empty($membershipId)) {
    http_response_code(422);
    echo json_encode(['error' => 'Membership ID is required for members.']);
    exit;
}

// 4. Tickets: at least one with quantity >= 1
$rawTickets = $body['tickets'] ?? [];

if (!is_array($rawTickets) || count($rawTickets) === 0) {
    http_response_code(422);
    echo json_encode(['error' => 'At least one ticket must be selected.']);
    exit;
}

$tickets = [];
$serverTotal = 0.0;

foreach ($rawTickets as $t) {
    $qty   = max(0, (int)   ($t['quantity']   ?? 0));
    $price = max(0, (float) ($t['priceEach']  ?? 0));
    if ($qty < 1) continue;
    $tickets[] = [
        'categoryId' => substr(trim($t['categoryId'] ?? ''), 0, 60),
        'label'      => substr(trim($t['label']      ?? ''), 0, 120),
        'quantity'   => $qty,
        'priceEach'  => $price,
    ];
    $serverTotal += $qty * $price;
}

if (count($tickets) === 0) {
    http_response_code(422);
    echo json_encode(['error' => 'At least one ticket with quantity ≥ 1 must be selected.']);
    exit;
}

// 5. Validate total (allow ±0.01 for float rounding)
$clientTotal = (float) ($body['totalAmount'] ?? -1);
if (abs($serverTotal - $clientTotal) > 0.01) {
    http_response_code(422);
    echo json_encode(['error' => 'Ticket total mismatch. Please refresh and try again.']);
    exit;
}

$currency = substr(trim($body['currency'] ?? 'EUR'), 0, 10);

// 6. Insert
try {
    $db   = (new Database())->getConnection();
    $stmt = $db->prepare(
        'INSERT INTO utsava_ticket_registrations
           (first_name, last_name, email, phone, is_member, membership_id, tickets_json, total_amount, currency)
         VALUES
           (:first_name, :last_name, :email, :phone, :is_member, :membership_id, :tickets_json, :total_amount, :currency)'
    );
    $stmt->execute([
        ':first_name'    => $firstName,
        ':last_name'     => $lastName,
        ':email'         => $email,
        ':phone'         => $phone,
        ':is_member'     => $isMember ? 1 : 0,
        ':membership_id' => $membershipId,
        ':tickets_json'  => json_encode($tickets),
        ':total_amount'  => round($serverTotal, 2),
        ':currency'      => $currency,
    ]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Could not save your registration. Please try again.']);
}
```

- [ ] **Step 3: Verify with curl**

Test the happy path:
```bash
curl -s -X POST http://localhost/php/utsava-register.php \
  -H 'Content-Type: application/json' \
  -d '{
    "firstName":"Test","lastName":"User","email":"test@example.com","phone":"+49123456",
    "isMember":false,"membershipId":"",
    "tickets":[{"categoryId":"adult","label":"Adult","quantity":2,"priceEach":22}],
    "totalAmount":44,"currency":"EUR",
    "turnstileToken":"1x00000000000000000000AA"
  }'
```
Expected: `{"success":true}`

Test totalAmount mismatch:
```bash
curl -s -X POST http://localhost/php/utsava-register.php \
  -H 'Content-Type: application/json' \
  -d '{
    "firstName":"Test","lastName":"User","email":"test@example.com","phone":"+49123456",
    "isMember":false,"membershipId":"",
    "tickets":[{"categoryId":"adult","label":"Adult","quantity":2,"priceEach":22}],
    "totalAmount":99,"currency":"EUR",
    "turnstileToken":"1x00000000000000000000AA"
  }'
```
Expected: `{"error":"Ticket total mismatch. Please refresh and try again."}`

- [ ] **Step 4: Commit**

```bash
git add php/utsava-register.php
git commit -m "feat: replace utsava registration handler with ticket-based payload and new DB schema"
```

---

## Task 3: Update `utsava.vue` — script section

**Files:**
- Modify: `app/pages/events/utsava.vue` (script block only in this task)

**Interfaces:**
- Consumes: `GET /php/utsava-tickets.php` → `{ member: TicketCategory[], nonMember: TicketCategory[] }`
- Produces (for template): `form`, `ticketConfig`, `quantities`, `ticketsLoading`, `ticketsError`, `computedTickets`, `computedTotal`, `computedTicketPayload`, `canSubmit`, `showDialog`, `submitting`, `formSuccess`, `formError`, `turnstileToken`, `submitForm`, `setMembership`, `stepQty`

- [ ] **Step 1: Replace the `<script setup>` block**

Replace lines 165–278 (the entire `<script setup>` block) with:

```vue
<script setup>
useSeoMeta({
  title: 'UTSAVA | Munich Kannadigaru',
  description: "UTSAVA — Munich Kannadigaru's flagship annual cultural festival celebrating Karnataka's heritage through music, dance, and food in Munich.",
  ogTitle: 'UTSAVA — Kannada Cultural Festival Munich',
  ogDescription: "Join Munich Kannadigaru for UTSAVA — a celebration of Karnataka culture with music, dance, food, and community.",
  ogImage: 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=1200&q=80',
  ogType: 'website',
  ogUrl: 'https://munichkannadigaru.org/events/utsava',
  twitterCard: 'summary_large_image',
  twitterTitle: 'UTSAVA — Munich Kannadigaru',
  twitterDescription: 'Karnataka cultural festival in Munich — music, dance, food, and community.',
})

useHead({
  script: [{
    type: 'application/ld+json',
    children: JSON.stringify({
      '@context': 'https://schema.org',
      '@type': 'Event',
      name: 'UTSAVA',
      description: "Munich Kannadigaru's flagship annual cultural festival celebrating Karnataka's rich heritage.",
      location: { '@type': 'Place', name: 'Munich', address: { '@type': 'PostalAddress', addressLocality: 'Munich', addressCountry: 'DE' } },
      organizer: { '@type': 'Organization', name: 'Munich Kannadigaru', url: 'https://munichkannadigaru.org' },
      image: 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=1200&q=80',
      eventAttendanceMode: 'https://schema.org/OfflineEventAttendanceMode',
    })
  }]
})

const DEFAULTS = {
  slug: 'utsava',
  title: 'UTSAVA',
  kannadaTitle: 'ಉತ್ಸವ',
  tag: 'Featured Event',
  date: '',
  time: '',
  venue: '',
  heroImage: 'https://images.unsplash.com/photo-1533174072545-7a4b6ad7a6c3?w=1200&q=80',
  galleryImages: [
    'https://images.unsplash.com/photo-1565299624946-b28f40a0ae38?w=600&q=80',
    'https://images.unsplash.com/photo-1492684223066-81342ee5ff30?w=600&q=80',
  ],
  contentHtml: '<p>UTSAVA is our annual flagship event that brings together the Kannada-speaking community in Munich for a day of celebration, culture, and connection.</p><p>Experience the vibrant traditions of Karnataka through classical dance performances, melodious Kannada songs, traditional drama, and much more.</p>',
  registrationStatus: 'closed',
  past: false,
}

const { liveData, loading } = useEventData('utsava', DEFAULTS)

const { public: { apiBaseUrl } } = useRuntimeConfig()
const registerUrl = `${apiBaseUrl.replace(/\/$/, '')}/utsava-register.php`
const ticketsUrl  = `${apiBaseUrl.replace(/\/$/, '')}/utsava-tickets.php`

// ── Dialog visibility ────────────────────────────────────────────────────────
const showDialog  = ref(false)
const submitting  = ref(false)
const formSuccess = ref(false)
const formError   = ref(false)
const turnstileToken = ref('')

// ── Personal details ─────────────────────────────────────────────────────────
const form = reactive({
  firstName: '', lastName: '', email: '', phone: '',
  isMember: null,   // null = unanswered, true/false after selection
  membershipId: '',
})

// ── Ticket state ─────────────────────────────────────────────────────────────
const ticketConfig   = ref(null)   // { member: [...], nonMember: [...] }
const quantities     = ref({})     // { [categoryId]: number }
const ticketsLoading = ref(false)
const ticketsError   = ref(false)

async function fetchTickets() {
  if (ticketConfig.value) return
  ticketsLoading.value = true
  ticketsError.value = false
  try {
    ticketConfig.value = await $fetch(ticketsUrl)
  } catch {
    ticketsError.value = true
  } finally {
    ticketsLoading.value = false
  }
}

// ── Derived ticket state ──────────────────────────────────────────────────────
const computedTickets = computed(() => {
  if (!ticketConfig.value || form.isMember === null) return []
  return form.isMember ? ticketConfig.value.member : ticketConfig.value.nonMember
})

const computedTotal = computed(() =>
  computedTickets.value.reduce((sum, t) => sum + (quantities.value[t.id] || 0) * t.price, 0)
)

const computedTicketPayload = computed(() =>
  computedTickets.value
    .filter(t => (quantities.value[t.id] || 0) > 0)
    .map(t => ({ categoryId: t.id, label: t.label, quantity: quantities.value[t.id], priceEach: t.price }))
)

const canSubmit = computed(() =>
  !!turnstileToken.value &&
  computedTicketPayload.value.length > 0 &&
  form.firstName && form.lastName && form.email && form.phone &&
  form.isMember !== null &&
  (!form.isMember || form.membershipId.trim() !== '')
)

// ── Membership toggle ────────────────────────────────────────────────────────
function setMembership(value) {
  form.isMember = value
  quantities.value = {}   // reset quantities when membership status changes
}

// ── Quantity stepper ─────────────────────────────────────────────────────────
function stepQty(categoryId, delta) {
  const current = quantities.value[categoryId] || 0
  quantities.value = { ...quantities.value, [categoryId]: Math.max(0, current + delta) }
}

// ── Form submission ───────────────────────────────────────────────────────────
async function submitForm() {
  submitting.value = true
  formSuccess.value = false
  formError.value = false
  try {
    await $fetch(registerUrl, {
      method: 'POST',
      body: {
        firstName:    form.firstName,
        lastName:     form.lastName,
        email:        form.email,
        phone:        form.phone,
        isMember:     form.isMember,
        membershipId: form.membershipId,
        tickets:      computedTicketPayload.value,
        totalAmount:  computedTotal.value,
        currency:     'EUR',
        turnstileToken: turnstileToken.value,
      }
    })
    formSuccess.value = true
    turnstileToken.value = ''
    Object.assign(form, { firstName: '', lastName: '', email: '', phone: '', isMember: null, membershipId: '' })
    quantities.value = {}
  } catch {
    formError.value = true
    turnstileToken.value = ''
  } finally {
    submitting.value = false
  }
}

// ── Countdown ────────────────────────────────────────────────────────────────
const countdown = reactive({ days: '00', hours: '00', minutes: '00', seconds: '00' })

function updateCountdown() {
  if (!liveData.value?.date) return
  const eventDate = new Date(liveData.value.date).getTime()
  const dist = eventDate - Date.now()
  if (dist > 0) {
    countdown.days    = String(Math.floor(dist / 86400000)).padStart(2, '0')
    countdown.hours   = String(Math.floor((dist % 86400000) / 3600000)).padStart(2, '0')
    countdown.minutes = String(Math.floor((dist % 3600000) / 60000)).padStart(2, '0')
    countdown.seconds = String(Math.floor((dist % 60000) / 1000)).padStart(2, '0')
  }
}

// ── Watch dialog open → fetch tickets ────────────────────────────────────────
watch(showDialog, (open) => { if (open) fetchTickets() })

let timer
onMounted(() => {
  updateCountdown()
  timer = setInterval(updateCountdown, 1000)

  const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('is-visible'); observer.unobserve(e.target) } })
  }, { threshold: 0.12, rootMargin: '0px 0px -60px 0px' })
  document.querySelectorAll('.animate-observe').forEach(el => observer.observe(el))
  document.addEventListener('keydown', e => { if (e.key === 'Escape') showDialog.value = false })
})
onUnmounted(() => clearInterval(timer))
</script>
```

- [ ] **Step 2: Commit (script only — template comes next)**

```bash
git add app/pages/events/utsava.vue
git commit -m "feat: utsava.vue — ticket-aware script (state, fetch, computed, submit)"
```

---

## Task 4: Update `utsava.vue` — template and styles

**Files:**
- Modify: `app/pages/events/utsava.vue` (template + style blocks)

**Interfaces:**
- Consumes: all refs/functions produced by Task 3

- [ ] **Step 1: Replace the dialog template section**

Replace lines 91–161 (the `<!-- Registration Dialog -->` Teleport block) with:

```vue
    <!-- Registration Dialog -->
    <Teleport to="body">
      <div class="dialog-overlay" :class="{ active: showDialog }">
        <div class="dialog" role="dialog" aria-modal="true" aria-label="Register for UTSAVA">
          <div class="dialog-header">
            <div>
              <h3>Register for UTSAVA</h3>
              <p class="dialog-kannada kannada-text">ಉತ್ಸವಕ್ಕೆ ನೋಂದಣಿ</p>
            </div>
            <button class="dialog-close" @click="showDialog = false" aria-label="Close dialog">✕</button>
          </div>
          <div class="dialog-body">
            <div v-if="formSuccess" class="alert alert-success">
              Thank you for registering! We'll be in touch with more details soon.
            </div>
            <div v-if="formError" class="alert alert-error">
              There was an error. Please try again or email us directly.
            </div>

            <form v-if="!formSuccess" @submit.prevent="submitForm">

              <!-- Section 1: Personal Details -->
              <p class="form-section-label">Personal Details</p>
              <div class="form-row">
                <div class="form-group">
                  <label>First Name <span class="required">*</span></label>
                  <input type="text" v-model="form.firstName" required placeholder="First name" />
                </div>
                <div class="form-group">
                  <label>Last Name <span class="required">*</span></label>
                  <input type="text" v-model="form.lastName" required placeholder="Last name" />
                </div>
              </div>
              <div class="form-row">
                <div class="form-group">
                  <label>Email Address <span class="required">*</span></label>
                  <input type="email" v-model="form.email" required placeholder="your@email.com" />
                </div>
                <div class="form-group">
                  <label>Phone Number <span class="required">*</span></label>
                  <input type="tel" v-model="form.phone" required placeholder="+49 123 456 789" />
                </div>
              </div>

              <!-- Section 2: Membership -->
              <p class="form-section-label">Membership</p>
              <div class="form-group">
                <label>Are you a member of Munich Kannadigaru? <span class="required">*</span></label>
                <div class="membership-toggle">
                  <button
                    type="button"
                    class="toggle-pill"
                    :class="{ active: form.isMember === true }"
                    @click="setMembership(true)"
                  >Yes, I'm a member</button>
                  <button
                    type="button"
                    class="toggle-pill"
                    :class="{ active: form.isMember === false }"
                    @click="setMembership(false)"
                  >No, I'm not a member</button>
                </div>
              </div>
              <div v-if="form.isMember === true" class="form-group">
                <label>Membership ID <span class="required">*</span></label>
                <input
                  type="text"
                  v-model="form.membershipId"
                  required
                  placeholder="e.g. MK-1234"
                />
              </div>

              <!-- Section 3: Tickets -->
              <template v-if="form.isMember !== null">
                <p class="form-section-label">Tickets</p>
                <div v-if="ticketsLoading" class="tickets-loading">Loading ticket options…</div>
                <div v-else-if="ticketsError" class="alert alert-error">Could not load ticket options. Please close and try again.</div>
                <template v-else-if="computedTickets.length">
                  <div class="ticket-list">
                    <div v-for="ticket in computedTickets" :key="ticket.id" class="ticket-row">
                      <div class="ticket-info">
                        <span class="ticket-label">{{ ticket.label }}</span>
                        <span class="ticket-price">€{{ ticket.price }}</span>
                      </div>
                      <div class="ticket-stepper">
                        <button type="button" class="stepper-btn" @click="stepQty(ticket.id, -1)">−</button>
                        <span class="stepper-qty">{{ quantities[ticket.id] || 0 }}</span>
                        <button type="button" class="stepper-btn" @click="stepQty(ticket.id, 1)">+</button>
                      </div>
                    </div>
                  </div>
                  <div class="ticket-total">
                    Total: <strong>€{{ computedTotal }}</strong>
                  </div>
                </template>
              </template>

              <NuxtTurnstile v-model="turnstileToken" class="form-turnstile" />
              <button
                type="submit"
                class="btn btn-primary btn-full"
                :disabled="submitting || !canSubmit"
              >
                {{ submitting ? 'Submitting…' : 'Complete Registration' }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </Teleport>
```

- [ ] **Step 2: Add new styles to the `<style scoped>` block**

Append these rules before the closing `</style>` tag (after the last `@media` block):

```css
/* Form section labels */
.form-section-label {
  font-size: 13px;
  font-weight: 700;
  text-transform: uppercase;
  letter-spacing: 0.08em;
  color: var(--primary-red);
  margin: 24px 0 12px;
  padding-bottom: 6px;
  border-bottom: 1px solid var(--cream);
  font-family: 'Manrope', sans-serif;
}
.form-section-label:first-of-type { margin-top: 0; }

/* Membership toggle pills */
.membership-toggle {
  display: flex;
  gap: 10px;
  flex-wrap: wrap;
}
.toggle-pill {
  padding: 9px 20px;
  border-radius: 50px;
  border: 2px solid var(--cream);
  background: var(--white);
  color: var(--text-dark);
  font-size: 14px;
  font-family: 'Manrope', sans-serif;
  font-weight: 500;
  cursor: pointer;
  transition: all 0.2s;
}
.toggle-pill:hover { border-color: var(--primary-red); color: var(--primary-red); }
.toggle-pill.active {
  background: var(--primary-red);
  border-color: var(--primary-red);
  color: var(--white);
}

/* Ticket list */
.ticket-list { display: flex; flex-direction: column; gap: 10px; margin-bottom: 14px; }
.ticket-row {
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 14px 16px;
  background: var(--cream);
  border-radius: 12px;
  gap: 12px;
}
.ticket-info { display: flex; flex-direction: column; gap: 2px; }
.ticket-label { font-size: 15px; font-weight: 600; color: var(--text-dark); font-family: 'Manrope', sans-serif; }
.ticket-price { font-size: 13px; color: var(--text-light); }
.ticket-stepper { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
.stepper-btn {
  width: 32px; height: 32px; border-radius: 50%;
  border: 2px solid var(--primary-red);
  background: var(--white); color: var(--primary-red);
  font-size: 18px; font-weight: 700; cursor: pointer;
  display: flex; align-items: center; justify-content: center;
  transition: all 0.2s; line-height: 1;
}
.stepper-btn:hover { background: var(--primary-red); color: var(--white); }
.stepper-qty {
  min-width: 28px; text-align: center;
  font-size: 17px; font-weight: 700;
  font-family: 'Manrope', sans-serif;
  color: var(--text-dark);
}
.ticket-total {
  text-align: right;
  font-size: 16px;
  color: var(--text-dark);
  font-family: 'Manrope', sans-serif;
  margin-bottom: 20px;
}
.ticket-total strong { color: var(--primary-red); font-size: 18px; }
.tickets-loading {
  font-size: 14px; color: var(--text-light);
  padding: 16px 0; text-align: center;
  font-family: 'Manrope', sans-serif;
}
```

- [ ] **Step 3: Verify in the browser**

Start the dev server (`npm run dev`) and open `http://localhost:3000/events/utsava`.

Check:
1. "Register Now" button opens dialog
2. Personal details section renders (4 fields, 2 rows)
3. Membership toggle shows two pills; clicking "Yes" reveals Membership ID field
4. Clicking either pill loads ticket list (member or non-member categories)
5. Switching membership pill resets all quantities to 0
6. `−` / `+` buttons increment/decrement quantities; min is 0
7. Total updates live as quantities change
8. Submit button stays disabled until all required fields filled, membership answered, ≥1 ticket selected, Turnstile resolved
9. Success message shown after submit

- [ ] **Step 4: Commit**

```bash
git add app/pages/events/utsava.vue
git commit -m "feat: utsava.vue — ticket-selection dialog with membership toggle and quantity steppers"
```
