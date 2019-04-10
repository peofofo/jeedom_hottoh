<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

/* * ***************************Includes********************************* */
require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';

class Socket {
  	private $id=0;
  	private $socket;
  	private $adresse;
  	private $port;
  	private $reponse;
  	private $requete;
  	private $connecte;
  	private $erreur;
  	private static $globalId = 1;
  
  
  	function CreateSocket(){
     	error_reporting(E_ALL);
      	/* Crée un socket TCP/IP. */
		$this->socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
		if ($this->socket === false) {
        	$this->erreur=true;
    		//echo "socket_create() a échoué : raison :  " . socket_strerror(socket_last_error()) . "\n";
		} else {
        	$this->erreur=false;
		}
    }
  
  	function ConnectSocket(){
     	$adresse="192.168.0.250";
      	$port=5001;
		if (socket_connect($this->socket, $this->adresse, $this->port)) {
    		$this->connecte=true;
          	$this->erreur=false;
          	//echo "OK Connection à ".$adresse." et ".$port.".\n";
        } else {
            $this->connecte=false;
            $this->erreur=true;
         }
    }
   
  
  	function SendReq(){
		socket_write($this->socket, $this->requete, strlen($this->requete));
      	flush();
    }
  
  
  	function ReadReq(){
     	 $this->reponse = socket_read($this->socket, 2048, PHP_NORMAL_READ);
    }
  
  	function CloseSocket(){
		socket_close($this->socket);
      	$this->connecte=false;
    }
  
  	/* * ***************************getters setters********************************* */
	public function getid(){
    	return $this->id;
  	}
  
  	public function getconnecte(){
    	return $this->connecte;
  	}
  	
  	public function geterreur(){
    	return $this->erreur;
  	}	
  
  	public function getsocket(){
    	return $this->socket;
  	}
  
  	public function getadresse(){
    	return $this->adresse;
  	}
  	public function getport(){
    	return $this->port;
  	}
  
  	public function getreponse(){
    	return $this->reponse;
  	}
  
  	public function getrequete(){
    	return $this->requete;
  	}
  
  	public static function getGlobalId(){
    	return self::$globalId;
  	}
  
  // Liste des setters
  
  public function setId($id)
  {
    $id = (int) $id;
    
    // On vérifie ensuite si ce nombre est bien strictement positif.
    if ($id > 0){
      // Si c'est le cas, c'est tout bon, on assigne la valeur à l'attribut correspondant.
      $this->id = $id;
    }
  }
  public static function setGlobalId(){
    	self::$globalId++;
  }
  public function setAdresse($adresse)
  {
    // On vérifie qu'il s'agit bien d'une chaîne de caractères.
    if (is_string($adresse))
    {
      $this->adresse = $adresse;
    }
  }
  public function setPort($port)
  {
    $port = (int) $port;
    if ($port > 0)
    {
      $this->port = $port;
    }
  }
  public function setRequete($req)
  {
    // On vérifie qu'il s'agit bien d'une chaîne de caractères.
    if (is_string($req))
    {
      $this->requete = $req;
    }
  }
}
?>


