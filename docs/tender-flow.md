# Tender Flow Documentation

> Reference guide for the full lifecycle of a tender in SUKSEL 3.0 — from creation to winner announcement.

---

## 1. Tender Creation

**Who:** Admin / Agency Admin / Agency User  
**Page:** `/tenders/create`

- Fill in tender details: name, ref number, type (tender/sebut harga), document dates, submission date, price, codes (MOF/CIDB), etc.
- Save → tender is created with `approver_id = NULL` (draft state)

**DB state after:**
```
approver_id     = NULL
publish_prices  = 0
publish_winner  = 0
```

---

## 2. Siar Tender (Publish)

**Who:** Admin / Agency Admin / Agency User (same organization unit)  
**Page:** `/tenders/{id}` → Tindakan dropdown → **"Siar Tender"**  
**Route:** `GET /tenders/{id}/publish`

**Condition to show button:** `approver_id` is empty (not yet published)

**What happens:**
1. Creates a new record in `approvals` table (`user_id` = current user)
2. Sets `tender.approver_id = auth()->user()->id` ← references `users.id`
3. Tender becomes **publicly visible** to guests and vendors

**DB state after:**
```
approver_id     = {user_id}   ← now set
publish_prices  = 0
publish_winner  = 0
```

**Where tender appears:** `/` (Laman Utama) senarai tender list, `/tenders/{id}` for guests & vendors

> To undo: Click **"Batal Siar"** → sets `approver_id = NULL`

---

## 3. Vendor Membeli Dokumen (Purchase)

**Who:** Vendor  
**Page:** `/tenders/{id}` (vendor view) → **"Tambah Kepada Senarai Tempahan"** button

**Conditions for button to appear (`canPurchase()`):**
- Logged in as Vendor
- `manual_payment = false`
- Vendor has not already purchased (`hasParticipate = false`)
- Tender not already in cart
- `canParticipate()` returns true:
  - Tender agency gateway not locked
  - Vendor subscription is valid (`canParticipateInTenders()`)
  - `validDocumentDate()` — today is within `document_start_date` to `document_stop_date`
  - Vendor matches MOF codes (if required)
  - Vendor matches CIDB grade/codes (if required)
  - Vendor from correct state/district (if restricted)
  - Vendor attended briefing (if `briefing_required`)
  - Vendor attended site visits (if required)

**Flow:**
1. Click "Tambah Kepada Senarai Tempahan" → added to cart session
2. Go to `/cart` → review cart
3. Click "Teruskan" → `/cart/checkout`
4. Select payment method (FPX / Kad Kredit / DuitNow)
5. Complete payment → redirected to `/cart/receipt/{id}`

**DB state after successful purchase:**
- `tender_vendors` record created with `participate = 1`
- `transactions` record with `status = success`

---

## 4. Umum Carta Tender / Publish Prices

**Who:** Admin / Agency Admin / Agency User  
**Page:** `/tenders/{id}` → Tindakan dropdown → **"Umum Carta Tender"**  
**Route:** `GET /tenders/{id}/publishPrices`

**Conditions for button to appear:**
- `canShowPrices()` = true → `submission_datetime <= now` (tender already closed)
- `approver_id > 0` (tender was published)
- `publish_prices = 0` (not yet published)

**What happens:** Sets `publish_prices = 1`

**DB state after:**
```
approver_id     = {user_id}
publish_prices  = 1           ← now set
publish_winner  = 0
```

**Where tender appears:** `/prices` (Senarai Carta Tender) page

> To undo: Click **"Batal Umum Carta Tender"** → sets `publish_prices = 0`

---

## 5. Tab: Anggaran Layak (Eligible Vendors)

**Who:** Admin / Agency Admin / Agency User  
**Page:** `/tenders/{id}/eligibles`

Shows vendors who are eligible to participate based on:
- MOF code matching
- CIDB grade/code matching
- Bumiputera requirement
- State/district restriction

This tab is accessible once the tender is published (`approver_id` set).

---

## 6. Tab: Maklumat Syarikat (Vendor List)

**Who:** Admin / Agency Admin / Agency User  
**Page:** `/tenders/{id}/vendors`

Shows all vendors who have purchased the tender document.

Only visible when `canShowTabs()` = true:
- `publish_prices > 0`, OR
- Admin/Agency viewing their own tender

---

## 7. Umum Penender Berjaya / Publish Winner

**Who:** Admin / Agency Admin / Agency User  
**Page:** `/tenders/{id}` → Tindakan dropdown → **"Umum Penender Berjaya"**  
**Route:** `GET /tenders/{id}/publishWinner`

**Conditions for button to appear (`canShowWinner()`):**
- `publish_prices > 0` (prices must be published first)
- `publish_winner = 0` (winner not yet announced)

**What happens:** Sets `publish_winner = 1`

**DB state after:**
```
approver_id     = {user_id}
publish_prices  = 1
publish_winner  = 1           ← now set
```

**Where tender appears:** `/results` (Keputusan Tender) page

> To undo: Click **"Batal Umum Penender Berjaya"** → sets `publish_winner = 0`

---

## Full Lifecycle Summary

```
CREATE TENDER
    │
    ▼
[Draft] approver_id=NULL
    │
    │  Admin clicks "Siar Tender"
    ▼
[Published] approver_id={user_id}
    │  → Appears on Laman Utama (/)
    │  → Vendors can view & purchase
    │
    │  submission_datetime passes
    │  Admin clicks "Umum Carta Tender"
    ▼
[Prices Published] publish_prices=1
    │  → Appears on Carta Tender (/prices)
    │
    │  Admin clicks "Umum Penender Berjaya"
    ▼
[Winner Announced] publish_winner=1
       → Appears on Keputusan Tender (/results)
```

---

## DB Column Reference

| Column | Table | Meaning |
|---|---|---|
| `approver_id` | `tenders` | User ID who published. NULL = draft |
| `publish_prices` | `tenders` | `1` = carta tender published |
| `publish_winner` | `tenders` | `1` = winner announced |
| `invitation` | `tenders` | `1` = tender terhad (invited vendors only) |
| `manual_payment` | `tenders` | `1` = purchase via manual only, no online payment |
| `registration_paid` | `vendors` | `1` = vendor subscription active |
| `expiry_date` | `vendors` | Vendor subscription expiry date |
| `participate` | `tender_vendors` | `1` = vendor has purchased the document |

---

## canShow() Rules

| Tender Type | User | Can View? |
|---|---|---|
| Invitation (`invitation=1`) | Guest | ✗ |
| Invitation (`invitation=1`) | Vendor (not invited) | ✗ |
| Invitation (`invitation=1`) | Vendor (invited) | ✓ |
| Invitation (`invitation=1`) | Admin | ✓ |
| Non-invitation, `approver_id=NULL` | Guest | ✓ (bypasses canShow) |
| Non-invitation, `approver_id=NULL` | Vendor | ✓ (bypasses canShow) |
| Non-invitation, `approver_id=NULL` | Admin | ✓ |
| Non-invitation, `approver_id` set | Everyone | ✓ |

> Note: Guest and Vendor bypass `canShow()` entirely in `TendersController@show` — they are routed to their own blade before the check runs.

---

## Dev Bypass (Non-Production Only)

For testing payment flows without a real gateway:

| Page | Bypass Location |
|---|---|
| `/cart/checkout` | FPX dropdown → **[DEV] Bypass Bayaran** |
| `/register/payment` | FPX dropdown → **[DEV] Bypass Bayaran** |

The bypass sets `transaction.status = success` directly and triggers `generateSubscription()` for registration payments.

> These options are hidden automatically when `APP_ENV=production`.
