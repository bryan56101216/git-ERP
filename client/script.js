document.addEventListener("DOMContentLoaded", function() {
   
const contentEl = document.getElementById('content');
const originalContent = contentEl.innerHTML;

// --- Envoyer un Message ---
document.getElementById('sendMessageBtn').addEventListener('click', () => {
    contentEl.innerHTML = `
        <div class="compose-wrapper">
            <div class="content-header"><h2 class="content-title">Nouveau Message</h2></div>
            <form id="messageForm" class="message-form">
                <label>Destinataire</label>
                <select id="recipient" required>
                    <option value="">Sélectionner un destinataire</option>
                    <option value="Chef de Projet">Chef de Projet</option>
                    <option value="Comptable">Comptable</option>
                </select>
                <label>Objet</label>
                <input type="text" id="subject" placeholder="Objet" required />
                <label>Message</label>
                <textarea id="messageBody" rows="6" placeholder="Tapez votre message..." required></textarea>
                <div class="compose-actions">
                    <button type="submit" class="btn btn-primary">Envoyer</button>
                    <button type="button" class="btn btn-secondary" id="cancelCompose">Annuler</button>
                </div>
            </form>
        </div>
    `;
    document.getElementById('messageForm').addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Message envoyé à '+document.getElementById('recipient').value+'!');
        contentEl.innerHTML = originalContent;
    });
    document.getElementById('cancelCompose').addEventListener('click', () => {
        contentEl.innerHTML = originalContent;
    });
});

// --- Contacts ---
function showContacts() {
    contentEl.innerHTML = `
        <div class="content-header"><h2 class="content-title">Contacts</h2></div>
        <div class="contact-info">
            <div class="info-section">
                <div class="info-title">Détails de Contact</div>
                <div class="info-item"><span class="info-label">Téléphone:</span><span class="info-value">${userInfo.numero}</span></div>
                <div class="info-item"><span class="info-label">WhatsApp:</span><span class="info-value link"><a href="https://wa.me/${userInfo.numero}" target="_blank">Chat</a></span></div>
                <div class="info-item"><span class="info-label">Facebook:</span><span class="info-value link"><a href="https://facebook.com/${userInfo.nom}" target="_blank">Profil</a></span></div>
                <div class="info-item"><span class="info-label">Twitter:</span><span class="info-value link"><a href="https://twitter.com/${userInfo.nom}" target="_blank">@${userInfo.nom}</a></span></div>
            </div>
            <button class="btn btn-secondary" id="backFromContacts" >Retour</button>
        </div>
    `;
    document.getElementById('backFromContacts').addEventListener('click', () => {
        contentEl.innerHTML = originalContent;
    });
};
document.getElementById('contactsBtn').addEventListener('click', showContacts);
document.getElementById('contactsSidebarBtn').addEventListener('click', showContacts);

// --- Messages ---
document.getElementById('messagesBtn').addEventListener('click', function showMessages() {
    const messages = [
        {sender:'Comptable', subject:'Facture #452', content:'Veuillez vérifier la facture en pièce jointe.'},
        {sender:'Comptable', subject:'Devis #78', content:'Voici le devis demandé.'},
        {sender:'Comptable', subject:'Facture #453', content:'Nouvelle facture pour juillet.'},
        {sender:'Comptable', subject:'Devis #79', content:'Devis pour le nouveau projet.'},
    ];
    let html = '<div class="content-header"><h2 class="content-title">Messages Reçus</h2></div><div class="messages-list">';
    messages.forEach(msg => {
        html += `<div class="message-item"><div class="sender">${msg.sender}</div><div class="subject">${msg.subject}</div><div class="content">${msg.content}</div></div>`;
    });
    html += '</div><button class="btn btn-secondary" id="backFromMessages" style="margin-top:3%">Retour</button>';
    contentEl.innerHTML = html;
    document.getElementById('backFromMessages').addEventListener('click', () => {
        contentEl.innerHTML = originalContent;
    });
   
});
});
  window.addEventListener("load", function() {
    document.getElementById("loader").classList.add("hidden");
  });