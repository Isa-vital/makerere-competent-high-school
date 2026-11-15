# Makerere Competent High School redesign progress

## Phase 1 — Project audit (complete)

- Laravel Blade public site with shared `layouts/app.blade.php`, `partials/header.blade.php`, and `partials/footer.blade.php`; Bootstrap 5, Font Awesome, AOS, and large legacy stylesheets in `public/assets/css`.
- Public routes: home, about, academics, circular, gallery, admissions, MACOSA (`alumni`), news, contact, and newsletter POST. Admissions, alumni, and contact forms have their existing controller actions; Filament manages site data.
- Home controller supplies published news and active gallery items. `setting()` reads CMS site settings. Header contact and social URLs come from `config/school.php`.
- Assets: `competentlogo.jpeg` is the official school mark; `gate.jpeg` is authentic campus photography. The WhatsApp image is another logo photo; `microscope.jpg` is generic stock-like imagery. Existing gallery uploads include a duplicate gate photo, a software logo, and two blank images. Better student and learning photographs are needed for later sections.
- Current issues: crowded header, duplicate/contradictory responsive CSS, centered 2-second hero carousel, inconsistent palette and typography, generic card styling, unsupported hard-coded homepage claims and statistics, and old circular text. Remaining homepage sections need a content review in the next phase.
- Inspiration reviewed: Stonyhurst Penang's institutional navigation, editorial hierarchy and school-life emphasis. The implementation remains original to Makerere and its available assets.

## Phase 2 — Design foundation (complete for header and hero)

- Added `public/assets/css/redesign.css` after legacy CSS in `resources/views/layouts/app.blade.php`. It defines navy/blue/gold tokens, Manrope/Inter typography, spacing, buttons, focus styles, reduced-motion behavior, and responsive layouts. Added the main landmark ID for the skip link.
- Decision: scope the new rules to the redesigned header and hero so untouched pages retain their current layout. The legacy CSS will be cleaned up as remaining pages are redesigned.

## Phase 3 — Header and homepage hero (complete)

- Rebuilt `resources/views/partials/header.blade.php` with a slim utility bar, official logo, all eight existing public destinations, active-page indication, prominent Apply Now action, and Bootstrap mobile collapse. Contact details and social URLs still come from `config/school.php`.
- Added `resources/views/partials/home-hero.blade.php`; `resources/views/pages/home.blade.php` now includes it instead of the four-slide carousel. The hero uses `gate.jpeg`, an editorial left-aligned headline, and existing admissions and academics routes.
- No routes, forms, controllers, database structures, CMS models, or admin features were changed.
- Validation: the changed Blade templates compiled successfully with Laravel's `BladeCompiler`. Full `php artisan` validation and a rendered browser check remain pending: Artisan commands hung during application startup in this environment. No frontend dependency install was needed.

### Files modified in phases 2–3

- `resources/views/layouts/app.blade.php`
- `resources/views/partials/header.blade.php`
- `resources/views/partials/home-hero.blade.php` (new)
- `resources/views/pages/home.blade.php`
- `public/assets/css/redesign.css` (new)
- `REDESIGN_PROGRESS.md` (new)

## Later phases

- Next: redesign About and Academics, then Admissions and Gallery, then News/MACOSA, then Contact/Circular, then cross-site verification. The revised user instruction authorizes proceeding across all public pages without a homepage approval gate.
- Render and review visual layout on desktop/tablet/mobile once Laravel application startup works; check the mobile collapse, contrast, overflow, and hero crop.
- Preserve CMS news/gallery relationships, forms, Filament features and database structures. Do not commit or push automatically.

## Revised brief — complete public-page inventory

| Route | View | Existing data/action |
| --- | --- | --- |
| `/` | `pages.home` | Published news, active gallery, contact POST |
| `/about` | `pages.about` | Leadership, facilities, achievements, history |
| `/academics` | `pages.academics` | Subjects, combinations, results, facilities, academic news |
| `/admissions` GET/POST | `pages.admissions` | Scholarships, fees, inquiry POST |
| `/gallery` | `pages.gallery` | Active gallery, category query/filter |
| `/news` | `pages.news.index` | Paginated published news |
| `/news/{news:slug}` | `pages.news.show` | Article detail |
| `/alumni` GET/POST | `pages.alumni` | MACOSA alumni, leadership, statistics, registration POST |
| `/contact` GET/POST | `pages.contact` | Departments, FAQs, contact POST |
| `/circular` | `pages.circular` | Fees for Term I 2026 alongside static older notice text |

`pages.stub` and `welcome.blade.php` are not registered as public routes. Newsletter POST is shared through the footer. `design-references/homepage-concept.png` is absent.

## Revised phase 2 — homepage and shared footer (complete)

- Replaced remaining homepage sections with editorial welcome, O-Level/A-Level pathways, a concise school approach, authentic campus image, CMS-backed published news, admissions CTA, and a contact form retaining all required fields and the existing POST action.
- Removed unsupported numerical claims and stale 2025 circular from the homepage. Avoided the blank and unrelated gallery uploads; gallery still has its own route for CMS content. The current photo library has only one credible campus image, so school-life visual storytelling remains limited until authentic photographs are supplied.
- Rebuilt shared footer with navigation, existing configured contact/social links, newsletter POST, and responsive columns. No controller or database changes.
- Files: `resources/views/pages/home.blade.php`, `resources/views/partials/footer.blade.php`, `resources/views/partials/header.blade.php`, `public/assets/css/redesign.css`, `REDESIGN_PROGRESS.md`.
- Test: changed Blade files compiled through Laravel `BladeCompiler`. Full Artisan/bootstrap and rendered visual checks still pending because Artisan previously hung in this environment.

## Revised phase 3 — About and Academics (complete)

- Rebuilt `resources/views/pages/about.blade.php` with an authentic school gate hero, concise school story, CMS settings for mission/vision/motto, and conditional CMS history, leadership, achievements and facilities. Removed unsupported 1998 and world-class claims and the misleading logo-as-person portrait fallback.
- Rebuilt `resources/views/pages/academics.blade.php` with O-Level subject groups, A-Level combinations, conditional CMS results, facilities and academic news. Anchors `#o-level` and `#a-level` remain for shared links. Avoided hard-coded exam results and invented student services.
- Expanded `public/assets/css/redesign.css` with editorial interior heroes, typography, responsive content grids and academic layouts.
- Test: both changed Blade templates compiled with Laravel `BladeCompiler`. Full rendered testing remains pending.
- Next: phase 4, Admissions and Gallery. Preserve the existing admissions POST field names and current controller validation. Gallery category query/filter and image paths need careful review.

## Revised phase 4 — Admissions and Gallery (complete)

- Rebuilt `resources/views/pages/admissions.blade.php` with a clear inquiry journey, conditional CMS fee tables and scholarships, and a responsive form. Preserved every field required by `AdmissionController::store`, old input values, error summary, CSRF and the existing POST route. Removed unsupported fee inclusions, named staff, office hours and static application dates.
- Rebuilt `resources/views/pages/gallery.blade.php` around server-backed category links and active CMS images. Added a native-dialog preview with keyboard Escape support and real image alt text. Removed fake virtual-tour and load-more interactions. Normalized uploaded image filenames to `assets/images/uploads/` to match current public uploads.
- Added page styles to `public/assets/css/redesign.css`.
- Test: both changed Blade templates compiled with Laravel `BladeCompiler`. Rendered interaction check pending. Existing active gallery data includes unrelated/blank images; CMS content cleanup is needed for a trustworthy gallery.
- Next: phase 5, News listing/detail and MACOSA. Preserve article route binding and alumni registration fields.

## Revised phase 5 — News and MACOSA (complete)

- Rebuilt `resources/views/pages/news/index.blade.php` and `resources/views/pages/news/show.blade.php` with editorial cards, CMS images where present, publication dates, pagination and article metadata. Preserved article route binding and CMS HTML rendering.
- Rebuilt `resources/views/pages/alumni.blade.php` with a MACOSA introduction, conditional CMS stats/stories/leadership messages and the existing registration POST fields. Removed hard-coded alumni profiles and unverifiable community claims.
- Added page styles to `public/assets/css/redesign.css`.
- Test: all three changed Blade templates compiled with Laravel `BladeCompiler`. Full render and form submission checks pending.
- Next: phase 6, Contact and Circular; preserve contact POST and CMS FAQ/department data. Circular mixes a 2025 hard-coded document with 2026 fees and needs careful removal or rewriting from verified data.

## Revised phase 6 — Contact and Circular (complete)

- Rebuilt `resources/views/pages/contact.blade.php` with configured school contact details, a form retaining all `ContactController::store` fields and validation feedback, conditional CMS department contacts and semantic FAQ disclosures. Removed the invalid embedded map coordinates and unverified office hours.
- Rebuilt `resources/views/pages/circular.blade.php` as a dated Term I 2026 fee schedule using existing CMS fee rows. Removed stale 2025 holiday notices, expired term opening dates and unverified bank/payment instructions. The page now directs families to confirm current details with the school.
- Added responsive page styles to `public/assets/css/redesign.css`.
- Test: both changed Blade templates compiled with Laravel `BladeCompiler`. Full rendered and interaction checks remain pending.
- Next: phase 7, cross-site functionality, accessibility, responsiveness and visual checks. Every registered public GET page now has redesigned markup. Recheck Laravel boot, route rendering, forms and gallery dialog. Fix any discovered issues before declaring completion.

## Revised phase 7 — Cross-site verification (complete for available environment)

- Removed the legacy `main.js` and AOS includes from `resources/views/layouts/app.blade.php`. The legacy script intercepted all forms, prevented Laravel POST requests and displayed false success notifications. Bootstrap remains for the mobile menu; the new gallery preview uses its own small script.
- Added `school_image_url()` in `app/helpers.php` and used it in news/gallery templates. It recognizes existing legacy uploads and public Filament paths only when the file is available. `PageController::gallery()` now lists categories and images with available files, avoiding broken thumbnails. The sole currently active gallery record points to a missing file, so the gallery correctly shows an empty state.
- Fixed 320px horizontal overflow on Home and Admissions by allowing long email links and grid children to wrap. Browser viewport checks show `scrollWidth == viewport` on all nine public GET pages at 320px, 390px and 768px. Desktop Home and About and phone Home/Contact were visually inspected; mobile Bootstrap menu opened with `aria-expanded=true`.
- `php artisan view:cache` passed. `php artisan test` passed: 2 tests, 2 assertions. All nine public GET routes returned HTTP 200. A published news article route returned HTTP 200. Invalid POSTs to Contact, Admissions and MACOSA returned visible Laravel validation feedback without writing records. Public CSS, gate photo and logo returned HTTP 200. PHP lint passed for changed helper/controller.
- Remaining content issues: CMS currently publishes an article titled “Add New Article”; its featured image file is missing. Active gallery media file is also missing. The authentic photo library still lacks student, classroom and activity images, so the design uses the verified gate photo and typographic sections. A gallery dialog interaction could not be exercised against live content because no valid active gallery image exists. Successful form submissions were not exercised to avoid creating test records.
- Modified during this phase: `resources/views/layouts/app.blade.php`, `resources/views/pages/home.blade.php`, `resources/views/pages/academics.blade.php`, `resources/views/pages/gallery.blade.php`, `resources/views/pages/news/index.blade.php`, `resources/views/pages/news/show.blade.php`, `app/helpers.php`, `app/Http/Controllers/PageController.php`, `public/assets/css/redesign.css`.
- Exact continuation at that point: publish authentic school images and replace/remove the CMS placeholder article; upload/repair gallery and news media through Filament, then recheck those cards and the gallery preview. No route, form endpoint or database schema changes are needed for the redesign.

## Phase 8 — Remove public fee sections (complete)

- Removed the fee tables from Admissions and School Circular, along with their unused database queries and CSS. The Circular route now offers a current-notice contact prompt instead of a dated fee schedule. Fee inquiries remain an option in the admissions form; the Filament fee management resource and database records are untouched.
- Modified: `resources/views/pages/admissions.blade.php`, `resources/views/pages/circular.blade.php`, `app/Http/Controllers/AdmissionController.php`, `app/Http/Controllers/PageController.php`, `public/assets/css/redesign.css`, `REDESIGN_PROGRESS.md`.
- Verification: both changed controllers pass `php -l`, `php artisan view:cache` succeeds, and `php artisan test` passes (2 tests, 2 assertions). The test suite does not cover Admissions or Circular rendering directly.
- Pending: replace the published “Add New Article” placeholder and its missing image; upload authentic school photos and repair gallery media; recheck image cards and the gallery dialog with real content; test successful form submissions in a safe environment; complete a full accessibility audit and production deployment checks. The circular page needs a verified current notice if it is to publish one rather than direct visitors to contact the school.

## CMS verification audit (read-only; functionality not fully confirmed)

- Admin route registration succeeds for the dashboard, login, 20 Filament resources and Site Settings. Database connection works and all 25 migrations are applied. `public/storage` exists as a junction. Existing tests pass (2 tests, 2 assertions) but do not exercise CMS create/edit/delete, login, media upload, or settings saving.
- Confirmed workflow mismatch: public Admissions POST creates a `ContactMessage`, so it appears under Contact Messages rather than the CMS Admissions resource. Existing `AdmissionResource` offers `waitlist`, while the `admissions.status` database enum permits only `pending`, `approved`, `rejected`, `enrolled`. Its editor also omits `enrolled`.
- Confirmed settings mismatch: Site Settings contains `contact_phone`, `contact_email`, `contact_address`, social URL and `site_title` fields, but public header/footer/contact pages read `config('school.*')`; changing those CMS settings will not update those public values.
- Live content check: one published news item (“Add New Article”) and one active gallery item (“isaac”); both referenced image files are missing, so their public images cannot render. The news card uses a text fallback and the gallery filters the missing item.
- Access review: `User::canAccessPanel()` permits Spatie `editor` role, while `UserResource` exposes user creation, role assignment and deletion and no resource policy/authorization override was found. Verify and restrict editor privileges before treating role separation as safe.
- Further verification requires an isolated test database and authenticated admin flows for CRUD, uploads, status transitions, settings persistence and submission review. No live records were changed during this audit.

## Phase 9 — CMS issue fixes (complete for reported code and local content)

- Added a dedicated **Admission Inquiries** Filament resource for public inquiry submissions, using the existing `contact_messages` records and status workflow. The general Contact Messages resource excludes those inquiries. The separate admissions record resource is labeled **Applications**; its editor and filters now use `enrolled` instead of unsupported `waitlist`, matching the database enum. No form fields or database schema were changed.
- Added `school_detail()` and `school_social_links()` helpers and switched public Blade templates to CMS Site Settings for school title, contact information and social links, with environment configuration fallbacks. The homepage and default metadata description now read the CMS description. SiteSetting model cache invalidation applies when values are saved.
- Registered a `UserPolicy` that permits user management only to active Spatie admins/super admins, or active legacy admins with no Spatie roles. An editor assigned through Spatie is denied even though the legacy `users.role` column defaults to `admin`; this closes the role bypass discovered during an independent security review.
- Local content changes: changed the published “Add New Article” placeholder to draft; changed the active gallery record “isaac” with a missing image to inactive; copied the verified `gate.jpeg` to `storage/app/public/gallery/school-entrance.jpeg` and created an active CMS gallery record titled “School entrance.” The original records remain in the CMS. Local database and storage media must accompany deployment; the copied file is inside ignored storage.
- Code files: `app/Filament/Resources/AdmissionResource.php`, `ContactMessageResource.php`, new `AdmissionInquiryResource.php` and its list/edit pages, `app/Policies/UserPolicy.php`, `app/Providers/AppServiceProvider.php`, `app/helpers.php`, public Blade layout/pages/partials using school configuration, `tests/Feature/CmsWorkflowTest.php`, and this progress file.
- Verification: focused SQLite tests cover public inquiry routing into the dedicated resource, separation from general messages, CMS settings cache/update reflected on Contact, gallery rendering with a real image, and editor denial versus both legacy and Spatie administrator access to user-management abilities. `php artisan view:cache` succeeded; full suite passed (6 tests, 33 assertions). All changed PHP files linted. Live data now has zero published placeholder news articles and one active gallery image with an existing file.
- Still pending: publish a verified real news article; add more authentic school photographs; test authenticated Filament browser flows for create/edit/delete, media uploads, setting saves, and submission review in a safe environment; check gallery dialog interaction and production deployment configuration. The code and local content fixes above do not by themselves establish that every CMS feature works in production.

## Phase 10 — GitHub publication

- Publishing the Laravel source to the `laravel-redesign` branch of `Isa-vital/makerere-competent-high-school`, based on the existing `main` commit. The legacy `main` branch is retained for review and migration planning.
- Added generated Filament public CSS and JS paths to `.gitignore`; these assets should be published during deployment with the project's Composer/Filament build steps. Local `.env`, database records and `storage/app/public` media are excluded from the source push and require deployment-specific configuration/data transfer.
- Source verification before publication: `php artisan test` passed (6 tests, 33 assertions), `php artisan view:cache` passed and changed PHP files passed lint.
