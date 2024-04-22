import React, { useState } from "react";
import axios from "axios";

const MessageInput = ({ rootUrl, sendMessage }) => {
    const [message, setMessage] = useState("");

    const handleMessageChange = (e) => {
        setMessage(e.target.value);
    };

    const handleKeyPress = (e) => {
        if (e.key === 'Enter') {
            sendMessage(message);
            setMessage("");
        }
    };

    const handleSendClick = () => {
        if (message.trim() === "") {
            alert("Please enter a message!");
            return;
        }

        sendMessage(message);
        setMessage("");
    };

    return (
        <div className="input-group">
            <input
                onChange={handleMessageChange}
                onKeyPress={handleKeyPress}
                autoComplete="off"
                type="text"
                className="form-control"
                placeholder="Message..."
                value={message}
            />
            <div className="input-group-append">
                <button
                    onClick={handleSendClick}
                    className="btn btn-primary"
                    type="button">
                    Send
                </button>
            </div>
        </div>
    );
};

export default MessageInput;
