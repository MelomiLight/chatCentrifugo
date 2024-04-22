// ChatBox.jsx
import React, { useEffect, useRef, useState } from "react";
import axios from "axios";
import { Centrifuge } from "centrifuge";
import MessageInput from "./MessageInput.jsx";
import Message from "./Message.jsx";

const ChatBox = ({ rootUrl }) => {
    const userData = document.getElementById('main').getAttribute('data-user');
    const user = JSON.parse(userData);
    const webSocketChannel = `channel`;

    const [messages, setMessages] = useState([]);
    const scroll = useRef();

    const scrollToBottom = () => {
        scroll.current.scrollIntoView({ behavior: "smooth" });
    };

    const getMessages = async () => {
        try {
            const response = await axios.get(`${rootUrl}/messages`);
            setMessages(response.data);
            scrollToBottom();
        } catch (err) {
            console.log(err.message);
        }
    };

    useEffect(() => {
        getMessages();
    }, []);

    useEffect(() => {
        const centrifuge = new Centrifuge("ws://localhost:8000/connection/websocket", {
            token: "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJzdWIiOiIxMjM3MjIiLCJleHAiOjE3MTQwMjQ0ODMsImlhdCI6MTcxMzQxOTY4M30.FKqKfLBeslNflvnY-UyXvasMMB28N6pFI6Kt08pgsDM"
        });

        centrifuge.connect();

        const sub = centrifuge.newSubscription(webSocketChannel);

        sub.on('publication', function (ctx) {
            const messageData = ctx.data;
            const messageId = messageData.id;
            const userId = messageData.user_id;
            const text = messageData.text;
            const time = messageData.time;

            // Update messages state with the new message
            setMessages(prevMessages => [...prevMessages, { id: messageId, user_id: userId, text: text, time: time }]);

        }).subscribe();

        return () => {
            sub.unsubscribe();
            centrifuge.disconnect();
        };
    }, []);
    useEffect(() => {
        scrollToBottom();
    }, [messages]); // Trigger scroll when messages state updates
    const sendMessage = async (message) => {
        try {
            await axios.post(`${rootUrl}/message`, { text: message });
            // No need to fetch messages again, they should update via WebSocket
            scrollToBottom();
        } catch (err) {
            console.log(err.message);
        }
    };

    return (
        <div className="row justify-content-center">
            <div className="col-md-8">
                <div className="card">
                    <div className="card-header">Chat Box</div>
                    <div className="card-body"
                         style={{height: "500px", overflowY: "auto"}}>
                        {
                            messages?.map((message) => (
                                <Message key={message.id}
                                         userId={user.id}
                                         message={message}
                                />
                            ))
                        }
                        <span ref={scroll}></span>
                    </div>
                    <div className="card-footer">
                        <MessageInput rootUrl={rootUrl} sendMessage={sendMessage} />
                    </div>
                </div>
            </div>
        </div>
    );
};

export default ChatBox;
