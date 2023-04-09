#!/usr/bin/env bash

curl 'http://localhost:4000/graphql' -H 'Content-Type: application/json' --data-binary '{"query":"{\n  flag86\n}","variables":{}}'