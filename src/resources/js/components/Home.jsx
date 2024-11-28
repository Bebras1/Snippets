import React from "react";
import SnippetList from "./SnippetList";

const Home = ({ user }) => {
    return (
        <div className="bg-gray-900 text-gray-100 min-h-screen">
            <div className="container mx-auto p-6 bg-gray-800 rounded-lg shadow-md">
                <SnippetList isAuthenticated={Boolean(user)} user={user} />
            </div>
        </div>
    );
};

export default Home;
