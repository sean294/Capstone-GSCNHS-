document.addEventListener('DOMContentLoaded', function() {
    var userNameElement = document.getElementById('user_name');
    var settingElement = document.querySelector('.setting');

    // Variable to track the background color state
    var isBackgroundColorSet = false;

    userNameElement.addEventListener('click', function(event) {
        event.preventDefault();
        settingElement.classList.toggle('active');

        // Toggle the background color based on the state
        if (isBackgroundColorSet) {
            userNameElement.style.backgroundColor = '';
            
        } else {
            userNameElement.style.backgroundColor = 'rgba(229, 243, 255, 0.606)';
        }

        // Update the state for the next click
        isBackgroundColorSet = !isBackgroundColorSet;

        console.log('Clicked');
    });
});
