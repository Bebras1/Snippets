import React, { useState } from "react";
import CodeMirror from "@uiw/react-codemirror";
import { githubDark } from "@uiw/codemirror-theme-github";
import RatingStars from "./RatingStars";

const SnippetCard = ({ snippet, user, setSnippets }) => {
    const [expanded, setExpanded] = useState(false);
    const [editedCode, setEditedCode] = useState(snippet.code || "");
    const [comment, setComment] = useState("");

    const handleDelete = async () => {
        try {
            const response = await fetch(`/snippets/${snippet.id}`, {
                method: "DELETE",
                headers: {
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
            });

            if (response.ok) {
                setSnippets((prev) => prev.filter((s) => s.id !== snippet.id));
            } else {
                console.error("Failed to delete snippet");
            }
        } catch (error) {
            console.error("Error deleting snippet:", error);
        }
    };

    const handleSuggestion = async () => {
        try {
            const response = await fetch(`/snippets/${snippet.id}/suggest`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ suggested_code: editedCode, comment }),
            });

            if (!response.ok) throw new Error("Failed to submit suggestion");

            console.log("Suggestion submitted successfully");
            setComment("");
        } catch (error) {
            console.error("Error submitting suggestion:", error);
        }
    };

    return (
        <div className="p-4 bg-gray-800 rounded-lg mb-4 shadow-md">
            <div className="flex justify-between items-center">
                <div>
                    <strong>{snippet.title}</strong>{" "}
                    <span className="text-sm text-gray-400">
                        ({snippet.language || "N/A"})
                    </span>
                </div>
                <div className="flex items-center space-x-4">
                    <RatingStars snippet={snippet} />
                    <button
                        onClick={() => setExpanded(!expanded)}
                        className="text-gray-400 hover:text-gray-100"
                    >
                        {expanded ? "Collapse" : "Expand"}
                    </button>
                    {user && (
                        <button onClick={handleDelete} className="text-red-500 hover:text-red-700">
                            Delete
                        </button>
                    )}
                </div>
            </div>
            {expanded && (
                <div className="mt-4">
                    <CodeMirror
                        value={editedCode}
                        theme={githubDark}
                        onChange={(value) => setEditedCode(value)}
                        editable={!!user}
                    />
                    {user && (
                        <div className="mt-4">
                            <textarea
                                placeholder="Comment (optional)"
                                value={comment}
                                onChange={(e) => setComment(e.target.value)}
                                className="w-full p-2 rounded bg-gray-600 text-white"
                            />
                            <button
                                onClick={handleSuggestion}
                                className="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600 mt-2"
                            >
                                Suggest Changes
                            </button>
                        </div>
                    )}
                </div>
            )}
        </div>
    );
};

export default SnippetCard;
