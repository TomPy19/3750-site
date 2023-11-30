<?php
  
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../pages/694200/styles.css">
  <title>Collect App</title>
  <style>
    #about-btn {
      margin-bottom: .5rem;
    }
  </style>
</head>
<body>
  <!-- Title -->
  <h1 id=title></h1>
  <div class="body-content">
    <div class="acc-btns"></div>
    <div class="input-container">
      <!-- Dropdown for sorting data -->
      <button id="about-btn">About</button>
      <div class="sort">
        <label for="sort-dropdown">Sort By:</label>
        <select name="sort-dropdown" id="sort-dropdown">
          <option value='date-added-asc'>Date Added ↓</option>
          <option value='date-added-dec'>Date Added ↑</option>
          <option value="alph-a-z">Alphabetical (A-Z)</option>
          <option value="alph-z-a">Alphabetical (Z-A)</option>
        </select>
      </div>
    </div>
    <span id="page">0</span>
    <span id="last-page">0</span>
    <span id="data"></span>
  </div>
</body>
<script>
  function clickHandler(id) {
    let sortBy = $('#sort-dropdown').val();
    $.cookie('sortby', sortBy, {path: '/'});
    window.location.href = `manga/id/${id}`;
  }

  $(document).ready(function () {
    if (!$.cookie('login')) {
      window.location.href = '/694200/sign-in';
    }
    let query = `
      query ($ids: [Int]) {
        Page (page:1,perPage:100) {
          media(type:MANGA,id_in:$ids) {
            id
            title {
                english
                romaji
            }
            coverImage {
                extraLarge
            }
          }
        }
      }
    `;
    $('#about-btn').on('click', function() {
      window.location.href = '/694200/about';
    })
    $('#data').append('<div class="flex-grid"></div>');
    if ($.cookie('login')) {
      $('.acc-btns').append(/*html*/`<button id="home"><i class='fas fa-home'></button>`);
      $('.acc-btns').append(/*html*/`<button id="account"><i class='fas fa-user'></button>`);
      $('.acc-btns').append(/*html*/`<button id="sign-out">Sign out</button>`);
      $('.acc-btns').append(/*html*/`<div class="floating-acc-menu" style="display:none;transition:all,0.3s">
        <p>Welcome ${$.cookie('f_name')}</p>
      </div>`)
      $('#sign-out').click(function() {
        $.removeCookie('login', {path: '/'});
        $.removeCookie('user', {path: '/'});
        $.removeCookie('f_name', {path: '/'});
        $.removeCookie('l_name', {path: '/'});
        $.removeCookie('sortby', {path: '/'});
        window.location.href = '/694200/';
      })
      $('#title').append(/*html*/`<p>${$.cookie('f_name')}'s Favorites</p>`);
    }
    $('#home').click(function () {
      window.location.href = '/694200/';
    })
    if ($.cookie('sortby')) {
      $('#sort-dropdown').val($.cookie('sortby'));
    }
    $('#sort-dropdown').change(function() {
      $.cookie('sortby', $(this).val(), {path: '/'});
      window.location.reload()
    })
    $.post({
      url: '/694200/api/getFavorites',
      data: {
        userID: $.cookie('user'),
      },
      success: function(data) {
        data = JSON.parse(data).message
        let ids = [];
        for (var i = 0; i < data.length; i++) {
          ids.push(data[i].mangaID)
        }
        if (ids.length == 0) {
          $('.flex-grid').append(/*html*/`
            <div class="flex-item">
              <p>You have no favorites!</p>
            </div>
          `)
          return;
        } else {
          if ($('#sort-dropdown').val() == 'alph-a-z' || $('#sort-dropdown').val() == 'alph-z-a') {
            query = `
              query ($ids: [Int]) {
                Page (page:1,perPage:100) {
                  media(type:MANGA,id_in:$ids,sort:TITLE_ENGLISH) {
                    id
                    title {
                        english
                        romaji
                    }
                    coverImage {
                        extraLarge
                    }
                  }
                }
              }
            `;
          } else if ($('#sort-dropdown').val() == 'date-added-asc') {
            ids = ids.reverse();
          }
        }
        console.log($('#sort-dropdown').val())
        console.log(ids)
        $.post({
          url: 'https://graphql.anilist.co',
          datatype: 'json',
          data: {
            query: query,
            variables: {
              ids: ids
            }
          },
          success: function(data) {
            console.log(data)
            if ($('#sort-dropdown').val() == 'date-added-asc' || $('#sort-dropdown').val() == 'date-added-dec') {
              temp = []
              for (var i = 0; i < ids.length; i++) {
                for (var j = 0; j < data.data.Page.media.length; j++) {
                  if (data.data.Page.media[j].id == ids[i]) {
                    temp.push(data.data.Page.media[j])
                  }
                }
              }
              data.data.Page.media = temp;
            } else if ($('#sort-dropdown').val() == 'alph-z-a') {
              data.data.Page.media = data.data.Page.media.reverse();
            }
            let template = Handlebars.compile(/*html*/`
              {{#each media}}
                <div class="flex-item" value={{id}}>
                  <div class="img-container">
                    <button class="fas fav-btn fa-heart" style="color:red"></button>
                    <img onclick=clickHandler({{id}}) src="{{coverImage.extraLarge}}" alt="{{#if title.english}}{{title.english}}{{else}}{{title.romaji}}{{/if}}">
                  </div>
                  {{#if title.english}}
                    <p onclick=clickHandler({{id}})>{{title.english}}</p>
                  {{else}}
                    <p onclick=clickHandler({{id}})>{{title.romaji}}</p>
                  {{/if}}
                </div>
              {{/each}}
            `)
            $('.flex-grid').append(template(data.data.Page))
            $('.fav-btn').on('click', function() {
              $(this).parent().parent().remove();
              $.post({
                url: '/694200/api/rmFromFavorites',
                data: {
                  userID: $.cookie('user'),
                  mangaID: $(this).parent().parent().attr('value')
                },
                success: function(data) {
                  message = JSON.parse(data).message;
                  console.log(message)
                }
              })
            })
            $('.img-container > img').on('mouseenter', function() {
              $(this).css('opacity', '0.5');
              if ($(this).parent().children('button').css('color') == 'rgb(255, 255, 255)') {
                $(this).parent().children('button').css('display', 'block');
              }
            })
            $('.img-container > img').on('mouseleave', function() {
              $(this).css('opacity', '1');
              if ($(this).parent().children('button').css('color') == 'rgb(255, 255, 255)') {
                $(this).parent().children('button').css('display', 'none');
              }
            })
            $('.img-container > button').on('mouseenter', function() {
              $(this).css('display', 'block');
              $(this).siblings('img').css('opacity', '0.5');
            })
            $('.fav-btn').each(function() {
              if ($(this).css('color') == 'rgb(255, 255, 255)') {
                $(this).css('display', 'none');
              } else {
                $(this).css('display', 'block');
              }
            })
          }
        })
      }
    })
    
  })
  
</script>
</html>