As requested at the hackathon, small scripts I have lying around parltrack

Standing on giants shoulders.

update from parltrack
---------------
curl http://parltrack.euwiki.org/dumps/ep_meps_current.json.xz | xz -cd > ep_meps_current.json

json2csv.php
-------------

Extract some info from ep_current_meps.json dump and creates a csv from it

See a problem or want more columns? Pull Requests welcome and all that jazz.

Love data, put them in good hands and go out campaining

simpler json
------------
if you simply want to deal with active meps and remove all the change log
jq "map(select(.active) | del(.changes))" ep_meps_current.json > ep_mep_active.json


csvtool
----------

csvtool col 1,2,3,4,6-9 ep_meps_current.csv

Some stuff to analyse from the command line 
------------------
check for duplicates

csvtool col 4,3,1,2,8 ep_meps_current.csv | sort | uniq -c -d

count from a country
csvtool col 4,3,1,2,8 ep_meps_current.csv | sort | ag "Germany," | wc 
count from a country and a group
csvtool col 4,3,1,2,8 ep_meps_current.csv | sort | ag "Germany," | ag ",S&D" | wc

remove the wc to get the list (sorted by last name, like on the europarl website)
