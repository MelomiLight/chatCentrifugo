import React, { useEffect, useState } from "react";
import axios from "axios";

const Message = ({ userId, message }) => {
    const [user, setUser] = useState(null);

    // useEffect(() => {
    //     // Fetch user details based on user_id
    //     const fetchUser = async () => {
    //         try {
    //             const response = await axios.get(`/users/${message.user_id}`);
    //             setUser(response.data);
    //         } catch (error) {
    //             console.log("Error fetching user:", error);
    //         }
    //     };
    //
    //     fetchUser();
    // }, [message.user_id]);

    return (
        <div className={`row ${userId === message.user_id ? "justify-content-end" : ""}`}>
            <div className="col-md-6">
                {user && (
                    <small className="text-muted">
                        <strong>{user.name} | </strong>
                    </small>
                )}
                <small className="text-muted float-right">
                    {message.time}
                </small>
                <div className={`alert alert-${userId === message.user_id ? "primary" : "secondary"}`} role="alert">
                    {message.text}
                </div>
            </div>
        </div>
    );
};

export default Message;
