# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is `ms_core` — a TYPO3 CMS extension (extension key: `ms_core`, composer name: `marekskopal/typo3-core`) that provides core configuration, TypoScript setup, and shared PHP classes for all of Marek Skopal's TYPO3 websites. It targets TYPO3 v14 and PHP 8.4.

## Development Commands

```sh
# Static analysis
vendor/bin/phpstan analyse

# Install dependencies
composer install
```

There is no test suite in this extension. PHP 8.4 is required (see `phpstan.neon`).

## Architecture

### Extension Key & Namespace

- Extension key: `ms_core`
- PHP namespace: `Skopal\MsCore\` → `Classes/`
- TypoScript set name: `MS - Core` (include as a TypoScript set in the TYPO3 backend)

### PHP Classes (`Classes/`)

All PHP classes override/extend TYPO3 core or third-party classes via `$GLOBALS['TYPO3_CONF_VARS']['SYS']['Objects']` (TYPO3's XClass mechanism) registered in `ext_localconf.php`:

- `Controller/NewsController` — extends `GeorgRinger\News\Controller\NewsController`
- `DataProcessing/LanguageMenuProcessor` — extends `TYPO3\CMS\Frontend\DataProcessing\LanguageMenuProcessor`
- `Plugins/ResultlistPlugin` — extends `Tpwd\KeSearch\Plugins\ResultlistPlugin`
- `RecordList/DatabaseRecordList` — extends `TYPO3\CMS\Backend\RecordList\DatabaseRecordList`
- `ViewHelpers/FlexImageViewHelper` — custom Fluid ViewHelper

### Configuration

- **`Configuration/Sets/MsCore/`** — TYPO3 v13+ Site Set:
  - `config.yaml` — set registration
  - `constants.typoscript` — TypoScript constants (paths default to `EXT:ms_web/...` — the consuming website extension)
  - `setup.typoscript` — main TypoScript setup (page object, `lib.*` objects, plugin configuration)
- **`Configuration/TCA/Overrides/`** — TCA modifications:
  - `tt_content.php` — adds custom fields (`section_hash`, `section_title`, `section_sorting`, `frame_css_class`) and modifies layout/frame_class items
  - `sys_template.php` — sys_template overrides
- **`Configuration/TsConfig/Page/rte.tsconfig`** — RTE page TSconfig
- **`Configuration/RTE/`** — CKEditor RTE preset (`Default.yaml`, `Processing.yaml`)
- **`ext_tables.sql`** — adds custom columns to `tt_content`

### TypoScript Structure (`setup.typoscript`)

Key `lib.*` objects defined globally:

- `lib.dynamicContent` — renders content from a specific colPos (used in page templates)
- `lib.pageParts` — FLUIDTEMPLATE for page part rendering (navigation, etc.)
- `lib.breadcrumb` — Bootstrap-styled breadcrumb with news article support
- `lib.language` — language switcher menu
- `lib.navigation` — main navigation using MenuProcessor

The `page` object (PAGE typeNum=0) uses FLUIDTEMPLATE. Template paths are configured via constants pointing to the consuming `ms_web` extension:
- `{$page.templatePage.templateRootPath}` for page layouts
- Template selection is driven by `backend_layout` field

### Frontend Assets (`Resources/Public/Javascript/`)

Included globally via TypoScript in `page.includeJSFooterlibs`:
- jQuery 4.0.0
- Bootstrap (with Popper, `bootstrap-popper.min.js`)
- lightGallery (all plugins)
- Custom `ms-gallery.js`
- Site-specific `main.js` (from `{$page.includePath.javascript}`)

### Fluid Templates (`Resources/Private/View/`)

- `Content/` — overrides for `fluid_styled_content` content elements (Text, Textmedia, Image, custom Bootstrap grid layouts)
- `Plugin/news/` — overrides for `georgringer/news` plugin templates
- `Plugin/indexed_search/` — overrides for indexed_search plugin

### Key Dependencies

- `georgringer/news` ^14.0 — news extension (XClassed controller)
- `tpwd/ke_search` ^7.0 — ke_search (XClassed ResultlistPlugin)
- `netresearch/rte-ckeditor-image` — RTE image handling
- `typo3/cms-fluid-styled-content` — base content rendering

### Relationship with `ms_web`

This extension (`ms_core`) provides shared infrastructure. Individual websites use a separate extension (`ms_web`) that provides site-specific page templates, CSS, and JS. The TypoScript constants in `ms_core` default all template paths to `EXT:ms_web/...`.
