document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('environmentalForm');
    
    if (form) {
        form.addEventListener('submit', function(e) {
            // Form validation can be added here if needed
            // If you want to handle the form submission with AJAX instead of a page redirect,
            // you can uncomment the code below
            
            /*
            e.preventDefault();
            
            const formData = new FormData(this);
            
            fetch('submit_help.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    // Redirect to the analysis results page
                    window.location.href = 'analysis_results.php?id=' + data.id;
                } else {
                    // Show error message
                    Swal.fire({
                        icon: 'error',
                        title: 'Submission Error',
                        text: data.message || 'An error occurred while submitting the form.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Submission Error',
                    text: 'An error occurred while submitting the form.'
                });
            });
            */
        });
    }
    
    // Add any other JavaScript functionality for the help page here
});