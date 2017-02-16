<?php
/**
 * Wp_Autenticacao_Ad_Widget_Minhaintranetmenu class
 **/
class Wp_Autenticacao_Ad_Widget_Minhaintranetmenu extends WP_Widget {

    /**
     * Configura o widget
     */

    public function __construct() {
        $widget_ops = array( 
			'classname' => 'wp-autenticacao-ad-widget-minhaintranetmenu',
			'description' => 'MinhaIntranetMenu',
		);
		parent::__construct( 'wp_autenticacao_ad_widget_minhaintranetmenu', 'MinhaIntranetMenu', $widget_ops );
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
            
            <div class="et_pb_widget widget_archive">
            
              <h4 class="widgettitle">Serviços</h4>
              
	      <?php 
            if (isset($meuMenu)) { 
                $i = 0;
                foreach ($meuMenu as $meuMenuItem) {
                    
                    if ($meuMenuItem['srv_link'] == null) {
                        if ($i > 0) echo '</ul>';
                        echo '<h5 class="widgettitle">' . utf8_encode($meuMenuItem['srv_titulo']) . '</h5>';
                        echo '<ul>';
						
                    } else {	
                        if ($meuMenuItem['srv_codigo'] == 52) {
                            $target = ($meuMenuItem['srv_janela'] == 'pop-up') ? "class='meuMenuPopup'" : "target='blank'";
                            echo '<li><a href="' . $meuMenuItem['srv_linke'] . '" target="_blank"> ' . utf8_encode($meuMenuItem['srv_titulo']) . '</a></li>';
                            
                        } else { 
                            $target = ($meuMenuItem['srv_janela'] == 'pop-up') ? "class='meuMenuPopup'" : "target='iframeMinhaPagina'";
                            $targetWindow = ($meuMenuItem['srv_janela'] != 'pop-up') ? "iframeMinhaPagina" : '';
                            echo '<li><a href="' . $meuMenuItem['srv_linke'] . ' "class="meuMenuPopup" target="' . $targetWindow . '">' . utf8_encode($meuMenuItem['srv_titulo']) . '</a></li>';
                        }
                    }
					
                    $i++; 
                }
                echo '</ul>';
            } 
?>            
            </div>
<?php
        }
	}
    
}

