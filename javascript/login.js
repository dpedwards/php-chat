const form = document.querySelector(".login form"),
      continueBtn = form.querySelector(".button input"),
      errorText = form.querySelector(".error-text");

form.addEventListener('submit', e => {
    e.preventDefault(); // Prevent the form from submitting traditionally
});

continueBtn.addEventListener('click', () => {
    // Using Fetch API to send the form data
    fetch('php/login.php', {
        method: 'POST',
        body: new FormData(form)
    })
    .then(response => response.text()) // Convert the response to text
    .then(data => {
        if(data === "success"){
            location.href = "users.php"; // Redirect on success
        }else{
            errorText.style.display = "block";
            errorText.textContent = data; // Display error message
        }
    })
    .catch(error => console.error('Error:', error)); // Log errors to the console
});
