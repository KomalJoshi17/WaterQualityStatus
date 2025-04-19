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
    
    // Quotes slider functionality
    const quotesContainer = document.querySelector('.quotes-container');
    if (quotesContainer) {
        const quotes = quotesContainer.querySelectorAll('blockquote');
        let currentQuote = 0;
        
        // Create dots container
        const dotsContainer = document.createElement('div');
        dotsContainer.className = 'quote-dots';
        quotesContainer.after(dotsContainer);
        
        // Create dots for each quote
        quotes.forEach((_, index) => {
            const dot = document.createElement('span');
            dot.className = 'quote-dot';
            if (index === 0) dot.classList.add('active');
            dotsContainer.appendChild(dot);
            
            // Add click event to dots
            dot.addEventListener('click', () => {
                showQuote(index);
                resetTimer();
            });
        });
        
        // Function to show a specific quote
        function showQuote(index) {
            // Hide current quote
            quotes[currentQuote].style.opacity = '0';
            
            // Update dots
            document.querySelectorAll('.quote-dot').forEach((dot, i) => {
                dot.classList.toggle('active', i === index);
            });
            
            // Show new quote after a small delay
            setTimeout(() => {
                currentQuote = index;
                quotes[currentQuote].style.opacity = '1';
            }, 500);
        }
        
        // Function to show the next quote
        function showNextQuote() {
            const nextIndex = (currentQuote + 1) % quotes.length;
            showQuote(nextIndex);
        }
        
        // Set up interval for automatic rotation
        let quoteInterval = setInterval(showNextQuote, 3000);
        
        // Reset timer when manually changing quotes
        function resetTimer() {
            clearInterval(quoteInterval);
            quoteInterval = setInterval(showNextQuote, 3000);
        }
    }
});
