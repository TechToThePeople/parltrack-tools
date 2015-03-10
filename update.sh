curl http://parltrack.euwiki.org/dumps/ep_meps_current.json.xz | xz -cd > ep_meps_current.json
cat ep_meps_current.json | fgrep '"active": true' |  jq -c 'del(.changes)' | { echo '['; sed 's/$/,/'; echo '{}]'; }  > ep_mep_active.json 

#cat ep_meps_current.json | sed '1s/^\[//; n;d;' > ep_meps_separated.json
#cat ep_meps_current.json  | fgrep '"active": true' | sed 's/}$/},/' > tmp.json
#sed -i '1i[' tmp.json
#jq 'del(.changes)' > ep_mep_active.json
#nice -n19 jq "map(select(.active) | del(.changes))" ep_meps_current.json > ep_mep_active.json
nice -n19 php json2csv.php
git commit -am "update `date`"
git push
