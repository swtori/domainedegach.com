document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('reservationForm');
    const messageEl = document.getElementById('reservationMessage');
    const chambreSelect = document.getElementById('id_chambre');

    if (!form || !messageEl || !chambreSelect) {
        return;
    }

    let csrfToken = '';

    function showMessage(type, text) {
        messageEl.className = 'form-message ' + type;
        messageEl.textContent = text;
    }

    function loadCsrfToken() {
        return fetch('php/api/csrf.php', { credentials: 'same-origin' })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                if (data.success && data.csrf_token) {
                    csrfToken = data.csrf_token;
                }
            })
            .catch(function () {
                showMessage('error', 'Impossible de préparer le formulaire. Réessayez plus tard.');
            });
    }

    function loadChambres() {
        return fetch('php/api/chambres.php', { credentials: 'same-origin' })
            .then(function (response) { return response.json(); })
            .then(function (data) {
                chambreSelect.innerHTML = '';
                if (!data.success || !Array.isArray(data.data) || data.data.length === 0) {
                    chambreSelect.innerHTML = '<option value="">Aucune chambre disponible</option>';
                    chambreSelect.disabled = true;
                    return;
                }
                data.data.forEach(function (ch) {
                    const option = document.createElement('option');
                    option.value = String(ch.id);
                    option.textContent = ch.designation + ' — ' + ch.prix.toFixed(2) + ' € / nuit';
                    chambreSelect.appendChild(option);
                });
            })
            .catch(function () {
                chambreSelect.innerHTML = '<option value="">Chargement impossible</option>';
                chambreSelect.disabled = true;
            });
    }

    Promise.all([loadCsrfToken(), loadChambres()]).then(function () {
        form.addEventListener('submit', function (event) {
            event.preventDefault();

            if (!csrfToken) {
                showMessage('error', 'Session expirée. Rechargez la page.');
                return;
            }

            const submitButton = form.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            submitButton.disabled = true;
            submitButton.textContent = 'Envoi en cours…';

            const body = new FormData(form);
            body.append('csrf_token', csrfToken);

            fetch('php/api/reservation.php', {
                method: 'POST',
                body: body,
                credentials: 'same-origin'
            })
                .then(function (response) { return response.json(); })
                .then(function (data) {
                    if (data.success) {
                        showMessage('success', data.message || 'Demande enregistrée.');
                        form.reset();
                        return loadChambres();
                    }

                    const errors = {
                        client_validation: 'Vérifiez vos coordonnées.',
                        reservation_validation: 'Dates invalides.',
                        chambre_invalid: 'Chambre invalide.',
                        reservation_overlap: 'Cette chambre n\'est pas disponible sur cette période.',
                        csrf_invalid: 'Session expirée. Rechargez la page.',
                        database_unavailable: 'Service temporairement indisponible.'
                    };
                    showMessage('error', errors[data.error] || 'La demande n\'a pas pu être enregistrée.');
                })
                .catch(function () {
                    showMessage('error', 'Erreur réseau. Réessayez ou contactez-nous par téléphone.');
                })
                .finally(function () {
                    submitButton.disabled = false;
                    submitButton.textContent = originalText;
                });
        });
    });
});
