# Global Agent Guidelines & Coding Standards

This ruleset defines universal coding standards, architecture principles, and best practices that the agent MUST follow across all sessions and projects without requiring manual reminders.

---

## 1. Skills Activation & Domain Knowledge

- **Proactively Activate Domain Skills**: This project provides specialized skills under `.agents/skills/` (or `skills/`). Whenever working on a specific domain, the agent MUST proactively view and activate the relevant skill (`SKILL.md`) before writing code—never wait until encountering errors:
  - **Laravel backend, models, Eloquent, migrations, queries**: Activate `laravel-best-practices`.
  - **Inertia.js & React pages, forms, routing, state**: Activate `inertia-react-development`.
  - **Tailwind CSS styling, responsive grid/flex layouts**: Activate `tailwindcss-development`.
  - **UI/UX component design and visual polish**: Activate `frontend-design`.
- Always follow the patterns, guidelines, and recommendations outlined in the respective skill documentation.

---

## 2. General Engineering & Architecture Principles

- **Single Responsibility & Lean Controllers**: Keep controllers focused strictly on request validation and response presentation. Offload business logic, external API integrations, and heavy data transformations to dedicated Action or Service classes (`app/Services/` or `app/Actions/`).
- **No Unrequested Files or Docs**: Do not create arbitrary documentation, READMEs, or scratch files unless explicitly requested by the user.
- **Convention Over Novelty**: Respect existing architecture and conventions in the codebase. Never introduce an unnecessary second way to do something when a pattern is already established.

---

## 3. PHP & Modern Laravel Standards (PHP 8.2+ / Laravel 11+)

### Class Imports & Namespaces
- **Top-Level Imports Only**: Always place `use` statements at the top of PHP files. **Never** use inline fully-qualified class names (FQCN) in the code (e.g., avoid `\Illuminate\Support\Facades\DB::transaction(...)` or `\App\Models\User::find(...)`; always add `use Illuminate\Support\Facades\DB;` and `use App\Models\User;` at the top).
- Applies uniformly to Facades, models, custom services, exceptions, Enums, and third-party vendor packages.

### Type Safety & Modern PHP Syntax
- **Strict Typing**: Always use explicit return type declarations and parameter types on every method and function (e.g., `public function execute(User $user, array $data): bool`).
- **Constructor Property Promotion**: Use PHP 8 constructor promotion for dependency injection (e.g., `public function __construct(protected OrderService $orderService) {}`).
- **Braces**: Always use curly braces for all control flow structures (`if`, `foreach`, `for`, `while`), including single-line statements.

### Eloquent & Database Performance
- **N+1 Query Prevention**: Always eager-load relationships (`with()`, `loadMissing()`) when records will access relations in views, Inertia props, or API resources.
- **Atomic Operations**: Wrap multiple database mutations within `DB::transaction(fn () => ...)` to preserve data consistency.
- **Safe Migrations & Foreign Keys**: Always define foreign key constraints with explicit cascade rules (e.g., `constrained()->cascadeOnDelete()`), index frequently filtered or joined columns, and write rollback-safe migrations.
- **Mass Assignment**: Explicitly specify `$fillable` (or guarded attributes) on all Eloquent models.

### Validation & Routing
- **Form Requests**: Use dedicated Form Request classes (`php artisan make:request ...`) for validation and authorization on mutating endpoints instead of inline controller validation.
- **Named Routes**: Always use named routes via `route('name')` or Ziggy route helpers; never hardcode URLs.

---

## 4. Frontend Standards (Inertia.js / React / Vue / TypeScript / Tailwind CSS)

### Navigation & Forms
- **SPA Routing**: Always use Inertia's `<Link href={route('...')} ...>` component or programmatic visits (`router.visit()`). Never use native HTML `<a>` tags for internal application navigation.
- **Form State Management**: Use Inertia's `useForm` hook for handling forms, submission lifecycles, validation errors, and dirty state.
- **Component Organization**: Keep full page views in `resources/js/Pages/` and modular, reusable UI components in `resources/js/Components/`.

### TypeScript & Styling
- **Type Safety**: Strictly define TypeScript types/interfaces for model payloads, Inertia page props, and component props. Avoid `any`.
- **Styling**: Use utility-first Tailwind CSS. Keep designs clean, responsive, and mobile-first. Reuse existing design system components before writing new one-off styles.
- **Consistent Icons**: Prefer a single coherent icon library (such as `lucide-react` or `lucide-vue-next`).

---

## 5. Environment & Command Execution (Windows / Laragon / Cross-Platform)

- **PowerShell / Windows Safety**: Be mindful of Windows environments (Laragon). Do NOT propose or run Bash-only commands (e.g., `grep`, `rm -rf`, `cat`). Use PowerShell-compatible syntax or cross-platform Artisan/Composer/NPM commands.
- **Non-Interactive Commands**: Always pass `--no-interaction` to Artisan commands.

