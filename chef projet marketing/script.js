
            const navItems = document.querySelectorAll('.nav-item');
            const sections = document.querySelectorAll('.content-section');

            navItems.forEach(item => {
            item.addEventListener('click', () => {
                // Retirer active de tous les nav-items
                navItems.forEach(nav => nav.classList.remove('active'));
                item.classList.add('active');

                // Masquer toutes les sections
                sections.forEach(sec => sec.classList.remove('active'));

                // Afficher la bonne section
                const targetId = item.querySelector('span').innerText.toLowerCase();
                const targetSection = document.getElementById(targetId);
                if (targetSection) targetSection.classList.add('active');
            });
            });
        
        const tachesPossibles = [
        "Préparer un rapport", "Analyser les données", "Créer une présentation",
        "Mettre à jour la base de données", "Réunion avec le client",
        "Concevoir un logo", "Corriger les bugs", "Rédiger un mail important",
        "Planifier une campagne", "Vérifier les comptes", "Tester une fonctionnalité",
        "Former un collègue", "Optimiser le code", "Surveiller le serveur"
        ];

        // Fonction pour générer 3 tâches aléatoires
        function genererTaches() {
        return [...tachesPossibles].sort(() => 0.5 - Math.random()).slice(0, 3);
        }

        // Initialisation
        document.querySelectorAll(".personneBloc").forEach(bloc => {
        const ul = bloc.querySelector(".listeMissions");

        // Assigner 3 tâches de base
        genererTaches().forEach(tache => {
            const li = document.createElement("li");
            li.innerHTML = `${tache} <button onclick="this.parentElement.remove()">X</button>`;
            ul.appendChild(li);
        });

        // Gestion ajout tâche
        const form = bloc.querySelector(".formMission");
        form.addEventListener("submit", function(event) {
            event.preventDefault();
            const input = form.querySelector("input");
            const texte = input.value.trim();
            if (texte !== "") {
            const li = document.createElement("li");
            li.innerHTML = `${texte} <button onclick="this.parentElement.remove()">X</button>`;
            ul.appendChild(li);
            input.value = "";
            }
        });
        });
    // Gestion des boutons
    document.querySelectorAll(".btn-report").forEach(btn => {
    btn.addEventListener("click", () => {
        alert("Rapport envoyé au client !");
    });
    });

    document.querySelectorAll(".btn-validate").forEach(btn => {
    btn.addEventListener("click", (e) => {
        e.target.parentElement.style.borderLeft = "5px solid green";
        alert("Feedback validé !");
    });
    });

    document.querySelectorAll(".btn-reject").forEach(btn => {
    btn.addEventListener("click", (e) => {
        e.target.parentElement.style.borderLeft = "5px solid red";
        alert("Feedback rejeté !");
    });
    });
    // Fonction pour recalculer le total
    function calculateTotalBudget() {
        let total = 0;
        document.querySelectorAll(".budget-amount").forEach(el => {
        total += parseInt(el.textContent.replace(/,/g, ""));
        });
        document.getElementById("totalBudget").textContent = total.toLocaleString();
    }

    // Initialisation du total
    calculateTotalBudget();

    // Gestion de la modification
    document.querySelectorAll(".update-btn").forEach(btn => {
        btn.addEventListener("click", function() {
        let budgetCell = this.closest("tr").querySelector(".budget-amount");
        let newBudget = prompt("Entrer le nouveau budget (XAF) :", budgetCell.textContent);
        if (newBudget && !isNaN(newBudget)) {
            budgetCell.textContent = parseInt(newBudget).toLocaleString();
            calculateTotalBudget();
        }
        });
    });
    
    // Liste initiale des rapports
    let rapports = [];

    // Fonction pour afficher les rapports
    function renderRapports() {
    const list = document.getElementById("rapportList");
    list.innerHTML = "";
    rapports.forEach((r, index) => {
        const card = document.createElement("div");
        card.classList.add("rapport-card");

        card.innerHTML = `
        <div class="rapport-info">
            <p><strong>Nom:</strong> ${r.name}</p>
            <p><strong>Projet:</strong> ${r.project}</p>
            <p><strong>Clients:</strong> ${r.clients.join(", ")}</p>
            <p><strong>Fichier:</strong> ${r.fileName}</p>
        </div>
        <div class="rapport-actions">
            <button class="edit-btn" onclick="editRapport(${index})">Modifier</button>
            <button class="delete-btn" onclick="deleteRapport(${index})">Supprimer</button>
        </div>
        `;
        list.appendChild(card);
    });
    }

    // Ajouter un rapport
    document.getElementById("addRapportBtn").addEventListener("click", () => {
    const name = document.getElementById("rapportName").value;
    const project = document.getElementById("rapportProject").value;
    const clientsSelect = document.getElementById("rapportClients");
    const clients = Array.from(clientsSelect.selectedOptions).map(opt => opt.value);
    const fileInput = document.getElementById("rapportFile");
    const fileName = fileInput.files.length > 0 ? fileInput.files[0].name : "Aucun fichier";

    if(name && project && clients.length > 0){
        rapports.push({name, project, clients, fileName});
        renderRapports();
        // Réinitialiser le formulaire
        document.getElementById("rapportName").value = "";
        clientsSelect.selectedIndex = -1;
        fileInput.value = "";
    } else {
        alert("Veuillez remplir tous les champs et sélectionner au moins un client.");
    }
    });

    // Supprimer un rapport
    function deleteRapport(index){
    if(confirm("Voulez-vous vraiment supprimer ce rapport ?")){
        rapports.splice(index,1);
        renderRapports();
    }
    }

    // Modifier un rapport
    function editRapport(index){
    const rapport = rapports[index];
    const newName = prompt("Modifier le nom du rapport :", rapport.name);
    if(newName) {
        rapports[index].name = newName;
        renderRapports();
    }
    }

    // Initial render
    renderRapports();
      window.addEventListener("load", function() {
    document.getElementById("loader").classList.add("hidden");
  });