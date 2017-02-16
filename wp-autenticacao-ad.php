<?php
/* 
Plugin Name: Autenticação no Active Directory
Description: Plugin para autenticação no AD - simplificação do plugin legado Minc Intranet mantendo apenas funcionalidades de login/logout
Version: 1.1
Author: IVIA/HEPTA
Author URI: 
*/

require_once(DIRNAME(__FILE__) . '/lib/adLDAP.php');
require_once(DIRNAME(__FILE__) . '/lib/ConexaoExtra.php');
require_once(DIRNAME(__FILE__) . '/lib/model/Usuario.php');
require_once(DIRNAME(__FILE__) . '/lib/dao/UsuarioDAO.php');

require_once(DIRNAME(__FILE__) . '/classes/class-wp-autenticacao-ad-widget-login.php');
require_once(DIRNAME(__FILE__) . '/classes/class-wp-autenticacao-ad-widget-minhaintranet.php');
require_once(DIRNAME(__FILE__) . '/classes/class-wp-autenticacao-ad-widget-minhaintranetmenu.php');


add_action('init', 'wp_autenticacao_ad_init', 1);
add_action('init', 'wp_autenticacao_ad_acao', 2);

/**
 * Setup
 */
function wp_autenticacao_ad_init() {
	// Inicializa a sessão, caso ela ainda não exista
	if (!session_id()) session_start();
	
    wp_enqueue_style('wp-autenticacao-ad-style', plugin_dir_url(__FILE__) . '/style.css');
}

/**
 * Detecta acoes do usuário relacionadas à intranet
 */
function wp_autenticacao_ad_acao() {
	if (isset($_POST['acao'])) {
		switch ($_POST['acao']) {
			case 'logout': wp_autenticacao_ad_logout(); break;
			case 'login': wp_autenticacao_ad_login(); break;
			case 'salvar_dados_pessoais': wp_autenticacao_ad_salvar_dados_pessoais(); break;
		}
	}
}

/**
 * Efetua o login do usuário
 */
function wp_autenticacao_ad_login() {
    
	if (isset($_POST["login"]) && isset($_POST["senha"])) {
		
		try {
			$adldap = new adLDAP(array(
									'account_suffix'=>get_option('account_suffix'),
									'base_dn'=>get_option('base_dn'),
									'domain_controllers'=>array(get_option('domain_controller')),
								));
			
		}
		catch (adLDAPException $e) {
			echo $e; exit();   
		}
		
		// autentica o usuário
		if ($adldap->authenticate($_POST["login"], $_POST["senha"])) {
			$dao = new UsuarioDAO();
			$logado = $dao->getByLogin($_POST["login"]);
			$_SESSION["usuario_intranet"] = $logado;
		} else {
			wp_autenticacao_ad_add_message('Erro de acesso ao sistema - Usuário ou senha incorreto.');
		}
	}
}

/**
 * Efetua o logout do usuário
 */
function wp_autenticacao_ad_logout() {
	unset($_SESSION['usuario_intranet']);
	wp_redirect( $_SERVER['REQUEST_URI'] );
	exit;
}

/**
 * Retorna o usuário logado
 */
function wp_autenticacao_ad_get_usuario() {
	return $_SESSION["usuario_intranet"];
}

/**
 * Testa se o usuário está logado
 */
function wp_autenticacao_ad_is_logado() {
    return isset($_SESSION['usuario_intranet']);
}

/**
 * Adiciona uma mensagem do sistema
 */
function wp_autenticacao_ad_add_message($msg) {
    if (!is_array($_SESSION['msg']))
		$_SESSION['msg'] = array();
		
	array_push($_SESSION['msg'], $msg);
}

/**
 * Exibe uma mensagem do sistema
 */
function wp_autenticacao_ad_get_messages() {
    $msg = $_SESSION['msg'];
	$_SESSION['msg'] = array();
	
	return $msg;
}

/**
 * Salva os dados do usuário na base da intranet
 */
function wp_autenticacao_ad_salvar_dados_pessoais() {
	$usuario = new Usuario();
	$dao 	 = new UsuarioDAO();
	
	$logado  = wp_autenticacao_ad_get_usuario();
	
	$logado->setLogin($logado->getLogin());
	$logado->setApelido($_POST['apelido']);
	$logado->setLocalizacao($_POST['localizacao']);
	$logado->setComplemento($_POST['complemento']);
	$logado->setTelefone($_POST['telefone']);
	$logado->setCadastroCompleto(true);
	
	$dao->excluir($logado->getLogin());
	
	if ($dao->inserir($logado)) {
		$_SESSION['usuario_intranet'] = $logado;
		wp_autenticacao_ad_add_message('Dados salvos com sucesso');
	} else {
		wp_autenticacao_ad_add_message('Ocorreu um erro ao salvar os dados');
	}
}

function wp_autenticacao_ad_meu_menu() {

    $logado = wp_autenticacao_ad_get_usuario();
    $dao = new UsuarioDAO();
    $meuMenu = $dao->getMenuUsuario($logado->getLogin());    
    return $meuMenu;
}

/**********************************************************************
 * Área Administrativa                                                *
 **********************************************************************/
add_action('admin_menu', 'wp_autenticacao_ad_admin_menu');

/**
 * Menu na área administrativa
 */
function wp_autenticacao_ad_admin_menu() {

	add_menu_page('Autenticação AD', 'Autenticação AD', 'administrator', 'autenticacao_ad_configuracoes', 'wp_autenticacao_ad_pagina_configuracoes');
	
	add_action('admin_init', 'wp_autenticacao_ad_configuracoes' );
}

/**
 * Registro dos campos relativos às configurações
 */
function wp_autenticacao_ad_configuracoes() {
	register_setting( 'wp-autenticacao-ad', 'account_suffix' );
	register_setting( 'wp-autenticacao-ad', 'base_dn' );
	register_setting( 'wp-autenticacao-ad', 'domain_controller' );
	
	register_setting( 'wp-autenticacao-ad', 'db_driver' );
	register_setting( 'wp-autenticacao-ad', 'db_host' );
	register_setting( 'wp-autenticacao-ad', 'db_database' );
	register_setting( 'wp-autenticacao-ad', 'db_username' );
    register_setting( 'wp-autenticacao-ad', 'db_password' );
    
    register_setting( 'wp-autenticacao-ad', 'titulo_login' );
    register_setting( 'wp-autenticacao-ad', 'texto_logon' );
}

/**
 * Página de configurações
 */
function wp_autenticacao_ad_pagina_configuracoes() { ?>
	<div class="wrap">

		<h1>Autenticação no Active Directory - AD </h1>

		<form method="post" action="options.php">
			<?php settings_fields( 'wp-autenticacao-ad' ); ?>

			<h3>Mensagens</h3>

			<table class="form-table">
				<tr valign="top">
					<th scope="row">Título login</th>
					<td><input type="text" name="titulo_login" value="<?php echo get_option('titulo_login'); ?>" /></td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Texto logon</th>
					<td><textarea cols="100" rows="3" name="texto_logon"><?php echo get_option('texto_logon'); ?></textarea>
					  <p id="texto_logon-description" class="description">Ex: "Bem vindo/a ao AD", @primeiro_nome. </p>
					  
					</td>
				</tr>
				 
			</table>				
			
			<h3>Active Directory</h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Account Suffix</th>
					<td><input type="text" name="account_suffix" value="<?php echo get_option('account_suffix'); ?>" /></td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Base DN</th>
					<td><input type="text" name="base_dn" value="<?php echo get_option('base_dn'); ?>" /></td>
				</tr>
				 
				<tr valign="top">
					<th scope="row">Domain Controller</th>
					<td><input type="text" name="domain_controller" value="<?php echo get_option('domain_controller'); ?>" /></td>
				</tr>
			</table>
			
			<h3>Banco de Dados Extra</h3>
			<table class="form-table">
				<tr valign="top">
					<th scope="row">Tipo</th>
					<td>
						<select name="db_driver">
							<option value="dblib" <?php echo get_option('db_driver') == 'dblib' ? 'selected="selected"' : '' ?>>SQL Server</option>
						</select>
					</td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Host</th>
					<td><input type="text" name="db_host" value="<?php echo get_option('db_host'); ?>" /></td>
				</tr>
				 
				<tr valign="top">
					<th scope="row">Nome do Banco</th>
					<td><input type="text" name="db_database" value="<?php echo get_option('db_database'); ?>" /></td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Usuário</th>
					<td><input type="text" name="db_username" value="<?php echo get_option('db_username'); ?>" /></td>
				</tr>
				
				<tr valign="top">
					<th scope="row">Senha</th>
					<td><input type="password" name="db_password" value="<?php echo get_option('db_password'); ?>" /></td>
				</tr>
			</table>
			
			<p class="submit">
				<input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
			</p>
		</form>
	</div><?php
}


/**********************************************************************
 * Funções Auxiliares                                                 *
 **********************************************************************/

/**
 * Retorna a URL atual
 */
function wp_autenticacao_ad_get_url() {
	$pageURL = 'http';
	if ($_SERVER["HTTPS"] == "on")
		$pageURL .= "s";
	
	$pageURL .= "://";
	if ($_SERVER["SERVER_PORT"] != "80")
		$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	else
		$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	
	return $pageURL;
}

/**
 * Altera um parâmetro GET
 * Autor: Alberto Lepe (www.alepe.com)
 */
function wp_autenticacao_ad_fix_get($args) { 
	if (count($_GET) > 0) {
		if (!empty($args)) {
			$lastkey = "";
			$pairs = explode("&",$args);
			
			foreach($pairs as $pair) {
				if (strpos($pair,":") !== false) {
					list($key,$value) = explode(":",$pair);
					unset($_GET[$key]);
					$lastkey = "&$key$value";
				
				} else if (strpos($pair,"=") === false) {
					unset($_GET[$pair]);

				} else {
					list($key, $value) = explode("=",$pair);
					$_GET[$key] = $value;
				}
			}
		} 
		return "?".((count($_GET) > 0)?http_build_query($_GET).$lastkey:"");
	}
}

 /**
 * função de logut
 */
function wp_autenticacao_ad_verifica_login() {
    if (isset($_REQUEST['acao'])) {
        if ($_REQUEST['acao'] == 'logout') {
            wp_autenticacao_ad_logout();
        }
    }
}

/**
 * Retorna o IP
 */
function wp_autenticacao_ad_get_ip() {
	if (isset($_SERVER)) {
		if (isset($_SERVER['HTTP_CLIENT_IP'])){
			$ip = $_SERVER['HTTP_CLIENT_IP'];
		} else if (isset($_SERVER['HTTP_FORWARDED_FOR'])){
			$ip = $_SERVER['HTTP_FORWARDED_FOR'];
		} else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip = $_SERVER['REMOTE_ADDR'];
		}
	} else {
		if (getenv( 'HTTP_CLIENT_IP')) {
			$ip = getenv( 'HTTP_CLIENT_IP' );
		} else if (getenv('HTTP_FORWARDED_FOR')) {
			$ip = getenv('HTTP_FORWARDED_FOR');
		} else if (getenv('HTTP_X_FORWARDED_FOR')) {
			$ip = getenv('HTTP_X_FORWARDED_FOR');
		} else {
			$ip = getenv('REMOTE_ADDR');
		}
	}
	return $ip;
}


add_action( 'widgets_init', function(){
	register_widget( 'Wp_Autenticacao_Ad_Widget_Login' );
	register_widget( 'Wp_Autenticacao_Ad_Widget_Minhaintranet' );
    register_widget( 'Wp_Autenticacao_Ad_Widget_Minhaintranetmenu' );
});

add_action('init', 'wp_autenticacao_ad_verifica_login', 1);