import React, { useState, useEffect } from "react";
import SnippetCard from "./SnippetCard";

const SnippetList = () => {
    const [snippets, setSnippets] = useState([]);
    const [searchQuery, setSearchQuery] = useState("");
    const [user, setUser] = useState(null);

    useEffect(() => {
        const fetchSnippets = async () => {
            try {
                setUser(window.__USER__);

                const response = await fetch("/api/snippets");
                if (!response.ok) throw new Error("Failed to fetch snippets");

                const data = await response.json();
                setSnippets(data);
            } catch (error) {
                console.error("Error fetching snippets:", error);
            }
        };

        fetchSnippets();
    }, []);

    const filteredSnippets = snippets.filter((snippet) =>
        snippet.title.toLowerCase().includes(searchQuery.toLowerCase())
    );

    return (
        <div className="bg-gray-800 rounded-lg p-6">
            <div className="p-4 bg-gray-700 rounded-t-lg">
                <input
                    type="text"
                    placeholder="Search snippets..."
                    value={searchQuery}
                    onChange={(e) => setSearchQuery(e.target.value)}
                    className="w-full bg-white p-2 rounded text-black"
                />
            </div>
            <div className="bg-gray-900 p-4 rounded-b-lg">
                {filteredSnippets.map((snippet) => (
                    <SnippetCard
                        key={snippet.id}
                        snippet={snippet}
                        user={user}
                        setSnippets={setSnippets}
                    />
                ))}
            </div>
        </div>
    );
};

export default SnippetList;
