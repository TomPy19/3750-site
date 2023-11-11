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

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style.css">
  <script src="https://cdn.jsdelivr.net/npm/handlebars@latest/dist/handlebars.js"></script>
  <script src="https://kit.fontawesome.com/cd8cff598a.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" integrity="sha512-3j3VU6WC5rPQB4Ld1jnLV7Kd5xr+cq9avvhwqzbH/taCRNURoeEpoPBK9pDyeukwSxwRPJ8fDgvYXd6SkaZ2TA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <style>
    .body-content {
      display: flex;
      width: 70%;
      margin: 0 auto;
      flex-direction: column;
      align-items: center;
    }
    #data {
      width: 100%;
    }
    table {
      border-collapse: collapse;
    }
    table, th, td {
      border: 1px solid bisque;
      width: 100%;
    }
    th, td {
      padding: 5px;
      width: fit-content;
    }
    img {
      width: 100px;
      border-radius: 8px;
      margin-bottom: 1rem;
    }
    span#entries {
      font-size: 1.5rem;
      margin: 1rem;
      align-self: flex-end;
    }
    div.search {
      display: flex;
      justify-items: center;
    }
    #search-form {
      display: flex;
      flex-wrap: none;
    }
    button#search-btn {
      border: none;
      background-color: transparent;
      cursor: pointer;
      position: relative;
      font-size: 1.25rem;
      left: -2rem;
      top: -.15rem;
    }
    input#search-field {
      padding: 0.5rem;
      border: 1px solid black;
      border-radius: 5px;
      margin-left: 0;
    }
    input#search-field:focus {
      outline: none;
    }
    input#search-field::placeholder {
      font-style: italic;
    }
    select#sort-dropdown {
      border: 1px solid black;
      border-radius: 5px;
    }
    .input-container {
      display: flex;
      width: 100%;
      align-items: flex-end;
      justify-content: space-between;
      margin-bottom: 1rem;
      flex-wrap: wrap;
    }
    label {
      margin-left: .5rem;
    }
    .flex-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
      grid-gap: 1rem;
    }
    .flex-item {
      text-align: center;
    }
    #page, #last-page {
      display: none;
    }
    .flex-item p {
      font-size: 1rem;
      margin: 0;
    }
    .flex-item img:hover {
      opacity: 0.5;
      cursor: pointer;
    }
    .flex-item p:hover {
      opacity: 0.5;
      cursor: pointer;
    }
    #about-btn {
      font-weight: bold;
      padding: 0.5rem;
      margin: 0.5rem;
      margin-bottom: -3rem;
      margin-left: 0rem;
      border-radius: 5px;
      border: none;
      background-color: bisque;
      cursor: pointer;
      align-self: flex-start;
    }
    @media screen and (max-width: 730px) {
      input#search-field {
        margin-left: 2.5rem;
      }
      .input-container {
        gap: 0.5rem;
        justify-content: center;
      }
    }
  </style>
  <title>Collect App</title>
</head>
<body>
  <!-- Title -->
  <h1>Manga Search App</h1>
  <div class="body-content">
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
    // 
    if ($('#search-field').val() != "") {
      sortBy = "SEARCH_MATCH";
      $.cookie('searchTerm', $('#search-field').val(), {path: '/'});
    }
    $.cookie('sortBy', sortBy, {path: '/'});
    window.location.href = `manga/id/${id}`;
  }

  $(document).ready(function() {
    // JavaScript code to handle the about button.
    $('#about-btn').on('click', function() {
      window.location.href = `about`;
    })

    // Hide search option
    $('#search-option').hide();
    
    // First query for stats
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

    // fetch stats
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

    // New query for manga list
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

    const trendArr = Array("TRENDING_DESC","SCORE_DESC");
    const searchForm = $('#search-form');

    // Handle search form submit
    searchForm.on('submit', function(e) {
      e.preventDefault();
      $('#data').empty();
      $('#search-option').show();
      $('#sort-dropdown').val("SEARCH_MATCH");
      apiQuery(sortBy="SEARCH_MATCH");
    })

    // Handle search form reset
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

    // Handle sort dropdown change
    $('#sort-dropdown').on('change', function() {
      $('#data').empty();
      $('#search-field').val("");
      $('#search-option').hide();
      apiQuery(sortBy=$(this).val());
    })
    
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
          Handlebars.registerHelper('addOne', function(value) {
            return value + 1;
          });
          curPage = resp.data.Page.pageInfo.currentPage;
          if (curPage == 1) {
            $('#data').append('<div class="flex-grid"></div>')
          }

          // Create Handlebars template
          let template = Handlebars.compile(`
            {{#each media}}
              <div class="flex-item">
                <img onclick=clickHandler({{id}}) src="{{coverImage.extraLarge}}" alt="{{#if title.english}}{{title.english}}{{else}}{{title.romaji}}{{/if}}">
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
          isLoading = false;
        }
      })
    }

    // Boolean to call function with double async
    let isLoading = false;
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