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

class Getstatus0 {
  	private $id=0;
  	private $values;
	  
  	const COMMAND="DAT";
  	const PARAMETERS=array("0");
  
  	const STATUS_OFF = 0;
  	const STATUS_STARTING_1 = 1;
  	const STATUS_STARTING_2 = 2;
  	const STATUS_STARTING_3 = 3;
  	const STATUS_STARTING_4 = 4;
  	const STATUS_STARTING_5 = 5;
  	const STATUS_STARTING_6 = 6;
  	const STATUS_STARTING_7 = 7;
  	const STATUS_POWER = 8;
  	const STATUS_STOPPING_1 = 9;
  	const STATUS_STOPPING_2 = 10;
  	const STATUS_ECO_STOP_1 = 11;
  	const STATUS_ECO_STOP_2 = 12;
  	const STATUS_ECO_STOP_3 = 13;
  	const STATUS_LOW_PELLET = 14;
  	const STATUS_END_PELLET = 15;
  	const STATUS_BLACK_OUT = 16;
  	const STATUS_ANTI_FREEZE = 17;
  
  	const INDEX_PAGE = 0;
  	const INDEX_MANUFACTURER = 1;
  	const INDEX_BITMAP_VISIBLE = 2;
  	const INDEX_VALID = 3;
  	const INDEX_STOVE_TYPE = 4;
  	const INDEX_STOVE_STATE = 5;
  	const INDEX_STOVE_ON = 6;
  	const INDEX_ECO_MODE = 7;
  	const INDEX_TIMER_ON = 8;
  	const INDEX_AMBIENT_T1 = 9;
  	const INDEX_AMBIENT_T1_SET = 10;
  	const INDEX_AMBIENT_T1_SET_MIN = 11;
  	const INDEX_AMBIENT_T1_SET_MAX = 12;
  	const INDEX_AMBIENT_T2 = 13;
  	const INDEX_AMBIENT_T2_SET = 14;
  	const INDEX_AMBIENT_T2_SET_MIN = 15;
  	const INDEX_AMBIENT_T2_SET_MAX = 16;
  	const INDEX_WATER = 17;
  	const INDEX_WATER_SET = 18;
  	const INDEX_WATER_SET_MIN = 19;
  	const INDEX_WATER_SET_MAX = 20;
  	const INDEX_SMOKE_T = 21;
  	const INDEX_POWER_LEVEL = 22;
  	const INDEX_POWER_SET = 23;
  	const INDEX_POWER_MIN = 24;
  	const INDEX_POWER_MAX = 25;
  	const INDEX_FAN_SMOKE = 26;
  	const INDEX_FAN_1 = 27;
  	const INDEX_FAN_1_SET = 28;
  	const INDEX_FAN_1_SET_MAX = 29;
  	const INDEX_FAN_2 = 30;
  	const INDEX_FAN_2_SET = 31;
  	const INDEX_FAN_2_SET_MAX = 32;
  	const INDEX_FAN_3 = 33;
  	const INDEX_FAN_3_SET = 34;
  	const INDEX_FAN_3_SET_MAX = 35;
  
  	const CHRONO_OFF = 0;
  	const CHRONO_SLEEP = 1;
  	const CHRONO_ON_1 = 2;
  	const CHRONO_ON_2 = 3;
  	const CHRONO_ON_3 = 4;
  	const CHRONO_ON_4 = 5;
  
  	const STOVE_MANUFACTURER_65 = 65;
  	const STOVE_MANUFACTURER_76 = 76;
  	const STOVE_MANUFACTURER_100 = 100;
 
  
  	public function __construct($values){
    	$this->values=$values;
    }
  
  	public function getStoveTypeBit($paramInt){
    	return 0x1 & $this->getStoveType() >> $paramInt;
    }
  
  	public function getStoveTypeBits($paramInt1, $paramInt2){
      	$res=$this->uRShift($this->getStoveType() << (31 - $paramInt2) , ($paramInt1 + (31-$paramInt2)));
      	return $res;
    }
  
  	public function uRShift($a, $b){ 
        $z = hexdec(80000000); 
        if ($z & $a) 
        { 
            $a = ($a >> 1); 
            $a &= (~$z); 
            $a |= 0x40000000; 
            $a = ($a >> ($b - 1)); 
        } else { 
            $a = ($a >> $b); 
        } 
        return $a; 
    }
  	
  	public function toString(){
    	echo'<br/>
        Index page: '.$this->getPageIndex().'<br/>
        Fabriquant: '.$this->getManufacturerId().'<br/>
        Image visible: '.$this->isLogoVisible().'<br/>
        Validité: '.$this->isValid().'<br/>
        Type de poele: '.$this->getStoveType().'<br/>
        Etat du poele: '.$this->getStoveState().'<br/>
        Poele en Marche: '.$this->isOn().'<br/>
        Poele en Mode ECO: '.$this->isEcoModeActive().'<br/>
        Poele en Mode Timer: '.$this->getChronoMode().'<br/>
        Température pièce 1: '.$this->getRoomTemperature1().'°C<br/>
        Température SET pièce 1: '.$this->getSetTemperature1().'°C<br/>
        Température MIN pièce 1: '.$this->getSetTemperatureMin1().'°C<br/>
        Température MAX pièce 1: '.$this->getSetTemperatureMax1().'°C<br/>
        Température pièce 2: '.$this->getRoomTemperature2().'°C<br/>
        Température SET pièce 2: '.$this->getSetTemperature2().'°C<br/>
        Température MIN pièce 2: '.$this->getSetTemperatureMin2().'°C<br/>
        Température MAX pièce 2: '.$this->getSetTemperatureMax2().'°C<br/>
        Température Eau: '.$this->getWaterT().'°C<br/>
        Température SET Eau: '.$this->getWaterTset().'°C<br/>
        Température MIN Eau: '.$this->getWaterSetMin().'°C<br/>
        Température MAX Eau: '.$this->getWaterSetMax().'°C<br/>
        Température des Fumées: '.$this->getSmokeT().'°C<br/>
        Niveau de puissance de chauffe: '.$this->getPowerLevel().'<br/>
        Niveau de puissance SET de chauffe: '.$this->getPowerSet().'<br/>
        Niveau de puissance Minimum de chauffe: '.$this->getPowerMin().'<br/>
        Niveau de puissance Maximum de chauffe: '.$this->getPowerMax().'<br/>
        Niveau de vitesse du ventilateur des fumées: '.$this->getFanSmoke().'<br/>
        Niveau de vitesse du ventilateur 1: '.$this->getFan1().'<br/>
        Niveau de vitesse SET du ventilateur 1: '.$this->getFan1Set().'<br/>
        Niveau de vitesse MAX du ventilateur 1: '.$this->getFan1SetMax().'<br/>
        Niveau de vitesse du ventilateur 2: '.$this->getFan2().'<br/>
        Niveau de vitesse SET du ventilateur 2: '.$this->getFan2Set().'<br/>
        Niveau de vitesse MAX du ventilateur 2: '.$this->getFan2SetMax().'<br/>
        Niveau de vitesse du ventilateur 3: '.$this->getFan3().'<br/>
        Niveau de vitesse SET du ventilateur 3: '.$this->getFan3Set().'<br/>
        Niveau de vitesse MAX du ventilateur 3: '.$this->getFan3SetMax().'<br/>
        Chaudière disponible BoilerEnabled: '.$this->getStoveTypeBits(6, 6).'<br/>
        DHW eau chaude disponible: '.$this->getStoveTypeBits(5, 5).'<br/>
        Nombre de ventilateurs: '.$this->getStoveTypeBits(2, 3).'<br/>
        Pompe disponible: '.$this->getStoveTypeBits(4, 4).'<br/>
        Température pièce 1 disponible: '.$this->getStoveTypeBits(0, 0).'<br/>
        Température pièce 2 disponible: '.$this->getStoveTypeBits(8, 8).'<br/>
        Température pièce 3 disponible: '.$this->getStoveTypeBits(9, 9).'<br/>
        Température eau disponible: '.$this->getStoveTypeBits(1, 1).'<br/>
        ';
    }
  
  	/* * ***************************getters setters********************************* */
	public function getid(){
    	return $this->id;
  	}
  	public function getChronoMode(){
    	return (int) $this->values[8];
  	}
  	public function getFan1(){
    	return (float) $this->values[27];
    }
 	public function getFan1Set(){
    	return (float) $this->values[28];
    }
  	public function getFan1SetMax(){
    	return (float) $this->values[29];
    }
  	public function getFan2(){
    	return (float) $this->values[30];
    }
 	public function getFan2Set(){
    	return (float) $this->values[31];
    }
  	public function getFan2SetMax(){
    	return (float) $this->values[32];
    }
  	public function getFan3(){
    	return (float) $this->values[33];
    }
 	public function getFan3Set(){
    	return (float) $this->values[34];
    }
  	public function getFan3SetMax(){
    	return (float) $this->values[35];
    }
  	public function getFanSmoke(){
    	return (float) $this->values[26];
    }
  	public function getManufacturerId(){
    	return (int) $this->values[1];
  	}
  	public function getPageIndex(){
    	return (int) $this->values[0];
  	}
  	public function getPowerLevel(){
    	return (int) $this->values[22];
  	}
  	public function getPowerMax(){
    	return (int) $this->values[25];
  	}
  	public function getPowerMin(){
    	return (int) $this->values[24];
  	}
  	public function getPowerSet(){
    	return (int) $this->values[23];
  	}
  	public function getRoomTemperature1(){
    	return ((int)$this->values[9])/10;
    }
  	public function getRoomTemperature2(){
    	return ((int)$this->values[13])/10;
    }
  	public function getSetTemperature1(){
    	return ((int)$this->values[10])/10;
    }
  	public function getSetTemperature2(){
    	return ((int)$this->values[14])/10;
    }
  	public function getSetTemperatureMax1(){
    	return ((int)$this->values[12])/10;
    }
  	public function getSetTemperatureMax2(){
    	return ((int)$this->values[16])/10;
    }
  	public function getSetTemperatureMin1(){
    	return ((int)$this->values[11])/10;
    }
  	public function getSetTemperatureMin2(){
    	return ((int)$this->values[15])/10;
    }
  	public function getSmokeT(){
    	return ((int)$this->values[21])/10;
  	}
  	public function getStoveState(){
    	return (int) $this->values[5];
    }
  	public function getStovetype(){
    	return (int) $this->values[4];
    }
  	public function getWaterT(){
    	return ((int)$this->values[17])/10;
    }
  	public function getWaterTset(){
    	return ((int)$this->values[18])/10;
    }
  	public function getWaterSetMax(){
    	return ((int)$this->values[20])/10;
    }
  	public function getWaterSetMin(){
    	return ((int)$this->values[19])/10;
    }
  	public function isEcoModeActive(){
    	return (int) $this->values[7];
    }
  	public function isLogoVisible(){
    	return (int) $this->values[2];
    }
  	public function isOn(){
    	return (int) $this->values[6];
    }
  	public function isValid(){
    	return (int) $this->values[3];
    }
    public function getValues(){
  		return $this->values;
  	}
  
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


