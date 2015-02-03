curl http://parltrack.euwiki.org/dumps/ep_meps_current.json.xz | xz -cd > ep_meps_current.json
 fgrep '"active": true' ep_meps_current.json |  jq 'del(.changes)' > ep_mep_active.json
#nice -n19 jq "map(select(.active) | del(.changes))" ep_meps_current.json > ep_mep_active.json
nice -n19 php json2csv.php
git commit -am "update `date`"
git push
