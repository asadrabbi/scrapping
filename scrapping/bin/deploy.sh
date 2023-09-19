#!/bin/sh

run_on_local() {
  ./bin/dcup.sh dev -d --build
}
target=$1

if [ "${target}" = "local" ]; then
  run_on_local
fi
