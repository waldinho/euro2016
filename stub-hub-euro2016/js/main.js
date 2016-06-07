// var LANG_DATA_PATH = SH.icmsServer + '/content-content/europeanfootball/data/language_data.json';
// var MATCH_DATA_PATH = SH.icmsServer + '/content-content/europeanfootball/data/matches.json';
var LANG_DATA_PATH = "data/language_data.json";
var MATCH_DATA_PATH = "data/matches.json";

var options = {
    lang: 'en',
    tab: 'group'
};
var langData;
var matchData;

// Handlebars plugins
Handlebars.registerHelper("if_eq", function(context, options) {
    if (context == options.hash.compare) {
        return options.fn(this);
    }
    return options.inverse(this);
});

function fetchLangData(lang, callback) {
    if (langData) {
        return callback(langData[lang]);
    }

    $.getJSON(LANG_DATA_PATH, function(json) {
        langData = json;
        return callback(langData[lang]);
    });
}

function fetchMatchData(type, callback) {
    if (matchData) {
        return callback(matchData[type]);
    }

    $.getJSON(MATCH_DATA_PATH, function(json) {
        matchData = json;
        return callback(matchData[type]);
    });
}

function formatDate(dateString, lang, copy) {
    var dateTime = dateString.split('T');
    var date = dateTime[0];
    var time = dateTime[1];
    var dateParts = date.split('-');
    var day = String(parseInt(dateParts[2]));
    var year = parseInt(dateParts[0]);
    var daySecondDigit = parseInt(dateParts[2].slice(-1));
    var dayPostfix = copy.dayPostfix[daySecondDigit];
    
    if (day === '11' || day === '12' || day === '13' || !dayPostfix) {
        dayPostfix = copy.dayPostfix['_'];
    }

    var month = copy.months[dateParts[1]];
    return day + dayPostfix + ' ' + month + ' - ' + time;
}

function setLanguage(event) {
    var lang = $(event.currentTarget).val();
    window.location.href = '?#lang=' + lang + '&tab=' + options.tab;
}

function parseHash() {
    var hash = String(window.location.hash).replace(/^#/, '');
    var obj = {};
    var hashParts = hash.split('&');

    for (var i = 0; i < hashParts.length; i++) {
        var keyVal = hashParts[i].split('=');
        obj[keyVal[0]] = keyVal[1];
    }
    if (window.location.hash.indexOf('&faq')>-1) {
        $('html, body').animate({
            scrollTop: $(document).height() + 1000
        }, '200');
    }
    return obj;
}

function init() {
    function isIe() {
        if (window.navigator.userAgent.indexOf('MSIE ') > -1) return true;
        return false;
    }
    var hashOptions = parseHash();
    options = $.extend({}, options, hashOptions);
    fetchLangData(options.lang, function(langData) {
        renderHeader("content-header", {
            lang: options.lang,
            copy: langData
        })
        renderTabs("content-tabs", {
            lang: options.lang,
            tab: options.tab,
            copy: langData
        })
        renderTabContent("content-content", {
            lang: options.lang,
            tab: options.tab,
            copy: langData
        })
        renderLightbox("content-lightbox", {
            lang: options.lang,
            copy: langData
        })
        accordion();
        if (!isIe()) $('.eufr16-hero .eufr16-languagePicker').addClass('eufr16-languagePicker-styled');
    })
    $('body').attr('class', 'eufr16-lang-' + options.lang);
}

function renderLightbox(selector, options) {
    var tmpl = "Handlebars-Template-lightbox";
    var source = document.getElementById(tmpl).innerHTML;
    var template = Handlebars.compile(source);
    document.getElementById(selector).innerHTML = template({
        lang: options.lang,
        tab: options.tab,
        copy: options.copy
    });
}

function renderHeader(selector, options) {
    var tmpl = "Handlebars-Template-header";
    var source = document.getElementById(tmpl).innerHTML;
    var template = Handlebars.compile(source);
    document.getElementById(selector).innerHTML = template(options);
    $('#languagePicker').on('change', setLanguage);
}

function renderTabs(selector, options) {
    var tmpl = "Handlebars-Template-tabs";
    var source = document.getElementById(tmpl).innerHTML;
    var template = Handlebars.compile(source);
    document.getElementById(selector).innerHTML = template({
        lang: options.lang,
        tab: options.tab,
        copy: options.copy
    });
}

function renderTabContent(selector, options) {
    var tmpl = "Handlebars-Template-howto";
    if (options.tab == 'group' || options.tab == 'knockout') {
        tmpl = "Handlebars-Template-matches";
    }

    var source = document.getElementById(tmpl).innerHTML;
    var template = Handlebars.compile(source);
    var copy = options.copy;

    if (options.tab == 'howto') {
        document.getElementById(selector).innerHTML = template({
            lang: options.lang,
            tab: options.tab,
            copy: options.copy
        });
    } else {
        fetchMatchData(options.tab, function(matchData) {
            var matches = []
            for (var i = 0; i < matchData.length; i++) {
                var groupData = matchData[i];
                var games = [];

                for (var g = 0; g < groupData.games.length; g++) {
                    var gameData = groupData.games[g];
                    games.push({
                        "number": copy.gameLabel + ' ' + gameData.number,
                        "gameNumber": copy.gameLabel + ' ' + gameData.gameNumber,
                        "buyUrl": gameData.buyUrl,
                        "sellUrl": gameData.sellUrl,
                        "final": gameData.final,
                        "teams": [{
                            "name": copy.teams[gameData.teams[0]].name,
                            "shortName": copy.teams[gameData.teams[0]].shortName,
                            "flag": copy.teams[gameData.teams[0]].flag
                        }, {
                            "name": copy.teams[gameData.teams[1]].name,
                            "shortName": copy.teams[gameData.teams[1]].shortName,
                            "flag": copy.teams[gameData.teams[1]].flag
                        }],
                        "date": formatDate(gameData.date, options.lang, copy),
                        "start": (gameData.start),
                        "homeGoals": (gameData.homeGoals),
                        "awayGoals": (gameData.awayGoals),
                        "aggregateScore": (gameData.aggregateScore),
                        "expired": (gameData.expired),
                        "gameOver": (gameData.gameOver),
                        "stadium": copy.stadiums[gameData.stadium].name,
                        "city": copy.stadiums[gameData.stadium].city
                    })
                }
                matches.push({
                    name: copy[groupData.name],
                    games: games
                })
            }
            document.getElementById(selector).innerHTML = template({
                lang: options.lang,
                tab: options.tab,
                matches: matches,
                copy: options.copy
            });
        })
    }
}

function accordion() {
    $(".eufr16-accordion-title").click(function() {
        $(this).children(".eufr16-accordion-image").toggle();
        $(this).next().slideToggle("slow");
    });
}

function overlayLightbox(e, foo, url) {
    var boxContent = '.eufr16-lightbox-' + foo;
    $('html, body').animate({
        scrollTop: 0
    }, '200');
    e.preventDefault ? e.preventDefault() : (e.returnValue = false);
    $('.eufr16-lightbox-cta').attr('href', url);
    $('.eufr16-lightbox-fade,' + boxContent).fadeIn(100);
    $('.eufr16-lightbox-fade, .eufr16-lightbox-close').on('click', function(event) {
        //Shut it down if user clicks anywhere but overlay
        $(boxContent + ', .eufr16-lightbox-fade').fadeOut();
    });
}


init();
$(window).on('load', init);
$(window).on('hashchange', init);