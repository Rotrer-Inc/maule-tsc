<?php
/**
 * Template Name: Actualizar Home
 */

get_header(); ?>
			<?php while ( have_posts() ) : the_post(); ?>
			<div class="content">
				<div class="inner">	
					<h2>Configuración de Datos</h2>
					<form class="block2">
						<p>
							<label>Rut</label>
							<span>14.446.169-k</span>
						</p>
						<p>
							<label>Tarjeta de prepago nº</label>
							<select name="some_name" id="some_name"  onchange="" size="1">
								<option value="option1">option1</option>
								<option value="option2">option2</option>

							</select>
						</p>
						<p>
							<label>Razón social o Nombre</label>
							<input type="text" />
						</p>
						<p>
							<label>E-mail</label>
							<input type="text" />
						</p>
						<p>
							<label>Giro</label>
							<input type="text" />
						</p>
						<p>
							<label>Dirección</label>
							<input type="text" />
						</p>
						<p>
							<label>Teléfono</label>
							<input type="text" />
						</p>
						<p>
							<label>Región</label>
							<input type="text" />
						</p>
						<p>
							<label>Ciudad</label>
							<input type="text" />
						</p>
						<p>
							<label>Comuna</label>
							<input type="text" />
						</p>
						<p class="button-holder">
							<a class="buttons button4" href="#">Guardar</a>
						</p>
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