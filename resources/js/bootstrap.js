import _ from 'lodash';
window._ = _;

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

let token = document.head.querySelector('meta[name="csrf-token"]');
let csrf_token = token ? token.content:false;
/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */


 import Echo from 'laravel-echo'

 import pusherJs from 'pusher-js';
 window.Pusher = pusherJs;
 
 window.Echo = new Echo({
     broadcaster: 'pusher',
     key: import.meta.env.VITE_PUSHER_APP_KEY,
     wsHost: import.meta.env.VITE_PUSHER_APP_HOST,
     wssHost: window.location.hostname,
     wsPort: 80,
     wssPort: 443,
     disableStats: true,
     cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
     encrypted: import.meta.env.VITE_APP_ENCRYPTED,
     enabledTransports: ['ws','wss'],
     forceTLS: import.meta.env.VITE_APP_FORCETLS,
     auth: {
         headers: {
             Authorization: 'Bearer ' + csrf_token
         },
     }
 });