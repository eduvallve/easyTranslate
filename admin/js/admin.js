/**
 * Write JS code here
 */

// Define config variables

const cfg = {
    selectors: {
        generalContainer: '#md-general',
        addLanguage: '.md-new-language',
        addLanguageSelect: '.md-new-language select',
        addLanguageBtn: '.md-add-language'
    },
    classes: {
        newLanguage: 'md-new-language',
        newLanguageBtn: 'md-add-language'
    }
}

/** Basic class */
class BasicComponent {
    constructor(el) {
        this.el = el;
    }
}

/** Class for General Admin */
class GeneralAdmin extends BasicComponent {
    constructor(el) {
        super (el);
        this.init();
    }

    init() {
        this.setRefs();
        this.addEventListeners();
    }

    setRefs() {
        this.newLanguageBtn = this.el.getElementsByClassName(cfg.classes.newLanguageBtn)[0];
        this.newLanguageSelect = this.el.getElementsByClassName(cfg.classes.newLanguage)[0].getElementsByTagName('select')[0];
    }

    addEventListeners() {
        this.newLanguageBtn.addEventListener('click', this.addLanguageBtn.bind(this));
    }

    addLanguageBtn() {
        console.log(this.newLanguageSelect.value);
    }
}

/**
 * Initialize first load class instances
 */

window.onload = function() {
    const GENERALADMIN = new GeneralAdmin(document.querySelector(cfg.selectors.generalContainer));
}