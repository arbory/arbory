export default class Navigator {
    constructor() {
        this.registerEventListeners();
    }

    registerEventListeners() {
        jQuery('.type-constructor').on('nestedfieldscreate', (...params) => {
            console.log('added constructor item', params);
        });

        jQuery('.navigator').on('click', 'li a', (e) => {
            let target = jQuery(e.target);

            let ref = target.data('reference');

            console.log('reference', ref);
        });
    }
}