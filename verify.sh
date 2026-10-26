#!/usr/bin/env bash
# ---------------------------------------------------------------------------
# verify.sh — build every lab, run its reference exploit, confirm the flag.
# Usage:  ./verify.sh            (all labs)
#         ./verify.sh 03-render-farm   (one lab)
# Exit code 0 only if every selected lab yields its FLAG{...}.
# Requires: docker (with compose), python3, curl.
# ---------------------------------------------------------------------------
set -u
ROOT="$(cd "$(dirname "$0")" && pwd)"

# lab -> host port (matches each docker-compose.yml)
ports() { case "$1" in
  01-looking-glass) echo 8081;; 02-estate-sale) echo 8082;; 03-render-farm) echo 8083;;
  04-ledger) echo 8084;; 05-polyfill) echo 8085;; *) echo "";; esac; }

ALL=(01-looking-glass 02-estate-sale 03-render-farm 04-ledger 05-polyfill)
LABS=("$@"); [ ${#LABS[@]} -eq 0 ] && LABS=("${ALL[@]}")

pass=0; fail=0; SUMMARY=()
cya="\033[36m"; grn="\033[32m"; red="\033[31m"; off="\033[0m"

for lab in "${LABS[@]}"; do
  dir="$ROOT/labs/$lab"; port="$(ports "$lab")"
  if [ -z "$port" ] || [ ! -d "$dir" ]; then
    echo -e "${red}?? unknown lab: $lab${off}"; fail=$((fail+1)); SUMMARY+=("FAIL  $lab  (unknown)"); continue
  fi
  echo -e "${cya}== $lab  (http://127.0.0.1:$port) ==${off}"

  ( cd "$dir" && docker compose up --build -d ) >/dev/null 2>&1 || {
    echo -e "  ${red}build/start failed${off}"; fail=$((fail+1)); SUMMARY+=("FAIL  $lab  (build)"); continue; }

  # wait for the app (curl retries through connection-refused, no sleep needed)
  code=$(curl -s --retry 60 --retry-delay 1 --retry-connrefused --retry-all-errors \
              --connect-timeout 2 -o /dev/null -w "%{http_code}" "http://127.0.0.1:$port/" 2>/dev/null || echo 000)

  out="$(python3 "$dir/exploit/exploit.py" "http://127.0.0.1:$port" 2>&1)"
  f1="$(printf '%s' "$out" | grep -oE 'FLAG1\{[^}]+\}' | head -1)"
  f2="$(printf '%s' "$out" | grep -oE 'FLAG2\{[^}]+\}' | head -1)"

  if [ -n "$f1" ] && [ -n "$f2" ]; then
    echo -e "  ${grn}PASS${off}  access=$f1  proof=$f2"; pass=$((pass+1)); SUMMARY+=("PASS  $lab  $f1  $f2")
  else
    echo -e "  ${red}FAIL${off}  (http=$code, access=${f1:-MISSING} proof=${f2:-MISSING})"; printf '%s\n' "$out" | sed 's/^/    | /'
    fail=$((fail+1)); SUMMARY+=("FAIL  $lab")
  fi

  ( cd "$dir" && docker compose down ) >/dev/null 2>&1
done

echo; echo "==================== SUMMARY ===================="
for s in "${SUMMARY[@]}"; do echo "  $s"; done
echo "-------------------------------------------------"
echo "  passed=$pass  failed=$fail  of $((pass+fail))"
[ "$fail" -eq 0 ]
