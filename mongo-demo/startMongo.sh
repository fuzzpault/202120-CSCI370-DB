#!/bin/bash
docker run -i -t -p 27017:27017 -v ${PWD}/db:/data/db -d mongo:5.0.7-focal