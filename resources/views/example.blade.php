<html>

<head>
    <title>Centrifugo quick start</title>
</head>

<body>

<div id="messages"></div>

<script src="https://unpkg.com/centrifuge@5.0.1/dist/centrifuge.js"></script>
<script type="text/javascript">
    const container = document.getElementById('data');


    const centrifuge = new Centrifuge("ws://localhost:8000/connection/websocket", {
        token: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM3MjIiLCJleHAiOjE3MTQwMjQ0ODMsImlhdCI6MTcxMzQxOTY4M30.FKqKfLBeslNflvnY-UyXvasMMB28N6pFI6Kt08pgsDM"
    });

    centrifuge.on('connecting', function (ctx) {
        console.log(`connecting: ${ctx.code}, ${ctx.reason}`);
    }).on('connected', function (ctx) {
        console.log(`connected over ${ctx.transport}`);
    }).on('disconnected', function (ctx) {
        console.log(`disconnected: ${ctx.code}, ${ctx.reason}`);
    }).connect();

    const sub = centrifuge.newSubscription("channel");

    sub.on('publication', function (ctx) {
        console.log("Received message:", ctx);
        const messageData = ctx.data;
        const messageId = messageData.id;
        const userId = messageData.user_id;
        const text = messageData.text;
        const time = messageData.time;

        // Create a new message element
        const messageElement = document.createElement("div");
        messageElement.textContent = `Message ID: ${messageId}, User ID: ${userId}, Text: ${text}, Time: ${time}`;

        // Append the message to the messages container
        const messagesContainer = document.getElementById("messages");
        messagesContainer.appendChild(messageElement);
    }).on('subscribing', function (ctx) {
        console.log(`subscribing: ${ctx.code}, ${ctx.reason}`);
    }).on('subscribed', function (ctx) {
        console.log('subscribed', ctx);
    }).on('unsubscribed', function (ctx) {
        console.log(`unsubscribed: ${ctx.code}, ${ctx.reason}`);
    }).subscribe();

</script>
</body>

</html>
