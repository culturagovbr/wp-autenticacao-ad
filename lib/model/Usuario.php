<?php

class Usuario {

	private $codUsuario;
	private $login;
	private $apelido;
	private $nomeCompleto;
	private $area;
	private $funcao;
	private $unidade;
	private $localizacao;
	private $complemento;
	private $telefone;
	private $validadeSenha;
	private $limiteUtilizacao;
	private $ultimaAtualizacao;
	private $email;
	private $situacao;
	
	private $cadastroCompleto;

	public function getCodUsuario() {
		return $this->codUsuario;
	}
	
	public function setCodUsuario($codUsuario) {
		$this->codUsuario = $codUsuario;
	}
	
	public function getLogin() {
		return $this->login;
	}
	
	public function setLogin($login) {
		$this->login = $login;
	}
	
	public function getApelido() {
		if (isset($this->apelido) && $this->apelido != '') {
			$nome = $this->apelido;
		
		} else if (isset($this->nomeCompleto) && $this->nomeCompleto != '') {
			$nomes = explode(' ', $this->nomeCompleto);
			$nome = $nomes[0];
		
		} else {
			$nome = $this->login;
		}
		
		return $nome;
	}
	
	public function setApelido($apelido) {
		$this->apelido = $apelido;
	}
	
	public function getNomeCompleto() {
		return $this->nomeCompleto;
	}
	
	public function setNomeCompleto($nomeCompleto) {
		$this->nomeCompleto = $nomeCompleto;
	}
	
	public function getArea() {
		return $this->area;
	}
	
	public function setArea($area) {
		$this->area = $area;
	}
	
	public function getFuncao() {
		return $this->funcao;
	}
	
	public function setFuncao($funcao) {
		$this->funcao = $funcao;
	}
	
	public function getUnidade() {
		return $this->unidade;
	}
	
	public function setUnidade($unidade) {
		$this->unidade = $unidade;
	}
	
	public function getLocalizacao() {
		return $this->localizacao;
	}
	
	public function setLocalizacao($localizacao) {
		$this->localizacao = $localizacao;
	}
	
	public function getComplemento() {
		return $this->complemento;
	}
	
	public function setComplemento($complemento) {
		$this->complemento = $complemento;
	}
	
	public function getTelefone() {
		return $this->telefone;
	}
	
	public function setTelefone($telefone) {
		$this->telefone = $telefone;
	}
	
	public function getValidadeSenha() {
		return $this->validadeSenha;
	}
	
	public function setValidadeSenha($validadeSenha) {
		$this->validadeSenha = $validadeSenha;
	}
	
	public function getLimiteUtilizacao() {
		return $this->limiteUtilizacao;
	}
	
	public function setLimiteUtilizacao($limiteUtilizacao) {
		$this->limiteUtilizacao = $limiteUtilizacao;
	}
	
	public function getUltimaAtualizacao() {
		return $this->ultimaAtualizacao;
	}
	
	public function setUltimaAtualizacao($ultimaAtualizacao) {
		$this->ultimaAtualizacao = $ultimaAtualizacao;
	}
	
	public function getEmail() {
		return $this->email;
	}
	
	public function setEmail($email) {
		$this->email = $email;
	}
	
	public function getSituacao() {
		return $this->situacao;
	}
	
	public function setSituacao($situacao) {
		$this->situacao = $situacao;
	}
	
	public function isCadastroCompleto() {
		return (boolean)$this->cadastroCompleto;
	}
	
	public function setCadastroCompleto($cadastroCompleto) {
		$this->cadastroCompleto = $cadastroCompleto;
	}
}
