require('./bootstrap');

// Import modules...
import { createApp, h } from 'vue';
import { App as InertiaApp, plugin as InertiaPlugin } from '@inertiajs/inertia-vue3';
import { InertiaProgress } from '@inertiajs/progress';
//import * as Sentry from "@sentry/vue";
// import { Integrations } from "@sentry/tracing";

const el = document.getElementById('app');

const app = createApp({
    render: () =>
        h(InertiaApp, {
            initialPage: JSON.parse(el.dataset.page),
            resolveComponent: (name) => require(`./Pages/${name}`).default,
        }),
})
    .mixin({ methods: { route } })
    .use(InertiaPlugin);

// Sentry.init({
//     app,
//     dsn: process.env.MIX_SENTRY_DSN_PUBLIC,
//     integrations: [
//         //     new Integrations.BrowserTracing({
//         //         routingInstrumentation: Sentry.vueRouterInstrumentation(router),
//         //         tracingOrigins: ["localhost", "my-site-url.com", /^\//],
//         //     }),
//     ],
//     beforeSend: (event, hint) => {

//         if (process.env.MIX_APP_DEBUG == "true") {
//             console.error(hint.originalException || hint.syntheticException);
//             return null; // this drops the event and nothing will be sent to sentry
//         }
//         return event;
//     },
//     // Set tracesSampleRate to 1.0 to capture 100%
//     // of transactions for performance monitoring.
//     // We recommend adjusting this value in production
//     tracesSampleRate: 1.0,
// });



app.mount(el);

InertiaProgress.init({ color: '#4B5563' });
