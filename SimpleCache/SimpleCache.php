<?php
/**
 * SimpleCache
 * Gerenciamento simples de cache
 * 
 * @author Renato Neto - http://renatodev.com <skapemr@gmail.com>
 * @version 1.0
 */
class SimpleCache {
    /**
     * Tempo de validade padrão do cache (minutos)
     */
    const DEFAULT_EXPIRATION_TIME = '10 minutes';

    /**
     * Extensão dos arquivos de cache
     */
    const CACHE_EXTENSION = '.sc';

    /**
     * Caminho do diretório de cache
     * 
     * @var string 
     */
    private $_cachePath;

    /**
     * Método construtor
     * 
     * @param string $path Caminho do diretório
     */
    public function __construct($path) {
	try {
	    $this->setCachePath($path);
	} catch(SimpleCache_Exception $e) {
	    echo $e->getMessage();
	}
    }

    /**
     * Define o diretório de cache
     * 
     * @param string $path Caminho do diretório
     * @return bool 
     */
    protected function setCachePath($path) {
	if(file_exists($path) && is_dir($path) && is_writable($path)) {
	    if(substr($path, -1) != DIRECTORY_SEPARATOR) {
		$path .= DIRECTORY_SEPARATOR;
	    }

	    $this->_cachePath = $path;
	} else {
	    throw new SimpleCache_Exception('Diretório de cache não encontrado ou sem permissão de escrita.');
	}
    }

    /**
     * Retorna o caminho da pasta de cache
     * 
     * @return string 
     */
    protected function getCachePath() {
	return $this->_cachePath;
    }

    /**
     * Salva um arquivo de cache
     * 
     * @param string $name Identificador do cache
     * @param mixed $content Conteúdo à ser salvo
     * @param mixed $time Tempo de validade (pode ser um inteiro que indica os minutos ou textual [inglês])
     * @return bool
     */
    public function save($name, $content, $time = self::DEFAULT_EXPIRATION_TIME) {
	try {

	    $cache = new SimpleCache_Item($content, $this->getExpiration($time));
	    if(!file_put_contents($this->getCacheFilePath($name), $cache)) {
		throw new SimpleCache_Exception('Houve um erro ao salvar o cache.');
	    }

	    return true;
	} catch(SimpleCache_Exception $e) {
	    echo $e->getMessage();
	}
    }

    /**
     * Lê um arquivo de cache
     * 
     * @param string $name Identificador do cache
     * @return mixed 
     */
    public function read($name) {
	$cacheFile = $this->getCacheFilePath($name);

	if(file_exists($cacheFile)) {
	    $cache = unserialize(file_get_contents($cacheFile));

	    if(!$cache instanceof SimpleCache_Item) {
		return false;
	    }

	    if($cache->expiration < time()) {
		$this->delete($name);
		return false;
	    }

	    return $cache->content;
	}

	return false;
    }

    /**
     * Remove um arquivo de cache
     * 
     * @param string $name Identificador do cache
     * @return bool 
     */
    public function delete($name) {
	$name = $this->getCacheFilePath($name);

	if(file_exists($name) && unlink($name)) {
	    return true;
	}

	return false;
    }

    /**
     * Gera o time de expiração do arquivo
     * de acordo com o parâmetro passado
     * 
     * @param mixed $time Expressão à ser convertida
     * @return integer 
     */
    protected function getExpiration($time) {
	switch($time) {

	    case is_int($time):
		return time() + ($time * 3600);
		break;

	    case is_string($time):
		return strtotime($time);
		break;

	    default:
		throw new SimpleCache_Exception('Formato de tempo inválido');
	}
    }

    /**
     * Retorna o caminho do arquivo
     * 
     * @param string $name Identificador do cache
     * @return string 
     */
    protected function getCacheFilePath($name) {
	return $this->getCachePath() . md5($name) . self::CACHE_EXTENSION;
    }

}

class SimpleCache_Exception extends Exception {}

class SimpleCache_Item {

    public $expiration;
    public $content;

    public function __construct($content, $expiration) {
	$this->expiration = $expiration;
	$this->content = $content;
    }

    public function __toString() {
	return serialize($this);
    }

}