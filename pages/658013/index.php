<!DOCTYPE html>
<html>
<head>
  <link rel="stylesheet" href="../style.css">
  <style>
    .word-list {
      max-height: 200px;
      overflow-y: scroll;
      border-bottom: bisque solid 1px;
      width: fit-content;
      min-width: 8rem;
      margin: 0 auto;
      padding-right: 1rem;
      padding-left: 1rem;
    }
    .vowel-count-button {
      background-color: bisque;
      border: none;
      color: #191f38;
      padding: .5rem 1rem;
      text-align: center;
      text-decoration: none;
      display: inline-block;
      font-size: 1.5rem;
      margin: 4px 2px;
      cursor: pointer;
      border-radius: .5rem;
    }
    #buttons {
      margin: 1rem;
    }
  </style>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
  <h1>Exam #2</h1>
  <div id="buttons"></div>
  <div id="word-list" class="word-list"></div>
  <script>
  $(document).ready(function() {
    $('#scroll').hide()
    $.get('/658013/api/get_words', function(data) {
      console.log(data)
      $.each(data, function(vowelCount, words) {
        $('<button>')
          .addClass('vowel-count-button')
          .text(vowelCount)
          .click(function() {
            if (vowelCount == 0 || vowelCount == 6) {
              $('#scroll').hide()
            } else {
              $('#scroll').show()
            }
            $('#word-list').empty();
            $.each(words, function(i, word) {
              $('#word-list').append($('<div>').text(word));
            });
          })
          .appendTo('#buttons');
      });
    });
  });
  </script>
  <br>
  <p id="scroll">Scroll to see more</p>
</body>
</html>
