#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"
php import_products_v10.php --dry-run
php import_products_v10.php --apply
