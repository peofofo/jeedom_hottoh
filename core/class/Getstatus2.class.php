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

class Getstatus2 {
  	private $id=0;
  	private $values;
	  
  	const COMMAND="DAT";
  	const PARAMETERS=array("2");
  
  	const INDEX_PAGE = 0;
  	const INDEX_FLOW_SWITCH = 1;
  	const INDEX_GENERIC_PUMP = 2;
  	const INDEX_AIREX_1 = 3;
  	const INDEX_AIREX_2 = 4;
  	const INDEX_AIREX_3 = 5;
  	const INDEX_PUFFER = 6;
  	const INDEX_PUFFER_SET = 7;
  	const INDEX_PUFFER_SET_MIN = 8;
  	const INDEX_PUFFER_SET_MAX = 9;
  	const INDEX_BOILER = 10;
  	const INDEX_BOILER_SET = 11;
  	const INDEX_BOILER_SET_MIN = 12;
  	const INDEX_BOILER_SET_MAX = 13;
  	const INDEX_DHW = 14;
  	const INDEX_DHW_SET = 15;
  	const INDEX_DHW_SET_MIN = 16;
  	const INDEX_DHW_SET_MAX = 17;
  	const INDEX_ROOM_TEMP_3 = 18;
  	const INDEX_ROOM_TEMP_3_SET = 19;
  	const INDEX_ROOM_TEMP_3_SET_MIN = 20;
  	const INDEX_ROOM_TEMP_3_SET_MAX = 21;
  
  	public function __construct($values){
    	$this->values=$values;
    }
   	
  	public function toString(){
    	echo'<br/>
        Index page: '.$this->getPageIndex().'<br/>
        Interupteur de débit: '.$this->getDHWOn().'<br/>
        Pompe en fonctionnement: '.$this->getPumpOn().'<br/>
        Vitesse ventilateur 1: '.$this->getFan1Speed().'<br/>
        Vitesse ventilateur 2: '.$this->getFan2Speed().'<br/>
        Vitesse ventilateur 3: '.$this->getFan3Speed().'<br/>
        Bouffante: '.$this->getPuffer().'<br/>
        Bouffante SET: '.$this->getPufferSet().'<br/>
        Bouffante MIN: '.$this->getPufferSetMin().'<br/>
        Bouffante MAX: '.$this->getPufferSetMax().'<br/>
        Chaudière: '.$this->getBoiler().'<br/>
        Chaudière SET: '.$this->getBoilerSet().'<br/>
        Chaudière MIN: '.$this->getBoilerSetMin().'<br/>
        Chaudière MAX: '.$this->getBoilerSetMax().'<br/>
        Eau chaude DHW: '.$this->getDHW().'°C<br/>
        Eau chaude DHW SET: '.$this->getDHWSet().'°C<br/>
        Eau chaude DHW MIN: '.$this->getDHWSetMin().'°C<br/>
        Eau chaude DHW MAX: '.$this->getDHWSetMax().'°C<br/>
        Température pièce 3: '.$this->getRoomTemperature3().'°C<br/>
        Température SET pièce 3: '.$this->getSetTemperature3().'°C<br/>
        Température MIN pièce 3: '.$this->getSetTemperatureMin3().'°C<br/>
        Température MAX pièce 3: '.$this->getSetTemperatureMax3().'°C<br/>';/*
        ';*/
    }
  
  	/* * ***************************getters setters********************************* */
	public function getid(){
    	return $this->id;
  	}
  	public function getBoiler(){
    	return ((int)$this->values[10])/10;
  	}
  	public function getBoilerSet(){
    	return ((int)$this->values[11])/10;
  	}
  	public function getBoilerSetMax(){
    	return ((int)$this->values[13])/10;
  	}
  	public function getBoilerSetMin(){
    	return ((int)$this->values[12])/10;
  	}
  	public function getDHW(){
    	return ((int)$this->values[14])/10;
  	}
  	public function getDHWOn(){
    	return (int) $this->values[1];
    }
  	public function getDHWSet(){
    	return ((int)$this->values[15])/10;
  	}
  	public function getDHWSetMax(){
    	return ((int)$this->values[17])/10;
  	}
  	public function getDHWSetMin(){
    	return ((int)$this->values[16])/10;
  	}
  	public function getFan1Speed(){
    	return (float) $this->values[3];
    }
 	public function getFan2Speed(){
    	return (float) $this->values[4];
    }
  	public function getFan3Speed(){
    	return (float) $this->values[5];
    }
  	public function getPageIndex(){
    	return (int) $this->values[0];
  	}
  	public function getPuffer(){
    	return ((int)$this->values[6])/10;
  	}
  	public function getPufferSet(){
    	return ((int)$this->values[7])/10;
  	}
  	public function getPufferSetMax(){
    	return ((int)$this->values[9])/10;
  	}
  	public function getPufferSetMin(){
    	return ((int)$this->values[8])/10;
  	}	
  	public function getPumpOn(){
    	return (int) $this->values[2];
  	}
  	public function getRoomTemperature3(){
    	return ((int)$this->values[18])/10;
  	}
  	public function getSetTemperature3(){
    	return ((int)$this->values[19])/10;
  	}
  	public function getSetTemperatureMax3(){
    	return ((int)$this->values[21])/10;
  	}
  	public function getSetTemperatureMin3(){
    	return ((int)$this->values[20])/10;
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


