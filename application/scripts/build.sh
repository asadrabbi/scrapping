#!/usr/bin/env bash

. ./bin/parse_env.sh

docker build -t "${CONTAINER_NAME}" -f ./docker/build.Dockerfile .
# docker tag "${CONTAINER_NAME}":latest
