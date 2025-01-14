import {applyAndRegister, reactive, startReactiveDom} from "./reactive.js";

let formulaireModificationColonne = reactive({
    titre: "",
    idColonne: "",

    /**
     * Modifie le titre de la colonne en front et via l'API
     * @returns {Promise<void>} La promesse habituelle
     */
    modifierColonne: async function () {
        if (this.titre !== '') {
            document.querySelector(`[data-columns="${document.querySelector('.menuColonnes').getAttribute('data-columns')}"] .main`).innerText = escapeHtml(this.titre);
            updateDraggables();
        }

        document.querySelector('.formulaireModificationColonne').style.display = 'none';
        document.querySelectorAll('.all').forEach(el => {
            el.style.opacity = '1';
        });

        if (this.titre !=='') {
            let response = await fetch(apiBase + '/colonne/modifier', {
                method: 'PATCH',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    nomColonne: escapeHtml(this.titre),
                    idColonne: document.querySelector('.menuColonnes').getAttribute('data-columns')
                })
            });

            if (response.status !== 200) {
                afficherMessageFlash("Erreur lors de la modification de la colonne", "danger")
            } else {
                afficherMessageFlash("Colonne modifiée avec succès", "success")
            }
        }
    }

}, "formulaireModificationColonne");

applyAndRegister({});

startReactiveDom();