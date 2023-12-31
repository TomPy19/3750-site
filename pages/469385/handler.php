<?php
header("Content-type: text/xml");
echo "<?xml version=\"1.0\" ?>\n";
echo "<response>";

// If the cookie is not set, create new files. Otherwise, use existing files.
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

// Write the number to the appropriate file.
function writeToPages($num) {
  global $armFile, $fibFile, $primeFile, $noneFile;
  $putInFile = false;
  if ($num == "") {
    return;
  }
  if (isArmstrong($num)) {
    fwrite($armFile, $num."\n");
    $putInFile = true;
  } 
  if (isFibonacci($num)) {
    fwrite($fibFile, $num."\n");
    $putInFile = true;
  }
  if (isPrime($num)) {
    fwrite($primeFile, $num."\n");
    $putInFile = true;
  } 
  if (!$putInFile) {
    fwrite($noneFile, $num."\n");
  }
}

// Check if the number is Armstrong
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

// Check if the number is Fibonacci
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

// Check if the number is Prime
function isPrime($num){
  if ($num == 1) {
    return 0;
  }
  for ($i = 2; $i <= $num/2; $i++) {
    if ($num % $i == 0) {
      return 0;
    }
  }
  return 1;
}

// Check if the numInput is set in the POST request.
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