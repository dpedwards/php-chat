/* Copyright Davain Pablo Edwards core8@gmx.net. Licensed https://creativecommons.org/licenses/by-nc-sa/4.0/deed.en */
const form = document.querySelector(".signup form"),
      continueBtn = form.querySelector(".button input"),
      errorText = form.querySelector(".error-text");

form.addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent form from submitting traditionally

    // Validate password and confirm password fields
    const password = form.querySelector('input[name="password"]').value;
    const confirmPassword = form.querySelector('input[name="confirm_password"]').value;

    // Check if the passwords match before sending the form data
    if (password !== confirmPassword) {
        errorText.style.display = "block";
        errorText.textContent = "Passwords do not match!";
        return; // Stop further execution and prevent form submission
    } else {
        errorText.style.display = "none"; // Hide error text if passwords match

        // Proceed to submit the form via the fetch API
        submitForm();
    }
});

function submitForm() {
    fetch('php/signup.php', {
        method: 'POST',
        body: new FormData(form)
    })
    .then(response => response.text())
    .then(data => {
        if (data === "success") {
            location.href = "users.php"; // Redirect to users.php on success
        } else {
            errorText.style.display = "block";
            errorText.textContent = data; // Display server-side error message
        }
    })
    .catch(error => {
        console.error('Error:', error); // Log error to the console
        errorText.style.display = "block";
        errorText.textContent = "An error occurred, please try again.";
    });
}
