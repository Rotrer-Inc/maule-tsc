<?php
/*
 * Si ya tiene session, va directo a resumen saldo
 */
 if($_SESSION["mitsc"] == 1){
	 wp_redirect( get_page_link(7) );
	 exit();
 }
?>
<?php get_header(); ?>
					<div class="inner">	
						
						<div class="block2-inicio">
                               <h2 class="accesotsc">Accede a tu cuenta</h2>
                                
                                
							   <form class="block2" method="post" action="<?php echo get_page_link(7); ?>" id="login_form">

									<p><label>Rut:</label><input name="rut" type="text" id="rut"  /></p>
									<p><label> N° de Tarjeta Prepago:</label><input  name="nrotarj"  id="nrotarj" type="text"  /></p>

									<p><input class="buttons button6" name="ingresar" type="submit" value="Ingresar" /></p>
									<input name="id" type="hidden" value="envio" />

									<div><span id="msgbox" style="display:none"></span></div>

                                </form>
                         </div>
                         <div class="block3 acceso">
                        		<h3>Centro de Gestión de Telepeaje</h3>
                                    <p>Ruta 5 Sur kilómetro 57,6 Mostazal VI Región.</p>
                                    
                                <h3>Horario de Atención</h3>
                                    <p>Lunes a Viernes 09:00 hrs a 19:00 hrs.<br />
                                    Sábado 9:00 hrs a 14:00 hrs.</p>
                        </div>
                         
					</div>
<?php get_footer(); ?>