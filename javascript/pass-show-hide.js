/* Copyright Davain Pablo Edwards core8@gmx.net. Licensed https://creativecommons.org/licenses/by-nc-sa/4.0/deed.en */
const pswrdField = document.querySelector(".form input[type='password']"),
      toggleIcon = document.querySelector(".form .field i");

toggleIcon.addEventListener('click', () => {
  pswrdField.type = pswrdField.type === "password" ? "text" : "password";
  toggleIcon.classList.toggle("active");
});
