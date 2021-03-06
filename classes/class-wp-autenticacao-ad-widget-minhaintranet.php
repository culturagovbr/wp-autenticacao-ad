<?php
/**
 * Wp_Autenticacao_Ad_Widget class
 **/
class Wp_Autenticacao_Ad_Widget_Minhaintranet extends WP_Widget {

    /**
     * Configura o widget
     */

    public function __construct() {
        $widget_ops = array( 
			'classname' => 'wp-autenticacao-ad-widget-minhaintranet',
			'description' => 'MinhaIntranet',
		);
		parent::__construct( 'wp_autenticacao_ad_widget_minhaintranet', 'MinhaIntranet', $widget_ops );
    }

    
	/**
	 * Outputs the content of the widget
	 *
	 * @param array $args
	 * @param array $instance
	 */
	public function widget( $args, $instance ) {
        
        $mensagens = wp_autenticacao_ad_get_messages();
        if (is_array($mensagens) && count($mensagens) > 0) {
?>
    <div class="caixa_mensagem">
		<?php foreach ($mensagens as $msg) { ?>
			<p><?php echo $msg ?></p>
		<?php } ?>
        <a href="#" class="txtIndent"></a>
    </div>
<?php
        } 

		// outputs the content of the widget
        if (wp_autenticacao_ad_is_logado()) {
            $usuario = wp_autenticacao_ad_get_usuario();
            $cpf = $usuario->getLogin();
            $meuMenu = wp_autenticacao_ad_meu_menu();
?>
            
<style>
.caixa_conteudo {
    float: left;
    width: 60%;
}

.caixa_menuinternas {
    float: right;
   width: 20.875%;
}
</style>
<div class="caixa_internas caixa_minhasferramentas">
                        
                        <h2>Minha Página na Intranet</h2>
                   		
                        <div class="caixa_conteudo">
                			
                          <iframe name="iframeMinhaPagina" width="100%" height="500" frameborder="0" src="http://intranet.minc.gov.br/intrascript/spoa/cgmi/credsist/usuarede.idc?LOGON_USER=<?php echo $cpf; ?>&amp;operacao=LOGON&amp;etapa=LOGON&amp;opcao=S"></iframe>
                        </div>
                        
                        <div class="caixa_menuinternas">
                			
                            <h3 class="txtIndent">Serviços</h3>
                            
							<?php 
							if (isset($meuMenu)) { 
								$i = 0;
								foreach ($meuMenu as $meuMenuItem) {
									
									if ($meuMenuItem['srv_link'] == null) {
										if ($i > 0) echo '</ul>';
										echo '<h4>' . utf8_encode($meuMenuItem['srv_titulo']) . '</h4>';
										echo '<ul>';
									
									} else {	
										if ($meuMenuItem['srv_codigo'] == 52) {
										$target = ($meuMenuItem['srv_janela'] == 'pop-up') ? "class='meuMenuPopup'" : "target='blank'";
                                                                                echo '<li><a href="' . $meuMenuItem['srv_linke'] . '" target="_blank"> ' . utf8_encode($meuMenuItem['srv_titulo']) . '</a></li>';

										} else { 
										$target = ($meuMenuItem['srv_janela'] == 'pop-up') ? "class='meuMenuPopup'" : "target='iframeMinhaPagina'";
										echo '<li><a href="' . $meuMenuItem['srv_linke'] . $target . '>' . utf8_encode($meuMenuItem['srv_titulo']) . '</a></li>';
										}
									}
									
									$i++; 
								}
								echo '</ul>';
							} 
							?>
                            
                        </div>
                        
                    </div>
<?php
        } else {
?>
<h3><?php echo get_option('titulo_login'); ?></h3>
<form action="<?php echo wp_autenticacao_ad_get_url() ?>" method="post" class="caixa_login">
  
  <label>Login</label>
  <input type="text" name="login" value="login" onfocus="if(this.value=='login') this.value='';" onblur="if(this.value=='') this.value='login';" />
  
  <label>Senha</label>
  <input type="password" name="senha" value="senha" onfocus="if(this.value=='senha') this.value='';" onblur="if(this.value=='') this.value='senha';" />
  
  <input type="hidden" name="acao" value="login"/>
  
  <input type="submit" value="Ok" class="txtIndent" />
</form>
<?php
  
        }        
	}
    
}

