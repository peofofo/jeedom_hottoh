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

class Getchronotemperature {
  	private $id=0;
  	private $values;
	  
  	const COMMAND="DAT";
  	const PARAMETERS=array("1");
  
  	const INDEX_PAGE = 0;
  	const INDEX_STATE = 1;
  	const INDEX_TEMPERATURE_1 = 2;
  	const INDEX_TEMPERATURE_1_MIN = 3;
  	const INDEX_TEMPERATURE_1_MAX = 4;
  	const INDEX_TEMPERATURE_2 = 5;
  	const INDEX_TEMPERATURE_2_MIN = 6;
  	const INDEX_TEMPERATURE_2_MAX = 7;
  	const INDEX_TEMPERATURE_3 = 8;
  	const INDEX_TEMPERATURE_3_MIN = 9;
  	const INDEX_TEMPERATURE_3_MAX = 10;
  
  	public function __construct($values){
    	$this->values=$values;
    }
   	
  	public function toString(){
    	echo'<br/>
        Index page: '.$this->getPageIndex().'<br/>
        Etat de Fonctionnement Chrono: '.$this->getState().'<br/>
        Température 1: '.$this->getTemperature1().'°C<br/>
        Température 1 MIN: '.$this->getTemperature1Min().'°C<br/>
        Température 1 MAX: '.$this->getTemperature1Max().'°C<br/>
        Température 2: '.$this->getTemperature2().'°C<br/>
        Température 2 MIN'.$this->getTemperature2Min().'°C<br/>
        Température 2 MAX: '.$this->getTemperature2Max().'°C<br/>
        Température 3: '.$this->getTemperature3().'°C<br/>
        Température 3 MIN: '.$this->getTemperature3Min().'°C<br/>
        Température 3 MAX: '.$this->getTemperature3Max().'°C<br/>
       	';/*
        ';*/
    }
  
  	/* * ***************************getters setters********************************* */
	public function getid(){
    	return $this->id;
  	}
  	public function getPageIndex(){
    	return (int) $this->values[0];
  	}
  	public function getState(){
    	return (int) $this->values[1];
  	}
  	public function getTemperature1(){
    	return ((int)$this->values[2])/10;
  	}
  	public function getTemperature1Min(){
    	return ((int)$this->values[3])/10;
  	}
  	public function getTemperature1Max(){
    	return ((int)$this->values[4])/10;
  	}
  	public function getTemperature2(){
    	return ((int)$this->values[5])/10;
  	}
  	public function getTemperature2Min(){
    	return ((int)$this->values[6])/10;
  	}
  	public function getTemperature2Max(){
    	return ((int)$this->values[7])/10;
  	}
  	public function getTemperature3(){
    	return ((int)$this->values[8])/10;
  	}
  	public function getTemperature3Min(){
    	return ((int)$this->values[9])/10;
  	}
  	public function getTemperature3Max(){
    	return ((int)$this->values[10])/10;
  	}
  	public function getValues(){
  		return $this->values;
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
  public function setValues($values){
  	$this->values=$values;
  }
}
?>


