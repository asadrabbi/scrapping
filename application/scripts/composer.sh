#!/usr/bin/env bash

. ./bin/parse_env.sh

composer_exec_cmd="docker exec ${CONTAINER_NAME} composer $@"
eval "${composer_exec_cmd}"
