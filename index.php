<!DOCTYPE html>

<?php
if(file_exists('config.php')){
  require('config.php');
}
$template_dir = array(getcwd() . "/template/*");

?>

<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Bootstrap Template</title>
        <meta name="viewport" content="width=device-width">
        <link rel="stylesheet" href="css/main.css">
    </head>

    <body>

	    <div class="canvas">

		    <header>


			    <nav>
			        <ul>
<?php
			            foreach ( $devtools as $tool ) {
			            	printf( '<li><a href="%1$s">%2$s</a></li>', $tool['url'], $tool['name'] );
			            }
?>
			        </ul>
			    </nav>

		    </header>



<?php if (getenv("SHOW_LOCAL_SITES") !== false): ?>

        <h2>My Local Sites</h2>
		    <content class="cf">
<?php
		    foreach ( $dir as $d ) {
			    $dirsplit = explode('/', $d);
			    $dirname = $dirsplit[count($dirsplit)-2];

				printf( '<ul class="sites %1$s">', $dirname );

		        foreach( glob( $d ) as $file )  {

		        	$project = basename($file);

		        	if ( in_array( $project, $hiddensites ) ) continue;

              if(isset($ignored) && in_array($project, $ignored)) {
                continue;
              }

		            echo '<li>';

		            $siteroot = sprintf( 'http://%1$s.%2$s.%3$s', $project, $dirname, $tld );

		            // Display an icon for the site
		            $icon_output = '<span class="no-img"></span>';
		            foreach( $icons as $icon ) {

		            	if ( file_exists( $file . '/' . $icon ) ) {
		            		$icon_output = sprintf( '<img src="%1$s/%2$s">', $siteroot, $icon );
		            		break;
		            	} // if ( file_exists( $file . '/' . $icon ) )

		            } // foreach( $icons as $icon )
		            echo $icon_output;

		            // Display a link to the site
		            $displayname = $project;
		            if ( array_key_exists( $project, $siteoptions ) ) {
		            	if ( is_array( $siteoptions[$project] ) )
		            		$displayname = array_key_exists( 'displayname', $siteoptions[$project] ) ? $siteoptions[$project]['displayname'] : $project;
		            	else
		            		$displayname = $siteoptions[$project];
		            }
		            printf( '<a class="site" href="%1$s">%2$s</a>', $siteroot, $displayname );


					// Display an icon with a link to the admin area
					$adminurl = '';
					// We'll start by checking if the site looks like it's a WordPress site
					if ( is_dir( $file . '/wp-admin' ) )
						$adminurl = sprintf( 'http://%1$s/wp-admin', $siteroot );

					// If the user has defined an adminurl for the project we'll use that instead
		            if (isset($siteoptions[$project]) &&  is_array( $siteoptions[$project] ) && array_key_exists( 'adminurl', $siteoptions[$project] ) )
		            	$adminurl = $siteoptions[$project]['adminurl'];

		            // If there's an admin url then we'll show it - the icon will depend on whether it looks like WP or not
		            if ( ! empty( $adminurl ) )
			            printf( '<a class="%2$s icon" href="%1$s">Admin</a>', $adminurl, is_dir( $file . '/wp-admin' ) ? 'wp' : 'admin' );


		            echo '</li>';

				} // foreach( glob( $d ) as $file )

		        echo '</ul>';

		   	} // foreach ( $dir as $d )
?>
			</content>

<?php endif ?>



<?php
$hosts_file = '/etc/hosts';
if (is_readable($hosts_file)) {
  $lines = file($hosts_file);

  $vhost_entries = array_filter($lines, function($line) {
    $parts = preg_split('/\s+/', $line);
    return count($parts) >= 2 && (substr( $parts[1], 0, 4 ) == "www." || substr( $parts[1], -6 ) == ".local" );
  });

  printf( '<h2>Virtual Hosts</h2><content class="cf"><ul class="sites %1$s">', 'vhosts');

  foreach ( $vhost_entries as $vhost_entry ) {

  $host_name = preg_split('/\s+/', $vhost_entry)[1];

  $host_url = sprintf( 'http://%1$s', $host_name );

  echo '<li>';
  printf( '<a class="site" href="%1$s">%2$s</a>', $host_url, $host_name );

  echo '</li>';
  }
  echo '</ul></content>';
}
?>



<h2>Bootstrap template</h2>
      <content class="cf">
<?php
      foreach ( $template_dir as $d ) {
        $dirsplit = explode('/', $d);
        $dirname = $dirsplit[count($dirsplit)-2];

      printf( '<ul class="sites %1$s">', $dirname );

          foreach( glob( $d ) as $file )  {

            $project = basename($file);

            if ( in_array( $project, $hiddensites ) ) continue;

              echo '<li>';

              $siteroot = sprintf( 'http://%1$s/%2$s/%3$s', $_SERVER['HTTP_HOST'], $dirname, $project );

              // Display an icon for the site
              $icon_output = '<span class="no-img"></span>';
              foreach( $icons as $icon ) {

                if ( file_exists( $file . '/' . $icon ) ) {
                  $icon_output = sprintf( '<img src="%1$s/%2$s">', $siteroot, $icon );
                  break;
                } // if ( file_exists( $file . '/' . $icon ) )

              } // foreach( $icons as $icon )
              echo $icon_output;

              // Display a link to the site
              $displayname = $project;
              if ( array_key_exists( $project, $siteoptions ) ) {
                if ( is_array( $siteoptions[$project] ) )
                  $displayname = array_key_exists( 'displayname', $siteoptions[$project] ) ? $siteoptions[$project]['displayname'] : $project;
                else
                  $displayname = $siteoptions[$project];
              }
              printf( '<a class="site" href="%1$s">%2$s</a>', $siteroot, $displayname );

              echo '</li>';

      } // foreach( glob( $d ) as $file )

          echo '</ul>';

      } // foreach ( $dir as $d )
?>
    </content>



		    <footer class="cf">
		    <p></p>
		    </footer>

	    </div>
    </body>
</html>
