<!DOCTYPE html>

<head>
  <meta content="text/html; charset=utf-8" http-equiv="Content-Type">
  <title>StubHub - European Football Championship 2016</title>
  <link href="style/eufr16-styling.css" rel="stylesheet" type="text/css">
  <link href="style/HCo_fonts.css" rel="stylesheet" type="text/css">
  <meta name="description" content="Buy and sell tickets for European Football Championship in France 2016 on StubHub!">
  <meta name="keywords" content="tickets, Sports Tickets, football, France, 2016, Euro, European, buy, sell">
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" />
  <script src="js/handlebars-v4.0.5.js"></script>
  <script src="js/jquery-1.11.3.min.js"></script>
</head>

<body>

<?php
    $url = 'https://api.crowdscores.com/api/v1/matches?competition_id=267&api_key=bfffed6e955441008f49c642a2dcb8d4';
    $content = file_get_contents($url);
    $json = json_decode($content, true);
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_TIMEOUT, 5);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $data = curl_exec($ch);
    curl_close($ch);
    $date = time();
    file_put_contents('data/api.json', $data);
?>

  <div id="content-header"></div>
  <div id="content-tabs"></div>
  <div id="content-content"></div>
  <div id="content-lightbox"></div>

  <script id="Handlebars-Template-header" type="text/x-handlebars-template">
    <div class="eufr16-wrapper eufr16-bgblue">
      <div class="eufr16-hero">
        <div class="eufr16-languagePicker eufr16-group">
          <select id="languagePicker">
            <option value="" selected="" disabled="">{{copy.chooseLanguage}}</option>
            <option value="en">{{copy.english}}</option>
            <option value="de">{{copy.german}}</option>
            <option value="fr">{{copy.french}}</option>
            <option value="es">{{copy.spanish}}</option>
            <option value="it">{{copy.italian}}</option>
          </select>
        </div>
        <h1>{{{copy.headline}}}</h1>
        <p>{{copy.subline}}</p>
        <ul class="eufr16-logo-list">
          <li>
            <img src="images/eufr16_guarantee_logo_{{copy.images}}.png">
          </li>
          <li class="eufr16-border-white">
            <img src="images/eufr16_banner_logo_{{copy.images}}.png">
          </li>
          <li>{{{copy.freeListing}}}</li>
        </ul>
      </div>
    </div>
  </script>
  <script id="Handlebars-Template-tabs" type="text/x-handlebars-template">
   <div class="eufr16-tabs eufr16-group">
      <nav>
        <a href="?#lang={{lang}}&tab=group" {{#if_eq tab compare="group" }}class="eufr16-active" {{/if_eq}}>{{{copy.tabGroup}}}</a><div class="eufr16-nav-divider"></div>
        <a href="?#lang={{lang}}&tab=knockout" {{#if_eq tab compare="knockout" }}class="eufr16-active" {{/if_eq}}>{{{copy.tabKnockout}}}</a><div class="eufr16-nav-divider"></div>
        <a href="?#lang={{lang}}&tab=howto" {{#if_eq tab compare="howto" }}class="eufr16-active" {{/if_eq}}>{{{copy.tabHowto}}}</a>
      </nav>
    </div>
  </script>
  <script id="Handlebars-Template-matches" type="text/x-handlebars-template">
    <div class="eufr16-wrapper eufr16-bgwhite">
      <div class="eufr16-matchTable">
        {{#each matches}}
        <div class="eufr16-matchPool eufr16-group">
          <h3>{{{name}}}</h3> {{#each games}}
          {{#if final}}<div class="eufr16-greyline"></div>{{/if}}
          <div class="eufr16-matchBox {{#if final}}eufr16-final{{/if}}">
            <div class="eufr16-venue">
              <p class="eufr16-date">{{date}}
                <br><b>{{gameNumber}}</b></p>
              <img src="images/eufr16_location_icon{{#if final}}_{{final}}{{/if}}.png" alt="">
              <p><b>{{stadium}}</b>,
                <br />{{{city}}}</p>
            </div>
            <div class="eufr16-flags">
              <img src="images/eufr16_flag_{{teams.0.flag}}.png" alt="{{teams.0.name}}">
              <p><b>{{teams.0.name}}</b></p>
              {{#if gameOver}}
              <p><b>{{homeGoals}}</b></p>
              {{/if}}
            </div>
            <p>Vs</p>
            <div class="eufr16-flags">
              <img src="images/eufr16_flag_{{teams.1.flag}}.png" alt="{{teams.1.name}}">
              <p><b>{{teams.1.name}}</b></p>
              {{#if gameOver}}
              <p><b>{{awayGoals}}</b></p>
              {{/if}}

            </div>
              {{#if expired}}
              <div class="eufr16-tickets-closed">{{@root.copy.sellingExpired}}</div>
              {{else}}
              <a href="#" onclick="overlayLightbox(event, 'buy', '{{buyUrl}}')" class="eufr16-buy-btn">{{@root.copy.mbBuy}}</a><a href="#" onclick="overlayLightbox(event, 'sell', '{{sellUrl}}')" class="eufr16-sell-btn">{{@root.copy.mbSell}}</a>  
              {{/if}}
          </div>
          {{/each}}
        </div>
        {{/each}}
      </div>
    </div>
  </script>
  <script id="Handlebars-Template-howto" type="text/x-handlebars-template">
    <div class="eufr16-wrapper eufr16-howto">
      <div>
        <h2>{{{copy.buyTicketsHl}}}</h2>
        <p><b>{{copy.buyTicketsSubl}}</b></p>
        <ul class="eufr16-no_bullet eufr16-numList">
          {{#each copy.buyTicketsBullets}}
          <li>
            <img src="images/eufr16_howto_bullet{{number}}.png">
            <p>{{{buyTickets}}}</p>
          </li>
          {{/each}}
          <li class="eufr16-howto-list">
            <img src="images/eufr16_howto_buy.png">
            <p><a href="http://cache1.stubhubstatic.com/promotions/scratch/lt/ticket_buying_guide_{{lang}}.pdf" class="eufr16-howto-dl">{{{copy.buyTicketsDownloadCta1}}}</a>
              <br/><a href="?#lang={{lang}}&tab=howto&faq" class="eufr16-howto-cta-faq">{{copy.buyTicketsDownloadCta2}}</a></p>
          </li>
        </ul>
      </div>
    </div>
    <div class="eufr16-wrapper eufr16-howto eufr16-bggray">
      <div>
        <h2>{{{copy.sellTicketsHl}}}</h2>
        <p><b>{{copy.sellTicketsSubl}}</b></p>
        <ul class="eufr16-no_bullet eufr16-numList">
          {{#each copy.sellTicketsBullets}}
          <li>
            <img src="images/eufr16_howto_bullet{{number}}.png">
            <p>{{{sellTickets}}}</p>
          </li>
          {{/each}}
          <li class="eufr16-howto-list">
            <img src="images/eufr16_howto_sell.png">
            <p><a href="http://cache1.stubhubstatic.com/promotions/scratch/lt/ticket_selling_guide_{{lang}}.pdf" class="eufr16-howto-dl">{{{copy.sellTicketsDownloadCta1}}}</a>
              <br/><a href="?#lang={{lang}}&tab=howto&faq" class="eufr16-howto-cta-faq">{{copy.sellTicketsDownloadCta2}}</a></p>
          </li>
        </ul>
      </div>
    </div>
    <div class="eufr16-wrapper eufr16-howto eufr16-bgwhite">
      <div class="eufr16-whychoose">
        <h2>{{{copy.whyChoose}}}</h2>
        <img class="eufr16-howto-fanprotect eufr16-group" src="images/eufr16_howto_guarantee_en.png">
        <p>{{{copy.whyChooseDescr}}}</p>
        <p><b>{{copy.whyChooseBuySubl}}</b></p>
        <ul>
        {{#each copy.whyChooseBuyBullets}}
          <li>{{{whyChooseBuy}}}</li>
          {{/each}}
        </ul>
        <p><b>{{copy.whyChooseSellSubl}}</b></p>
        <ul>
        {{#each copy.whyChooseSellBullets}}
          <li>{{{whyChooseSell}}}</li>
          {{/each}}
        </ul>
      </div>
    </div>
    <div class="eufr16-wrapper eufr16-howto eufr16-bggray eufr16-accordion">
      <div>
        <h2><a name="lang={{lang}}&tab=howto&faq" class="eufr16-accordion-anchor">{{{copy.faq}}}</a></h2>
      </div>
      {{#each copy.faqQuestions}}
      <div class="eufr16-accordion-title">
        <img class="eufr16-accordion-image" src="images/eufr16_howto_faq_max.png">
        <img class="eufr16-accordion-image eufr16-hide" src="images/eufr16_howto_faq_min.png">
        <p>{{{faqQuestion}}}</p>
      </div>
      <div class="eufr16-bgwhite eufr16-rem-max-width eufr16-accordion-content eufr16-hide">
        <div class="eufr16-width800">
          <p>{{{faqAnswer}}}</p>
        </div>
      </div>
      {{/each}}
    </div>
  </script>
 <script id="Handlebars-Template-lightbox" type="text/x-handlebars-template">
    <div class="eufr16-lightbox-fade"></div>
    <div class="eufr16-lightbox-sell eufr16-lightbox">
      <div>
        <img class="eufr16-lightbox-close" src="images/eufr16_lightbox_close.png">
        <h2>{{{copy.lightboxSellHl}}}</h2>
        <p><b>{{{copy.lightboxSellSl}}}</b></p>
        <ul class="eufr16-no_bullet eufr16-numList">
          {{#each copy.lightboxSellBullets}}
          <li>
            <img src="images/eufr16_lightbox_tick.png">
            <p>{{{lightboxSell}}}</p>
          </li>
          {{/each}}
          <li class="eufr16-howto-list">
            <img src="images/eufr16_howto_sell.png">
            <p><a href="http://cache1.stubhubstatic.com/promotions/scratch/lt/ticket_selling_guide_{{lang}}.pdf" class="eufr16-howto-dl">{{{copy.sellTicketsDownloadCta1}}}</a>
              <br/><a href="?#lang={{lang}}&tab=howto&faq" class="eufr16-howto-cta-faq">{{{copy.sellTicketsDownloadCta2}}}</a></p>
          </li>
        </ul>
        <a href="#" class="eufr16-lightbox-cta">{{{copy.lightboxSellCTA}}}</a>
      </div>
    </div>
    <div class="eufr16-lightbox-buy eufr16-lightbox">
      <div>
        <img class="eufr16-lightbox-close" src="images/eufr16_lightbox_close.png">
        <h2>{{{copy.lightboxBuyHl}}}</h2>
        <p>{{{copy.lightboxBuySl}}}</p>
        <ul class="eufr16-no_bullet eufr16-numList">
          {{#each copy.lightboxBuyBullets}}
          <li>
            <img src="images/eufr16_lightbox_tick.png">
            <p>{{{lightboxBuy}}}</p>
          </li>
          {{/each}}
          <li class="eufr16-howto-list">
            <img src="images/eufr16_howto_sell.png">
            <p><a href="http://cache1.stubhubstatic.com/promotions/scratch/lt/ticket_buying_guide_{{lang}}.pdf" class="eufr16-howto-dl">{{{copy.buyTicketsDownloadCta1}}}</a>
              <br/><a href="?#lang={{lang}}&tab=howto&faq" class="eufr16-howto-cta-faq">{{{copy.buyTicketsDownloadCta2}}}</a></p>
          </li>
        </ul>
        <a href="#" class="eufr16-lightbox-cta">{{{copy.lightboxBuyCTA}}}</a>
      </div>
    </div>
  </script>

  <script type="text/javascript" src="js/main.js"></script>
</body>

</html>
