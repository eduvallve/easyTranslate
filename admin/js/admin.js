/**
 * Write JS code here
 */

// Define config variables

const cfg = {
    selectors: {
        generalContainer: '#md-general',
        newLanguage: '.md-new-language',
        addLanguageSelect: '.md-new-language select',
        newLanguageBtn: '.md-add-language',
        languagesTableBody: 'table.md-language__table tbody'
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

    file_get_contents(filename) {
        fetch(filename).then((resp) => resp.text()).then(data => {
            // Initialize the document parser
            const parser = new DOMParser();
            const doc = parser.parseFromString(data, 'text/html');

            // Get the <body> element content
            const row = doc.querySelector('tbody').innerHTML;

            // Replace my empty element with the retrieved content
            this.languagesTableBody.innerHTML += row;
        });
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
        this.newLanguageBtn = this.el.querySelector(cfg.selectors.newLanguageBtn);
        this.newLanguageSelect = this.el.querySelector(`${cfg.selectors.newLanguage} select`);
        this.pluginUrl = this.el.dataset.pluginUrl;
        this.languagesTableBody = this.el.querySelector(cfg.selectors.languagesTableBody);
    }

    addEventListeners() {
        this.newLanguageBtn.addEventListener('click', this.addLanguageBtn.bind(this));
    }

    addLanguageBtn() {
        if (this.newLanguageSelect.value.trim() !== '') {
            const filename = `${this.pluginUrl}general.template.language.row.php?md_code=${this.newLanguageSelect.value}`;
            this.file_get_contents(filename);
        }
    }
}

/**
 * Initialize first load class instances
 */

window.onload = function() {
    const GENERALADMIN = new GeneralAdmin(document.querySelector(cfg.selectors.generalContainer));
}