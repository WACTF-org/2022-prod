#!/bin/bash
source /server/vars.conf

ENVIRONMENT=DEV /server/app &
ENVIRONMENT=PROD /server/app &
wait -n
pkill -P $$