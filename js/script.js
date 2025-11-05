// js/script.js
// Custom JavaScript for the SOUND Entertainment website

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Rating system
    const ratingStars = document.querySelectorAll('.rating-star');
    if (ratingStars.length > 0) {
        ratingStars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = this.getAttribute('data-rating');
                const contentId = this.closest('.rating-container').getAttribute('data-content-id');
                const contentType = this.closest('.rating-container').getAttribute('data-content-type');
                
                // Send rating to server via AJAX
                rateContent(contentId, contentType, rating);
            });
        });
    }
    
    // Audio/Video player controls
    const audioPlayers = document.querySelectorAll('audio');
    audioPlayers.forEach(player => {
        // Add custom controls if needed
    });
    
    // Search functionality
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchTerm = document.getElementById('searchInput').value;
            if (searchTerm.trim() !== '') {
                // Implement search logic
                window.location.href = `search.php?q=${encodeURIComponent(searchTerm)}`;
            }
        });
    }
});

function rateContent(contentId, contentType, rating) {
    // Check if user is logged in
    if (!isUserLoggedIn()) {
        alert('Please login to rate content');
        return;
    }
    
    // Send AJAX request to save rating
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'rate_content.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4 && xhr.status === 200) {
            const response = JSON.parse(xhr.responseText);
            if (response.success) {
                updateRatingDisplay(contentId, contentType, rating);
            } else {
                alert('Error: ' + response.message);
            }
        }
    };
    xhr.send(`content_id=${contentId}&content_type=${contentType}&rating=${rating}`);
}

function updateRatingDisplay(contentId, contentType, rating) {
    const ratingContainer = document.querySelector(`.rating-container[data-content-id="${contentId}"]`);
    if (ratingContainer) {
        const stars = ratingContainer.querySelectorAll('.rating-star');
        stars.forEach((star, index) => {
            if (index < rating) {
                star.classList.add('text-warning');
            } else {
                star.classList.remove('text-warning');
            }
        });
        
        // Update average rating display
        const avgRating = ratingContainer.querySelector('.avg-rating');
        if (avgRating) {
            // In a real implementation, you would fetch the new average from the server
            avgRating.textContent = rating;
        }
    }
}

function isUserLoggedIn() {
    // Check if user is logged in (this would typically check a session or token)
    // For now, we'll assume there's a global variable or we'll check the DOM
    return document.body.classList.contains('user-logged-in');
}

// Play media function
function playMedia(mediaId, mediaType) {
    if (mediaType === 'audio') {
        const audioPlayer = document.getElementById(`audio-player-${mediaId}`);
        if (audioPlayer) {
            audioPlayer.play();
        }
    } else if (mediaType === 'video') {
        const videoPlayer = document.getElementById(`video-player-${mediaId}`);
        if (videoPlayer) {
            videoPlayer.play();
        }
    }
}

// Pause media function
function pauseMedia(mediaId, mediaType) {
    if (mediaType === 'audio') {
        const audioPlayer = document.getElementById(`audio-player-${mediaId}`);
        if (audioPlayer) {
            audioPlayer.pause();
        }
    } else if (mediaType === 'video') {
        const videoPlayer = document.getElementById(`video-player-${mediaId}`);
        if (videoPlayer) {
            videoPlayer.pause();
        }
    }
}