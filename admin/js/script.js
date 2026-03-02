const hamBurger = document.querySelector(".btn-button");

hamBurger.addEventListener("click", function () {
  document.querySelector("#sidebar").classList.toggle("expand");
});