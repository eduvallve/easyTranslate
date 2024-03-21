/**
 * UI JS code
*/

const cfgMdUi = {
    selectors: {
        menu: {
            languageSwitcher: '.md-menu_language-switcher'
        }
    },
    classes: {
    }
}

/** Basic class */
class BasicComponent {
    constructor(el) {
        this.el = el;
    }
}

/** Class for Language Switchers  */
class LanguageSwitcher extends BasicComponent {
    constructor(el) {
        super (el);
        this.init();
    }

    init() {
        this.addEventListeners();
    }

    addEventListeners() {
        this.el.addEventListener('change', event => {
            document.cookie = `md_lang_cookie=${event.target.value};path=/;`;
            window.location = window.location.href.split("?")[0];
        });
    }
}

/**
 * Initialize first load class instances
 */

window.onload = function() {
    document.querySelectorAll(cfgMdUi.selectors.menu.languageSwitcher).forEach(languageSwitcher => {
        new LanguageSwitcher(languageSwitcher);
    });
}