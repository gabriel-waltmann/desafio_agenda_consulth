import { RouteRecordRaw } from "vue-router";

import IndexView from "../../views/IndexView.vue";
import ContactView from "../../views/contact/ContactView.vue";
import ContactNewView from "../../views/contact/ContactNewView.vue";

export default [
  { name: "index", path: '/', component: IndexView },
  { name: "contact-new", path: '/contact/new', component: ContactNewView },
  { name: "contact", path: '/contact/:id', component: ContactView },
] as RouteRecordRaw[];