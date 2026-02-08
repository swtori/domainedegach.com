// Configuration EmailJS
const EMAILJS_SERVICE_ID = 'service_gach';
const EMAILJS_TEMPLATE_ID = 'template_2fn54q3';
const DOMAIN_EMAIL = 'contact@domainedegach.com';

// Fonction pour récupérer les données du formulaire
function getFormData() {
    const name = document.getElementById('name').value.trim();
    const surname = document.getElementById('surname')?.value.trim() || '';
    const fullName = surname ? `${name} ${surname}`.trim() : name;
    
    return {
        name,
        surname,
        fullName: fullName || name || 'Visiteur',
        email: document.getElementById('email').value.trim(),
        phone: document.getElementById('phone').value.trim(),
        subject: document.getElementById('subject').value.trim(),
        message: document.getElementById('message').value.trim()
    };
}

// Fonction pour préparer les paramètres EmailJS
function prepareTemplateParams(formData) {
    return {
        to_surname: formData.surname || formData.name || 'Visiteur',
        to_lastname: formData.name || '',
        to_email: formData.email,
        phone: formData.phone || 'Non renseigné',
        subject: formData.subject || 'Message depuis le site',
        message: formData.message
    };
}

// Fonction pour réinitialiser le bouton
function resetButton(button, originalText, form) {
    button.disabled = false;
    button.textContent = originalText;
    if (form) {
        form.classList.remove('loading');
    }
}

// Fonction pour désactiver le bouton pendant l'envoi
function disableButton(button, loadingText, form) {
    button.disabled = true;
    button.textContent = loadingText;
    if (form) {
        form.classList.add('loading');
    }
}

// Fonction pour envoyer l'email via EmailJS
function sendEmail(templateParams, onSuccess, onError) {
    if (typeof emailjs === 'undefined') {
        onError('EmailJS n\'est pas chargé. Veuillez rafraîchir la page.');
        return;
    }

    emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_ID, templateParams)
        .then(function(response) {
            onSuccess(response);
        }, function(error) {
            const errorMessage = error.text || 'Une erreur est survenue lors de l\'envoi.';
            onError(errorMessage);
            console.error('Erreur EmailJS:', error);
        });
}

// Fonction pour envoyer deux emails : un à l'utilisateur et un au domaine
function sendBothEmails(templateParams, onSuccess, onError) {
    if (typeof emailjs === 'undefined') {
        onError('EmailJS n\'est pas chargé. Veuillez rafraîchir la page.');
        return;
    }

    // Email 1 : au client — {{destinataire}} = client, {{to_email}} = client
    const paramsClient = { ...templateParams, destinataire: templateParams.to_email };

    // Email 2 : aux hôtes — {{destinataire}} = contact@domainedegach.com, {{to_email}} = client (inchangé)
    const paramsDomaine = { ...templateParams, destinataire: DOMAIN_EMAIL };

    // Envoyer d'abord l'email au client
    emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_ID, paramsClient)
        .then(function(userResponse) {
            console.log('Email client envoyé:', userResponse);
            // Puis envoyer l'email aux hôtes
            return emailjs.send(EMAILJS_SERVICE_ID, EMAILJS_TEMPLATE_ID, paramsDomaine);
        })
        .then(function(domainResponse) {
            console.log('Email domaine envoyé:', domainResponse);
            onSuccess(domainResponse);
        })
        .catch(function(error) {
            const errorMessage = error.text || 'Une erreur est survenue lors de l\'envoi.';
            onError(errorMessage);
            console.error('Erreur EmailJS:', error);
        });
}

// Gestion du formulaire de contact
document.addEventListener('DOMContentLoaded', function() {
    const emailForm = document.getElementById('emailForm');
    const contactForm = document.getElementById('contactForm');
    const formMessage = document.getElementById('formMessage');
    
    // Gérer le formulaire emailForm
    if (emailForm) {
        emailForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitButton = emailForm.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            disableButton(submitButton, 'Envoi en cours...', emailForm);

            const formData = getFormData();
            const templateParams = prepareTemplateParams(formData);

            sendBothEmails(
                templateParams,
                function(response) {
                    alert('Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.');
                    emailForm.reset();
                    resetButton(submitButton, originalText, emailForm);
                },
                function(errorMessage) {
                    alert(`${errorMessage}\n\nVeuillez réessayer ou nous contacter directement par email.`);
                    resetButton(submitButton, originalText, emailForm);
                }
            );
        });
    }

    // Gérer l'ancien formulaire contactForm (pour compatibilité)
    if (contactForm && formMessage) {
        contactForm.addEventListener('submit', function(e) {
            e.preventDefault();

            const submitButton = contactForm.querySelector('button[type="submit"]');
            const originalText = submitButton.textContent;
            
            disableButton(submitButton, 'Envoi en cours...', contactForm);

            const formData = getFormData();
            const templateParams = prepareTemplateParams(formData);

            sendBothEmails(
                templateParams,
                function(response) {
                    formMessage.className = 'form-message success';
                    formMessage.textContent = 'Votre message a été envoyé avec succès ! Nous vous répondrons dans les plus brefs délais.';
                    contactForm.reset();
                    resetButton(submitButton, originalText, contactForm);
                },
                function(errorMessage) {
                    formMessage.className = 'form-message error';
                    formMessage.textContent = `${errorMessage} Veuillez réessayer ou nous contacter directement par email.`;
                    resetButton(submitButton, originalText, contactForm);
                }
            );
        });
    }
});
