<?php
/**
 * --------------------------------------------------------------
 * File: mits_imageslider.php
 * Date: 16.07.2020
 * Time: 17:37
 *
 * Author: Hetfield
 * Copyright: (c) 2020 - MerZ IT-SerVice
 * Web: https://www.merz-it-service.de
 * Contact: info@merz-it-service.de
 * --------------------------------------------------------------
 */

defined('HEADING_TITLE_IMAGESLIDERS') or define('HEADING_TITLE_IMAGESLIDERS', 'MITS ImageSlider <small style="font-weight:normal;font-size:0.6em;">&copy; 2008-' . date('Y') . ' by <a href="https://www.merz-it-service.de/" target="_blank">Hetfield</a></small>');
defined('HEADING_SUBTITLE_IMAGESLIDERS') or define('HEADING_SUBTITLE_IMAGESLIDERS', '<a href="https://www.merz-it-service.de/" target="_blank"><img src="' . DIR_WS_EXTERNAL . 'mits_imageslider/images/merz-it-service.png" border="0" alt="" style="display:block;max-width:100%;height:auto;max-height:40px;margin-top:6px;margin-bottom:6px;" /></a>');
defined('TABLE_HEADING_IMAGESLIDERS') or define('TABLE_HEADING_IMAGESLIDERS', 'MITS ImageSlider');
defined('TABLE_HEADING_IMAGESLIDERS_NAME') or define('TABLE_HEADING_IMAGESLIDERS_NAME', 'Nom ImageSlider');
defined('TABLE_HEADING_IMAGESLIDERS_IMAGE') or define('TABLE_HEADING_IMAGESLIDERS_IMAGE', 'Image');
defined('TABLE_HEADING_SLIDERGROUP') or define('TABLE_HEADING_SLIDERGROUP', 'Groupe ImageSlider');
defined('TABLE_HEADING_SORTING') or define('TABLE_HEADING_SORTING', 'Tri');
defined('TABLE_HEADING_STATUS') or define('TABLE_HEADING_STATUS', 'Statut');
defined('TABLE_HEADING_ACTION') or define('TABLE_HEADING_ACTION', 'Action');
defined('TEXT_HEADING_NEW_IMAGESLIDER') or define('TEXT_HEADING_NEW_IMAGESLIDER', 'Nouvelle image');
defined('TEXT_HEADING_EDIT_IMAGESLIDER') or define('TEXT_HEADING_EDIT_IMAGESLIDER', 'Modifier l&rsquo;image');
defined('TEXT_HEADING_DELETE_IMAGESLIDER') or define('TEXT_HEADING_DELETE_IMAGESLIDER', 'Supprimer l&rsquo;image');
defined('TEXT_IMAGESLIDERS') or define('TEXT_IMAGESLIDERS', 'Image:');
defined('TEXT_DATE_ADDED') or define('TEXT_DATE_ADDED', 'ajout&eacute; le:');
defined('TEXT_LAST_MODIFIED') or define('TEXT_LAST_MODIFIED', 'derni&egrave;re modification:');
defined('TEXT_IMAGE_NONEXISTENT') or define('TEXT_IMAGE_NONEXISTENT', 'Image inexistante');
defined('TEXT_NEW_INTRO') or define('TEXT_NEW_INTRO', 'Veuillez ajouter la nouvelle image avec toutes les donn&eacute;es utiles.');
defined('TEXT_EDIT_INTRO') or define('TEXT_EDIT_INTRO', 'Veuillez effectuer les modifications n&eacute;cessaires');
defined('TEXT_IMAGESLIDERS_TITLE') or define('TEXT_IMAGESLIDERS_TITLE', 'Titre de l&rsquo;image:');
defined('TEXT_IMAGESLIDERS_ALT') or define('TEXT_IMAGESLIDERS_ALT', 'Texte alt de l&rsquo;image: <small style="font-weight:normal">(&lt;img <b>alt=""</b>&gt;)</small>');
defined('TEXT_IMAGESLIDERS_NAME') or define('TEXT_IMAGESLIDERS_NAME', 'Nom de l&rsquo;entr&eacute;e image:');
defined('TEXT_IMAGESLIDERS_IMAGE') or define('TEXT_IMAGESLIDERS_IMAGE', 'Image:');
defined('TEXT_IMAGESLIDERS_TABLET_IMAGE') or define('TEXT_IMAGESLIDERS_TABLET_IMAGE', 'Image pour tablettes (600px - 1023px):');
defined('TEXT_IMAGESLIDERS_MOBILE_IMAGE') or define('TEXT_IMAGESLIDERS_MOBILE_IMAGE', 'Image pour mobile (- 600px):');
defined('TEXT_IMAGESLIDERS_URL') or define('TEXT_IMAGESLIDERS_URL', 'Lien de l&rsquo;image vers l&rsquo;URL suivante:');
defined('TEXT_IMAGESLIDERS_LINKTITLE') or define('TEXT_IMAGESLIDERS_LINKTITLE', 'Titre du lien: <small style="font-weight:normal">(&lt;a <b>title=""</b>&gt;)</small>');
defined('TEXT_TARGET') or define('TEXT_TARGET', 'Fen&ecirc;tre cible:');
defined('TEXT_TYP') or define('TEXT_TYP', 'Type de lien:');
defined('TEXT_URL') or define('TEXT_URL', 'URL du lien:');
defined('NONE_TARGET') or define('NONE_TARGET', 'aucune cible');
defined('TARGET_BLANK') or define('TARGET_BLANK', '_blank');
defined('TARGET_TOP') or define('TARGET_TOP', '_top');
defined('TARGET_SELF') or define('TARGET_SELF', '_self');
defined('TARGET_PARENT') or define('TARGET_PARENT', '_parent');
defined('TYP_PRODUCT') or define('TYP_PRODUCT', 'Lien vers un produit (saisir uniquement la productsID dans l&rsquo;URL du lien)');
defined('TYP_CATEGORIE') or define('TYP_CATEGORIE', 'Lien vers une cat&eacute;gorie (saisir uniquement la catID dans l&rsquo;URL du lien)');
defined('TYP_CONTENT') or define('TYP_CONTENT', 'Lien vers une page de contenu (saisir uniquement la coID dans l&rsquo;URL du lien)');
defined('TYP_MANUFACTURER') or define('TYP_MANUFACTURER', 'Lien vers un fabricant (saisir uniquement la mID dans l&rsquo;URL du lien)');
defined('TYP_INTERN') or define('TYP_INTERN', 'Lien interne de la boutique (p. ex. account.php ou newsletter.php)');
defined('TYP_EXTERN') or define('TYP_EXTERN', 'Lien externe (p. ex. http://www.example.org)');
defined('TEXT_IMAGESLIDERS_DESCRIPTION') or define('TEXT_IMAGESLIDERS_DESCRIPTION', 'Description de l&rsquo;image:');
defined('TEXT_DELETE_INTRO') or define('TEXT_DELETE_INTRO', 'Voulez-vous vraiment supprimer cette image?');
defined('TEXT_DELETE_IMAGE') or define('TEXT_DELETE_IMAGE', 'Supprimer aussi l&rsquo;image?');
defined('ERROR_DIRECTORY_NOT_WRITEABLE') or define('ERROR_DIRECTORY_NOT_WRITEABLE', 'Erreur: le r&eacute;pertoire %s n&rsquo;est pas accessible en &eacute;criture. Veuillez corriger les droits d&rsquo;acc&egrave;s!');
defined('ERROR_DIRECTORY_DOES_NOT_EXIST') or define('ERROR_DIRECTORY_DOES_NOT_EXIST', 'Erreur: le r&eacute;pertoire %s n&rsquo;existe pas!');
defined('TEXT_DISPLAY_NUMBER_OF_IMAGESLIDERS') or define('TEXT_DISPLAY_NUMBER_OF_IMAGESLIDERS', 'Affichage de <b>%d</b> &agrave; <b>%d</b> (sur <b>%d</b> entr&eacute;es ImageSlider)');
defined('IMAGE_ICON_STATUS_GREEN') or define('IMAGE_ICON_STATUS_GREEN', 'Actif');
defined('IMAGE_ICON_STATUS_GREEN_LIGHT') or define('IMAGE_ICON_STATUS_GREEN_LIGHT', 'Activer');
defined('IMAGE_ICON_STATUS_RED') or define('IMAGE_ICON_STATUS_RED', 'Inactif');
defined('IMAGE_ICON_STATUS_RED_LIGHT') or define('IMAGE_ICON_STATUS_RED_LIGHT', 'D&eacute;sactiver');
defined('MITS_ACTIVE') or define('MITS_ACTIVE', 'Activer');
defined('MITS_NOTACTIVE') or define('MITS_NOTACTIVE', 'D&eacute;sactiver');
defined('TEXT_IMAGESLIDERS_GROUP') or define('TEXT_IMAGESLIDERS_GROUP', 'Groupe ImageSlider:');
defined('TEXT_IMAGESLIDERS_NEW_GROUP') or define('TEXT_IMAGESLIDERS_NEW_GROUP', 'Choisissez un groupe ImageSlider existant ou saisissez un nouveau groupe ImageSlider ci-dessous.');
defined('TEXT_IMAGESLIDERS_NEW_GROUP_NOTE') or define('TEXT_IMAGESLIDERS_NEW_GROUP_NOTE', 'Pour afficher un ImageSlider dans le template, le template doit &ecirc;tre adapt&eacute;.<br/>Exemple: si le groupe ImageSlider est <i>MITS_IMAGESLIDER</i>, il peut &ecirc;tre affich&eacute; dans index.html avec <i>{$MITS_IMAGESLIDER}</i>.<br /><br /><a href="https://imageslider.merz-it-service.de/readme.html" target="_blank" onclick="window.open(\'https://imageslider.merz-it-service.de/readme.html\', \'Instructions MITS ImageSlider\', \'scrollbars=yes,resizable=yes,menubar=yes,width=960,height=600\'); return false"><strong><u>Instructions MITS ImageSlider &raquo;</u></strong></a>');
defined('ERROR_IMAGESLIDER_NAME_REQUIRED') or define('ERROR_IMAGESLIDER_NAME_REQUIRED', 'Erreur: un titre ImageSlider est requis.');
defined('ERROR_IMAGESLIDER_GROUP_REQUIRED') or define('ERROR_IMAGESLIDER_GROUP_REQUIRED', 'Erreur: un groupe ImageSlider est requis.');
defined('ERROR_IMAGESLIDER_IMAGE_REQUIRED') or define('ERROR_IMAGESLIDER_IMAGE_REQUIRED', 'Erreur: une image ImageSlider est requise.');
defined('TEXT_IMAGESLIDERS_DATE_FORMAT') or define('TEXT_IMAGESLIDERS_DATE_FORMAT', 'AAAA-MM-JJ');
defined('TEXT_IMAGESLIDERS_EXPIRES_ON') or define('TEXT_IMAGESLIDERS_EXPIRES_ON', 'Valable jusqu&rsquo;au:');
defined('TEXT_IMAGESLIDERS_IMPRESSIONS') or define('TEXT_IMAGESLIDERS_IMPRESSIONS', 'impressions/vues.');
defined('TEXT_IMAGESLIDERS_SCHEDULED_AT') or define('TEXT_IMAGESLIDERS_SCHEDULED_AT', 'Valable &agrave; partir du:');
defined('TEXT_IMAGESLIDERS_EXPIRCY_NOTE') or define('TEXT_IMAGESLIDERS_EXPIRCY_NOTE', '<b>Remarques sur la fin de validit&eacute;:</b><ul><li>La date de d&eacute;but et la date de fin peuvent &ecirc;tre combin&eacute;es.</li><li>Laissez ces champs vides si l&rsquo;image doit &ecirc;tre affich&eacute;e sans limite de date.</li><li>Le statut manuel n&rsquo;est pas modifi&eacute; automatiquement.</li></ul>');
defined('TEXT_IMAGESLIDERS_SCHEDULE_NOTE') or define('TEXT_IMAGESLIDERS_SCHEDULE_NOTE', '<b>Remarques sur la planification:</b><ul><li>Si une date de d&eacute;but est d&eacute;finie, l&rsquo;image est affich&eacute;e &agrave; partir de cette date.</li><li>La v&eacute;rification des dates se fait dans le frontend sans modification automatique du statut.</li></ul>');
defined('TEXT_IMAGESLIDERS_DATE_ADDED') or define('TEXT_IMAGESLIDERS_DATE_ADDED', 'Date d&rsquo;ajout:');
defined('TEXT_IMAGESLIDERS_SCHEDULED_AT_DATE') or define('TEXT_IMAGESLIDERS_SCHEDULED_AT_DATE', 'Valable &agrave; partir du: <b>%s</b>');
defined('TEXT_IMAGESLIDERS_EXPIRES_AT_DATE') or define('TEXT_IMAGESLIDERS_EXPIRES_AT_DATE', 'Valable jusqu&rsquo;au: <b>%s</b>');
defined('TEXT_IMAGESLIDERS_RECURRING') or define('TEXT_IMAGESLIDERS_RECURRING', 'Validit&eacute; r&eacute;currente:');
defined('TEXT_IMAGESLIDERS_RECURRING_YEARLY') or define('TEXT_IMAGESLIDERS_RECURRING_YEARLY', 'r&eacute;p&eacute;ter chaque ann&eacute;e');
defined('TEXT_IMAGESLIDERS_RECURRING_NOTE') or define('TEXT_IMAGESLIDERS_RECURRING_NOTE', '<b>Validit&eacute; r&eacute;currente:</b><ul><li>Si activ&eacute;, seuls le jour et le mois de la date de d&eacute;but/fin sont utilis&eacute;s.</li><li>Exemple: 2026-12-24 &agrave; 2027-01-06 est affich&eacute; chaque ann&eacute;e du 24.12. au 06.01.</li><li>Le statut manuel reste le commutateur principal.</li></ul>');
defined('MITS_IMAGESLIDER_IMPORT_HEADING') or define('MITS_IMAGESLIDER_IMPORT_HEADING', 'MITS ImageSlider - Import du gestionnaire de banni&egrave;res');
defined('MITS_IMAGESLIDER_IMPORT_INTRO') or define('MITS_IMAGESLIDER_IMPORT_INTRO', 'Cet outil importe les groupes et entr&eacute;es de banni&egrave;res du gestionnaire de banni&egrave;res dans MITS ImageSlider.');
defined('MITS_IMAGESLIDER_IMPORT_DRY_RUN') or define('MITS_IMAGESLIDER_IMPORT_DRY_RUN', 'Aper&ccedil;u / simulation');
defined('MITS_IMAGESLIDER_IMPORT_EXECUTE') or define('MITS_IMAGESLIDER_IMPORT_EXECUTE', 'Lancer l&rsquo;import');
defined('MITS_IMAGESLIDER_IMPORT_OVERWRITE') or define('MITS_IMAGESLIDER_IMPORT_OVERWRITE', 'Mettre &agrave; jour les banni&egrave;res d&eacute;j&agrave; import&eacute;es');
defined('MITS_IMAGESLIDER_IMPORT_GROUP_FILTER') or define('MITS_IMAGESLIDER_IMPORT_GROUP_FILTER', 'Filtrer le groupe de banni&egrave;res');
defined('MITS_IMAGESLIDER_IMPORT_RESULT') or define('MITS_IMAGESLIDER_IMPORT_RESULT', 'R&eacute;sultat de l&rsquo;import');
defined('MITS_IMAGESLIDER_IMPORT_TOTAL') or define('MITS_IMAGESLIDER_IMPORT_TOTAL', 'Total');
defined('MITS_IMAGESLIDER_IMPORT_IMPORTED') or define('MITS_IMAGESLIDER_IMPORT_IMPORTED', 'Import&eacute;');
defined('MITS_IMAGESLIDER_IMPORT_UPDATED') or define('MITS_IMAGESLIDER_IMPORT_UPDATED', 'Mis &agrave; jour');
defined('MITS_IMAGESLIDER_IMPORT_SKIPPED') or define('MITS_IMAGESLIDER_IMPORT_SKIPPED', 'Ignor&eacute;');
defined('MITS_IMAGESLIDER_IMPORT_ERRORS') or define('MITS_IMAGESLIDER_IMPORT_ERRORS', 'Erreurs');
defined('MITS_IMAGESLIDER_IMPORT_NO_BANNERS_TABLE') or define('MITS_IMAGESLIDER_IMPORT_NO_BANNERS_TABLE', 'La table des banni&egrave;res est introuvable.');
defined('MITS_IMAGESLIDER_IMPORT_NO_BANNERS') or define('MITS_IMAGESLIDER_IMPORT_NO_BANNERS', 'Aucune banni&egrave;re trouv&eacute;e.');
defined('MITS_IMAGESLIDER_IMPORT_BACK') or define('MITS_IMAGESLIDER_IMPORT_BACK', 'Retour &agrave; ImageSlider');
