document.addEventListener("DOMContentLoaded", () => {
  const botao = document.getElementById("menuToggle");
  const menu = document.getElementById("menu");

  if (!botao || !menu) return;

  botao.addEventListener("click", (e) => {
    e.preventDefault();
    menu.classList.toggle("active");
  });

  document.addEventListener("click", (e) => {
    if (!menu.contains(e.target) && !botao.contains(e.target)) {
      menu.classList.remove("active");
    }
  });
});
