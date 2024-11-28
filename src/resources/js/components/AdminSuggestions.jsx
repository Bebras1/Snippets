import React, { useEffect, useState } from "react";

const AdminSuggestions = () => {
    const [suggestions, setSuggestions] = useState([]);

    useEffect(() => {
        const fetchSuggestions = async () => {
            try {
                const response = await fetch("/api/suggestions");
                const data = await response.json();
                setSuggestions(data);
            } catch (error) {
                console.error("Failed to fetch suggestions:", error);
            }
        };

        fetchSuggestions();
    }, []);

    const handleAction = async (id, action) => {
        try {
            const response = await fetch(`/suggestions/${id}/${action}`, {
                method: "POST",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
            });

            if (response.ok) {
                setSuggestions((prev) => prev.filter((s) => s.id !== id));
            } else {
                console.error(`Failed to ${action} suggestion`);
            }
        } catch (error) {
            console.error(`Error during ${action} suggestion:`, error);
        }
    };

    return (
        <div className="admin-suggestions">
            <h2>Pending Suggestions</h2>
            {suggestions.map((suggestion) => (
                <div key={suggestion.id} className="suggestion">
                    <h3>{suggestion.snippet_title}</h3>
                    <pre>{suggestion.suggested_code}</pre>
                    <p>{suggestion.comment}</p>
                    <button
                        onClick={() => handleAction(suggestion.id, "approve")}
                        className="approve-button"
                    >
                        Approve
                    </button>
                    <button
                        onClick={() => handleAction(suggestion.id, "reject")}
                        className="reject-button"
                    >
                        Reject
                    </button>
                </div>
            ))}
        </div>
    );
};

export default AdminSuggestions;
