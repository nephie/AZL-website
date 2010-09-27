<?php

$holidays['nieuwjaarsdag'] = strtotime('1 January');
$holidays['nieuwjaarsdag2'] = strtotime('2 January');
$holidays['pasen'] = easter_date();
$holidays['paasmaandag'] = strtotime('+1 day',$holidays['pasen']);
$holidays['olhhemelvaart'] = strtotime('+39 days',$holidays['pasen']);
$holidays['pinksteren'] = strtotime('+49 days',$holidays['pasen']);
$holidays['pinkstermaandag'] = strtotime('+1 day',$holidays['pinksteren']);

$algemenegebruikers = "GG_Algemene_gebruikers";
$agemenegebruikersdn= "CN=GG_Algemene_gebruikers,OU=Groepen,DC=stadskliniek,DC=lokeren,DC=be";
$ordergroup = "app_maaltijdbestellen";
$externgroup = "gg_maaltijdbestellen_extern";
$ordergroupdn = 'CN=app_maaltijdbestellen,OU=Applicaties,OU=Groepen,DC=stadskliniek,DC=lokeren,DC=be';
$ordergroupdnoc = 'CN=GG_OCMW_EVERYONE,OU=AZL Broodjes,OU=My Business,DC=ocmw,DC=lokeren,DC=local';


$promotionarticlesection = 1;

?>
