<?php
  /**
 * This file contains the HTML and JavaScript code for a manga search app.
 * The app allows users to search for manga titles and sort them by various criteria.
 * The app uses the AniList GraphQL API to fetch manga data.
 * The app also displays the total number of manga entries available on the AniList site.
 * 
 * The app uses Handlebars.js to dynamically generate HTML for the manga titles.
 * The app also uses jQuery and jQuery Cookie plugins for DOM manipulation and cookie management.
 * 
 * The app has the following features:
 * - Search for manga titles using a search field
 * - Sort manga titles by various criteria using a dropdown menu
 * - Display manga titles in a grid layout with cover images and titles
 * - Click on a manga title to go to a details page for that title
 * - Display the total number of manga entries available on the AniList site
 * - Implement infinite scrolling to load more manga titles as the user scrolls down the page
 * - Display an "About" button that links to an about page for the app
 */
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../pages/694200/styles.css">
  <title>Collect App</title>
</head>
<body>
  <!-- Title -->
  <h1>Manga Search App</h1>
  <div class="body-content">
    <div class="acc-btns">
      <button id="sign-in">Sign in</button>
      <button id="sign-up">Sign up</button>
    </div>
    <button id="about-btn">About</button>
    <span id="entries"></span>
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
          <option value=''>Trending</option>
          <option value="POPULARITY_DESC">Most Popular</option>
          <option value="POPULARITY">Least Popular</option>
          <option value="SCORE_DESC">Highest Score</option>
          <option value="SCORE">Lowest Score</option>
          <option value="START_DATE_DESC">Most Recent</option>
          <option value="START_DATE">Oldest</option>
          <option value="CHAPTERS_DESC">Most Chapters</option>
          <option value="TITLE_ENGLISH_DESC">Title (A to Z)</option>
          <option value="UPDATED_AT_DESC">Last Updated</option>
          <option value="UPDATED_AT">First Updated</option>
          <option value="SEARCH_MATCH" id="search-option">Search Results</option>
        </select>
      </div>
    </div>
    <span id="page">0</span>
    <span id="last-page">0</span>
    <span id="data"></span>
  </div>
  
</body>
<script>
  // JavaScript code to handle the single entry function and set cookie info.
  function clickHandler(id) {
    let sortBy = $('#sort-dropdown').val();
    // Set search value cookie if search field is not empty
    if ($('#search-field').val() != "") {
      sortBy = "SEARCH_MATCH";
      $.cookie('searchTerm', $('#search-field').val(), {path: '/'});
    }
    $.cookie('sortBy', sortBy, {path: '/'});
    window.location.href = `manga/id/${id}`;
  }

  $(document).ready(function() {
    // Const declarations
    const trendArr = Array("TRENDING_DESC","SCORE_DESC");
    const searchForm = $('#search-form');

    // Boolean to call function with double async
    let isLoading = false;

    if ($.cookie('login')) {
      $('.acc-btns').empty();
      $('.acc-btns').append(/*html*/`<button id="favorites"><i class='fas fa-heart'></button>`);
      $('.acc-btns').append(/*html*/`<button id="account"><i class='fas fa-user'></button>`);
      $('.acc-btns').append(/*html*/`<button id="sign-out">Sign out</button>`);
      $('.acc-btns').append(/*html*/`<div class="floating-acc-menu" style="display:none;transition:all,0.3s">
        <p>Welcome ${$.cookie('f_name')}</p>
        </div>`)
    }

    $('#favorites').on('click', function() {
      window.location.href = `favorites`;
    })

    // JavaScript code to handle the about button.
    $('#about-btn').on('click', function() {
      window.location.href = `about`;
    })

    $('#sort-dropdown').on('change', function() {
      $('#data').empty();
      $('#search-field').val("");
      $('#search-option').hide();
      apiQuery(sortBy=$(this).val());
    })

    $('#sign-in').on('click', function() {
      window.location.href = `sign-in`;
    })

    $('#sign-up').on('click', function() {
      window.location.href = `sign-up`;
    })

    $('#sign-out').on('click', function() {
      $.removeCookie('login', {path: '/'});
      $.removeCookie('user', {path: '/'});
      $.removeCookie('f_name', {path: '/'});
      $.removeCookie('l_name', {path: '/'});
      $.removeCookie('sortBy', {path: '/'});
      window.location.reload();
    })

    $('#account').on('click', function() {
      if ($('.floating-acc-menu').css('display') == 'block')
        $('.floating-acc-menu').css('display', 'none');
      else if ($('.floating-acc-menu').css('display') == 'none') {
        $('.floating-acc-menu').css('display', 'block');
      }
    })


    // Hide search option
    $('#search-option').hide();
    
    // Function to query the API for manga
    function apiQuery(sortBy, page=1) {
      let searchTerm = "";
      let vars = {};
      
      if (sortBy == "SEARCH_MATCH") {
        searchTerm = $('#search-field').val();
      }

      vars = {
        sortBy: sortBy,
        page: page,
      }

      if (searchTerm != "") {
        vars['search']= searchTerm;
      }
      if (sortBy == "") {
        vars['sortBy'] = trendArr;
      }

      $.post({
        url: 'https://graphql.anilist.co',
        dataType: 'json',
        data: {
          query: query,
          variables: vars
        },
        success: function(resp) {
          curPage = resp.data.Page.pageInfo.currentPage;
          if (curPage == 1) {
            $('#data').append('<div class="flex-grid"></div>')
          }

          // Create Handlebars template
          let template = Handlebars.compile(/*html*/`
            {{#each media}}
              <div class="flex-item" value={{id}}>
                <div class="img-container">
                  <button class="fas fav-btn fa-heart"></button>
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
          $('.flex-grid').append(template(resp.data.Page))
          $('#page').text(resp.data.Page.pageInfo.currentPage);
          $('#last-page').text(resp.data.Page.pageInfo.lastPage);
          if ($.cookie('login')) {
            $.post({
              url: '/694200/api/getFavorites',
              data: {
                userID: $.cookie('user'),
              },
              success: function(data) {
                data = JSON.parse(data).message;
                console.log(data);
                for (let i = 0; i < data.length; i++) {
                  $(`[value=${data[i].mangaID}]`).children('div').children('button').css('color', 'red');
                  $(`[value=${data[i].mangaID}]`).children('div').children('button').css('display', 'block');
                }
              }
            })
            $('.fav-btn').on('click', function() {
              if ($(this).css('color') == 'rgb(255, 255, 255)') {
                // console.log($(this).parent().parent().attr('value'));
                $.post({
                  url: '/694200/api/addToFavorites',
                  data: {
                    userID: $.cookie('user'),
                    mangaID: $(this).parent().parent().attr('value'),
                  },
                  success: function(data) {
                    data = JSON.parse(data).message;
                    console.log(data);
                  }
                })
                $(this).css('color', 'red');
              }
              else if ($(this).css('color') == 'rgb(255, 0, 0)') {
                $.post({
                  url: '/694200/api/rmFromFavorites',
                  data: {
                    userID: $.cookie('user'),
                    mangaID: $(this).parent().parent().attr('value'),
                  },
                  success: function(data) {
                    data = JSON.parse(data).message;
                    console.log(data);
                  }
                })
                $(this).css('color', 'white');
              }
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
          } else {
            $('.fav-btn').css('display', 'none');
          }
          isLoading = false;
        }
      })
    }
    
    // First create query string for stats
    let query = `
      query {
        SiteStatistics {
          manga (sort:[COUNT_DESC],perPage:1){
            nodes {
              count
            }
          }
        }
      }
    `

    // Fetch stats from api using jquery ajax
    $.post({
      url: 'https://graphql.anilist.co',
      dataType: 'json',
      data: {
        query: query,
        variables: {}
      },
      success: function(resp) {
        $('#entries').text('Total Manga Entries: '+resp.data.SiteStatistics.manga.nodes[0].count);
      }
    })

    // New query string for manga list
    query = `
      query ($sortBy:[MediaSort],$search:String,$page:Int){
        Page (page:$page,perPage:30) {
          pageInfo {
            total
            currentPage
            lastPage
          }
          media (type:MANGA,sort:$sortBy,isAdult:false,search:$search) {
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
    `

    // Run query based on cookie info
    if ($.cookie('sortBy')) {
      if ($.cookie('searchTerm')) {
        $('#search-field').val($.cookie('searchTerm'));
      } else {
        $('#sort-dropdown').val($.cookie('sortBy'));
      }
      apiQuery(sortBy=$.cookie('sortBy'));
    } else {
      $('#sort-dropdown').val("");
      apiQuery(trendArr);
    }

    // Handle search form submit
    searchForm.on('submit', function(e) {
      e.preventDefault();
      $('#data').empty();
      $('#search-option').show();
      $('#sort-dropdown').val("SEARCH_MATCH");
      apiQuery(sortBy="SEARCH_MATCH");
    })

    // Handle sort dropdown change
    $('#sort-dropdown').on('change', function() {
      $('#data').empty();
      $('#search-field').val("");
      $('#search-option').hide();
      apiQuery(sortBy=$(this).val());
    })
    
    // Handle infinite scrolling
    $(window).scroll(function() {

      // Second async layer
      if (isLoading) {
        return;
      }

      // Check if at bottom of page
      if($(window).scrollTop() + $(window).height() >= $(document).height()-50) {
        console.log('Reached bottom of page');
        let currentPage = parseInt($('#page').text());
        let lastPage = parseInt($('#last-page').text());
        console.log('currentPage:', currentPage);
        console.log('lastPage:', lastPage);
        if (currentPage < lastPage) {
          isLoading = true;
          if ($('#search-field').val() == "") {
            console.log('Calling apiQuery with sortBy:', $('#sort-dropdown').val(), 'and page:', currentPage + 1);
            apiQuery(sortBy=$('#sort-dropdown').val(),currentPage + 1);
          } else {
            sleep = (time) => {
              return new Promise(resolve => setTimeout(resolve, time));
            }
            apiQuery(sortBy="SEARCH_MATCH",currentPage + 1);
            sleep(100);
          }
        }
      }
    });

  });
</script>
</html>