// Function to create and show the floating button
function showFloatingButton() {
    // Create the button element
    const button = document.createElement('button');

    // Set the button's text content
    button.textContent = 'Switch to Manager';

    // Apply styles to the button
    button.style.position = 'fixed'; // Fixed position
    button.style.top = '20px'; // Position 20px from the bottom
    button.style.right = '20px'; // Position 20px from the right
    button.style.zIndex = '1000'; // Ensure it is on top
    button.style.padding = '10px 20px'; // Padding for the button
    button.style.backgroundColor = 'orange'; // Button color
    button.style.color = 'white'; // Text color
    button.style.border = 'none'; // Remove border
    button.style.borderRadius = '21%'; // Rounded corners
    button.style.cursor = 'pointer'; // Pointer cursor on hover
    button.style.fontSize = '16px'; // Font size
    button.style.boxShadow = '0px 2px 10px rgba(0, 0, 0, 0.2)'; // Shadow for floating effect

    // Optional: Add some hover effect
    button.addEventListener('mouseover', function() {
        button.style.backgroundColor = '#0056b3'; // Darker color on hover
    });

    button.addEventListener('mouseout', function() {
        button.style.backgroundColor = '#007BFF'; // Original color when not hovering
    });

    // Add an event listener for the button click (if needed)
    button.addEventListener('click', function() {
        // window.location.href = '/admin/business/switch';

        fetch('/admin/business/switch', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Reload the page to reflect changes
                window.location.reload();
            } else {
                alert('Failed to switch business');
            }
        })
        .catch(error => {
            console.error('Error:', error);
        });


    });

    // Append the button to the body
    document.body.appendChild(button);
}

// Call the function to show the button when the page loads
document.addEventListener('DOMContentLoaded', showFloatingButton);
