#!/bin/sh
set -euo pipefail

export PATH="$PATH:$COMPOSER_HOME/vendor/bin"

exec "$@"
