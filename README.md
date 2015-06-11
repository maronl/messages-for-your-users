# messages-for-your-users
WordPress Plugin to enable administrator menage messages to be showed to the users once they are logged into the website.

##TODO List
-make option to inject message in the footer by default or disable injection
-if inject is disabled create shortocode to be used by website adminstrator to show the messagge where desired
-make option to include or not bootstrap.min.js

##DONE
###11/06/2015
-made the plugin structure
-inject bootstrap modal into the footer
-customize message with a custom post defined in the backoffice
-make modal message customizable with dynami data (implemented shortcode [m4yuphp])
-separate JS from HTML markup
-show a message only once (creata tabella che traccia le letture dei messagi per semplificare altre operazioni in seguito come le statistiche)
-make template for message customizable using a template file in the theme root (file nella root si deve chiamare m4yu_message.php)



