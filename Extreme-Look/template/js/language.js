$(document).ready(function () { //Мой код
    var languageCol = document.body.querySelector('.ex-lang'),
        dropMenuLang = document.body.querySelector('[data-role="dropdownLanguage"]'),
        langMenuItemRu = document.body.querySelector('[data-role="ex-lang-ru"]'),
        langMenuItemEn = document.body.querySelector('[data-role="ex-lang-en"]'),
        enSite = '//extreme-look.ru.com',
        ruSite = '//extreme-look.ru';

    BX.bind(languageCol, 'click', function() {
        if(BX.isNodeHidden(dropMenuLang)) {
            BX.style(dropMenuLang, 'display', '');
        } else {
            BX.style(dropMenuLang, 'display', 'none');
        }
    });

    BX.bind(langMenuItemRu, 'click', function() {
        document.location.href = window.location.protocol + ruSite + window.location.pathname;
    });

    BX.bind(langMenuItemEn, 'click', function() {
        document.location.href = window.location.protocol + enSite + window.location.pathname;
    });
});