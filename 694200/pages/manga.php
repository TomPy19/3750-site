<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../../style.css">
  <link rel="stylesheet" href="../../../includes/header.css">
  <script src="https://kit.fontawesome.com/cd8cff598a.js" crossorigin="anonymous"></script>
  <style>
    .body-content {
      display: flex;
      width: 70%;
      margin: 0 auto;
      flex-direction: column;
      align-items: center;
    }
    .banner {
      width: 100%;
      height: auto;
      overflow: hidden;
    }
    .banner img {
      width: 100%;
      max-width: none;
    }
    .title {
      font-size: 3rem;
      margin-bottom: -1rem;
    }
    /* put back button at the top right  */
    #back-btn {
      float: left;
      font-size: 1.5rem;
      position: relative;
      top: 0;
      right: 0;
      padding: 1rem;
      margin: .5rem;
      margin-bottom: -4rem;
      margin-right: -5rem;
      border-radius: 5px;
      border: none;
      background-color: bisque;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <script id="banner-template" type="text/x-handlebars-template">
    {{#if bannerImage}}
    <div class="banner">
      <img src="{{bannerImage}}" alt="{{title}}">
    </div>
    {{/if}}
  </script>
    
  </div>
  <script id="manga-template" type="text/x-handlebars-template">
    <div class="body-content">
      {{#if title.english}}
        <h1 class=title>{{title.english}}</h1>
      {{else}}
        <h1 class=title>{{title.romaji}}</h1>
      {{/if}}
      <img src="{{coverImage}}" alt="{{title}}">
      <br>
      <p>Start Date: {{startDate.year}}-{{startDate.month}}-{{startDate.day}}</p>
      <p>End Date: {{endDate.year}}-{{endDate.month}}-{{endDate.day}}</p>
      <br>
      <p>Genres: {{genres}}</p>
      <p>Average Score: {{averageScore}}</p>
      <p>Popularity: {{popularity}}</p>
      <br>
      <p>Status: {{status}}</p>
      <br>
      {{#unless (equals status "RELEASING")}}
        <p>Chapters: {{chapters}}</p>
        <p>Volumes: {{volumes}}</p>
      {{/unless}}
      <p id='description'></p>
      <br>
      <br>
      <br>
      <br>
    </div>
  </script>
</body>
<script>
  $(document).ready(function() {
    Handlebars.registerHelper('equals', function(arg1, arg2) {
      return arg1 == arg2;
    });
  // Create ajax request to https://graphql.anilist.co and display information about id from $_POST['id']
    $.post({
      url: 'https://graphql.anilist.co',
      dataType: 'json',
      data: {
        query: `
          query ($id: Int) {
            Media (id: $id, type: MANGA) {
              id
              title {
                romaji
                english
                native
              }
              description
              startDate {
                year
                month
                day
              }
              endDate {
                year
                month
                day
              }
              coverImage {
                large
                medium
              }
              bannerImage
              genres
              averageScore
              popularity
              chapters
              volumes
              status
              siteUrl
            }
          }
        `,
        variables: {
          id: <?php echo $id; ?>
        }
      },
      success: function(response) {
        console.log(response)
        var data = response.data.Media
        var title = data.title
        var description = data.description
        var startDate = data.startDate
        var endDate = data.endDate
        var coverImage = data.coverImage.large
        var bannerImage = data.bannerImage
        var genres = data.genres
        var averageScore = data.averageScore
        var popularity = data.popularity
        var chapters = data.chapters
        var volumes = data.volumes
        var status = data.status
        var siteUrl = data.siteUrl

        // Use handlebars to create template for banner
        var source = $('#banner-template').html()
        var template = Handlebars.compile(source)
        var context = {
          bannerImage: bannerImage,
          title: title
        }
        $('body').append(template(context))
        $('body').append('<button id="back-btn">Back</button>')

        // Use handlebars to create template for manga
        var source = $('#manga-template').html()
        var template = Handlebars.compile(source)
        var context = {
          title: title,
          startDate: startDate,
          endDate: endDate,
          coverImage: coverImage,
          bannerImage: bannerImage,
          genres: genres,
          averageScore: averageScore,
          popularity: popularity,
          chapters: chapters,
          volumes: volumes,
          status: status,
          siteUrl: siteUrl
        }
        $('body').append(template(context))
        $('#description').html(description)
        $('#back-btn').click(function() {
          window.history.back();
        })
      }
    })
  });
</script>
</html>