<?php if (minc_intranet_is_logado()) { $usuario = minc_intranet_get_usuario(); ?>
	<div class="caixa_bem_vindo">	
        <script type="text/javascript" src="<?php bloginfo( 'template_directory' ); ?>/js/cufon-yui.js"></script>
		<script type="text/javascript" src="<?php bloginfo( 'template_directory' ); ?>/js/Code_Light_300-Code_Bold_700.font.js"></script>       
        <script type="text/javascript">
            
            Cufon.replace('.cufon2');
            Cufon.replace('.cufon3');
            
        </script>
        
        <div class="fr">
            <span class="cufon2">Esse é o portal de <?php echo $usuario->getApelido() ?></span>
        </div>
        
        <br clear="all" />
        <span class="cufon3">Que Faz o Ministério da Cultura</span>
        
	</div>
 } else { ?>
	<form action="<?php echo minc_intranet_get_url() ?>" method="post" class="caixa_login">
		<label>Login</label>
		<input type="text" name="login" value="login" onfocus="if(this.value=='login') this.value='';" onblur="if(this.value=='') this.value='login';" />
		
		<label>Senha</label>
		<input type="password" name="senha" value="senha" onfocus="if(this.value=='senha') this.value='';" onblur="if(this.value=='') this.value='senha';" />
         
		<input type="hidden" name="minc_acao" value="login"/>
         
		<input type="submit" value="Ok" class="txtIndent" />
	</form>
<?php } ?>