const pswrdField = document.querySelector(".form input[type='password']"),
      toggleIcon = document.querySelector(".form .field i");

toggleIcon.addEventListener('click', () => {
  pswrdField.type = pswrdField.type === "password" ? "text" : "password";
  toggleIcon.classList.toggle("active");
});
