<?php 
$ficha = new FichaTecnica(); //solo para habilitar texto predictivo
$ficha = $ficha_tecnica; /* parámetro recibido al cargar esta vista */
?>
<h3>Se ha creado exitosamente la exposición</h3>

<h3>Nombre:<?=$ficha->nombreExpo?></h3>

<?php //"<pre>".print_r($ficha, TRUE)."</pre>"?>

<?php if( count($ficha->archivosSobrantes) > 0){ ?>
    <p>Los siguientes archivos contenidos en el ZIP no fueron referenciados en la ficha técnica y no fueron tomados en cuenta</p>
    <ul>
    <?php foreach($ficha->archivosSobrantes as $sobrante){ ?>
        <li><?=$sobrante?></li>
    <?php } //fin ciclo sobrantes?>
    </ul>
<?php } //fin si hay sobrantes?>

<?php if( count($ficha->archivosFaltantes) > 0){ ?>
    <p>Los siguientes archivos declarados en la ficha técnica no fueron encontrados en el ZIP</p>
    <ul>
    <?php foreach($ficha->archivosFaltantes as $faltante){ ?>
        <li><?=$faltante?></li>
    <?php } //fin ciclo faltantes?>
    </ul>
<?php } //fin si hay faltantes?>

<p>Para hacer visible esta exposición al público, cambie su estado desde <strong>Administración de exposiciones</strong></p>