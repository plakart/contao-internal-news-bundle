# plakart/contao-internal-news-bundle

Internal news on the Contao back end dashboard (Contao 5.7, PHP 8.4). Only admins can
manage entries, all back end users can read them.

## Features

- Table `tl_plakart_internal_news`: `date`, `category`, `headline`, `text` (TinyMCE with bold and links)
- Categories (fixed in code, labels in `contao/languages/de`): security, hosting, redaktionstool, recht, web, vertrag, schulung
- Dashboard: the 5 latest entries plus an "all" link (`parseBackendTemplate` hook on `be_welcome`)
- Module `plakart_internal_news` (System): management, admin flag only. It cannot be granted through
  user groups (`disablePermissionChecks`, check in the `config.onload` callback, menu item hidden for non-admins)
- Module `plakart_internal_news_list`: read-only list of all entries for every back end user

## Installation on the server

1. Make the bundle available as a Git repository and add it to the installation's `composer.json`
   as a VCS repository (not a `path` repository):

   ```json
   "repositories": [
       { "type": "vcs", "url": "<git-url-of-the-bundle>" }
   ]
   ```

2. Require only this package (do not copy the local `composer.lock`):

   ```bash
   composer require plakart/contao-internal-news-bundle:dev-main
   ```

3. Create the database schema. Check the dry run first and make sure that only
   `CREATE TABLE tl_plakart_internal_news` comes from this bundle:

   ```bash
   php bin/console contao:migrate --dry-run
   php bin/console contao:migrate
   ```

   Alternatively, run the database update in the Contao install tool.

4. Install the assets (back end stylesheet), then clear and warm up the cache
   (`contao-setup` usually does both during `composer require`):

   ```bash
   php bin/console assets:install --symlink --relative
   php bin/console cache:clear --env=prod
   php bin/console cache:warmup --env=prod
   ```

5. Reload PHP-FPM if opcache has `validate_timestamps` disabled.

## Verification

- As admin: System → "Interne News" is available, entries can be created and edited.
- As an editor without the admin flag: no menu item, `?do=plakart_internal_news` is denied,
  the start page shows the news, the "all" link opens the read-only list.
