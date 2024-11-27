#!/bin/bash

docker compose up -d
symfony server:start -d
docker ps
