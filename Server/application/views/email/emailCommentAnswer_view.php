<div style="background: #EEE; padding: 5px 20px 20px 20px; font-family: sans-serif; margin: 0;">
    <p style="color:#444; font-weight: bold;">
        Hola <?php echo $name; ?>,
    </p>
    <p>
        Respondieron tu consulta sobre <?php echo $title ?>.
    </p>
    <p style="color: #444; font-size: 12px;">
        <?php echo $query; ?>
        <hr>
        <?php echo $msg; ?>
    </p>
    <p style="color: #444; font-size: 12px;">
        Para ir al anuncio haz clic <a href="<?php echo $link; ?>">aquí</a>
    </p>
</div>