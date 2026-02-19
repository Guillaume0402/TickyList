/**
 * TickLyst - Frontend JavaScript
 * Handles AJAX task status toggle
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // Task status toggle via AJAX
    const taskToggles = document.querySelectorAll('.task-toggle');
    
    taskToggles.forEach(function(checkbox) {
        checkbox.addEventListener('change', function() {
            const taskId = this.getAttribute('data-task-id');
            const isChecked = this.checked;
            
            // Send AJAX request
            fetch('/tasks/' + taskId + '/toggle-status', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI - add strikethrough if checked
                    const listItem = checkbox.closest('.list-group-item, .card');
                    if (listItem) {
                        const title = listItem.querySelector('h6');
                        if (title) {
                            if (isChecked) {
                                title.classList.add('text-decoration-line-through');
                            } else {
                                title.classList.remove('text-decoration-line-through');
                            }
                        }
                    }
                    
                    // Show success feedback (optional)
                    console.log('Task status updated:', data.status);
                } else {
                    // Revert checkbox on error
                    checkbox.checked = !isChecked;
                    alert('Failed to update task status');
                }
            })
            .catch(error => {
                // Revert checkbox on error
                checkbox.checked = !isChecked;
                console.error('Error:', error);
                alert('Failed to update task status');
            });
        });
    });

    // Auto-hide alerts after 5 seconds
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert');
        alerts.forEach(function(alert) {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
