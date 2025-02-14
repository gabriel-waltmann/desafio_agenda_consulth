import { createWebHistory } from "vue-router";
import { createRouter } from "vue-router";

import routes from "./routes";

export const router = createRouter({
  history: createWebHistory(),
  routes,
})