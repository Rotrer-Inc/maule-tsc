<?php
/**
 * Template Name: Actualizar Home
 */
$dataUser = getActualizaUsuario();
$selectTscs = getTscsPorRut();
$selectRegiones = getRegion();
$selectCiudades = getCiudad($dataUser->ciudadRecep);
$selectComuna = getComunas($dataUser->comunaRecep);
$destinatario = "jgerding@cintra.cl";
$sendMail = false;

if ($_POST){
	$sendMail = sendActualizaTsc($_POST);
}
?>
<?php get_header(); ?>
			<?php while ( have_posts() ) : the_post(); ?>
			<div class="content">
				<div class="inner">	
                
				<div class="clear"></div>
					<h2>Configuración de Datos</h2>
					<form name="frm_mitsc_actualiza" class="block2" id="data" action="" method="post">		
						<input type="hidden" name="rut" id="rut" value="<?php print $dataUser->rutRecep; ?>">
						<p>
							<label>Rut</label>
							<span><?php print getPuntosRut( $dataUser->rutRecep ); ?></span>
						</p>
						<p>
							<label>Tarjeta de prepago nº</label>
							<select name="numerocuenta" size="1" class="required select" text="Seleccione tarjeta TSC">							
								<option value="">Seleccione Tarjeta de Prepago</option>
								<?php print $selectTscs; ?>
							</select>
						</p>
						<p>
							<label>Razón social o Nombre</label>
							<input name="razonsocial" type="text" class="required text" value="<?php print $dataUser->razonSocialRecep; ?>" maxlength="32" text="Ingrese Razón Social">
						</p>
						<p>
							<label>E-mail</label>
							<input name="email" type="text" class="required email" value="<?php print $dataUser->emailRecep; ?>" maxlength="50" text="Ingrese E-mail">
						</p>
						<p>
							<label>Giro</label>
							<input name="giro" type="text" class="required text" value="<?php print $dataUser->giroRecep; ?>" maxlength="32" text="Ingrese Giro">
						</p>
						<p>
							<label>Dirección</label>
							<input name="direccion" type="text" class="required text" value="<?php print $dataUser->direccionRecep; ?>" maxlength="50" text="Ingrese Dirección">
						</p>
						<p>
							<label>Teléfono</label>
							<input name="fono" type="text" class="required phone" value="<?php print $dataUser->fonoRecep; ?>" maxlength="8" text="Ingrese Teléfono">
						</p>
						<p>
							<label>Región</label>
							<select name="region" id="region" class="required select" text="Seleccione Región">
								<option value="">Seleccione Regi&oacute;n</option>
								<?php print $selectRegiones; ?>
							</select>
						</p>
						<p>
							<label>Ciudad</label>
							<select name="ciudad" id="ciudad" class="required select" text="Seleccione Ciudad">
								<option value="">Seleccione Ciudad</option>
								<?php print $selectCiudades; ?>
¡							</select>
						</p>
						<p>
							<label>Comuna</label>
							<select name="comuna" id="comuna" class="required select" text="Seleccione Comuna">
								<option value="">Seleccione Comuna</option>
								<?php print $selectComuna; ?>
							</select>
						</p>
							<a class="buttons button4 submitForm" href="">Guardar</a>
					
						<div class="error"><p></p></div>
						<?php if($sendMail){ ?>
						<h2>Sus datos han sido enviados para actualizar.</h2>
						<?php } ?>
						<div class="clear"></div>
					</form>
				</div>
			</div>
			<div class="content2">
				<div class="inner">
					<p>Les recordamos que todas las recargas se validan en las vías de peaje, cada 6 horas y en horarios fijos. Los horarios de validación son: 11:00 am a 17:00 pm y de 23:00 pm a 05:00 am. Es decir, si realiza una recarga a las 09:00 am esta ya estará disponible en las vías de peajes a las 11:00 am, así cuando aproxime su Tarjeta de Prepago a cualquier lector de vía de peaje, el saldo se grabará en el chip de su plástico.</p>
					<p>Todas las recargas que se realicen vía Web, quedan inmediatamente disponible para su revisión en el modulo “Recargas No Activas”, donde podrá identificar, el día, el monto y el horario de la recarga. La Recarga se hará efectiva la próxima vez que utilice su TSC en las plazas de peajes de la concesión. Es recién acá, cuando la consulta de saldo se actualizará en el sitio.</p>
				</div>
			</div>	
			<?php endwhile; // end of the loop. ?>
<?php get_footer(); ?>