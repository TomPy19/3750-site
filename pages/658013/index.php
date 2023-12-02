<?php
// Read the file and split it into words
$words = file('words.txt', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

// Initialize the associative array
$vowel_counts = [];

// Loop over the words
foreach ($words as $word) {
  // Count the vowels in the word
  $count = preg_match_all('/[aeiou]/i', $word);

  // Add the word to the appropriate array
  if (!isset($vowel_counts[$count])) {
    $vowel_counts[$count] = [];
  }
  $vowel_counts[$count][] = $word;
}

// Sort the words in each array by length
foreach ($vowel_counts as $count => $words) {
  usort($words, function($a, $b) {
    return strlen($a) - strlen($b);
  });
  $vowel_counts[$count] = $words;
}

// Generate the buttons
foreach ($vowel_counts as $count => $words) {
  echo "<button class='vowel-btn' data-vowels='$count'>$count</button>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style.css">
  <title>Exam #2</title>
</head>
<body>
  <div id="drop-area"></div>
  <ul id="word-list"></ul>
  <p id="word-count">0</p>
</body>
<script>
  $(document).ready(function() {
    $('.vowel-btn').on('click', function() {
      var count = $(this).data('vowels');
      $.get('get_words.php', {count: count}, function(words) {
        $('#word-list').empty();
        words.forEach(function(word) {
          var li = $('<li>').text(word).attr('draggable', 'true');
          $('#word-list').append(li);
        });
      });
    });

    $('#drop-area').on('dragover', function(e) {
      e.preventDefault();
    });

    $('#drop-area').on('drop', function(e) {
      e.preventDefault();
      var word = e.originalEvent.dataTransfer.getData('text');
      var li = $('<li>').text(word);
      $(this).append(li);
      var count = $('#word-count').text();
      $('#word-count').text(parseInt(count) + 1);
    });

    $('#word-list').on('dragstart', 'li', function(e) {
      e.originalEvent.dataTransfer.setData('text', $(this).text());
    });
  });
</script>
</html>