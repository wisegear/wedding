# Wedding Guest System Plan

## Objective

Restructure the guest system around invited guest groups so that:

- admins create and manage invitation groups and guests in the admin area
- only invited guests can register
- a registered guest can manage RSVP data for their entire guest group

There is no live guest data yet, so the existing guest migrations can be rewritten in place instead of adding compatibility migrations.

## Current State

The current implementation is simpler than the required flow:

- `guest_groups` exists with only `group_name` and `notes`
- `wedding_guests` exists instead of `guests`
- `wedding_guests` links directly to `users`
- admin guests only has a placeholder index page
- registration creates a user without validating against invited guests

This means the next change should be treated as a structural reset of the guest system, not an incremental extension.

## Target Data Model

### 1. `guest_groups`

Required fields:

- `id`
- `group_name`
- `invitation_code` nullable unique string
- `primary_guest_id` nullable foreign key to `guests`, `nullOnDelete()`
- `notes` nullable text
- timestamps

Migration order matters because of the circular relationship:

1. create `guest_groups` without `primary_guest_id`
2. create `guests`
3. alter `guest_groups` and add `primary_guest_id`

### 2. `guests`

Replace the current `wedding_guests` table with `guests`.

Required fields:

- `id`
- `guest_group_id` foreign key to `guest_groups`, cascade on delete
- `first_name`
- `last_name`
- `display_name` nullable string
- `email` nullable string
- `phone` nullable string
- `is_primary` boolean default `false`
- `rsvp_status` nullable string
- `dinner_choice` nullable string
- `dietary_requirements` nullable text
- `notes` nullable text
- timestamps

Indexes:

- `guest_group_id`
- `first_name`
- `last_name`
- `rsvp_status`

### 3. `guest_group_user`

This becomes the registration link between an application user, their invited guest record, and the group they belong to.

Required fields:

- `id`
- `guest_group_id` foreign key to `guest_groups`, cascade on delete
- `guest_id` foreign key to `guests`, cascade on delete
- `user_id` foreign key to `users`, cascade on delete
- timestamps

Constraints:

- unique `user_id`
- unique `guest_id`
- unique (`guest_group_id`, `user_id`)

## Model Changes

### Replace `WeddingGuest` with `Guest`

Because there is no live data and the new schema is materially different, the codebase should move to the new names instead of keeping `WeddingGuest` as legacy terminology.

Expected models:

- `App\Models\GuestGroup`
- `App\Models\Guest`
- `App\Models\GuestGroupUser` or a pivot-backed relationship if kept intentionally simple

### Relationships

`GuestGroup`

- `guests()`
- `primaryGuest()`
- `users()`

`Guest`

- `group()`
- `users()` if modeled through the linking table
- optionally `registeredUser()` if a single linked user helper is useful

`User`

- `guestGroups()`
- `guest()`

## Admin Scope

Create a real guest-group CRUD section under the existing admin middleware.

### Routes

Replace the current guest placeholder route with guest-group management routes under the existing `auth`, `verified`, `admin` middleware stack in [routes/web.php](/Users/wisenerl/Herd/wedding/routes/web.php).

Recommended routes:

- `GET /admin/guest-groups`
- `GET /admin/guest-groups/create`
- `POST /admin/guest-groups`
- `GET /admin/guest-groups/{guestGroup}/edit`
- `PUT/PATCH /admin/guest-groups/{guestGroup}`
- `DELETE /admin/guest-groups/{guestGroup}`

### Controller

Replace or rename the current guest admin controller so it manages groups rather than only guest counts.

Recommended controller:

- `App\Http\Controllers\Admin\GuestGroupController`

Responsibilities:

- list guest groups with guest counts
- show create form
- store one group and many guests in a transaction
- show edit form
- update group details
- sync guests for that group
- remove guests from the group
- delete a group and cascade-delete its guests

### Validation

Use form requests or controller validation for:

- `group_name` required string
- `notes` nullable string
- `guests` required array with at least one row
- `guests.*.first_name` required string
- `guests.*.last_name` required string
- `guests.*.email` nullable email
- `guests.*.phone` nullable string
- `guests.*.is_primary` boolean
- `guests.*.notes` nullable string

Business rules:

- allow zero or one primary guest per group
- if none selected, leave `primary_guest_id` null or infer later
- wrap create/update operations in a database transaction

### Views

Add Blade views under `resources/views/admin/guest-groups/`.

Recommended files:

- `index.blade.php`
- `create.blade.php`
- `edit.blade.php`
- `_form.blade.php`

Form requirements:

- start with one guest row
- allow dynamic row addition with simple JavaScript
- allow row removal during editing
- submit the group and all guests together

Keep the frontend simple and consistent with the current admin area instead of introducing Livewire unless there is already a clear project preference.

## Registration Gatekeeping

The registration flow currently lives in [app/Actions/Fortify/CreateNewUser.php](/Users/wisenerl/Herd/wedding/app/Actions/Fortify/CreateNewUser.php). That is the right integration point for guest validation.

### Required Registration Flow

1. Normalize first and last name by trimming whitespace and comparing case-insensitively.
2. Look up a matching `guests` record.
3. If none exists, reject registration with:
   `We could not find your name on the guest list. Please contact us if you think this is a mistake.`
4. If a guest exists but is already linked in `guest_group_user`, reject registration with:
   `This guest has already registered.`
5. If a matching guest exists and is unlinked:
   - create the user
   - create the `guest_group_user` record
   - allow the normal login flow to continue

### Design Constraint

Implement the guest lookup behind a small service or dedicated method so the matching rules can later expand to include:

- email
- invitation code
- duplicate-name handling

For now, name-only matching is acceptable, but the code should not bury that logic directly inside controller-style validation strings.

## RSVP Authorization Direction

Do not build the public RSVP pages yet, but the data model and relationships must support them cleanly.

Expected rule:

- a logged-in user can only access the guest group linked to their invited guest
- any registered user in that group can update RSVP and dinner choices for every guest in the group

When RSVP work starts, authorization should key off the linked `guest_group_user` record rather than raw user IDs on guest rows.

## Existing Code To Replace

The following areas should be updated as part of the implementation:

- rewrite [database/migrations/2026_06_21_210000_create_guest_groups_table.php](/Users/wisenerl/Herd/wedding/database/migrations/2026_06_21_210000_create_guest_groups_table.php)
- rewrite [database/migrations/2026_06_21_210100_create_wedding_guests_table.php](/Users/wisenerl/Herd/wedding/database/migrations/2026_06_21_210100_create_wedding_guests_table.php) into a `guests` table migration
- add a migration for `guest_group_user`
- replace [app/Models/WeddingGuest.php](/Users/wisenerl/Herd/wedding/app/Models/WeddingGuest.php) with `Guest`
- update [app/Models/GuestGroup.php](/Users/wisenerl/Herd/wedding/app/Models/GuestGroup.php) relationships
- update [app/Models/User.php](/Users/wisenerl/Herd/wedding/app/Models/User.php) relationships
- replace the placeholder admin guest controller logic in [app/Http/Controllers/Admin/GuestController.php](/Users/wisenerl/Herd/wedding/app/Http/Controllers/Admin/GuestController.php)
- update admin routing in [routes/web.php](/Users/wisenerl/Herd/wedding/routes/web.php)
- update registration behavior in [app/Actions/Fortify/CreateNewUser.php](/Users/wisenerl/Herd/wedding/app/Actions/Fortify/CreateNewUser.php)
- update any dashboard/admin counters that still reference `WeddingGuest`

The dining-related tables may stay as-is for now, but they should be reviewed once `WeddingGuest` becomes `Guest` because foreign keys and model references will likely need follow-up updates.

## Suggested Implementation Order

1. Rewrite guest-related migrations because the schema change drives everything else.
2. Replace `WeddingGuest` with `Guest` and update relationships.
3. Add the `guest_group_user` model/relationships.
4. Build guest-group admin CRUD with transactional create/update flows.
5. Update Fortify registration to require an invited guest match and create the link record.
6. Update dashboard/admin summaries and any affected dining references.
7. Add feature tests before considering the work complete.

## Test Coverage

Add or update feature tests for:

- admin can create a guest group with multiple guests
- admin can edit a group and add/remove guests
- deleting a group deletes its guests
- non-admin users cannot access admin guest-group routes
- registration fails for a non-invited name
- registration fails when the guest is already linked
- registration succeeds for a matching invited guest
- registration creates the `guest_group_user` link
- matching ignores surrounding whitespace and letter casing

## Out Of Scope For This Change

- public RSVP pages
- invitation-code based registration
- duplicate-name resolution beyond keeping the lookup logic extensible
- guest import tooling

## Summary

The correct next implementation is to rebuild the guest system around:

- `guest_groups`
- `guests`
- `guest_group_user`

and to make registration depend on pre-created invited guest records. Because there is no live data yet, the cleanest approach is to rewrite the existing guest schema and replace the current `WeddingGuest` terminology rather than layering compatibility code on top of it.
