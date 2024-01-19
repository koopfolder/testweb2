window.Vue = require('vue');
Vue.config.productionTip = false;
window.axios = require('axios');

import { Plugin } from 'vue-fragment';
Vue.use(Plugin);

import vueNumeralFilterInstaller from 'vue-numeral-filter';
Vue.use(vueNumeralFilterInstaller, { locale: 'en-gb' });

import loading from 'vue-loading-overlay';
import 'vue-loading-overlay/dist/vue-loading.css';
Vue.use(loading);

Vue.component('statistic-keyword-component', require('./components/dashboard/StatisticKeywordComponent.vue'));
Vue.component('most-viewed-statistics-component', require('./components/dashboard/MostViewedStatisticsComponent.vue'));

/* Frontend */
Vue.component('ncds1-component', require('./components/frontend/Ncds1Component.vue'));
Vue.component('ncds2-component', require('./components/frontend/Ncds2Component.vue'));
Vue.component('ncds3-component', require('./components/frontend/Ncds3Component.vue'));
Vue.component('ncds4-component', require('./components/frontend/Ncds4Component.vue'));
Vue.component('ncds5-component', require('./components/frontend/Ncds5Component.vue'));
Vue.component('ncds6-component', require('./components/frontend/Ncds6Component.vue'));
Vue.component('ncds7-component', require('./components/frontend/Ncds7Component.vue'));
Vue.component('ncds2-list-component', require('./components/frontend/Ncds2ListComponent.vue'));
Vue.component('notable-books-component', require('./components/frontend/NotableBooksComponent.vue'));
/* Thaihealth-Watch */
Vue.component('th-watch1-component', require('./components/frontend/ThWatch1Component.vue'));
Vue.component('th-watch2-component', require('./components/frontend/ThWatch2Component.vue'));
Vue.component('th-watch3-component', require('./components/frontend/ThWatch3Component.vue'));
Vue.component('th-watch4-component', require('./components/frontend/ThWatch4Component.vue'));
Vue.component('th-watch5-component', require('./components/frontend/ThWatch5Component.vue'));
Vue.component('th-watch6-component', require('./components/frontend/ThWatch6Component.vue'));
Vue.component('th-watch7-component', require('./components/frontend/ThWatch7Component.vue'));
Vue.component('th-watch8-component', require('./components/frontend/ThWatch8Component.vue'));
Vue.component('panel-discussion-list-component', require('./components/frontend/PanelDiscussionListComponent.vue'));
Vue.component('interesting-point-list-component', require('./components/frontend/InterestingPointListComponent.vue'));
Vue.component('health-trends-list-component', require('./components/frontend/HealthTrendsListComponent.vue'));





const app = new Vue({
    el: '#app'
});