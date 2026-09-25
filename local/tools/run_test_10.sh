#!/usr/bin/env bash
set -euo pipefail
cd "$(dirname "$0")"
php import_products_v10.php --dry-run --ids=626,627,683,695,716,727,782,788,794,798
php import_products_v10.php --apply --ids=626,627,683,695,716,727,782,788,794,798
