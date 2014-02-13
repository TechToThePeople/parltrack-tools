<?php
$file = "ep_meps_current";

$fp = fopen($file.'.csv', 'w');

$meps= json_decode (file_get_contents($file.'.json'));
$out=array ('epid','country','first_name','last_name','email','birthdate','gender','eugroup','party','phone','office','committee','delegation');

fputcsv($fp, $out);

foreach ($meps as $mep){
//if (!$mep->Constituencies[0]->end) {
if (!$mep->active) {
  echo "\nskip ". $mep->Name->full;
  continue;
}
//print_r($mep);
$out = array (
  $mep->UserID,
  $mep->Constituencies[0]->country,
  $mep->Name->sur,
  $mep->Name->family,
  $mep->Mail[0],
  substr($mep->Birth->date,0,10),
  $mep->Gender,
  $mep->Groups[0]->groupid);
  $out[]=$mep->Constituencies[0]->party;
print_r($mep);die ("toto");
  if (isset($mep->Addresses) && isset ($mep->Addresses->Brussels)) {
    $out[] = $mep->Addresses->Brussels->Phone;
    $out[] = $mep->Addresses->Brussels->Address->Office;
  } else {
   $out[]= null;
   $out[]= null;
  }
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
