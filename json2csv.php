<?php
$file = "ep_meps_current";
$countries = array();
$sum = 0;
$tt = json_decode (file_get_contents ("./countries.json"));
foreach ($tt as $t) {
  $countries [$t->name] = $t->iso_code;
  $countrySum [ $t->iso_code] = 0;
}

$fp = fopen($file.'.csv', 'w');

$meps= json_decode (file_get_contents($file.'.json'));
$out=array ('epid','country','first_name','last_name','email','birthdate','gender','eugroup','party','phone','building','office','committee','substitute','delegation', 'twitter');

fputcsv($fp, $out);

foreach ($meps as $mep){

if (!$mep->active) {
  echo "\nskip ". $mep->Name->full;
  continue;
}
if (!isset( $mep->Birth)) {
//  echo "\npotential skip ghost ". $mep->Name->full;
//  continue;
}
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
    $out[] = $mep->Addresses->Brussels->Address->Building;
    $out[] = $mep->Addresses->Brussels->Address->Office;
  } else {
   $out[]= null;
   $out[]= null;
   $out[]= null;
  }
  if (is_array($out[6])) 
    $out[6] = implode('/',$out[6]);
if (isset($mep->Committees)) {
  $com = array();
  $sub = array();
  foreach ($mep->Committees as $c) {
    if ($c->end != "9999-12-31T00:00:00") {
      continue;
    }
    if ($c->role == "Substitute") {
      $sub [] = $c->abbr;
    } else {
      $com [] = $c->abbr;
    }
  }
  $out[] = implode(',',$com);
  $out[] = implode(',',$sub);
} else {
  $out [] = '';
  $out [] = '';
}
if (isset($mep->Delegations)){
  $del = array();
  foreach ($mep->Delegations as $d) {
    if ($d->end != "9999-12-31T00:00:00") {
      continue;
    }
    if (!empty($d->abbr)){
      $del [] = $d->abbr;
    } else {
      $del [] = str_replace (array ("Delegation for relations with the ","Delegation to the "),array ("",""),$d->Organization);
    }
  }
  $out[] = implode(',',$del);
} else {
  $out [] = '';
}if (property_exists($mep,"Twitter") && is_array($mep->Twitter)){
  $out [] = $mep->Twitter[0];
}else {
  $out [] = '';
}

$countrySum[ $countries[$out[1]]] += 1;
$sum +=1;
fputcsv($fp, $out);

}
print_r($countrySum);

echo "\nMEPs found $sum\n";

fclose ($fp);

