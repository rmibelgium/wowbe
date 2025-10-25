#!/bin/bash

git pull --all
/home/extraweb/scripts/pushit.sh config1
ssh yoda "cd ~/Production/docker/radar-wowbe && ./build.sh && docker compose down && docker compose up -d"

