
document.addEventListener("DOMContentLoaded", function() {
   
const contentEl = document.getElementById('content');
const originalContent = contentEl.innerHTML;

// --- Send Message ---
document.getElementById('sendMessageBtn').addEventListener('click', () => {
    contentEl.innerHTML = `
        <div class="compose-wrapper">
            <div class="content-header"><h2 class="content-title">New Message</h2></div>
            <form id="messageForm" class="message-form">
                <label>Recipient</label>
                <select id="recipient" required>
                    <option value="">Select recipient</option>
                    <option value="Project Manager">Project Manager</option>
                    <option value="Accountant">Accountant</option>
                </select>
                <label>Subject</label>
                <input type="text" id="subject" placeholder="Subject" required />
                <label>Message</label>
                <textarea id="messageBody" rows="6" placeholder="Type your message..." required></textarea>
                <div class="compose-actions">
                    <button type="submit" class="btn btn-primary">Send</button>
                    <button type="button" class="btn btn-secondary" id="cancelCompose">Cancel</button>
                </div>
            </form>
        </div>
    `;
    document.getElementById('messageForm').addEventListener('submit', (e) => {
        e.preventDefault();
        alert('Message sent to '+document.getElementById('recipient').value+'!');
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
                <div class="info-title">Contact Details</div>
                <div class="info-item"><span class="info-label">Phone:</span><span class="info-value">${userInfo.numero}</span></div>
                <div class="info-item"><span class="info-label">WhatsApp:</span><span class="info-value link"><a href="https://wa.me/${userInfo.numero}" target="_blank">Chat</a></span></div>
                <div class="info-item"><span class="info-label">Facebook:</span><span class="info-value link"><a href="https://facebook.com/${userInfo.nom}" target="_blank">Profile</a></span></div>
                <div class="info-item"><span class="info-label">Twitter:</span><span class="info-value link"><a href="https://twitter.com/${userInfo.nom}" target="_blank">@${userInfo.nom}</a></span></div>
            </div>
            <button class="btn btn-secondary" id="backFromContacts" >Back</button>
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
        {sender:'Accountant', subject:'Invoice #452', content:'Please review the attached invoice.'},
        {sender:'Accountant', subject:'Devis #78', content:'Here is the requested quotation.'},
        {sender:'Accountant', subject:'Invoice #453', content:'New invoice for July.'},
        {sender:'Accountant', subject:'Devis #79', content:'Quotation for new project.'},
    ];
    let html = '<div class="content-header"><h2 class="content-title">Messages Received</h2></div><div class="messages-list">';
    messages.forEach(msg => {
        html += `<div class="message-item"><div class="sender">${msg.sender}</div><div class="subject">${msg.subject}</div><div class="content">${msg.content}</div></div>`;
    });
    html += '</div><button class="btn btn-secondary" id="backFromMessages" style="margin-top:3%">Back</button>';
    contentEl.innerHTML = html;
    document.getElementById('backFromMessages').addEventListener('click', () => {
        contentEl.innerHTML = originalContent;
    });
   
});
});
  window.addEventListener("load", function() {
    document.getElementById("loader").classList.add("hidden");
  });

