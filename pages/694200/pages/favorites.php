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
    <button id="about-btn">About</button>
    <div class="input-container">
      <div class="search">
        <form id="search-form">
          <input type="text" name="search-field" id="search-field" placeholder="Search...">
          <button type="submit" id="search-btn"><i class="fas fa-search" style="position:relative;top: 1.5px;"></i></button>
        </form>
      </div>
      <!-- Dropdown for sorting data -->
      <div class="sort">
        <label for="sort-dropdown">Sort By:</label>
        <select name="sort-dropdown" id="sort-dropdown">
          <option value=''>Date Added</option>
        </select>
      </div>
    </div>
    <span id="page">0</span>
    <span id="last-page">0</span>
    <span id="data"></span>
  </div>
</body>
<script>
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
    $.post({
      url: '/694200/api/getFavorites',
      data: {
        userID: $.cookie('user'),
      },
      success: function(data) {
        data = JSON.parse(data).message
        console.log(data)
        let ids = [];
        for (var i = 0; i < data.length; i++) {
          ids.push(data[i].mangaID)
        }
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