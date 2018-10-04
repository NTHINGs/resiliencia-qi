<?php
/**
 * Funciones compartidas entre varios archivos del plugin
 *
 *
 * @package	 resiliencia-qi
 * @since    1.0.0
 */

function get_user_hash() {
    $current_user = wp_get_current_user();
    $hash = get_user_meta($current_user->ID, 'hash', true);
    return $hash;
}

function get_resultados($cuestionario_id) {
    global $wpdb;
    $resultados = array();
    
    $obj = array(
        'Autoestima' => array(
            'P' => 'S',
            'N' => 'N',
        ),
        'Empatía' => array(
            'P' => 'S',
            'N' => 'N',
        ),
        'Autonomía' => array(
            'P' => 'S',
            'N' => 'N',
        ),
        'Humor' => array(
            'P' => 'S',
            'N' => 'N',
        ),
        'Creatividad' => array(
            'P' => 'S',
            'N' => 'N',
        ),
    );

    foreach($obj as $grupo => $array_tipo_res) {
        $resultado = 0;
        foreach($array_tipo_res as $tipo => $respuesta) {
            $sql = "SELECT COUNT(RS.respuesta)
                    FROM {$wpdb->prefix}resiliencia_resultados RS, {$wpdb->prefix}resiliencia_preguntas P, {$wpdb->prefix}resiliencia_registros R
                    WHERE RS.pregunta = P.id 
                    AND RS.cuestionario = R.id 
                    AND R.id = '{$cuestionario_id}'
                    AND P.tipo = '{$tipo}'
                    AND P.grupo = '{$grupo}'
                    AND RS.respuesta = '{$respuesta}'";
            $resultado += (int)$wpdb->get_var($sql);
        }
        array_push($resultados, $resultado);
    }

    return $resultados;
}