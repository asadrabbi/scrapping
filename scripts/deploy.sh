#!/bin/sh

get_git_branch() {
    echo "$(git symbolic-ref --short -q HEAD 2>/dev/null)"
}

run_on_local() {
    . ./scripts/parse_env.sh
    ./scripts/dcup.sh local -d --build
}

branch=$1
if [ -z "${branch}" ]; then
    branch="$(get_git_branch)"
fi

if [ "${branch}" = "local" ]; then
    run_on_local
fi
