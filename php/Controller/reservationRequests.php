<?php

/**
 * Actions POST liées aux réservations.
 */

function controller_handleReservationAction($action, $baseUrl)
{
    if ($action === 'add_reservation') {
        $dateIn = trim(controller_postString('date_in'));
        $dateOut = trim(controller_postString('date_out'));
        $idChambre = (int) controller_postString('id_chambre');
        $idClient = (int) controller_postString('id_client');

        if (!validation_reservationDateRange($dateIn, $dateOut)) {
            controller_redirect($baseUrl, '?error=reservation_validation');
        }
        if (!validation_positiveInt($idChambre) || !validation_positiveInt($idClient)) {
            controller_redirect($baseUrl, '?error=reservation_validation');
        }
        if (!reservationModel_foreignKeysExist($idChambre, $idClient)) {
            controller_redirect($baseUrl, '?error=reservation_validation');
        }
        if (reservationModel_hasOverlap($idChambre, $dateIn, $dateOut, null)) {
            controller_redirect($baseUrl, '?error=reservation_overlap');
        }

        $ok = reservationModel_insert($dateIn, $dateOut, $idChambre, $idClient);
        controller_redirect($baseUrl, $ok ? '?success=reservation_add' : '?error=reservation_db');
    }

    if ($action === 'update_reservation') {
        $id = (int) controller_postString('id');
        $dateIn = trim(controller_postString('date_in'));
        $dateOut = trim(controller_postString('date_out'));
        $idChambre = (int) controller_postString('id_chambre');
        $idClient = (int) controller_postString('id_client');

        if (!validation_positiveInt($id)) {
            controller_redirect($baseUrl, '?error=reservation_validation');
        }
        if (!validation_reservationDateRange($dateIn, $dateOut)) {
            controller_redirect($baseUrl, '?error=reservation_validation&edit_reservation=' . $id);
        }
        if (!validation_positiveInt($idChambre) || !validation_positiveInt($idClient)) {
            controller_redirect($baseUrl, '?error=reservation_validation&edit_reservation=' . $id);
        }
        if (reservationModel_getById($id) === null) {
            controller_redirect($baseUrl, '?error=reservation_db');
        }
        if (!reservationModel_foreignKeysExist($idChambre, $idClient)) {
            controller_redirect($baseUrl, '?error=reservation_validation&edit_reservation=' . $id);
        }
        if (reservationModel_hasOverlap($idChambre, $dateIn, $dateOut, $id)) {
            controller_redirect($baseUrl, '?error=reservation_overlap&edit_reservation=' . $id);
        }

        $ok = reservationModel_update($id, $dateIn, $dateOut, $idChambre, $idClient);
        controller_redirect($baseUrl, $ok ? '?success=reservation_update' : '?error=reservation_db');
    }

    if ($action === 'delete_reservation') {
        $id = (int) controller_postString('id');
        if (!validation_positiveInt($id)) {
            controller_redirect($baseUrl, '?error=reservation_validation');
        }

        $ok = reservationModel_delete($id);
        controller_redirect($baseUrl, $ok ? '?success=reservation_delete' : '?error=reservation_db');
    }
}

