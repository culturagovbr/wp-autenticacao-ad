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
?>
<div class="caixa_internas caixa_minhasferramentas">
                        
                        <h2>Minha Página na Intranet</h2>
                   		
                        <div class="caixa_conteudo">
                			
                          <iframe name="iframeMinhaPagina" width="450" height="500" frameborder="0" src="http://intranet.minc.gov.br/intrascript/spoa/cgmi/credsist/usuarede.idc?LOGON_USER=<?php echo $cpf; ?>&amp;operacao=LOGON&amp;etapa=LOGON&amp;opcao=S"></iframe>
                        </div>
                        
                        <div class="caixa_menuinternas">
                			
                            <h3 class="txtIndent">Serviços</h3>
                            
			    <h4>Cadastros</h4>
			    <ul>
			      <li>
				<a href="http://intranet.minc.gov.br/IntraScript/spoa/cgmi/credsist/atuacada.idc?LOGON_USER=<?php echo $cpf; ?>&amp;etapa=ATUACADA&amp;operacao=LOGON" class="meuMenuPopup">Cadastro Pessoal</a>
			      </li>
			      <li><a href="http://intranet.minc.gov.br/IntraScript/spoa/cgmi/credsist/atuaaces.idc?LOGON_USER=<?php echo $cpf; ?>&amp;etapa=CARREGA&amp;operacao=ACESSOS&amp;opcao=P" class="meuMenuPopup">Sistemas Ativos</a>
			      </li>
			    </ul>
			    <h4>Recursos Humanos</h4>
			    <ul>
			      <li>
				<a href="http://intranet.minc.gov.br/IntraScript/spoa/cgmi/sae/solicite.idc?LOGON_USER=<?php echo $cpf; ?>&amp;servico=http://intra/srh/ponto/frmfolhaponto.php" class="meuMenuPopup">Imp. Folha Ponto</a>
			      </li>
			      <li>
				<a href="http://intranet.minc.gov.br/IntraScript/spoa/cgmi/sae/solicite.idc?LOGON_USER=<?php echo $cpf; ?>&amp;servico=http://intra/srh/ferias/avisoferias.html" class="meuMenuPopup">Progr. de Férias</a>
			      </li>
			      <li>
				<a href="http://intranet.minc.gov.br/IntraScript/spoa/cgmi/sae/solicite.idc?LOGON_USER=<?php echo $cpf; ?>&amp;servico=http://intra/srh/ir/frmimprenda.php" class="meuMenuPopup">Form Imp Renda</a>
			      </li>
			      <li>
				<a href="http://intranet.minc.gov.br/IntraScript/spoa/cgmi/sae/solicite.idc?LOGON_USER=<?php echo $cpf; ?>&amp;servico=http://intra/srh/ponto/ctrRegistraFrequencia/ctrRegistraFrequencia.php" class="meuMenuPopup">Ponto On-Line</a>
			      </li>
			      <li>
				<a href="http://intranet.minc.gov.br/IntraScript/spoa/cgmi/frequencia/ponto.idc?LOGON_USER=<?php echo $cpf; ?>" class="meuMenuPopup">Folha Frequência</a>
			      </li>
			      <li><a href="http://intranet.minc.gov.br/IntraScript/spoa/cgmi/sae/solicite.idc?LOGON_USER=<?php echo $cpf; ?>&amp;servico=http://intra/srh/ponto/frmrecesso.php" class="meuMenuPopup">Recesso de fim de ano</a>
			      </li>
			    </ul>
			    <h4>Demandas</h4>
			    <ul>
			      <li>
				<a href="http://intranet.minc.gov.br/IntraScript/spoa/cgmi/sae/dgisrv.idc?LOGON_USER=<?php echo $cpf; ?>&amp;servico=mUsuario/mUsuario.php" class="meuMenuPopup">CGLOG (Recursos Logísticos)</a>
			      </li>
			      <li>
				<a href="http://atendeti.cultura.gov.br?LOGON_USER=<?php echo $cpf; ?>" target="_blank"> atendeTI - Central de Sv de TI</a>
			      </li>
			    </ul>
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

