<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
<<<<<<< Updated upstream:694200/pages/home.php
=======
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
    #about-btn:hover {
      opacity: 0.5;
    }
    .acc-btns {
      position: relative;
      left: 0;
      top: -3rem;
      display: flex;
      justify-content: flex-end;
      width: 100%;
      margin-bottom: -3rem;
    }
    .acc-btns button {
      font-weight: bold;
      padding: 0.5rem;
      margin: 0.5rem;
      border-radius: 5px;
      border: none;
      background-color: bisque;
      cursor: pointer;
    }

    #sign-in {
      background-color: #191F38;
      border: 1px solid bisque;
      color: bisque;
    }
    .acc-btns button:hover {
      opacity: 0.5;
    }
    @media screen and (max-width: 1000px) {
      .body-content {
        width: 90%;
      }
    }
    @media screen and (max-width: 800px) {
      .body-content {
        width: 100%;
      }
    }
>>>>>>> Stashed changes:pages/694200/pages/home.php
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
  <div class="body-content">
<<<<<<< Updated upstream:694200/pages/home.php
=======
    <div class="acc-btns">
      <button id="sign-in">Sign in</button>
      <button id="sign-up">Sign up</button>
    </div>
    <button id="about-btn">About</button>
>>>>>>> Stashed changes:pages/694200/pages/home.php
    <span id="entries"></span>
    <div class="input-container">
      <div class="search">
        <form id="search-form">
          <input type="text" name="search-field" id="search-field" placeholder="Search...">
          <button type="submit" id="search-btn"><i class="fas fa-search" style="position:relative;top: 1.5px;"></i></button>
        </form>
      </div>
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
  function clickHandler(id) {
    let sortBy = $('#sort-dropdown').val();
    if ($('#search-field').val() != "") {
      sortBy = "SEARCH_MATCH";
      $.cookie('searchTerm', $('#search-field').val(), {path: '/'});
    }
    $.cookie('sortBy', sortBy, {path: '/'});
    // console.log();
    window.location.href = `manga/id/${id}`;
  }
  $(document).ready(function() {
    $('#search-option').hide();
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

    searchForm.on('submit', function(e) {
      e.preventDefault();
      $('#data').empty();
      $('#search-option').show();
      $('#sort-dropdown').val("SEARCH_MATCH");
      apiQuery(sortBy="SEARCH_MATCH");
    })

<<<<<<< Updated upstream:694200/pages/home.php
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

    $('#sort-dropdown').on('change', function() {
      $('#data').empty();
      $('#search-field').val("");
      $('#search-option').hide();
      apiQuery(sortBy=$(this).val());
    })
=======
    $('#sign-in').on('click', function() {
      window.location.href = `sign-in`;
    })

    $('#sign-up').on('click', function() {
      window.location.href = `sign-up`;
    })

    // Hide search option
    $('#search-option').hide();
>>>>>>> Stashed changes:pages/694200/pages/home.php
    
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

    let isLoading = false;
    // if user scrolls to bottom, load 20 more entries
    $(window).scroll(function() {
      // console.log('Scroll event triggered');
      // console.log('scrollTop:', $(window).scrollTop());
      // console.log('window height:', $(window).height());
      // console.log('document height:', $(document).height()-50);

      if (isLoading) {
        return;
      }

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
            console.log('Calling apiQuery with sortBy: SEARCH_MATCH and page:', currentPage + 1);
            apiQuery(sortBy="SEARCH_MATCH",currentPage + 1);
            sleep(100);
          }
        }
      }

    });
  });
</script>
</html>