# BUBBA CAMPER

## DESCRIPTION

Bubba Camper is a modern web application designed to streamline camper van rentals. Built with a focus on user experience and clean aesthetics, it serves as a bridge between the host and travelers looking for their next adventure.

## LANGUAGES AND FRAMEWORKS

- Backend: Laravel 11, PHP 8.4, MySQL

- Frontend: Livewire 3 (Volt), Alpine.js, Tailwind CSS

- Payments: Stripe API (Checkout & Webhooks)

- Tooling: Vite, Laravel Breeze

- Libraries: intl-tel-input (v27), Flatpickr (Booking calendar), FontAwesome (UI Icons & Navigation)

## KEY FEATURES

### Secure Payment System

- Stripe Integration: Full implementation of Stripe Checkout for secure, SCA-compliant transactions.

- Webhook Architecture: Robust backend listener to handle asynchronous payment events (success/failure) and automatically update booking statuses in the database.

- Metadata Management: Seamless synchronization between Stripe sessions and internal database records for reliable tracking.

### Document & Verification Workflow

- Secure Uploads: Integrated multi-file upload system (Driver License & ID Card) using Livewire's `WithFileUploads`.

- Real-time Validation: Client-side file type and size validation with dynamic feedback to ensure compliant uploads.

- Status Management: Automated booking status transitions (e.g., from 'pending documents' to 'awaiting review') ensuring a structured verification workflow.

- Interactive UX: Modal-based document management powered by Alpine.js with seamless state synchronization between the UI and server-side components.

### Advanced Booking & UI

- Dynamic Pricing: Real-time cost calculation based on date ranges selected via an interactive calendar.

- Adaptive UI: Fully responsive design with native Dark Mode support and optimized Alpine.js transitions.

- International Phone Integration: Professional-grade phone input using intl-tel-input v27 with flag detection and E.164 formatting.

- Smart Data Sanitization: Strict client-side and server-side validation to ensure data integrity.

### Management & Security

- Role-Based Access Control (RBAC): Dedicated Dashboards for Admins (Stefano) and Users, ensuring secure data separation.

- CSRF Protection: Hardened security layers with specific exclusions for external API communications (Webhooks).

- SEO & Performance: Optimized for fast loading and search engine visibility.

### Work in progress

- [ ] **Language Strings:** Move hardcoded UI strings to localization files (`lang/`) for future multi-language support.

## DEVELOPER

- [Nicola Stradiotto](https://github.com/NicolaStradiotto96)