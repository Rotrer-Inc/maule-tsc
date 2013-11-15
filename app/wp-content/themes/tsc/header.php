<?php session_start(); ?>
<!doctype html>
<html lang="es">
<head>
	<meta charset="UTF-8" xml:lang="es">
	<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
	<link rel="stylesheet" type="text/css" href="<?php echo get_template_directory_uri(); ?>/css/style.css" />
	<link href="<?php echo get_template_directory_uri(); ?>/css/jquery-ui-1.9.0.custom.css" rel="stylesheet">
	<!--[if lt IE 9]>
	<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/functions.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/rut.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/validate.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/jquery.PrintArea.js_4.js"></script>
	<script src="<?php echo get_template_directory_uri(); ?>/js/apps.js"></script>
	<script type="text/javascript">
		var APP_JQ = '<?php print APP_JQ; ?>';
	</script>
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
	<div class="deco"></div>
	<div id="wrap">		
		<header>
			
			<h1 class="logo">
				<a href="<?php bloginfo("url"); ?>">Ruta del Maule - TSC</a>
			</h1>
			
			<?php if( $_SESSION['mitsc'] == true && !empty($_SESSION['mitsc_rut']) ){ ?>
			<div class="user-log">
				<p>Bienvenido: Nombre de usuario</p>
				<p class="button-holder">
					<a href="<?php bloginfo("url"); ?>" class="close_sesion">Cerrar sesión</a>
				</p>
			</div>
			<div class="search">
				<input type="text" placeholder="Buscar Tarjetas Prepago" />
				<input type="submit" value="Buscar" />
			</div>
			<?php } ?>
		</header><!-- #header -->
		
		<section id="main-content">
				<?php if( $_SESSION['mitsc'] == true && !empty($_SESSION['mitsc_rut']) ){ ?>
				<nav class="nav">
                	<ul>
						<li class="<?php echo ( is_page(array(7,9,11)) ) ? 'current' : ''; ?>"><a href="<?php echo get_page_link(7); ?>">Mi Saldo</a></li>
						<li class="<?php echo ( is_page(5) ) ? 'current' : ''; ?>"><a href="<?php echo get_page_link(5); ?>">Recarga</a></li>
						<li class="<?php echo ( is_page(array(13,15)) ) ? 'current' : ''; ?>"><a href="<?php echo get_page_link(13); ?>">Transacciones en Peajes</a></li>
						<li class="<?php echo ( is_page(17) ) ? 'current' : ''; ?>"><a href="<?php echo get_page_link(17); ?>">Actualizar contacto</a></li>
                    </ul>
				</nav>
				<?php } ?>