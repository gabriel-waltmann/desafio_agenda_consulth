import axios from 'axios';

const context = window as any;

context.axios = axios;

context.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
