<?php
ini_set('display_startup_errors', 1);
ini_set('display_errors', 1);
error_reporting(-1);

header("Content-type: text/xml");
echo "<?xml version=\"1.0\" ?>\n";
echo "<response>";


if (!intval($_POST['cookie'])) {
  $armFile = fopen('./num-classes/armstrong.txt', 'w');
  $fibFile = fopen('./num-classes/fibonacci.txt', 'w');
  $primeFile = fopen('./num-classes/prime.txt', 'w');
  $noneFile = fopen('./num-classes/none.txt', 'w');
  echo "Created new files.\n";
} else {
  $armFile = fopen('./num-classes/armstrong.txt', 'a');
  $fibFile = fopen('./num-classes/fibonacci.txt', 'a');
  $primeFile = fopen('./num-classes/prime.txt', 'a');
  $noneFile = fopen('./num-classes/none.txt', 'a');
  echo "Using existing files.\n";
}

function writeToPages($num) {
  global $armFile, $fibFile, $primeFile, $noneFile;

  if (isArmstrong($num)) {
    fwrite($armFile, $num."\n");
  } 
  if (isFibonacci($num)) {
    fwrite($fibFile, $num."\n");
  }
  else if (isPrime($num)) {
    fwrite($primeFile, $num."\n");
  } 
  else {
    fwrite($noneFile, $num."\n");
  }
}

function isArmstrong($num) {
  $armstrongNums = array(
    0,1,2,3,4,5,6,7,8,9,153,370,371,407,1634,8208,9474,54748,92727,93084,
    24678050,24678051,88593477,146511208,472335975,534494836,912985153,
    4679307774,32164049650,32164049651,40028394225,42678290603,
    44708635679,49388550606,82693916578,94204591914,28116440335967
  );
  if (in_array($num, $armstrongNums)) {
    return 1;
  } return 0;
}

function isFibonacci($num) {
  $temp = 5*$num*$num+4;
  if ((int)(sqrt($temp))*(int)(sqrt($temp))==$temp) {
    return 1;
  }
  $temp = 5*$num*$num-4;
  if ((int)(sqrt($temp))*(int)(sqrt($temp))==$temp) {
    return 1;
  }
  return 0;
}

function isPrime($num){
  if ($num == 1) return 0;
  for ($i = 2; $i <= sqrt($num); $i++){
    if ($num % $i == 0) return 0;
  }
  return 1;
}

if (isset($_POST['numInput'])) {
  $nums = explode(',',$_POST['numInput']);
  foreach ($nums as $num) {
    $num = trim($num);
    // echo $num."\n";
    writeToPages($num);
  }
} 

echo "</response>";

fclose($armFile);
fclose($fibFile);
fclose($primeFile);
fclose($noneFile);