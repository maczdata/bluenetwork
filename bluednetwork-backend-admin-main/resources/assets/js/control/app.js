require('../bootstrap');
window.Pikaday = require('pikaday');
window.EasyMDE = require('easymde');
import flatpickr from "flatpickr";
import 'alpine-magic-helpers'
import 'alpinejs'
import AlpineEditor from 'alpine-editor';
import moment from 'moment-timezone';
import 'moment-range';

function isMobile() {
    if (
        (window.screen.width <= 640) ||
        (window.matchMedia &&
            window.matchMedia('only screen and (max-width: 640px)').matches
        )
    ) {
        return true;
    }
    return false;
}
//let isMobile = window.matchMedia("only screen and (max-width: 760px)").matches;
window.chistelWrapper = () => {
    return {
        isMobile: isMobile(),
        //isSidebarOpen: this.isMobile
    }
}
