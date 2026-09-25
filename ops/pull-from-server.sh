#!/usr/bin/env bash
# Обновляет рабочую копию с боевого сервера (сервер — источник правды, правки там делают и другие агенты).
# Запуск из любой папки репозитория:  bash ops/pull-from-server.sh
# Потом: git status / git diff → git add -A → git commit.
set -euo pipefail

HOST="${MEDCOMPANY_HOST:-ct94339@92.53.96.171}"
KEY="${MEDCOMPANY_SSH_KEY:-$HOME/.ssh/medcompany_92_53_96_171_ed25519}"

cd "$(git rev-parse --show-toplevel)"
if [ -n "$(git status --porcelain)" ]; then
  echo "Есть незакоммиченные изменения — сначала закоммить или убери их (git stash)." >&2
  exit 1
fi

tmp="$(mktemp -d)"
trap 'rm -rf "$tmp"' EXIT

# Тот же набор файлов, что и в .gitignore: код без ядра, upload, секретов, дампов и логов.
ssh -i "$KEY" -o BatchMode=yes "$HOST" 'bash -s' > "$tmp/src.tgz" <<'REMOTE'
set -e
cd ~/medcompany.rf/public_html
custom_components=()
for d in ./bitrix/components/*/; do
  [ "$d" = ./bitrix/components/bitrix/ ] || custom_components+=("${d%/}")
done
{
  find . \( -path ./bitrix -o -path ./upload -o -path ./.git -o -path ./cgi-bin \
            -o -path ./local/tools/logs -o -path ./local/ai-agent/logs -o -name .sass-cache \) -prune \
    -o -type f \
       ! -path ./.gitignore \
       ! -name '*.sql' ! -name '*.tar.gz' ! -name '*.zip' \
       ! -name 'sitemap*.xml' ! -name '~*sitemap*' \
       ! -path ./index.htm ! -path ./bitrix408 ! -path ./ct94339 ! -path './otp@gs.timeweb.net' \
       ! -path './local/tools/*.csv' \
       ! -path './local/tools/descriptions_import/*.csv' ! -path './local/tools/descriptions_import/*.json' \
       ! -path ./auth/manager.php ! -path ./local/ai-agent/config.php \
       -print0
  # *-02.svg в img — неиспользуемая копия head.svg с именем в cp1251, на Windows имя ломается.
  find ./bitrix/templates ./bitrix/php_interface "${custom_components[@]}" \
       \( -path './bitrix/templates/medcompany (1) (1)' -o -name .sass-cache \) -prune \
    -o -type f ! -name 'dbconn.php*' ! -path './bitrix/templates/*/img/*-02.svg' -print0
} | tar --null -T - -czf -
REMOTE

# Полная замена рабочей копии, чтобы удалённые на сервере файлы тоже пропали из репо.
find . -mindepth 1 -maxdepth 1 \
  ! -name .git ! -name ops ! -name README.md ! -name .gitignore ! -name .gitattributes \
  -exec rm -rf {} +
tar -xzf "$tmp/src.tgz"
git checkout -- local/ai-agent/config.example.php

git status --short | tail -n 40
echo "Готово: $(git status --short | wc -l) изменённых путей. Проверь diff и закоммить."
