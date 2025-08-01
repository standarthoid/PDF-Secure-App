<?php
/**
 * Plugin Name: PDF Viewer Protect
 * Description: Zeigt geschützte PDFs im PDF.js Viewer mit Wasserzeichen und verhindert Download/Speichern/Drucken.
 * Version: 1.0
 * Author: Dein Name
 */

add_shortcode('secure_pdf_viewer', 'pdf_viewer_protect_render');

function pdf_viewer_protect_render($atts) {
    $atts = shortcode_atts([
        'url' => ''
    ], $atts);

    if (empty($atts['url'])) {
        return '<p><strong>Kein PDF angegeben.</strong></p>';
    }

    $pdf_url = esc_url($atts['url']);

    ob_start();
    ?>
    <div id="secure-pdf-container" style="position: relative; height: 800px;">
        <div id="watermark">VERTRAULICH</div>
        <iframe 
            src="<?php echo plugin_dir_url(__FILE__) . 'pdfjs/web/viewer.html?file=' . urlencode($pdf_url); ?>" 
            style="width: 100%; height: 100%;" 
            frameborder="0" 
            allowfullscreen>
        </iframe>
    </div>
    <script>
    // Hotkeys und Kontextmenü deaktivieren
    document.addEventListener('keydown', function(e) {
        if (
            (e.ctrlKey && (e.key === 's' || e.key === 'p' || e.key === 'u')) ||
            e.key === 'PrintScreen'
        ) {
            e.preventDefault();
            alert("Diese Aktion ist deaktiviert.");
        }
    });
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
    });
    </script>
    <style>
    #watermark {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-30deg);
        font-size: 5em;
        color: rgba(0, 0, 0, 0.08);
        pointer-events: none;
        z-index: 9999;
        user-select: none;
        white-space: nowrap;
    }

    @media print {
        #watermark {
            display: none !important;
        }
    }
    </style>
    <?php
    return ob_get_clean();
}
?>
