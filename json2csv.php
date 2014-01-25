<?php
$file = "ep_meps_current";

$fp = fopen($file.'.csv', 'w');

$meps= json_decode (file_get_contents($file.'.json'));
$out=array ('epid','first_name','last_name','email','birthdate','gender','eugroup','phone','office','committee','delegation');

foreach ($meps as $mep){
//if (!$mep->Constituencies[0]->end) {
if (!$mep->active) {
  echo 'skip '. $mep->Name->full;
  continue;
}
//print_r($mep);
$out = array (
  $mep->UserID,
  $mep->Name->sur,
  $mep->Name->family,
  $mep->Mail[0],
  substr($mep->Birth->date,0,10),
  $mep->Gender,
  $mep->Groups[0]->groupid,
  $mep->Addresses->Brussels->Phone,
  $mep->Addresses->Brussels->Address->Office,
);
  if (is_array($out[6])) 
    $out[6] = implode('/',$out[6]);
if (isset($mep->Committees)) {
  $com = array();
  foreach ($mep->Committees as $c) {
    $com [] = $c->abbr;
  }
  $out[] = implode(',',$com);
} else 
  $out [] = '';
if (isset($mep->Delegations)){
  $del = array();
  foreach ($mep->Delegations as $d) {
    $del [] = $d->abbr;
  }
  $out[] = implode(',',$del);
} else {
  $out [] = '';
}
fputcsv($fp, $out);

}

fclose ($fp);
