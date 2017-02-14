<?php

/**
 * Wp_Autenticacao_Ad_Widget class
 **/
class Wp_Autenticacao_Ad_Widget_Login extends WP_Widget {

    /**
     * Configura o widget
     */

    public function __construct() {
        $widget_ops = array( 
			'classname' => 'wp-autenticacao-ad-widget-login',
			'description' => 'Fazer login no AD',
		);
		parent::__construct( 'wp_autenticacao_ad_widget_login', 'Fazer login no AD', $widget_ops );
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
?>
	<div class="caixa_bem_vindo">	
          <div>
	    <span><?php echo get_option('texto_logon'); ?>, <?php echo $usuario->getApelido() ?></span>
          </div>
          <br clear="all" />
            <iframe width="700" height="350" src="http://intra/srh/ponto/ctrRegistraFrequencia/ctrRegistraFrequencia.php?sLogon=<?php echo $usuario->getLogin() ?>&sIp=<?php echo wp_autenticacao_ad_get_ip(); ?>">ponto</iframe>

          <br clear="all" /><br clear="all" />
          <form action="<?php echo wp_autenticacao_ad_get_url() ?>" method="post" class="caixa_bem_vindo">
	    <input type="hidden" name="acao" value="logout"/>
            <input type="submit" value="Sair" class="txtIndent" />
          </form>
	  <br clear="all" />
	  
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

