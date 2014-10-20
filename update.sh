curl http://parltrack.euwiki.org/dumps/ep_meps_current.json.xz | xz -cd > ep_meps_current.json
jq "map(select(.active) | del(.changes))" ep_meps_current.json > ep_mep_active.json
php json2csv.php
git commit -am "update `date`"
git push
