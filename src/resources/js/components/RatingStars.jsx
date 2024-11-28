import React, { useState } from "react";
import { FaStar } from "react-icons/fa";

const RatingStars = ({ snippet }) => {
    const [hoverRating, setHoverRating] = useState(0);
    const [ratings, setRatings] = useState({
        average: snippet.averageRating || 0, // Default to 0 if undefined
        totalRatings: snippet.totalRatings || 0,
    });

    const handleRating = async (rating) => {
        try {
            const response = await fetch(`/snippets/${snippet.id}/rate`, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ rating }),
            });

            if (!response.ok) throw new Error("Failed to submit rating");

            const { average_rating = 0, total_ratings = 0 } = await response.json();
            setRatings({ average: average_rating, totalRatings: total_ratings });
        } catch (error) {
            console.error("Error submitting rating:", error);
        }
    };

    const average = ratings.average || 0;

    return (
        <div className="flex space-x-1">
            {[1, 2, 3, 4, 5].map((star) => (
                <FaStar
                    key={star}
                    className={`cursor-pointer ${
                        hoverRating >= star || average >= star
                            ? "text-yellow-500"
                            : "text-gray-500"
                    }`}
                    onMouseEnter={() => setHoverRating(star)}
                    onMouseLeave={() => setHoverRating(0)}
                    onClick={() => handleRating(star)}
                />
            ))}
            <span className="text-sm text-gray-400 ml-2">
            {Number(average).toFixed(1)}/5 {/* Ensure average is a number */}
        </span>
        </div>
    );
};

export default RatingStars;
