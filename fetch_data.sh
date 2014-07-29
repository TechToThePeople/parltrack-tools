#!/bin/sh

wget 'http://parltrack.euwiki.org/dumps/ep_meps_current.json.xz' && rm ep_meps_current.json
unxz ep_meps_current.json.xz
