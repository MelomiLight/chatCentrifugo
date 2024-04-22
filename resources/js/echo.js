import Echo from 'laravel-echo';

window.Echo = new Echo({
    broadcaster: 'centrifugo',
    key: process.env.CENTRIFUGO_API_KEY,
    token: process.env.CENTRIFUGO_TOKEN_HMAC_SECRET_KEY,
    url: process.env.CENTRIFUGO_URL,
    sslKey: process.env.CENTRIFUGO_SSL_KEY,
    verify: process.env.CENTRIFUGO_VERIFY === 'true',
    useNamespace: process.env.CENTRIFUGO_USE_NAMESPACE === 'true',
    defaultNamespace: process.env.CENTRIFUGO_DEFAULT_NAMESPACE,
    privateNamespace: process.env.CENTRIFUGO_PRIVATE_NAMESPACE,
    presenceNamespace: process.env.CENTRIFUGO_PRESENCE_NAMESPACE,
});
