## Overview

Ports 11 content management modules from the CI admin panel to Filament v5, with forms and logic matching the original CI project exactly. This is Phase 1 of the admin panel migration.

## What's Included

### Filament Resources (CRUD)

| Resource | Route | Navigation Group | CI Form Fields (exact match) |
|---|---|---|---|
| Blog | `/admin/blogs` | Social | name, description (textarea), image, meta_* |
| News | `/admin/news` | Social | name, description (textarea), image, type, priority |
| Events | `/admin/events` | Social | name, description (textarea), image, meta_* |
| Case Studies | `/admin/case-studies` | Social | name, description (textarea), image, meta_* |
| Press Releases | `/admin/press-releases` | Media Relations | name, description (textarea), image, meta_* |
| Brand Guidelines | `/admin/brand-guidelines` | Media Relations | name, description (textarea), image, meta_* |
| Media Resources | `/admin/media-resources` | Media Relations | name, description (textarea), image, meta_* |
| Media Videos | `/admin/media-videos` | Media Relations | name, description (textarea), video_link, meta_* |
| Recommended | `/admin/recommended` | Social | name, description (textarea), image, meta_* |
| Random Acts | `/admin/random-acts` | Social | name, description (textarea), image, meta_* |
| Testimonials | `/admin/testimonials` | Social | customer_id, service_used, message, status (read-only) |

### CI-Exact Behavior

- **Description**: Textarea (not rich editor) — matches CI admin forms
- **No published toggle**: CI doesn't expose this in the admin form
- **No link field**: Auto-generated from title via `Str::slug()` — matches CI's `validate()` method
- **Image required only on create**: Matches CI's conditional `required` attribute
- **Auto-slug**: Title → slugified automatically, matching CI's slug generation logic
- **Blog slug prefix**: Blog posts get `blog/` prefix, matching CI's link generation

### Migrations

- `recommended` table (was missing from local DB)
- `random_acts_of_kindness` table (was missing from local DB)

### Model Updates

- `Recommended` — updated fillable to match CI schema (added description, image, published)
- `RandomActsOfKindness` — updated fillable to match CI schema (added description, image, published)

## Navigation Groups

Resources are organized into two groups matching the CI admin sidebar:
- **Social** — Blog, News, Events, Case Studies, Recommended, Random Acts, Testimonials
- **Media Relations** — Media Resources, Press Releases, Media Videos, Brand Guidelines

## Files Changed

- 11 Filament resource classes
- 30 Filament page classes (List, Create, Edit for each resource)
- 2 model updates (Recommended, RandomActsOfKindness)
- 2 new migrations (recommended, random_acts_of_kindness)

## Tests

All 29 existing tests pass.
