<?php
/*
Plugin Name: QuestMaster Pro
Description: Une extension pour intégrer le jeu AventuraQuest dans WordPress.
Version: 1.0
Author: Anselm Trinh
*/

// Enregistrez la fonction d'activation du plugin
register_activation_hook(__FILE__, 'activer_questmaster_pro');

// Fonction d'activation du plugin
function activer_questmaster_pro()
{
    // Effectuez les actions nécessaires lors de l'activation du plugin
    // Par exemple, créez des tables de base de données, initialisez des options, etc.
}

// Fonction pour afficher la page de votre plugin
function afficher_page_questmaster_pro()
{
    echo '<div class="wrap">';
    echo '<h2>QuestMaster Pro</h2>';

    // Ajoutez ici le contenu de votre page
    // Par exemple, la carte du jeu, la gestion des armes, etc.

    echo '</div>';
    $selected_page = get_option('questmaster_page_id'); // Récupère la valeur actuelle de l'option
    $pages = get_pages(); // Récupère la liste des pages WordPress

    echo '<label for="questmaster_page_id">Sélectionnez la page :</label>';
    echo '<select id="questmaster_page_id" name="questmaster_page_id">';
    echo '<option value="0">-- Aucune page --</option>';
    foreach ($pages as $page) {
        echo '<option value="' . $page->ID . '" ' . selected($selected_page, $page->ID, false) . '>' . esc_html($page->post_title) . '</option>';
    }
    echo '</select>';

    echo '</div>';
    // Bouton de sauvegarde
    echo '<p>';
    submit_button('Enregistrer', 'primary', 'enregistrer_option_questmaster', false);
    echo '</p>';

    echo '</form>';
    echo '</div>';
}

// Ajoutez la page de votre plugin au menu d'administration
function ajouter_menu_questmaster_pro()
{
    add_menu_page(
        'QuestMaster Pro',       // Titre de la page
        'QuestMaster Pro',       // Texte du menu
        'manage_options',        // Capacité nécessaire pour accéder à la page
        'questmaster-pro',       // Slug de la page
        'afficher_page_questmaster_pro' // Fonction pour afficher la page
    );
}

// Ajoutez des actions pour gérer d'autres fonctionnalités de votre plugin
// Par exemple, la gestion des armes, la carte du jeu, etc.

add_action('admin_menu', 'ajouter_menu_questmaster_pro');


function questmaster_pro_display()
{

    ob_start();

?>

    <div>

        <?php

        // Inclure le contenu de votre fichier index.php

        include(plugin_dir_path(__FILE__) . '\index.php'); // le fichier pricipal du projet de php 

        ?>

    </div>

<?php

    return ob_get_clean();
}



function questmaster_pro_display_shortcode()
{

    return questmaster_pro_display();
}



add_shortcode('questmaster_pro-game', 'questmaster_pro_display_shortcode');


function enqueue_custom_styles()
{

    wp_enqueue_style('custom-style', plugins_url('style.css', __FILE__), array(), null);
}

add_action('wp_enqueue_scripts', 'enqueue_custom_styles');

function enregistrer_script_mon_jeu()
{
    // Enregistrez le script du jeu JavaScript
    wp_enqueue_script('mon-jeu', plugins_url('script.js', __FILE__), array('jquery'), '1.0', true);
}
add_action('wp_enqueue_scripts', 'enregistrer_script_mon_jeu');


function afficher_option_selectionnee()
{
    // Affichez l'option de sélection dans la page d'administration
    $selected_page = get_option('questmaster_page_id'); // Récupère la valeur actuelle de l'option
    $pages = get_pages(); // Récupère la liste des pages WordPress

    echo '<div class="wrap">';
    echo '<h2>QuestMaster Pro - Paramètres</h2>';
    echo '<form method="post" action="">';
    echo '<label for="questmaster_page_id">Sélectionnez la page :</label>';
    echo '<select id="questmaster_page_id" name="questmaster_page_id">';
    echo '<option value="0">-- Aucune page --</option>';
    foreach ($pages as $page) {
        echo '<option value="' . $page->ID . '" ' . selected($selected_page, $page->ID, false) . '>' . esc_html($page->post_title) . '</option>';
    }
    echo '</select>';
    echo '<p><input type="submit" class="button button-primary" value="Enregistrer"></p>';
    echo '</form>';
    echo '</div>';
}

// Ajoutez un lien vers la page de configuration dans le menu d'administration
function ajouter_lien_parametres()
{
    add_submenu_page(
        'questmaster-pro',            // Slug de la page parente
        'Paramètres QuestMaster Pro', // Titre de la page
        'Paramètres',                 // Texte du menu
        'manage_options',             // Capacité nécessaire pour accéder à la page
        'questmaster-pro-parametres', // Slug de la page
        'afficher_option_selectionnee' // Fonction pour afficher la page de configuration
    );
}
add_action('admin_menu', 'ajouter_lien_parametres');


// Fonction pour enregistrer l'option sélectionnée
function enregistrer_option_questmaster()
{
    if (isset($_POST['enregistrer_option_questmaster'])) {
        if (isset($_POST['questmaster_page_id'])) {
            update_option('questmaster_page_id', intval($_POST['questmaster_page_id']));
        }
    }
}
add_action('admin_post', 'enregistrer_option_questmaster');


// BOUTIQUE
function afficher_page_boutique_questmaster_pro()
{
    echo '<div class="wrap">';
    echo '<h2>Boutique QuestMaster Pro</h2>';

    // Créez un tableau contenant plusieurs cartes
    $cartes = array();

    // Ajoutez des cartes au tableau
    $cartes[] = array(
        'nom' => 'Carte 1',
        'description' => 'Description de la carte 1',
        'prix' => 10.00,
        'image' => './assets/carte.jpg',
    );

    $cartes[] = array(
        'nom' => 'Carte 2',
        'description' => 'Description de la carte 2',
        'prix' => 15.00,
        'image' => './assets/epee.png',
    );

    // Utilisez la boucle foreach pour afficher toutes les cartes
    foreach ($cartes as $carte) {
        echo '<div class="carte">';
        echo '<img src="' . esc_url($carte['image']) . '" alt="' . esc_attr($carte['nom']) . '">';
        echo '<h3>' . esc_html($carte['nom']) . '</h3>';
        echo '<p>' . esc_html($carte['description']) . '</p>';
        echo '<p>Prix : ' . esc_html($carte['prix']) . ' €</p>';
        echo '<button class="buy-button" data-product="' . esc_attr($carte['nom']) . '">Acheter</button>';
        echo '</div>';
    }

    echo '</div>';
}

add_action('admin_menu', 'ajouter_lien_parametres');

// Ajoutez la page de la boutique au menu d'administration
function ajouter_menu_boutique_questmaster_pro()
{
    add_submenu_page(
        'questmaster-pro',                  // Slug de la page parente
        'Boutique QuestMaster Pro',         // Titre de la page
        'Boutique',                         // Texte du menu
        'manage_options',                   // Capacité nécessaire pour accéder à la page
        'questmaster-pro-boutique',         // Slug de la page
        'afficher_page_boutique_questmaster_pro' // Fonction pour afficher la page de la boutique
    );
}
add_action('admin_menu', 'ajouter_menu_boutique_questmaster_pro');
