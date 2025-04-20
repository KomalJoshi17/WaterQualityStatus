document.addEventListener('DOMContentLoaded', function() {
    // Video hover functionality
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
    
    // Chat functionality
    const chatIcon = document.getElementById('chatIcon');
    const chatContainer = document.getElementById('chatContainer');
    const closeChat = document.getElementById('closeChat');
    const resetChat = document.getElementById('resetChat');
    const sendMessage = document.getElementById('sendMessage');
    const userMessage = document.getElementById('userMessage');
    const chatMessages = document.getElementById('chatMessages');
    
    // Toggle chat visibility
    if (chatIcon) {
        chatIcon.addEventListener('click', function() {
            chatContainer.classList.add('active');
            chatContainer.style.display = 'flex'; // Add this line to make it visible
        });
    }
    
    if (closeChat) {
        closeChat.addEventListener('click', function() {
            chatContainer.classList.remove('active');
            setTimeout(() => {
                chatContainer.style.display = 'none'; // Hide after animation completes
            }, 300);
        });
    }
    
    // Reset chat
    if (resetChat) {
        resetChat.addEventListener('click', function() {
            // Clear all messages except the first one
            while (chatMessages.children.length > 1) {
                chatMessages.removeChild(chatMessages.lastChild);
            }
        });
    }
    
    // Send message function
    function sendUserMessage() {
        const message = userMessage.value.trim();
        if (message) {
            // Add user message
            addMessage(message, true);
            
            // Clear input
            userMessage.value = '';
            
            // Get bot response
            setTimeout(() => {
                const botResponse = getBotResponse(message);
                addMessage(botResponse, false);
            }, 500);
        }
    }
    
    // Add message to chat
    function addMessage(text, isUser) {
        const messageDiv = document.createElement('div');
        messageDiv.className = isUser ? 'message user' : 'message bot';
        
        const paragraph = document.createElement('p');
        paragraph.textContent = text;
        
        messageDiv.appendChild(paragraph);
        chatMessages.appendChild(messageDiv);
        
        // Scroll to bottom
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }
    
    // Handle send button click
    if (sendMessage) {
        sendMessage.addEventListener('click', sendUserMessage);
    }
    
    // Handle enter key press
    if (userMessage) {
        userMessage.addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendUserMessage();
            }
        });
    }
    
    // Bot response logic
    function getBotResponse(message) {
        message = message.toLowerCase();
        
        if (message.includes('hello') || message.includes('hi') || message.includes('hey')) {
            return "Hello! How can I assist you with water quality information today?";
        } 
        else if (message.includes('water quality') || message.includes('parameters')) {
            return "Water quality is measured using parameters like pH, dissolved oxygen, turbidity, and conductivity. You can find detailed information in our Information section.";
        } 
        else if (message.includes('ph') || message.includes('acidity')) {
            return "pH measures how acidic or basic water is. The ideal range for drinking water is 6.5-8.5. Values outside this range can indicate pollution.";
        } 
        else if (message.includes('pollution') || message.includes('contaminants')) {
            return "Common water pollutants include industrial waste, agricultural runoff, plastic, and oil spills. Check our Information page for more details.";
        } 
        else if (message.includes('conservation') || message.includes('save water')) {
            return "Water conservation tips: fix leaky faucets, take shorter showers, use water-efficient appliances, collect rainwater, and turn off taps when not in use.";
        } 
        else if (message.includes('thank')) {
            return "You're welcome! Feel free to ask if you have any other questions.";
        } 
        else if (message.includes('bye') || message.includes('goodbye')) {
            return "Goodbye! Have a great day!";
        } 
        else {
            return "I'm not sure I understand. Could you rephrase your question about water quality?";
        }
    }
});

// Add this to your existing DOMContentLoaded event listener
document.addEventListener('DOMContentLoaded', function() {
    // Your existing code...
    
    // Check if we're on the analysis results page
    if (document.querySelector('.results-container')) {
        // Load soil quality data if the container exists
        const soilChartContainer = document.getElementById('soilChartContainer');
        if (soilChartContainer) {
            fetch('fetch_soil_quality.php')
                .then(response => response.json())
                .then(data => {
                    // Create soil quality chart
                    const ctx = document.getElementById('soilChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'bar',
                        data: {
                            labels: data.map(item => item.location),
                            datasets: [{
                                label: 'Soil pH Level',
                                data: data.map(item => item.ph_level),
                                backgroundColor: '#4CAF50',
                                borderColor: '#388E3C',
                                borderWidth: 1
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'pH Level'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Location'
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error loading soil data:', error));
        }
        
        // Load noise data if the container exists
        const noiseChartContainer = document.getElementById('noiseChartContainer');
        if (noiseChartContainer) {
            fetch('fetch_noise_data.php')
                .then(response => response.json())
                .then(data => {
                    // Create noise level chart
                    const ctx = document.getElementById('noiseChart').getContext('2d');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: data.map(item => item.time_of_day),
                            datasets: [{
                                label: 'Noise Level (dB)',
                                data: data.map(item => item.decibel_level),
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 2,
                                tension: 0.3
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: {
                                    beginAtZero: true,
                                    title: {
                                        display: true,
                                        text: 'Decibel Level (dB)'
                                    }
                                },
                                x: {
                                    title: {
                                        display: true,
                                        text: 'Time of Day'
                                    }
                                }
                            }
                        }
                    });
                })
                .catch(error => console.error('Error loading noise data:', error));
        }
    }
});
