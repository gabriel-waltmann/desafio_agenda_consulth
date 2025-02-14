import './bootstrap';
import { createApp } from 'vue';

import index from "../views/App.vue";
import { router } from './router';

createApp(index)
  .use(router)
  .mount("#app")