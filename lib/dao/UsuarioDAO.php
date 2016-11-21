<?php

class UsuarioDAO {

	public function inserir($usuario) {
		global $wpdb;
		try {
			$resp = $wpdb->query($wpdb->prepare("INSERT INTO minc_usuarios (login, apelido, localizacao, complemento, telefone) VALUES ('%s', '%s', '%s', '%s', '%s')", $usuario->getLogin(), $usuario->getApelido(), $usuario->getLocalizacao(), $usuario->getComplemento(), $usuario->getTelefone()));
			
			return $resp;
		
		} catch (Exception $e) {
			minc_intranet_add_message('Erro de acesso ao Banco de Dados - Entre em contato com o administrador do sistema.'); 
		}
	}
	
	public function alterar($usuario) {
		global $wpdb;
		try{
			$resp = $wpdb->query($wpdb->prepare("UPDATE minc_usuarios SET apelido = '%s', localizacao = '%s', complemento = '%s', telefone = '%s' WHERE login = '%s'", $usuario->getApelido(), $usuario->getLocalizacao(), $usuario->getComplemento(), $usuario->getTelefone(), $usuario->getLogin()));
			
			return $resp;
		
		} catch (Exception $e) {
			minc_intranet_add_message('Erro de acesso ao Banco de Dados - Entre em contato com o administrador do sistema.'); 
		}
	}
	
	public function excluir($login) {
		global $wpdb;
		try {
			$wpdb->query($wpdb->prepare("DELETE FROM minc_usuarios WHERE login LIKE '%s'", $login));
			
		} catch (Exception $e) {
			minc_intranet_add_message('Erro de acesso ao Banco de Dados - Entre em contato com o administrador do sistema.'); 
		}
	}
	
	public function listar() {
		try{
			$pdo = new ConexaoExtra();
			
			$stmt = $pdo->query("SELECT * FROM TABELAS.dbo.vwMincUsuarios");
			
			$usuarios = array();
			
			if ($stmt) {
				foreach ($stmt as $row) {
					$usuario = new Usuario();
					
					$usuario->setCodUsuario($row['usu_codigo']);
					$usuario->setLogin($row['login']);
					$usuario->setApelido($row['apelido']);
					$usuario->setNomeCompleto($row['nome_completo']);
					$usuario->setArea($row['area']);
					$usuario->setFuncao($row['funcao']);
					$usuario->setUnidade($row['unidade']);
					$usuario->setValidadeSenha($row['validade_senha']);
					$usuario->setLimiteUtilizacao($row['limite_utilizacao']);
					$usuario->setUltimaAtualizacao($row['ultima_atualizacao']);
					$usuario->setEmail($row['email']);
					$usuario->setSituacao($row['situacao']);
					
					$usuarios[] = $usuario;
				}
			}
			$pdo = null;
			
			return $usuarios;
		
		} catch (Exception $e) {
			minc_intranet_add_message('Erro de acesso ao Banco de Dados - Entre em contato com o administrador do sistema.'); 
		}
	}
	
	public function contar() {
		try{
			$pdo = new ConexaoExtra();
			
			$stmt = $pdo->query("SELECT COUNT(*) AS total FROM TABELAS.dbo.vwMincUsuarios");
			$pdo = null;
			
			if ($stmt) {
				foreach ($stmt as $row) {
					return $row['total'];
				}
			}
					
		} catch (Exception $e) {
			minc_intranet_add_message('Erro de acesso ao Banco de Dados - Entre em contato com o administrador do sistema.'); 
		}
	}
	
	public function pesquisar($usuario, $pagina, $num_por_pagina = 10) {
		global $wpdb;
	
		try{
			$pdo = new ConexaoExtra();
		
			$sql = "SELECT * FROM (SELECT *, ROW_NUMBER() OVER (ORDER BY nome_completo) AS RowNum FROM TABELAS.dbo.vwMincUsuarios WHERE 1=1";
			
			if ($usuario->getNomeCompleto())
				$sql .= " AND UPPER(nome_completo) LIKE UPPER('%" . $usuario->getNomeCompleto() . "%')";
			if ($usuario->getEmail())
				$sql .= " AND UPPER(email) LIKE UPPER('%" . $usuario->getEmail() . "%')";
			if ($usuario->getArea())
				$sql .= " AND UPPER(area) LIKE UPPER('%" . $usuario->getArea() . "%')";
			if ($usuario->getFuncao())
				$sql .= " AND UPPER(funcao) LIKE UPPER('%" . $usuario->getFuncao() . "%')";
			
			$inicio = ($pagina*$num_por_pagina) - $num_por_pagina;
			$fim 	= $inicio + $num_por_pagina;
			$sql .= " ) AS q1 WHERE q1.RowNum BETWEEN $inicio AND $fim";
			
			$stmt = $pdo->query($sql);
			
			$usuarios = array();
			
			if ($stmt) {
				foreach ($stmt as $row) {
					$usuario = new Usuario();
					
					$usuario->setCodUsuario($row['usu_codigo']);
					$usuario->setLogin($row['login']);
					$usuario->setApelido($row['apelido']);
					$usuario->setNomeCompleto($row['nome_completo']);
					$usuario->setArea($row['area']);
					$usuario->setFuncao($row['funcao']);
					$usuario->setUnidade($row['unidade']);
					$usuario->setValidadeSenha($row['validade_senha']);
					$usuario->setLimiteUtilizacao($row['limite_utilizacao']);
					$usuario->setUltimaAtualizacao($row['ultima_atualizacao']);
					$usuario->setEmail($row['email']);
					$usuario->setSituacao($row['situacao']);
					$usuario->setCadastroCompleto(false);
						
					$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM minc_usuarios WHERE login LIKE '%s' LIMIT 1", $row['login']));
					
					if ($results) {
						foreach ($results as $res) {
							$usuario->setApelido($res->apelido);
							$usuario->setLocalizacao($res->localizacao);
							$usuario->setComplemento($res->complemento);
							$usuario->setTelefone($res->telefone);
							$usuario->setCadastroCompleto(true);
						}
					}
					
					$usuarios[] = $usuario;
				}
			}
			$pdo = null;
			
			return $usuarios;
		
		} catch (Exception $e) {
			minc_intranet_add_message('Erro de acesso ao Banco de Dados - Entre em contato com o administrador do sistema.'); 
		}
	}
	
	public function totalizarPesquisa($usuario) {
		try{
			$pdo = new ConexaoExtra();
		
			$sql = "SELECT COUNT(*) AS total FROM TABELAS.dbo.vwMincUsuarios WHERE 1=1";
			
			if ($usuario->getNomeCompleto())
				$sql .= " AND UPPER(nome_completo) LIKE UPPER('%" . $usuario->getNomeCompleto() . "%')";
			if ($usuario->getEmail())
				$sql .= " AND UPPER(email) LIKE UPPER('%" . $usuario->getEmail() . "%')";
			if ($usuario->getArea())
				$sql .= " AND UPPER(area) LIKE UPPER('%" . $usuario->getArea() . "%')";
			if ($usuario->getFuncao())
				$sql .= " AND UPPER(funcao) LIKE UPPER('%" . $usuario->getFuncao() . "%')";
		
			$stmt = $pdo->query($sql);
			$pdo = null;
			
			if ($stmt) {
				foreach ($stmt as $row) {
					return $row['total'];
				}
			}
		
		} catch (Exception $e) {
			minc_intranet_add_message('Erro de acesso ao Banco de Dados - Entre em contato com o administrador do sistema.'); 
		}
	}
	
	public function get($cod_usuario) {
		global $wpdb;
	
		try{
			$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM minc_usuarios WHERE cod_usuario = %d"), $cod_usuario);
			
			$usuario = new Usuario();
			
			if ($results) {
				foreach ($results as $row) {
					$usuario->setCodUsuario($row->cod_usuario);
					$usuario->setLogin($row->login);
					$usuario->setApelido($row->apelido);
					$usuario->setComplemento($row->complemento);
					$usuario->setTelefone($row->telefone);
					$usuario->setEmail($row->emails);
				}
			}
			
			return $usuario;
		
		} catch (Exception $e) {
			minc_intranet_add_message('Erro de acesso ao Banco de Dados - Entre em contato com o administrador do sistema.'); 
		}
	}
	
	public function getByLogin($login) {
		global $wpdb;
	
		try{
			$pdo = new ConexaoExtra();
			
			$usuario = new Usuario();
			$usuario->setLogin($login);
		
			$stmt = $pdo->query("SELECT TOP 1 * FROM TABELAS.dbo.vwMincUsuarios WHERE login = '$login'");
			
			if ($stmt) {
				foreach ($stmt as $row) {
					$usuario->setCodUsuario($row['usu_codigo']);
					$usuario->setApelido($row['apelido']);
					$usuario->setNomeCompleto($row['nome_completo']);
					$usuario->setArea($row['area']);
					$usuario->setFuncao($row['funcao']);
					$usuario->setUnidade($row['unidade']);
					$usuario->setValidadeSenha($row['validade_senha']);
					$usuario->setLimiteUtilizacao($row['limite_utilizacao']);
					$usuario->setUltimaAtualizacao($row['ultima_atualizacao']);
					$usuario->setEmail($row['email']);
					$usuario->setSituacao($row['situacao']);
					$usuario->setLocalizacao($row['localizacao']);
					$usuario->setComplemento($row['complemento']);
					$usuario->setTelefone($row['telefone']);
					$usuario->setCadastroCompleto(false);
				}
			}
			
			$results = $wpdb->get_results($wpdb->prepare("SELECT * FROM minc_usuarios WHERE login LIKE '%s' LIMIT 1", $login));
			
			if ($results) {
				foreach ($results as $row) {
					$usuario->setApelido($row->apelido);
					$usuario->setLocalizacao($row->localizacao);
					$usuario->setComplemento($row->complemento);
					$usuario->setTelefone($row->telefone);
					$usuario->setCadastroCompleto(true);
				}
			}
			
			$pdo = null;
			
			return $usuario;
		
		} catch (Exception $e) {
			minc_intranet_add_message('Erro de acesso ao Banco de Dados - Entre em contato com o administrador do sistema.'); 
		}
	}
	
	public function isCadastroCompleto($login) {
		global $wpdb;
	
		try{
			$result = $wpdb->get_results($wpdb->prepare("SELECT * FROM minc_usuarios WHERE login LIKE '%s' LIMIT 1", $login));
			
			if (is_array($result) && count($result) > 0)
				return true;
				
			return false;
		
		} catch (Exception $e) {
			minc_intranet_add_message('Erro de acesso ao Banco de Dados - Entre em contato com o administrador do sistema.'); 
		}
	}
	
	public function getMenuUsuario($login) {
		try{
			$pdo = new ConexaoExtra();
			
			$stmt = $pdo->prepare("EXEC cgmi..spCredenciamento '$login','MENUSERV',0,'','',''");
			$stmt->setFetchMode(PDO::FETCH_ASSOC);
			$stmt->execute();
			$dados = $stmt->fetchAll();
			
			$pdo = null;
			
			return $dados;
		
		} catch (Exception $e) {
			minc_intranet_add_message('Erro de acesso ao Banco de Dados - Entre em contato com o administrador do sistema.'); 
		}
	}
}