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

class Setvalue {
  	private $id=0;
  	private $parametre;
  	private $param;
  	private $value;
  	private $erreur=true;
  	private $repvalue=false;
	  
  	const COMMAND="DAT";
  
  	const PARAM_ON_OFF = 0;
  	const PARAM_ECO_MODE = 1;
  	const PARAM_NIVEAU_PUISSANCE = 2;
  	const PARAM_AMBIANCE_TEMPERATURE_1 = 3;
  	const INCONNU_4 = 4;
  	const PARAM_NIVEAU_FAN_1 = 5;
  	const PARAM_NIVEAU_FAN_2 = 6;
  	const PARAM_NIVEAU_FAN_3 = 7;
  	const PARAM_CHRONO_ON_OFF = 8;
  	const PARAM_CHRONO_TEMPERATURE_1 = 9;
  	const PARAM_CHRONO_TEMPERATURE_2 = 10;
  	const PARAM_CHRONO_TEMPERATURE_3 = 11;
  
  	public function __construct($param, $value){
      	$this->param=$param;
      	$this->value=$value;
    	$this->testValue();
      	if($param==self::PARAM_AMBIANCE_TEMPERATURE_1 || $param==self::PARAM_CHRONO_TEMPERATURE_1 || $param==self::PARAM_CHRONO_TEMPERATURE_2 || $param==self::PARAM_CHRONO_TEMPERATURE_3)
        	$this->value=$value*10;// On multiplie la consigne pour ne pas avoir de virgule
      	if(!$this->erreur)
        	$this->parametre=array($this->param, intval($this->value));
    }
  
  	private function testValue(){
    	if($this->param==self::PARAM_ON_OFF)
          	$this->erreur = ($this->value==0 || $this->value==1) ? false : true;
      	elseif($this->param==self::PARAM_ECO_MODE)
          	$this->erreur = ($this->value==0 || $this->value==1) ? false : true;
      	elseif($this->param==self::PARAM_NIVEAU_PUISSANCE)
          	$this->erreur = ($this->value>=0 && $this->value<=5) ? false : true;
      	elseif($this->param==self::PARAM_AMBIANCE_TEMPERATURE_1)
          	$this->erreur = ($this->value>=15 && $this->value<=30) ? false : true;
      	elseif($this->param==self::INCONNU_4)
          	$this->erreur =true;
      	elseif($this->param==self::PARAM_NIVEAU_FAN_1)
        	$this->erreur = ($this->value>=0 && $this->value<=5) ? false : true;
      	elseif($this->param==self::PARAM_NIVEAU_FAN_2)
          	$this->erreur = ($this->value>=0 && $this->value<=5) ? false : true;
      	elseif($this->param==self::PARAM_NIVEAU_FAN_3)
          	$this->erreur = ($this->value>=0 && $this->value<=5) ? false : true;
      	elseif($this->param==self::PARAM_CHRONO_TEMPERATURE_1)
          	$this->erreur = ($this->value>=15 && $this->value<=30) ? false : true;
      	elseif($this->param==self::PARAM_CHRONO_TEMPERATURE_2)
          	$this->erreur = ($this->value>=15 && $this->value<=30) ? false : true;
      	elseif($this->param==self::PARAM_CHRONO_TEMPERATURE_3)
          	$this->erreur = ($this->value>=15 && $this->value<=30) ? false : true;
    }
   	
  	public function toString(){
    }
  
  	/* * ***************************getters setters********************************* */
	public function getid(){
    	return $this->id;
  	}
  	public function getparametre(){
    	return $this->parametre;
  	}
  	public function geterreur(){
    	return $this->erreur;
  	}
  	public function getrepvalue(){
    	return $this->repvalue;
  	}

  // Liste des setters
  
  public function setId($id){
    $id = (int) $id;
    
    // On vérifie ensuite si ce nombre est bien strictement positif.
    if ($id > 0){
      // Si c'est le cas, c'est tout bon, on assigne la valeur à l'attribut correspondant.
      $this->id = $id;
    }
  }
  public function setValue($value){
  	$this->value=$value;
  }
	public function setRepvalue($value){
    	$this->repvalue=($value['0']=="OK")?true:false;   
    }
}
?>


