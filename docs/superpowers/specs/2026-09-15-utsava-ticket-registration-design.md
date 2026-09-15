# Utsava Ticket Registration Form — Design Spec

**Date:** 2026-09-15  
**Status:** Approved

---

## Overview

Replace the simple registration form on `utsava.vue` with a ticket-aware registration dialog. Users select how many tickets of each category they want, declare membership, and submit. The frontend collects all booking intent — no payment processing happens here.

---

## Backend: Two PHP Endpoints

### 1. `GET /php/utsava-tickets.php`

Returns available ticket categories, split by membership status. Data lives in `php/data/utsava-tickets.json` (same read-from-JSON pattern as `events.php`).

**Response shape:**
```json
{
  "member": [
    { "id": "adult_member", "label": "Adult (Member)", "price": 15, "currency": "EUR" },
    { "id": "child_member", "label": "Child (Member)", "price": 5,  "currency": "EUR" }
  ],
  "nonMember": [
    { "id": "early_bird", "label": "Early Bird",                    "price": 18, "currency": "EUR" },
    { "id": "adult",      "label": "Adult",                         "price": 22, "currency": "EUR" },
    { "id": "student",    "label": "Student",                       "price": 12, "currency": "EUR" },
    { "id": "family",     "label": "Family (2 adults + 2 children)", "price": 55, "currency": "EUR" }
  ]
}
```

Read-only, no Turnstile, no DB — same pattern as `events.php`.

---

### 2. `POST /php/utsava-register.php` (replace existing)

**Request payload:**
```json
{
  "firstName":    "string (required)",
  "lastName":     "string (required)",
  "email":        "string (required, valid email)",
  "phone":        "string (required)",
  "isMember":     "boolean",
  "membershipId": "string (required if isMember=true)",
  "tickets": [
    { "categoryId": "string", "label": "string", "quantity": "int ≥ 1", "priceEach": "number" }
  ],
  "totalAmount":  "number",
  "currency":     "string (e.g. EUR)",
  "turnstileToken": "string"
}
```

Validation rules:
- `firstName`, `lastName`, `email`, `phone` required
- At least 1 ticket with quantity ≥ 1
- If `isMember=true`, `membershipId` must be non-empty
- `totalAmount` must match sum of `quantity × priceEach` across all tickets (server recalculates and rejects on mismatch)

**DB schema (new table):**
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

Stores raw ticket selection as JSON — no need to normalise into separate rows since this is a booking record, not inventory management.

---

## Frontend: `app/pages/events/utsava.vue`

### Dialog structure — 3 logical steps in one scrollable dialog

The form does not use wizard/step navigation (too much UX friction for a short form). Instead it flows top-to-bottom in one dialog scroll, with the steps visually separated by section headings.

**Section 1 — Personal Details**
- First Name (required)
- Last Name (required)
- Email Address (required)
- Phone Number (required)

**Section 2 — Membership**
- "Are you a member of Munich Kannadigaru?" — Yes / No toggle (radio-style pill buttons)
- If Yes: show "Membership ID" text input (required)

**Section 3 — Tickets**
- Fetched from `/php/utsava-tickets.php` on dialog open (or on first render if registration is open)
- Show the ticket list matching the membership answer (member categories or non-member categories)
- Re-renders list when membership toggle changes; quantities reset on toggle change
- Each ticket row: `[Label — €Price]  [− qty +]`
- Quantity stepper: min 0, no max enforced client-side
- Running total line: "Total: €XX" — updates live
- At least 1 ticket required (total quantity > 0) to enable submit

**Submit button** — disabled until: Turnstile resolved, total quantity > 0, all required fields filled.

**Success state** — replaces form body with a confirmation message (same pattern as existing form).

---

## State shape (Vue reactive)

```js
const form = reactive({
  firstName: '', lastName: '', email: '', phone: '',
  isMember: null,        // null = unanswered, true/false after selection
  membershipId: '',
})

const ticketConfig = ref(null)   // { member: [...], nonMember: [...] }
const quantities = ref({})       // { [categoryId]: number }
const ticketsLoading = ref(false)
const ticketsError = ref(false)
```

`computedTickets` — derived from `ticketConfig` + `form.isMember`  
`computedTotal` — sum of `quantities[id] * price` for all categories  
`computedTicketPayload` — array of `{ categoryId, label, quantity, priceEach }` with quantity > 0

---

## Files changed

| File | Change |
|---|---|
| `app/pages/events/utsava.vue` | Replace dialog form section; add ticket fetch logic |
| `php/utsava-register.php` | Replace handler; new DB schema, new payload |
| `php/utsava-tickets.php` | New read-only endpoint |
| `php/data/utsava-tickets.json` | New data file with ticket categories |

---

## Out of scope

- Payment processing
- Ticket PDF/email generation
- Capacity limits / sold-out states
- Admin UI for managing ticket categories
