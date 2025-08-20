<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<!-- Botón flotante -->
<!-- Ícono estilo WhatsApp -->
<button id="toggleChat" onclick="toggleChatBox()">
  <i class="fas fa-comments"></i>
</button>

<!-- Chat box -->
<div id="chat-container" style="display: none;">
  <div id="chat-header">
    Contactanos Rosita Forever 📞
    <span id="closeChat" onclick="toggleChatBox()">✖</span>
  </div>
  <div id="chat-messages">
    <div class="message bot">¡Hola! ¿En qué puedo ayudarte? Recuerda que, al oprimir el botón “Enviar”, serás dirigido a un chat de WhatsApp donde resolveremos todas tus preguntas y dudas. 📞📢</div>
  </div>
  <div id="chat-input">
    <input type="text" id="userInput" placeholder="Escribe tu mensaje..." />
    <button onclick="sendMessage()">Enviar</button>
  </div>
</div>