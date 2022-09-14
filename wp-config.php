<?php
/**
 * As configurações básicas do WordPress
 *
 * O script de criação wp-config.php usa esse arquivo durante a instalação.
 * Você não precisa usar o site, você pode copiar este arquivo
 * para "wp-config.php" e preencher os valores.
 *
 * Este arquivo contém as seguintes configurações:
 *
 * * Configurações do banco de dados
 * * Chaves secretas
 * * Prefixo do banco de dados
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Configurações do banco de dados - Você pode pegar estas informações com o serviço de hospedagem ** //
/** O nome do banco de dados do WordPress */
define('WP_CACHE', true);
define( 'WPCACHEHOME', 'E:\Arquivos de Programas\xamp\htdocs\wordpress\wp-content\plugins\wp-super-cache/' );
define( 'DB_NAME', "desafio12_wp" );

/** Usuário do banco de dados MySQL */
define( 'DB_USER', "natanaelricardo" );

/** Senha do banco de dados MySQL */
define( 'DB_PASSWORD', "jtinket9a6" );

/** Nome do host do MySQL */
define( 'DB_HOST', "localhost" );

/** Charset do banco de dados a ser usado na criação das tabelas. */
define( 'DB_CHARSET', 'utf8mb4' );

/** O tipo de Collate do banco de dados. Não altere isso se tiver dúvidas. */
define( 'DB_COLLATE', '' );

/**#@+
 * Chaves únicas de autenticação e salts.
 *
 * Altere cada chave para um frase única!
 * Você pode gerá-las
 * usando o {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org
 * secret-key service}
 * Você pode alterá-las a qualquer momento para invalidar quaisquer
 * cookies existentes. Isto irá forçar todos os
 * usuários a fazerem login novamente.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '^$|nH.=[b!dUO5HcURZ$yAOkSCz^OY?E[<Nx<^|L(ejtB02gs ~~SCte$m3]t#kN' );
define( 'SECURE_AUTH_KEY',  'h]68x_8A+]J@z#|.Nx5TmiY^@g+xJwt1n_X]44PIBt$NN?_1_8@&k@a I<3#JXQ7' );
define( 'LOGGED_IN_KEY',    '3k=,i^X4:0d^tcTPWkO9#yw{us~(RwqjjCxT.,k A!B^EQ)3:-U]Zh  rBr}* }!' );
define( 'NONCE_KEY',        'hEKeVDl~+PqGv0e|5:Vqdv5$=~&?kK$k76x>%&Wl6}E@IXZ:#&W7DI^ C}#IP<w&' );
define( 'AUTH_SALT',        '~Z<SK]a0.hUrnA!ugBUVA@a[Sixrf;X)*`>CB!RRM|D:jfL u$Y7Z`[^x9b__q{N' );
define( 'SECURE_AUTH_SALT', '2SYzjx-kTpj!`Ut/x+TTt6.&#wnnwj/0OE61QZ7iSVNC57>1],U3Qdd>&wRC&SZa' );
define( 'LOGGED_IN_SALT',   'leHMbCaN%L?LM:}!4yS|w#ncnBC5k` J=NFEoGqu!_fvnxuw>fb1*:?q;C^7rLfo' );
define( 'NONCE_SALT',       '][&/=,bx&I6)2 G_79XM!i1S]jss,i2l7nAaJm7?xof0zU0ORIKGCRjh`D@R%ij3' );

/**#@-*/

/**
 * Prefixo da tabela do banco de dados do WordPress.
 *
 * Você pode ter várias instalações em um único banco de dados se você der
 * um prefixo único para cada um. Somente números, letras e sublinhados!
 */
$table_prefix = 'wp_';

/**
 * Para desenvolvedores: Modo de debug do WordPress.
 *
 * Altere isto para true para ativar a exibição de avisos
 * durante o desenvolvimento. É altamente recomendável que os
 * desenvolvedores de plugins e temas usem o WP_DEBUG
 * em seus ambientes de desenvolvimento.
 *
 * Para informações sobre outras constantes que podem ser utilizadas
 * para depuração, visite o Codex.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Adicione valores personalizados entre esta linha até "Isto é tudo". */



/* Isto é tudo, pode parar de editar! :) */

/** Caminho absoluto para o diretório WordPress. */
define( 'WP_SITEURL', 'http://localhost/wordpress/' );
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname(__FILE__) . '/' );
}

/** Configura as variáveis e arquivos do WordPress. */
require_once ABSPATH . 'wp-settings.php';
