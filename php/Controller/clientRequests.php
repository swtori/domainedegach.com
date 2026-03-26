<?php

/**
 * Actions POST liées aux clients.
 */

function controller_handleClientAction($action, $baseUrl)
{
    if ($action === 'add_client') {
        $username = controller_postString('username');
        $lastname = controller_postString('lastname');
        $tel = controller_postString('tel');
        $email = controller_postString('email');

        $errs = validation_clientPayload($username, $lastname, $tel, $email);
        if (!empty($errs)) {
            controller_redirect($baseUrl, '?error=client_validation');
        }
        if (clientModel_emailTakenByOther($email, 0)) {
            controller_redirect($baseUrl, '?error=client_email_dup');
        }

        $ok = clientModel_insert($username, $lastname, $tel, $email);
        controller_redirect($baseUrl, $ok ? '?success=client_add' : '?error=client_db');
    }

    if ($action === 'update_client') {
        $id = (int) controller_postString('id');
        $username = controller_postString('username');
        $lastname = controller_postString('lastname');
        $tel = controller_postString('tel');
        $email = controller_postString('email');

        if (!validation_positiveInt($id)) {
            controller_redirect($baseUrl, '?error=client_validation');
        }

        $errs = validation_clientPayload($username, $lastname, $tel, $email);
        if (!empty($errs)) {
            controller_redirect($baseUrl, '?error=client_validation&edit_client=' . $id);
        }
        if (clientModel_emailTakenByOther($email, $id)) {
            controller_redirect($baseUrl, '?error=client_email_dup&edit_client=' . $id);
        }
        if (clientModel_getById($id) === null) {
            controller_redirect($baseUrl, '?error=client_db');
        }

        $ok = clientModel_update($id, $username, $lastname, $tel, $email);
        controller_redirect($baseUrl, $ok ? '?success=client_update' : '?error=client_db');
    }

    if ($action === 'delete_client') {
        $id = (int) controller_postString('id');
        if (!validation_positiveInt($id)) {
            controller_redirect($baseUrl, '?error=client_validation');
        }

        $ok = clientModel_delete($id);
        controller_redirect($baseUrl, $ok ? '?success=client_delete' : '?error=client_db');
    }
}

