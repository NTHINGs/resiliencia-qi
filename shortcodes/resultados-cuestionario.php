<?php
/**
 * Mostrar resultados del cuestionario
 *
 * [resultados-cuestionario]
 *
 * @package	 resiliencia-qi
 * @since    1.0.0
 */
if ( file_exists( RES_PLUGIN_PATH . 'util/util.php' ) ) {
	require_once( RES_PLUGIN_PATH . 'util/util.php' );
}
if ( ! function_exists( 'resultados_cuestionario_shortcode' ) ) {
	// Add the action.
	add_action( 'plugins_loaded', function() {
		// Add the shortcode.
		add_shortcode( 'resultados-cuestionario', 'resultados_cuestionario_shortcode' );
	});

	/**
	 * resultados-cuestionario shortcode.
	 *
	 * @return string
	 * @since  1.0.0
	 */
	function resultados_cuestionario_shortcode($atts) {
        ob_start();
        render_html_resultados_cuestionario($atts);
        return ob_get_clean();
    }
    
    function render_html_resultados_cuestionario($atts) {
        $_atts = shortcode_atts( array(
            'cuestionario_id'  => NULL,
        ), $atts );
        $cuestionario_id = $_atts['cuestionario_id'];
        if($cuestionario_id == NULL) {
            // Resultados generales de la organizacion
            echo resultados_organizacion_resiliencia();
        } elseif ($cuestionario_id != NULL) {
            // Resultados individuales
            echo resultados_por_cuestionario_resiliencia($cuestionario_id);
        } else {
            echo 'ERROR: El shortcode tiene parametros incorrectos.';
        }
    }

    function resultados_organizacion_resiliencia() {
        $current_user = wp_get_current_user();
        $org_id = get_user_meta($current_user->ID, 'hash', true);
    }
    
    function resultados_por_cuestionario_resiliencia($cuestionario_id) {
        $variables = array(
            '%DATA%',
        );

        $resultados = get_resultados($cuestionario_id);
        $values = array(
            json_encode($resultados),
        );
        
        return str_replace($variables, $values, file_get_contents( plugin_dir_path( __DIR__ ) . "/templates/resultados-cuestionario-individuales.html" ));
    }

}
