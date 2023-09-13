#!/usr/bin/env bash

compose_folder="./docker/composes"

compose="docker compose --env-file=./.env -f $compose_folder/docker-compose.yml"

if [[ $1 = "local" ]]; then
    extra="-f $compose_folder/docker-compose.yml"
fi

compose="$compose $extra up"

all_args=($@)
len=${#all_args[@]}
other_args=${all_args[@]:1:${len}-1}
compose="$compose $other_args"

eval "${compose}"
