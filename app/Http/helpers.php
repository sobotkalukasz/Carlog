<?php
/*******************************
* My very usefull functions :) *
*******************************/


function sort_by_date ($a, $b){
  if ($a['date'] == '' && $b['date'] != '') return 1;
  if ($b['date'] == '' && $a['date'] != '') return -1;
  $t1 = strtotime($a['date']);
  $t2 = strtotime($b['date']);
  return $t1 - $t2;
}


/*********************
* Thousand separator *
**********************/

function withSpace($number, $i=0){
  return number_format($number, $i, '.', ' ');
}


/**********************************************
* Returns diffrence between two dates in days *
**********************************************/

function howManyDays($date){
  $diff = date_diff(date_create($date), date_create(date('Y-m-d')));
  return $diff->days;
}
