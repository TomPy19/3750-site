<?php
$words = file('./pages/658013/words.txt', FILE_IGNORE_NEW_LINES);
// print_r($words);
$vowel_counts = [];
foreach ($words as $word) {
  $vowel_count = preg_match_all('/[aeiou]/i', $word);
  if (!isset($vowel_counts[$vowel_count])) {
    $vowel_counts[$vowel_count] = [];
  }
  $vowel_counts[$vowel_count][] = $word;
}
foreach ($vowel_counts as $vowel_count => $words) {
  usort($words, function ($a, $b) {
    return strlen($a) - strlen($b);
  });
  $vowel_counts[$vowel_count] = $words;
}
header('Content-Type: application/json');
echo json_encode($vowel_counts);