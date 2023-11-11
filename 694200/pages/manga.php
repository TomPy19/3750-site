/**
 * This file displays information about a manga using data from the AniList API.
 * It uses Handlebars.js to create templates for the banner and manga information.
 * The information displayed includes the title, cover image, start and end dates, genres, average score, popularity, chapters, volumes, status, and description.
 * The back button allows the user to return to the previous page.
 */

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../../../style.css">
  <link rel="stylesheet" href="../../../includes/header.css">
  <script src="https://cdn.jsdelivr.net/npm/handlebars@latest/dist/handlebars.js"></script>
  <script src="https://kit.fontawesome.com/cd8cff598a.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" integrity="sha512-3j3VU6WC5rPQB4Ld1jnLV7Kd5xr+cq9avvhwqzbH/taCRNURoeEpoPBK9pDyeukwSxwRPJ8fDgvYXd6SkaZ2TA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <style>
    .body-content {
      display: flex;
      width: 70%;
      max-width: 1000px;
      margin: 0 auto;
      flex-direction: column;
      align-items: center;
      padding-bottom: 2.8rem;
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
  // Banner handlebars template
  <script id="banner-template" type="text/x-handlebars-template">
    {{#if bannerImage}}
    <div class="banner">
      <img src="{{bannerImage}}" alt="{{title}}">
    </div>
    {{/if}}
  </script>
    
  // Manga handlebars template
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
      <br>
      <p id='description'></p>
    </div>
  </script>
</body>
<script>
  // Helper for checking if two values are equal
  $(document).ready(function() {
    Handlebars.registerHelper('equals', function(arg1, arg2) {
      return arg1 == arg2;
    });
  // Create ajax request to https://graphql.anilist.co and display information about id from $id
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
                extraLarge
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
        var coverImage = data.coverImage.extraLarge
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
        // Add back button below banner
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