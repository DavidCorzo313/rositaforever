function sendMessage() {
  const input = document.getElementById("userInput");
  const message = input.value.trim();

  if (message !== "") {
    const numeroSoporte = "573186220874"; // SIN espacios
    const url = `https://wa.me/${numeroSoporte}?text=${encodeURIComponent(message)}`;
    window.open(url, '_blank');
    input.value = "";
  }
}

function toggleChatBox() {
  const chat = document.getElementById("chat-container");
  const isVisible = chat.style.display === "block";

  if (isVisible) {
    chat.style.display = "none";
    localStorage.setItem("chatEstado", "cerrado");
  } else {
    chat.style.display = "block";
    localStorage.setItem("chatEstado", "abierto");
  }
}

window.onload = function () {
  const chatEstado = localStorage.getItem("chatEstado");
  const chat = document.getElementById("chat-container");
  if (chat && chatEstado === "abierto") {
    chat.style.display = "block";
  }
};
