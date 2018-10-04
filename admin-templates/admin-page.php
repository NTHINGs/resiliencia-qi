<?php
/**
 * Mostrar menu en wp-admin
 *
 *
 * @package	 resiliencia-qi
 * @since    1.0.0
 */
if ( file_exists( RES_PLUGIN_PATH . 'util/util.php' ) ) {
	require_once( RES_PLUGIN_PATH . 'util/util.php' );
}
if(!class_exists('Resultados_Resiliencia_Table')){
    require_once( RES_PLUGIN_PATH . 'util/Resultados_Resiliencia_Table.php' );
}
// Creando Página en dashboard
add_action( 'admin_menu', 'resiliencia_qi_admin' );

function resiliencia_qi_admin() {
    add_menu_page(
        'Cuestionario Resiliencia',     // page title
        'Cuestionario Resiliencia',     // menu title
        'resiliencia',   // capability
        'cuestionario-resiliencia',     // menu slug
		'render_resiliencia_qi_admin', // callback function
		'dashicons-universal-access'
    );
}
function render_resiliencia_qi_admin() {
    global $title;
	
	if (current_user_can('resiliencia') && !current_user_can('resiliencia_admin')) {
        // Render pagina de organizacion
        $wp_list_table = new Resultados_Resiliencia_Table();
        $wp_list_table->prepare_items();
        $variables = array(
            "%TITLE%",
            "%SITE_URL%",
            "%HASH%",
        );
        $values = array(
            $title,
            get_site_url(),
            get_user_hash(),
        );
        print str_replace($variables, $values, file_get_contents(  RES_PLUGIN_PATH . "templates/resultados-organizacion.html" ));
        print '<div id="poststuff">';
        print '<div id="post-body" class="metabox-holder columns-2">';
        print '<div id="post-body-content">';
        print '<div class="meta-box-sortables ui-sortable">';
        print '<form method="post">';
        $wp_list_table->display();
        print '</form>';
        print '</div>';
        print '</div>';
        print '</div>';
        print '<br class="clear">';
        print '</div>';
        print '</div>';
	} elseif (current_user_can('resiliencia_admin')) {
        // Render pagina de todas las organizaciones
        $variables = array(
            "%TITLE%",
        );
        $values = array(
            $title,
        );
		print str_replace($variables, $values, file_get_contents(  RES_PLUGIN_PATH . "templates/resultados-generales.html" ));
	}
	
}