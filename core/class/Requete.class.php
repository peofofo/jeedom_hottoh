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
//require_once dirname(__FILE__) . '/../../../../core/php/core.inc.php';
//require 'Socket.class.php';
  //////////////////////////////////////////////////////
    // CCITT, X24
    define('CRC16POLYN',0x1021);
    define('CRC16POLYI',0x8408);
  
class Requete {
  	private $id=0;
  	private $requete;
  	private $type;
  	private $command;
  	private $parametres;
  
  	const READ="R";
  	const WRITE="W";
  	const EXECUTE="E";
  
  	public function __construct($commande, $type, $parametres){
    	$this->command=$commande;
      	$this->type=$type;
      	$this->parametres=$parametres;
    	$this->buildRawPacket();
      	HottohSocket::setGlobalId();
    }
  
  
  	private function buildRawPacket(){
    	$str1=sprintf("%05d", HottohSocket::getGlobalId());
      	$str2=$this->getRawData();
      	$str3=$str1."A---".$str2;
      	$str4=$this->getCRC($str3);
      	//echo"CRC:".$str3;
      	$str5=$str3.$str4;
      	$this->requete="#".$str5."\n";
    }
  
  
  	private function getParametersString(){
      	$str1="";
    	foreach($this->parametres as $value){
        	$str2=$value;
          	$str1=$str1.$str2.";";
        }
      	return $str1;
    }
  
  	private function getRawData(){
    	$str=(integer) strlen($this->getParametersString());
      	$str1=sprintf("%04X", $str);
      	$str2=$str1.$this->command.$this->type;
      	return $str2.$this->getParametersString();
    }
  
  	private function getCRC($paramString){
      	//echo"hexa:".sprintf("%04X", $this->CRC16Normal($paramString));
     	//echo '<br />0x'.dechex(CRC16Normal('123456789')).' == 0x29B1<br />';
      	return sprintf("%04X", $this->CRC16Normal($paramString));
    }
  
  	// for "STANDARD CRC16" use 0x8005 and 0xA001
  	private function CRC16Normal($buffer) {
    $result = 0xFFFF;
    if ( ($length = strlen($buffer)) > 0) {
    	for ($offset = 0; $offset < $length; $offset++) {
        	$result ^= (ord($buffer[$offset]) << 8);
            for ($bitwise = 0; $bitwise < 8; $bitwise++) {
            	if (($result <<= 1) & 0x10000) $result ^= CRC16POLYN;
                	$result &= 0xFFFF; /* gut the overflow as php has no 16 bit types */
                }
            }
        }
        return $result;
    }
    
    private function CRC16Inverse($buffer) {
 		$result = 0xFFFF;
        if ( ($length = strlen($buffer)) > 0) {
            for ($offset = 0; $offset < $length; $offset++) {
                $result ^= ord($buffer[$offset]);
                for ($bitwise = 0; $bitwise < 8; $bitwise++) {
                    $lowBit = $result & 0x0001;
                    $result >>= 1;
                    if ($lowBit) $result ^= CRC16POLYI;
                }
            }
        }
        return $result;
    }
  
  
  	/* * ***************************getters setters********************************* */
	public function getid(){
    	return $this->id;
  	}
  
  	public function getrequete(){
    	return $this->requete;
  	}
  	
  	public function getResponseID($response){
    	return substr($response,1,5);
    }
  
  	public function getResponseCommand($response){
    	return substr($response,14,3);
    }
  	
  	public function getResponsePayload($response){
    	return substr($response,18,-5);
    }
  	
  	public function getResponseValues($response){
      	return explode(";",$this->getResponsePayload($response),-1);
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