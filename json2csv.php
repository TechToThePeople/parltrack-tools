<?php
$file = "ep_meps_current";
$counties = array();
$tt = json_decode (file_get_contents ("./countries.json"));
foreach ($tt as $t) {
  $countries [$t->name] = $t->iso_code;
  $countrySum [ $t->iso_code] = 0;
}

$fp = fopen($file.'.csv', 'w');

$meps= json_decode (file_get_contents($file.'.json'));
$out=array ('epid','country','first_name','last_name','email','birthdate','gender','eugroup','party','phone','office','committee','delegation', 'twitter');

fputcsv($fp, $out);

foreach ($meps as $mep){
//if (!$mep->Constituencies[0]->end) {
if (!$mep->active) {
  echo "\nskip ". $mep->Name->full;
  continue;
}
//print_r($mep);
if (!isset ($mep->Mail)) 
  $mep->Mail = [""];
$out = array (
  $mep->UserID,
  //$countries[$mep->Constituencies[0]->country],
  $mep->Constituencies[0]->country,
  $mep->Name->sur,
  $mep->Name->family,
  $mep->Mail[0],
  date("d/m/Y",strtotime(substr($mep->Birth->date,0,10))),
  $mep->Gender);
  if (isset( $mep->Groups)) {
    if (is_array ($mep->Groups[0]->groupid)) {
      $mep->Groups[0]->groupid = implode ("/",$mep->Groups[0]->groupid);
    }
    $out[]=$mep->Groups[0]->groupid;
  }
  $out[]=$mep->Constituencies[0]->party;
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
if (isset($mep->Twitter)){
  $out [] = $mep->Twitter[0];
}else {
  $out [] = '';
}

}
$countrySum[ $countries[$out[1]]] += 1;
fputcsv($fp, $out);

}

fclose ($fp);

print_r ($countrySum);
