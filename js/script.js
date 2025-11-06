// js/script.js
// Custom JavaScript for the SOUND Entertainment website

document.addEventListener('DOMContentLoaded', function() {
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Initialize all modals properly
    const reviewModals = document.querySelectorAll('.modal');
    reviewModals.forEach(modal => {
        modal.addEventListener('shown.bs.modal', function () {
            // Focus on textarea when modal opens
            const textarea = this.querySelector('textarea');
            if (textarea) {
                textarea.focus();
            }
        });
        
        modal.addEventListener('hidden.bs.modal', function () {
            // Reset form when modal closes
            const form = this.querySelector('form');
            if (form) {
                form.reset();
            }
        });
    });
    
    // Enhanced Rating system with hover effects
    const ratingStars = document.querySelectorAll('.rating-star');
    ratingStars.forEach(star => {
        // Add hover effects
        star.addEventListener('mouseenter', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            const container = this.closest('.rating-container');
            highlightStars(container, rating);
        });
        
        star.addEventListener('mouseleave', function() {
            const container = this.closest('.rating-container');
            resetStars(container);
        });
        
        // Click to rate
        star.addEventListener('click', function() {
            const rating = parseInt(this.getAttribute('data-rating'));
            const container = this.closest('.rating-container');
            const contentId = container.getAttribute('data-content-id');
            const contentType = container.getAttribute('data-content-type');
            
            rateContent(contentId, contentType, rating);
        });
    });
    
    // Audio/Video player controls
    const audioPlayers = document.querySelectorAll('audio');
    audioPlayers.forEach(player => {
        // Add custom progress tracking
        player.addEventListener('timeupdate', function() {
            updateProgressBar(this);
        });
        
        // Add volume controls
        addVolumeControl(player);
    });
    
    // Search functionality
    const searchForm = document.getElementById('searchForm');
    if (searchForm) {
        searchForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const searchTerm = document.getElementById('searchInput').value.trim();
            if (searchTerm !== '') {
                window.location.href = `search.php?q=${encodeURIComponent(searchTerm)}`;
            }
        });
    }
    
    // Auto-dismiss alerts after 5 seconds
    const autoDismissAlerts = document.querySelectorAll('.alert.auto-dismiss');
    autoDismissAlerts.forEach(alert => {
        setTimeout(() => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        }, 5000);
    });
    
    // Add loading states to buttons
    const submitButtons = document.querySelectorAll('button[type="submit"]');
    submitButtons.forEach(button => {
        button.addEventListener('click', function() {
            const form = this.closest('form');
            if (form && form.checkValidity()) {
                this.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Loading...';
                this.disabled = true;
            }
        });
    });
    
    // Initialize AOS with better settings
    if (typeof AOS !== 'undefined') {
        AOS.init({
            duration: 800,
            once: true,
            offset: 100,
            delay: 100
        });
    }
});

// Star rating highlight functions
function highlightStars(container, rating) {
    const stars = container.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        if (index < rating) {
            star.classList.add('text-warning');
            star.classList.remove('text-muted');
        } else {
            star.classList.remove('text-warning');
            star.classList.add('text-muted');
        }
    });
}

function resetStars(container) {
    const userRating = parseInt(container.getAttribute('data-user-rating') || '0');
    const stars = container.querySelectorAll('.rating-star');
    stars.forEach((star, index) => {
        if (index < userRating) {
            star.classList.add('text-warning');
            star.classList.remove('text-muted');
        } else {
            star.classList.remove('text-warning');
            star.classList.add('text-muted');
        }
    });
}

function rateContent(contentId, contentType, rating) {
    if (!isUserLoggedIn()) {
        showNotification('Please login to rate content', 'warning', true);
        // Redirect to login page after 2 seconds
        setTimeout(() => {
            window.location.href = 'login.php?redirect=' + encodeURIComponent(window.location.href);
        }, 2000);
        return;
    }
    
    const xhr = new XMLHttpRequest();
    xhr.open('POST', 'rate_content.php', true);
    xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status === 200) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // Update the user rating display
                        const container = document.querySelector(`.rating-container[data-content-id="${contentId}"]`);
                        if (container) {
                            container.setAttribute('data-user-rating', rating);
                            resetStars(container);
                        }
                        
                        showNotification('Rating submitted successfully!', 'success');
                        
                        // Update average rating if element exists
                        updateAverageRating(contentId, contentType);
                        
                    } else {
                        showNotification('Error: ' + response.message, 'error');
                    }
                } catch (e) {
                    showNotification('Error processing response', 'error');
                    console.error('Rating error:', e);
                }
            } else {
                showNotification('Network error occurred', 'error');
            }
        }
    };
    
    xhr.send(`content_id=${contentId}&content_type=${contentType}&rating=${rating}`);
}

function updateAverageRating(contentId, contentType) {
    // This would typically make another AJAX call to get updated average
    // For now, we'll just reload the ratings section after a delay
    setTimeout(() => {
        const ratingSection = document.querySelector(`.rating-container[data-content-id="${contentId}"]`)?.closest('.rating-section');
        if (ratingSection) {
            // In a real implementation, you'd fetch the updated average via AJAX
            // For now, we'll just indicate that it's updating
            const avgDisplay = ratingSection.querySelector('.avg-rating');
            if (avgDisplay) {
                avgDisplay.innerHTML = '<small class="text-muted">Updating...</small>';
            }
        }
    }, 500);
}

function isUserLoggedIn() {
    // Check both methods for login status
    return (typeof USER_LOGGED_IN !== 'undefined' && USER_LOGGED_IN) || 
           document.body.classList.contains('user-logged-in');
}

// Enhanced notification system
function showNotification(message, type = 'info', showLoginButton = false) {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(notification => {
        notification.remove();
    });
    
    // Create notification element
    const notification = document.createElement('div');
    notification.className = `custom-notification alert alert-${getAlertType(type)} alert-dismissible fade show`;
    notification.style.cssText = `
        position: fixed;
        top: 100px;
        right: 20px;
        z-index: 9999;
        min-width: 300px;
        max-width: 400px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        border: none;
        border-radius: 8px;
    `;
    
    let notificationContent = `
        <div class="d-flex align-items-center">
            <i class="fas ${getNotificationIcon(type)} me-2"></i>
            <div class="flex-grow-1">${message}</div>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    if (showLoginButton && !isUserLoggedIn()) {
        notificationContent += `
            <div class="mt-2">
                <a href="login.php" class="btn btn-sm btn-outline-${getAlertType(type)}">Login Now</a>
            </div>
        `;
    }
    
    notification.innerHTML = notificationContent;
    document.body.appendChild(notification);
    
    // Auto remove after 5 seconds for non-error messages
    if (type !== 'error') {
        setTimeout(() => {
            if (notification.parentNode) {
                const bsAlert = new bootstrap.Alert(notification);
                bsAlert.close();
            }
        }, 5000);
    }
}

function getAlertType(type) {
    const types = {
        'success': 'success',
        'error': 'danger',
        'warning': 'warning',
        'info': 'info'
    };
    return types[type] || 'info';
}

function getNotificationIcon(type) {
    const icons = {
        'success': 'fa-check-circle',
        'error': 'fa-exclamation-circle',
        'warning': 'fa-exclamation-triangle',
        'info': 'fa-info-circle'
    };
    return icons[type] || 'fa-info-circle';
}

// Media player enhancements
function updateProgressBar(player) {
    const progress = (player.currentTime / player.duration) * 100;
    // You can update a custom progress bar here if needed
}

function addVolumeControl(player) {
    // Add volume control functionality if needed
    // This is a placeholder for future volume control implementation
}

// Play media function
function playMedia(mediaId, mediaType) {
    if (mediaType === 'audio') {
        const audioPlayer = document.getElementById(`audio-player-${mediaId}`);
        if (audioPlayer) {
            audioPlayer.play().catch(error => {
                console.error('Error playing audio:', error);
                showNotification('Error playing audio file', 'error');
            });
        }
    } else if (mediaType === 'video') {
        const videoPlayer = document.getElementById(`video-player-${mediaId}`);
        if (videoPlayer) {
            videoPlayer.play().catch(error => {
                console.error('Error playing video:', error);
                showNotification('Error playing video file', 'error');
            });
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

// Toggle media play/pause
function toggleMedia(mediaId, mediaType) {
    if (mediaType === 'audio') {
        const audioPlayer = document.getElementById(`audio-player-${mediaId}`);
        if (audioPlayer) {
            if (audioPlayer.paused) {
                audioPlayer.play();
            } else {
                audioPlayer.pause();
            }
        }
    } else if (mediaType === 'video') {
        const videoPlayer = document.getElementById(`video-player-${mediaId}`);
        if (videoPlayer) {
            if (videoPlayer.paused) {
                videoPlayer.play();
            } else {
                videoPlayer.pause();
            }
        }
    }
}

// Utility function to format time
function formatTime(seconds) {
    const minutes = Math.floor(seconds / 60);
    const remainingSeconds = Math.floor(seconds % 60);
    return `${minutes}:${remainingSeconds.toString().padStart(2, '0')}`;
}

// Debounce function for search
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Smooth scroll to element
function smoothScrollTo(elementId) {
    const element = document.getElementById(elementId);
    if (element) {
        element.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Form validation helper
function validateEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
}

function validatePhone(phone) {
    const re = /^[\+]?[1-9][\d]{0,15}$/;
    return re.test(phone.replace(/[\s\-\(\)]/g, ''));
}

// Initialize image lazy loading
function initLazyLoading() {
    const lazyImages = document.querySelectorAll('img[data-src]');
    
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    lazyImages.forEach(img => imageObserver.observe(img));
}

// Call lazy loading initialization
if ('IntersectionObserver' in window) {
    initLazyLoading();
}

// Add to playlist functionality (placeholder)
function addToPlaylist(mediaId, mediaType) {
    if (!isUserLoggedIn()) {
        showNotification('Please login to add to playlist', 'warning', true);
        return;
    }
    showNotification('Added to playlist successfully!', 'success');
}

// Share media functionality
function shareMedia(mediaId, mediaType, title) {
    if (navigator.share) {
        navigator.share({
            title: title,
            text: `Check out this ${mediaType} on SOUND Entertainment`,
            url: window.location.href,
        })
        .catch(error => console.log('Error sharing:', error));
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            showNotification('Link copied to clipboard!', 'success');
        }).catch(() => {
            showNotification('Share functionality not supported', 'warning');
        });
    }
}

// Keyboard shortcuts
document.addEventListener('keydown', function(e) {
    // Space bar to play/pause focused media player
    if (e.code === 'Space' && e.target.tagName !== 'INPUT' && e.target.tagName !== 'TEXTAREA') {
        e.preventDefault();
        const focusedPlayer = document.querySelector('audio:focus, video:focus');
        if (focusedPlayer) {
            if (focusedPlayer.paused) {
                focusedPlayer.play();
            } else {
                focusedPlayer.pause();
            }
        }
    }
    
    // Escape to close modals
    if (e.code === 'Escape') {
        const openModal = document.querySelector('.modal.show');
        if (openModal) {
            const modal = bootstrap.Modal.getInstance(openModal);
            if (modal) {
                modal.hide();
            }
        }
    }
});

console.log('SOUND Entertainment JavaScript loaded successfully!');