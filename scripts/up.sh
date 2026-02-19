#!/usr/bin/env bash
set -e

LOG="/tmp/$(basename "$PWD")-docker-up.log"

echo "[up] Starting docker compose (logs: $LOG)"
docker compose up -d --build >"$LOG" 2>&1 || {
  echo "[up] FAILED. Last 120 lines:"
  tail -n 120 "$LOG"
  exit 1
}

docker compose ps
