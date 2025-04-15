document.addEventListener('DOMContentLoaded', function() {
    const videos = document.querySelectorAll('.video-container video');
    
    videos.forEach(video => {
        video.addEventListener('mouseenter', function() {
            this.play();
        });
        
        video.addEventListener('mouseleave', function() {
            this.pause();
        });
    });
});
