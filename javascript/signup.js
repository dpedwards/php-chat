const form = document.querySelector(".signup form"),
      continueBtn = form.querySelector(".button input"),
      errorText = form.querySelector(".error-text");

form.addEventListener('submit', (e) => {
    e.preventDefault(); // Prevent form from submitting traditionally
});

continueBtn.addEventListener('click', async () => {
    try {
        const formData = new FormData(form);
        const response = await fetch('php/signup.php', {
            method: 'POST',
            body: formData
        });

        if (response.ok) {
            const data = await response.text();
            if (data === "success") {
                location.href = "users.php";
            } else {
                errorText.style.display = "block";
                errorText.textContent = data;
            }
        } else {
            throw new Error('Network response was not ok.');
        }
    } catch (error) {
        console.error('Error:', error);
        errorText.style.display = "block";
        errorText.textContent = "An error occurred, please try again.";
    }
});
