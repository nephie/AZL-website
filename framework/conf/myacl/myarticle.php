<?php

$myacl['myarticlesection']['rights']['addsection']['description'] = 'Sectie toevoegen';
$myacl['myarticlesection']['requesters']['userObject']['searchfunction'] = 'searchforacl';
$myacl['myarticlesection']['requesters']['userObject']['getfunction'] = 'getfromDisplayname';
$myacl['myarticlesection']['requesters']['groupObject']['searchfunction'] = 'getfromName';
$myacl['myarticlesection']['requesters']['groupObject']['getfunction'] = 'getfromName';

$myacl['myarticlesectionObject']['rights']['edit']['description'] = 'Sectie aanpassen';
$myacl['myarticlesectionObject']['rights']['delete']['description'] = 'Sectie verwijderen';
$myacl['myarticlesectionObject']['rights']['delete']['requires'] = 'edit';
$myacl['myarticlesectionObject']['rights']['manage_articlelinks']['description'] = 'Links met artikels beheren';
$myacl['myarticlesectionObject']['rights']['manage_articlelinks']['requires'] = 'edit';
$myacl['myarticlesectionObject']['requesters']['userObject']['searchfunction'] = 'searchforacl';
$myacl['myarticlesectionObject']['requesters']['userObject']['getfunction'] = 'getfromDisplayname';
$myacl['myarticlesectionObject']['requesters']['groupObject']['searchfunction'] = 'getfromName';
$myacl['myarticlesectionObject']['requesters']['groupObject']['getfunction'] = 'getfromName';

$myacl['myarticle']['rights']['addarticle']['description'] = 'Artikel toevoegen';
$myacl['myarticle']['requesters']['userObject']['searchfunction'] = 'searchforacl';
$myacl['myarticle']['requesters']['userObject']['getfunction'] = 'getfromDisplayname';
$myacl['myarticle']['requesters']['groupObject']['searchfunction'] = 'getfromName';
$myacl['myarticle']['requesters']['groupObject']['getfunction'] = 'getfromName';

$myacl['myarticleObject']['rights']['edit']['description'] = 'Artikel aanpassen';
$myacl['myarticleObject']['rights']['create_newversion']['description'] = 'Nieuwe versie maken';
$myacl['myarticleObject']['rights']['create_newversion']['requires'] = 'edit';
$myacl['myarticleObject']['rights']['manage_sectionlinks']['description'] = 'Links met secties beheren';
$myacl['myarticleObject']['rights']['manage_sectionlinks']['requires'] = 'edit';
$myacl['myarticleObject']['requesters']['userObject']['searchfunction'] = 'searchforacl';
$myacl['myarticleObject']['requesters']['userObject']['getfunction'] = 'getfromDisplayname';
$myacl['myarticleObject']['requesters']['groupObject']['searchfunction'] = 'getfromName';
$myacl['myarticleObject']['requesters']['groupObject']['getfunction'] = 'getfromName';

?>