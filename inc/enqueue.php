<?php

/**
 * Detecta si el servidor de Vite (dev) está activo
 */
function vite_is_dev() {
    $ch = curl_init('http://localhost:5173/@vite/client');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 1);
    curl_exec($ch);
    $http = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return $http === 200;
}

/**
 * Encola los assets de Vite (dev y build)
 */
function vite_enqueue_assets() {

    $theme_uri  = get_template_directory_uri();
    $theme_path = get_template_directory();

    if (vite_is_dev()) {

        // 🔥 MODO DEV (HMR)
        wp_enqueue_script(
            'vite-client',
            'http://localhost:5173/@vite/client',
            [],
            null,
            true
        );

        wp_enqueue_script(
            'vite-main',
            'http://localhost:5173/js/main.js',
            [],
            null,
            true
        );

    } else {

        // 🏁 MODO BUILD (PRODUCCIÓN)
        $manifest_path = $theme_path . '/dist/.vite/manifest.json';


        if (!file_exists($manifest_path)) {
            return;
        }

        $manifest = json_decode(file_get_contents($manifest_path), true);

        // 👇 CLAVE CORRECTA SEGÚN TU MANIFEST REAL
        $entry = $manifest['js/main.js'];

        // CSS generado por Vite
        if (isset($entry['css'])) {
            foreach ($entry['css'] as $css_file) {
                wp_enqueue_style(
                    'vite-style',
                    $theme_uri . '/dist/' . $css_file,
                    [],
                    null
                );
            }
        }

        // JS generado por Vite
        wp_enqueue_script(
            'vite-main-js',
            $theme_uri . '/dist/' . $entry['file'],
            [],
            null,
            true
        );
    }
}

add_action('wp_enqueue_scripts', 'vite_enqueue_assets');


/**
 * Fuerza type="module" en los scripts de Vite
 */
add_filter('script_loader_tag', function($tag, $handle) {
    $handles = ['vite-client', 'vite-main', 'vite-main-js'];

    if (in_array($handle, $handles)) {
        return str_replace('<script ', '<script type="module" ', $tag);
    }

    return $tag;
}, 10, 2);
