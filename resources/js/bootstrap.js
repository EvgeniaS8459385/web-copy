import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

import Prism from 'prismjs';
window.Prism = Prism;

import tinymce from 'tinymce/tinymce';
window.tinymce = tinymce;

import 'tinymce/icons/default/icons.min.js';
import 'tinymce/themes/silver/theme.min.js';
import 'tinymce/models/dom/model.min.js';
import 'tinymce/skins/ui/oxide/skin.js';
import 'tinymce/plugins/advlist';
import 'tinymce/plugins/code';
import 'tinymce/plugins/codesample';
import 'tinymce/plugins/emoticons';
import 'tinymce/plugins/emoticons/js/emojis';
import 'tinymce/plugins/link';
import 'tinymce/plugins/lists';
import 'tinymce/plugins/table';

import 'tinymce/skins/ui/oxide/skin.min.css';
import 'tinymce/skins/content/default/content.min.css';
import 'tinymce/skins/content/default/content.css';



import * as Popper from '@popperjs/core'
window.Popper = Popper

import 'bootstrap';

window.addEventListener('DOMContentLoaded', () => {
    tinymce.init({
        selector: '.tinymce-editor',
        promotion: false,
        plugins: 'advlist code emoticons link lists table codesample',
        extended_valid_elements: 'script[language|type|src]',
    });
})
