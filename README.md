# BUBBA CAMPER

## DESCRIPTION

Bubba Camper is a modern web application designed to streamline camper van rentals. Built with a focus on user experience and clean aesthetics, it serves as a bridge between the host and travelers looking for their next adventure.

## LANGUAGES AND FRAMEWORKS

- Backend: Laravel 11, PHP 8.4, MySQL

- Frontend: Livewire 3 (Volt), Alpine.js, Tailwind CSS

- Payments: Stripe API (Checkout & Webhooks)

- Tooling: Vite, Laravel Breeze

- Libraries: intl-tel-input (v27), Flatpickr (Booking calendar)

## Key Features

### Secure Payment System

- Stripe Integration: Full implementation of Stripe Checkout for secure, SCA-compliant transactions.

- Webhook Architecture: Robust backend listener to handle asynchronous payment events (success/failure) and automatically update booking statuses in the database.

- Metadata Management: Seamless synchronization between Stripe sessions and internal database records for reliable tracking.

### Advanced Booking & UI

- Dynamic Pricing: Real-time cost calculation based on date ranges selected via an interactive calendar.

- Adaptive UI: Fully responsive design with native Dark Mode support and optimized Alpine.js transitions.

- International Phone Integration: Professional-grade phone input using intl-tel-input v27 with flag detection and E.164 formatting.

- Smart Data Sanitization: Strict client-side and server-side validation to ensure data integrity.

### Management & Security

- Role-Based Access Control (RBAC): Dedicated Dashboards for Admins (Stefano) and Users, ensuring secure data separation.

- CSRF Protection: Hardened security layers with specific exclusions for external API communications (Webhooks).

- SEO & Performance: Optimized for fast loading and search engine visibility.

## DEVELOPER

- [Nicola Stradiotto](https://github.com/NicolaStradiotto96)