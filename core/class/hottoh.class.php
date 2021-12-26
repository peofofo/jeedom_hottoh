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
require 'Setvalue.class.php';
require 'Socket.class.php';
require 'Requete.class.php';
require 'Getstatus0.class.php';
require 'Getstatus2.class.php';
require 'Getchronotemperature.class.php';


class hottoh extends eqLogic { 
  	//private $socket;
  
    /*     * *************************Attributs****************************** */



    /*     * ***********************Methode static*************************** */
 
   	//Fonction exÃ©cutÃ©e automatiquement toutes les minutes par Jeedom
    public static function cron() {
      	log::add('hottoh', 'debug', 'Cron');
      	if ($_eqLogic_id == null) { // La fonction n’a pas d’argument donc on recherche tous les équipements du plugin
			$eqLogics = self::byType('hottoh', true);
		} else {// La fonction a l’argument id(unique) d’un équipement(eqLogic)
			$eqLogics = array(self::byId($_eqLogic_id));
		}
    	foreach ($eqLogics as $hottoh) {//parcours tous les équipements du plugin hottoh
			  if ($hottoh->getIsEnable() == 1) {//vérifie que l'équipement est actif
				  $cmd = $hottoh->getCmd(null, 'refresh');//retourne la commande "refresh si elle existe
                  //log::add('hottoh', 'debug', 'Cron_refresh');
				  if (!is_object($cmd)) {//Si la commande n'existe pas
				  	continue; //continue la boucle
				  }
				  $cmd->execCmd(); // la commande existe on la lance
			  }
		  }	
    }
     
	public function Ecriture_parametre($setvalue){
      	      	log::add('hottoh', 'debug', 'Ecriture_parametre');
        //$setvalue = new Setvalue(Setvalue::PARAM_NIVEAU_FAN_1,3);
        if(!$setvalue->geterreur()){
        	if(!is_object($this->socket) OR !$this->socket->getconnecte()){
        		$this->Opensocket();
        	}  
        
            $requete= new Requete(Setvalue::COMMAND,Requete::WRITE,$setvalue->getparametre());
			log::add('hottoh', 'debug', $requete->getrequete());
            $this->socket->setRequete($requete->getrequete());
            $this->socket->SendReq(); // Envoi
            $this->socket->ReadReq(); // Reception
            $value=$requete->getResponseValues($this->socket->getreponse());
			log::add('hottoh', 'debug', $this->socket->getreponse());
          
            $setvalue->setRepvalue($value);
            if(!$setvalue->getrepvalue())
            	log::add('hottoh', 'debug', 'Réponse NOK suite a une action');
          	$this->socket->CloseSocket();
        } else 
          log::add('hottoh', 'error', 'Valeur impossible!');
    }
  
  	private function Opensocket(){
      	//log::add('hottoh', 'debug', 'Opensocket');
      	if (!$this->getConfiguration('ipDirect', ''))
            $adresse = '192.168.1.1';
        else 
            $adresse = $this->getConfiguration('ipDirect', '');
      	if (!$this->getConfiguration('localPort', ''))
            $port = '5001';
        else 
            $port = $this->getConfiguration('localPort', '');
      	log::add('hottoh', 'debug', 'Connection au serveur '.$adresse.':'.$port);
        
         //Ouverture Socket
    	$this->socket= new Socket;
        $this->socket->CreateSocket();
        $this->socket->setAdresse($adresse);
        $this->socket->setPort($port);
        $this->socket->ConnectSocket();
    }
  
	public function Lecture_parametre(){
      	log::add('hottoh', 'debug', 'Lecture_parametre');      
      	if(!is_object($this->socket) OR !$this->socket->getconnecte()){
        	$this->Opensocket();
        }
      	//$this->Opensocket();
      	if(!$this->socket->geterreur()){
      
          $requete1= new Requete(Getstatus0::COMMAND,Requete::READ,Getstatus0::PARAMETERS);
          $requete2= new Requete(Getstatus2::COMMAND,Requete::READ,Getstatus2::PARAMETERS);
          $requete3= new Requete(Getchronotemperature::COMMAND,Requete::READ,Getchronotemperature::PARAMETERS);          

          //Requete GetStatus0
          $this->socket->setRequete($requete1->getrequete());
          $this->socket->SendReq(); // Envoi
          $this->socket->ReadReq(); // Reception
          $getstatus0=new Getstatus0($requete1->getResponseValues($this->socket->getreponse()));
          log::add('hottoh', 'debug', 'Requete_1'.$requete1->getrequete());
          log::add('hottoh', 'debug', 'Reponse_1'.$this->socket->getreponse());

          //Requete GetStatus2
          $this->socket->setRequete($requete2->getrequete());
          $this->socket->SendReq(); // Envoi
          $this->socket->ReadReq(); // Reception
          $getstatus2=new Getstatus2($requete2->getResponseValues($this->socket->getreponse()));
          log::add('hottoh', 'debug', 'Requete_2'.$requete2->getrequete());
          log::add('hottoh', 'debug', 'Reponse_2'.$this->socket->getreponse());

          //Requete chronotemperature
          $this->socket->setRequete($requete3->getrequete());
          $this->socket->SendReq(); // Envoi
          $this->socket->ReadReq(); // Reception
          $getchronotemmperature=new Getchronotemperature($requete3->getResponseValues($this->socket->getreponse()));
          log::add('hottoh', 'debug', 'Requete_3'.$requete3->getrequete());
          log::add('hottoh', 'debug', 'Reponse_3'.$this->socket->getreponse());
          //log::add('hottoh', 'defaut', 'Requete_3'.$requete3->getrequete());
          //log::add('hottoh', 'defaut', 'Reponse_3'.$this->socket->getreponse());

          //Fermeture Socket
          $this->socket->CloseSocket();

          if(count($getstatus0->getValues())==36 && count($getstatus2->getValues())==22 && count($getchronotemmperature->getValues())==11){
            //log::add('hottoh', 'debug', 'test etat stove0:'.count($getstatus0->getValues()));
            $this->checkAndUpdateCmd('fabriquant',$getstatus0->getManufacturerId());
            $this->checkAndUpdateCmd('type',$getstatus0->getStoveType());
            $this->checkAndUpdateCmd('etat',$getstatus0->getStoveState());
            $this->checkAndUpdateCmd('marche',$getstatus0->isOn());
            $this->checkAndUpdateCmd('eco',$getstatus0->isEcoModeActive());
            $this->checkAndUpdateCmd('t1',$getstatus0->getRoomTemperature1());
            $this->checkAndUpdateCmd('t1set',$getstatus0->getSetTemperature1());
            $this->checkAndUpdateCmd('t1min',$getstatus0->getSetTemperatureMin1());
            $this->checkAndUpdateCmd('t1max',$getstatus0->getSetTemperatureMax1());
            $this->checkAndUpdateCmd('t2',$getstatus0->getRoomTemperature2());
            $this->checkAndUpdateCmd('t2set',$getstatus0->getSetTemperature2());
            $this->checkAndUpdateCmd('t2min',$getstatus0->getSetTemperatureMin2());
            $this->checkAndUpdateCmd('t2max',$getstatus0->getSetTemperatureMax2());
            $this->checkAndUpdateCmd('t3',$getstatus2->getRoomTemperature3());
            $this->checkAndUpdateCmd('t3set',$getstatus2->getSetTemperature3());
            $this->checkAndUpdateCmd('t3min',$getstatus2->getSetTemperatureMin3());
            $this->checkAndUpdateCmd('t3max',$getstatus2->getSetTemperatureMax3());
            $this->checkAndUpdateCmd('tfumee',$getstatus0->getSmokeT());
            $this->checkAndUpdateCmd('pactu',$getstatus0->getPowerLevel());
            $this->checkAndUpdateCmd('pset',$getstatus0->getPowerSet());
            $this->checkAndUpdateCmd('pmin',$getstatus0->getPowerMin());
            $this->checkAndUpdateCmd('pmax',$getstatus0->getPowerMax());
            $this->checkAndUpdateCmd('fanfumee',$getstatus0->getFanSmoke());
            $this->checkAndUpdateCmd('fan1speed',$getstatus2->getFan1Speed());
            $this->checkAndUpdateCmd('fan1',$getstatus0->getFan1());
            $this->checkAndUpdateCmd('fan1set',$getstatus0->getFan1Set());
            $this->checkAndUpdateCmd('fan1max',$getstatus0->getFan1SetMax());
            $this->checkAndUpdateCmd('fan2speed',$getstatus2->getFan2Speed());
            $this->checkAndUpdateCmd('fan2',$getstatus0->getFan2());
            $this->checkAndUpdateCmd('fan2set',$getstatus0->getFan2Set());
            $this->checkAndUpdateCmd('fan2max',$getstatus0->getFan2SetMax());
            $this->checkAndUpdateCmd('fan3speed',$getstatus2->getFan3Speed());
            $this->checkAndUpdateCmd('fan3',$getstatus0->getFan3());
            $this->checkAndUpdateCmd('fan3set',$getstatus0->getFan3Set());
            $this->checkAndUpdateCmd('fan3max',$getstatus0->getFan3SetMax());
            $this->checkAndUpdateCmd('teau',$getstatus0->getWaterT());
            $this->checkAndUpdateCmd('teauset',$getstatus0->getWaterTset());
            $this->checkAndUpdateCmd('teaumin',$getstatus0->getWaterSetMin());
            $this->checkAndUpdateCmd('teaumax',$getstatus0->getWaterSetMax());
            $this->checkAndUpdateCmd('DHWon',$getstatus2->getDHWOn());
            $this->checkAndUpdateCmd('DHW',$getstatus2->getDHW());
            $this->checkAndUpdateCmd('DHWset',$getstatus2->getDHWSet());
            $this->checkAndUpdateCmd('DHWmin',$getstatus2->getDHWSetMin());
            $this->checkAndUpdateCmd('DHWmax',$getstatus2->getDHWSetMax());
            $this->checkAndUpdateCmd('pompeon',$getstatus2->getPumpOn());
            $this->checkAndUpdateCmd('pompe',$getstatus2->getPuffer());
            $this->checkAndUpdateCmd('pompeset',$getstatus2->getPufferSet());
            $this->checkAndUpdateCmd('pompemin',$getstatus2->getPufferSetMin());
            $this->checkAndUpdateCmd('pompemax',$getstatus2->getPufferSetMax());
            $this->checkAndUpdateCmd('chaudiere',$getstatus2->getBoiler());
            $this->checkAndUpdateCmd('chaudiereset',$getstatus2->getBoilerSet());
            $this->checkAndUpdateCmd('chaudieremin',$getstatus2->getBoilerSetMin());
            $this->checkAndUpdateCmd('chaudieremax',$getstatus2->getBoilerSetMax());
            $this->checkAndUpdateCmd('timer',$getstatus0->getChronoMode());
            $this->checkAndUpdateCmd('timerstate',$getchronotemmperature->getState());
            $this->checkAndUpdateCmd('timert1',$getchronotemmperature->getTemperature1());
            $this->checkAndUpdateCmd('timert1min',$getchronotemmperature->getTemperature1Min());
            $this->checkAndUpdateCmd('timert1max',$getchronotemmperature->getTemperature1Max());
            $this->checkAndUpdateCmd('timert2',$getchronotemmperature->getTemperature2());
            $this->checkAndUpdateCmd('timert2min',$getchronotemmperature->getTemperature2Min());
            $this->checkAndUpdateCmd('timert2max',$getchronotemmperature->getTemperature2Max());
            $this->checkAndUpdateCmd('timert3',$getchronotemmperature->getTemperature3());
            $this->checkAndUpdateCmd('timert3min',$getchronotemmperature->getTemperature3Min());
            $this->checkAndUpdateCmd('timert3max',$getchronotemmperature->getTemperature3Max());
            $this->refreshWidget();
            log::add('hottoh', 'debug', 'Rafraichissement des informations du poele');
          }
          else{
          	log::add('hottoh', 'error', 'Erreur de reception de lecture');
          }
       }
      else{
        log::add('hottoh', 'error', 'Erreur de connection');
      }
    }
  
    /*     * *********************MÃ©thodes d'instance************************* */

    public function preInsert() {
        
    }

    public function postInsert() {
        
    }

    public function preSave() {
      	log::add('hottoh', 'debug', 'presave');
      
    }


    public function postSave() {
    	log::add('hottoh', 'debug', 'postsave');
      
      	$hottohCmd = $this->getCmd(null, 'fabriquant');
        if (!is_object($hottohCmd)) {
        	$hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Fabriquant', __FILE__));
    	}
        $hottohCmd->setLogicalId('fabriquant');
        $hottohCmd->setEqLogic_id($this->getId());
        $hottohCmd->setDisplay("Fabriquant",0);
        $hottohCmd->setType('info');
        $hottohCmd->setSubType('string');
      	//$hottohCmd->setOrder(8);
        //$hottohCmd->setIsVisible(false);
      	$hottohCmd->setConfiguration('type','etat');
      	$hottohCmd->setTemplate('dashboard','tile');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();

        $hottohCmd = $this->getCmd(null, 'type');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Type', __FILE__));
        }
        $hottohCmd->setLogicalId('type');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Type de poele",0);
        $hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','etat');
      	$hottohCmd->setTemplate('dashboard','tile');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
        $hottohCmd = $this->getCmd(null, 'etat');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Etat', __FILE__));
        }
        $hottohCmd->setLogicalId('etat');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Etat du poele",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','etat');
      	$hottohCmd->setConfiguration('historizeMode', 'none');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();	
      
      	$hottohCmd = $this->getCmd(null, 'marche');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Allumer', __FILE__));
        }
        $hottohCmd->setLogicalId('marche');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Poele en fonctionnement",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('binary');
      	$hottohCmd->setConfiguration('type','etat');
      	$hottohCmd->setConfiguration('historizeMode', 'none');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'on');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('ON', __FILE__));
        }
        $hottohCmd->setEqLogic_id($this->getId());
        $hottohCmd->setLogicalId('on');
        $hottohCmd->setType('action');
        $hottohCmd->setSubType('other');
      	$hottohCmd->setConfiguration('type','etat');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'off');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('OFF', __FILE__));
        }
        $hottohCmd->setEqLogic_id($this->getId());
        $hottohCmd->setLogicalId('off');
        $hottohCmd->setType('action');
        $hottohCmd->setSubType('other');
      	$hottohCmd->setConfiguration('type','etat');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'eco');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Mode ECO', __FILE__));
        }
        $hottohCmd->setLogicalId('eco');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Mode eco",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('binary');
      	$hottohCmd->setConfiguration('type','etat');
      	$hottohCmd->setConfiguration('historizeMode', 'none');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'eco_on');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('ECO ON', __FILE__));
        }
        $hottohCmd->setEqLogic_id($this->getId());
        $hottohCmd->setLogicalId('eco_on');
        $hottohCmd->setType('action');
        $hottohCmd->setSubType('other');
      	$hottohCmd->setConfiguration('type','etat');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'eco_off');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('ECO OFF', __FILE__));
        }
        $hottohCmd->setEqLogic_id($this->getId());
        $hottohCmd->setLogicalId('eco_off');
        $hottohCmd->setType('action');
        $hottohCmd->setSubType('other');
      	$hottohCmd->setConfiguration('type','etat');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 't1');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Pièce 1', __FILE__));
        }
        $hottohCmd->setLogicalId('t1');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température pièce 1",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setConfiguration('type','temperature');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 't1set');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th Pièce 1', __FILE__));
        }
        $hottohCmd->setLogicalId('t1set');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température pièce 1",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setConfiguration('type','temperature');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 't1set_action');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Thermostat Pièce 1', __FILE__));
        }
        $hottohCmd->setLogicalId('t1set_action');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat pièce 1",0);
      	$hottohCmd->setType('action');
        $hottohCmd->setSubType('slider');
      	$hottohCmd->setValue($this->getCmd(null, 't1set')->getId());
      	$hottohCmd->setConfiguration('type','temperature');
      	$hottohCmd->setConfiguration('minValue', 15);
        $hottohCmd->setConfiguration('maxValue', 30);
      	$hottohCmd->setTemplate('dashboard','button');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 't1min');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Pièce 1 min', __FILE__));
        }
        $hottohCmd->setLogicalId('t1min');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température pièce 1 minimum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','temperature');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
          
        $hottohCmd = $this->getCmd(null, 't1max');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Pièce 1 max', __FILE__));
        }
        $hottohCmd->setLogicalId('t1max');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température pièce 1 maximum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','temperature');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();	
      
      	$hottohCmd = $this->getCmd(null, 't2');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Pièce 2', __FILE__));
        }
        $hottohCmd->setLogicalId('t2');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température pièce 2",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','temperature');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 't2set');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th Pièce 2', __FILE__));
        }
        $hottohCmd->setLogicalId('t2set');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat pièce 2",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','temperature');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 't2min');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Pièce 2 min', __FILE__));
        }
        $hottohCmd->setLogicalId('t2min');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température pièce 2 minimum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','temperature');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
          
        $hottohCmd = $this->getCmd(null, 't2max');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Pièce 2 max', __FILE__));
        }
        $hottohCmd->setLogicalId('t2max');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température pièce 2 maximum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','temperature');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 't3');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Pièce 3', __FILE__));
        }
        $hottohCmd->setLogicalId('t3');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température pièce 3",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','temperature');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 't3set');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th Pièce 3', __FILE__));
        }
        $hottohCmd->setLogicalId('t3set');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat pièce 3",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','temperature');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 't3min');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Pièce 3 min', __FILE__));
        }
        $hottohCmd->setLogicalId('t3min');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température pièce 3 minimum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','temperature');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
          
        $hottohCmd = $this->getCmd(null, 't3max');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Pièce 3 max', __FILE__));
        }
        $hottohCmd->setLogicalId('t3max');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température pièce 3 maximum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','temperature');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'tfumee');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('T Fumées', __FILE__));
        }
        $hottohCmd->setLogicalId('tfumee');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température des fumées",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','temperature');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'pactu');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Gain Puissance', __FILE__));
        }
        $hottohCmd->setLogicalId('pactu');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Puissance",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','puissance');
      	$hottohCmd->setConfiguration('historizeMode', 'none');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '%' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'pset');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Puissance', __FILE__));
        }
        $hottohCmd->setLogicalId('pset');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Puissance",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','puissance');
      	$hottohCmd->setConfiguration('historizeMode', 'none');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'pmin');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Puissance min', __FILE__));
        }
        $hottohCmd->setLogicalId('pmin');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Niveau de puissance minimum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','puissance');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'pmax');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Puissance max', __FILE__));
        }
        $hottohCmd->setLogicalId('pmax');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Niveau de puissance maximum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','puissance');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
	    
	$hottohCmd = $this->getCmd(null, 'pset_action');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Niveau Puissance', __FILE__));
        }
        $hottohCmd->setLogicalId('pset_action');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Niveau de puissance",0);
      	$hottohCmd->setType('action');
        $hottohCmd->setSubType('slider');
      	$hottohCmd->setValue($this->getCmd(null, 'pset')->getId());
      	$hottohCmd->setConfiguration('type','puissance');
	if($this->getCmd(null, 'pmin')->execCmd()!=0){
          $hottohCmd->setConfiguration('minValue', $this->getCmd(null, 'pmin')->execCmd());
          log::add('hottoh', 'debug', 'Nouvelle valeur pmin:'.$this->getCmd(null, 'pmin')->execCmd());
        }
      	else{
        	$hottohCmd->setConfiguration('minValue', 0);
        }
       	if($this->getCmd(null, 'pmax')->execCmd()!=5){
          $hottohCmd->setConfiguration('maxValue', $this->getCmd(null, 'pmax')->execCmd());
          log::add('hottoh', 'debug', 'Nouvelle valeur pmax:'.$this->getCmd(null, 'pmax')->execCmd());
        }
      	else{
        	$hottohCmd->setConfiguration('maxValue', 5);
        }
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'fanfumee');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('FAN Fumées', __FILE__));
        }
        $hottohCmd->setLogicalId('fanfumee');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Ventilateur des fumées",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','ventilateur');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( 'g/m' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'fan1speed');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Gain Ventilateur 1', __FILE__));
        }
        $hottohCmd->setLogicalId('fan1speed');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Vitesse du ventilateur 1",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','ventilateur');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '%' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'fan1');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Niveau Ventilateur 1', __FILE__));
        }
        $hottohCmd->setLogicalId('fan1');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Niveau de vitesse du ventilateur 1",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','ventilateur');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'fan1set');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Ventilateur 1', __FILE__));
        }
        $hottohCmd->setLogicalId('fan1set');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Réglage niveau de vitesse du ventilateur 1",0);
      	$hottohCmd->setType('action');
        $hottohCmd->setSubType('slider');
      	$hottohCmd->setValue($this->getCmd(null, 'fan1')->getId());
      	$hottohCmd->setConfiguration('type','ventilateur');
      	$hottohCmd->setConfiguration('minValue', 0);
        $hottohCmd->setConfiguration('maxValue', 5);
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'fan1max');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Ventilateur 1 max', __FILE__));
        }
        $hottohCmd->setLogicalId('fan1max');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Niveau de vitesse du ventilateur 1 maximum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','ventilateur');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'fan2speed');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Gain Ventilateur 2', __FILE__));
        }
        $hottohCmd->setLogicalId('fan2speed');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Vitesse du ventilateur 2",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','ventilateur');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '%' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'fan2');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Niveau Ventilateur 2', __FILE__));
        }
        $hottohCmd->setLogicalId('fan2');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Niveau de vitesse du ventilateur 2",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','ventilateur');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'fan2set');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Ventilateur 2', __FILE__));
        }
        $hottohCmd->setLogicalId('fan2set');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Réglage niveau de vitesse du ventilateur 2",0);
      	$hottohCmd->setType('action');
        $hottohCmd->setSubType('slider');
      	$hottohCmd->setValue($this->getCmd(null, 'fan2')->getId());
      	$hottohCmd->setConfiguration('type','ventilateur');
      	$hottohCmd->setConfiguration('minValue', 0);
        $hottohCmd->setConfiguration('maxValue', 5);
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'fan2max');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Ventilateur 2 max', __FILE__));
        }
        $hottohCmd->setLogicalId('fan2max');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Niveau de vitesse du ventilateur 2 maximum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','ventilateur');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'fan3speed');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Gain Ventilateur 3', __FILE__));
        }
        $hottohCmd->setLogicalId('fan3speed');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Vitesse du ventilateur 3",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','ventilateur');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '%' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'fan3');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Niveau Ventilateur 3', __FILE__));
        }
        $hottohCmd->setLogicalId('fan3');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Niveau de vitesse du ventilateur 3",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','ventilateur');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'fan3set');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Ventilateur 3', __FILE__));
        }
        $hottohCmd->setLogicalId('fan3set');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Réglage niveau de vitesse du ventilateur 3",0);
      	$hottohCmd->setType('action');
        $hottohCmd->setSubType('slider');
      	$hottohCmd->setValue($this->getCmd(null, 'fan3')->getId());
      	$hottohCmd->setConfiguration('type','ventilateur');
      	$hottohCmd->setConfiguration('minValue', 0);
        $hottohCmd->setConfiguration('maxValue', 5);
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'fan3max');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Ventilateur 3 max', __FILE__));
        }
        $hottohCmd->setLogicalId('fan3max');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Niveau de vitesse du ventilateur 3 maximum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','ventilateur');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'teau');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('T Eau', __FILE__));
        }
        $hottohCmd->setLogicalId('teau');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température de l\'eau",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'teauset');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th Eau', __FILE__));
        }
        $hottohCmd->setLogicalId('teauset');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat de l\'eau",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'teaumin');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('T EAU min', __FILE__));
        }
        $hottohCmd->setLogicalId('teaumin');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température de l\'eau minimum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
          
        $hottohCmd = $this->getCmd(null, 'teaumax');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('T eau MAX', __FILE__));
        }
        $hottohCmd->setLogicalId('teaumax');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température de l\'eau maximum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'DHWon');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('DHW ON', __FILE__));
        }
        $hottohCmd->setLogicalId('DHWon');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Fonctionnement DHW Eau chaude",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('binary');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'DHW');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('T DHW', __FILE__));
        }
        $hottohCmd->setLogicalId('DHW');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température DHW Eau chaude",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'DHWset');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th DHW', __FILE__));
        }
        $hottohCmd->setLogicalId('DHWset');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat DHW Eau chaude",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'DHWmin');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('T DHW min', __FILE__));
        }
        $hottohCmd->setLogicalId('DHWmin');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température DHW Eau chaude minimum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      	
      	$hottohCmd = $this->getCmd(null, 'DHWmax');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('T DHW max', __FILE__));
        }
        $hottohCmd->setLogicalId('DHWmax');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température DHW Eau chaude maximum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'pompeon');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Pompe ON', __FILE__));
        }
        $hottohCmd->setLogicalId('pompeon');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Pompe en fonctionnement",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('binary');
      	$hottohCmd->setConfiguration('type','eau');
      	$hottohCmd->setConfiguration('historizeMode', 'none');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'pompe');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('T Pompe', __FILE__));
        }
        $hottohCmd->setLogicalId('pompe');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température de la pompe",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'pompeset');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th pompe', __FILE__));
        }
        $hottohCmd->setLogicalId('pompeset');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat température de la pompe",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'pompemin');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('T pompe min', __FILE__));
        }
        $hottohCmd->setLogicalId('pompemin');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température de la pompe minimum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'pompemax');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('T pompe max', __FILE__));
        }
        $hottohCmd->setLogicalId('pompemax');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température de la pompe maximum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'chaudiere');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('T chaudière', __FILE__));
        }
        $hottohCmd->setLogicalId('chaudiere');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température de la chaudière",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'chaudiereset');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th chaudière', __FILE__));
        }
        $hottohCmd->setLogicalId('chaudiereset');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat de la chaudière",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'chaudieremin');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('T chaudière min', __FILE__));
        }
        $hottohCmd->setLogicalId('chaudieremin');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température de la chaudière minimum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'chaudieremax');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('T chaudière max', __FILE__));
        }
        $hottohCmd->setLogicalId('chaudieremax');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Température de la chaudière maximum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','eau');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'timer');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th programmable', __FILE__));
        }
        $hottohCmd->setLogicalId('timer');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat programmable",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('binary');
      	$hottohCmd->setConfiguration('type','timer');
      	$hottohCmd->setConfiguration('historizeMode', 'none');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      	
      	$hottohCmd = $this->getCmd(null, 'timer_on');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th programmable ON', __FILE__));
        }
        $hottohCmd->setEqLogic_id($this->getId());
        $hottohCmd->setLogicalId('timer_on');
        $hottohCmd->setType('action');
        $hottohCmd->setSubType('other');
      	$hottohCmd->setConfiguration('type','timer');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'timer_off');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th programmable OFF', __FILE__));
        }
        $hottohCmd->setEqLogic_id($this->getId());
        $hottohCmd->setLogicalId('timer_off');
        $hottohCmd->setType('action');
        $hottohCmd->setSubType('other');
      	$hottohCmd->setConfiguration('type','timer');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'timerstate');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Etat Th programmable', __FILE__));
        }
        $hottohCmd->setLogicalId('timerstate');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Etat du thermostat programmable",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','timer');
      	$hottohCmd->setConfiguration('historizeMode', 'none');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'timert1');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th Température 1', __FILE__));
        }
        $hottohCmd->setLogicalId('timert1');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat programmable température 1 minimum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','timer');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'timert1_action');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Thermostat Température 1', __FILE__));
        }
        $hottohCmd->setLogicalId('timert1_action');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat programmable température 1",0);
      	$hottohCmd->setType('action');
        $hottohCmd->setSubType('slider');
      	$hottohCmd->setValue($this->getCmd(null, 'timert1')->getId());
      	$hottohCmd->setConfiguration('type','timer');
      	$hottohCmd->setConfiguration('minValue', 15);
        $hottohCmd->setConfiguration('maxValue', 30);
      	$hottohCmd->setTemplate('dashboard','button');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'timert1min');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th température 1 min', __FILE__));
        }
        $hottohCmd->setLogicalId('timert1min');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat programmable température 1 minimum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','timer');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'timert1max');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th température 1 max', __FILE__));
        }
        $hottohCmd->setLogicalId('timert1max');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat programmable température 1 maximum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','timer');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'timert2');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th Température 2', __FILE__));
        }
        $hottohCmd->setLogicalId('timert2');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat programmable température 1 minimum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','timer');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'timert2_action');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Thermostat température 2', __FILE__));
        }
        $hottohCmd->setLogicalId('timert2_action');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat programmable température 2",0);
      	$hottohCmd->setType('action');
        $hottohCmd->setSubType('slider');
      	$hottohCmd->setValue($this->getCmd(null, 'timert2')->getId());
      	$hottohCmd->setConfiguration('type','timer');
      	$hottohCmd->setConfiguration('minValue', 15);
        $hottohCmd->setConfiguration('maxValue', 30);
      	$hottohCmd->setTemplate('dashboard','button');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'timert2min');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th température 2 min', __FILE__));
        }
        $hottohCmd->setLogicalId('timert2min');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat programmable température 2 minimum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','timer');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'timert2max');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th température 2 max', __FILE__));
        }
        $hottohCmd->setLogicalId('timert2max');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat programmable température 2 maximum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','timer');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'timert3');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th Température 3', __FILE__));
        }
        $hottohCmd->setLogicalId('timert3');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat programmable température 1 minimum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','timer');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'timert3_action');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Thermostat température 3', __FILE__));
        }
        $hottohCmd->setLogicalId('timert3_action');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat programmable température 3",0);
      	$hottohCmd->setType('action');
        $hottohCmd->setSubType('slider');
      	$hottohCmd->setValue($this->getCmd(null, 'timert3')->getId());
      	$hottohCmd->setConfiguration('type','timer');
      	$hottohCmd->setConfiguration('minValue', 15);
        $hottohCmd->setConfiguration('maxValue', 30);
      	$hottohCmd->setTemplate('dashboard','button');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'timert3min');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th température 3 min', __FILE__));
        }
        $hottohCmd->setLogicalId('timert3min');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat programmable température 3 minimum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','timer');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
      
      	$hottohCmd = $this->getCmd(null, 'timert3max');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Th température 3 max', __FILE__));
        }
        $hottohCmd->setLogicalId('timert3max');
        $hottohCmd->setEqLogic_id($this->getId());
        //$hottohCmd->setDisplay("Thermostat programmable température 3 maximum",0);
      	$hottohCmd->setType('info');
        $hottohCmd->setSubType('numeric');
      	$hottohCmd->setConfiguration('type','timer');
      	//$hottohCmd->setConfiguration('repeatEventManagement','always');
      	$hottohCmd->setUnite( '°C' );
        $hottohCmd->save();
     	
      	$hottohCmd = $this->getCmd(null, 'refresh');
        if (!is_object($hottohCmd)) {
            $hottohCmd = new hottohCmd();
            $hottohCmd->setName(__('Rafraichir', __FILE__));
        }
        $hottohCmd->setEqLogic_id($this->getId());
        $hottohCmd->setLogicalId('refresh');
        $hottohCmd->setType('action');
        $hottohCmd->setSubType('other');
        $hottohCmd->save();
      
		log::add('hottoh', 'debug', 'postsaveFIN');
    }

    public function preUpdate() {
		
    }

    public function postUpdate() {
      	log::add('hottoh', 'debug', 'postupdate');
      	self::cron($this->getId());// lance la fonction cron avec l’id de l’eqLogic
    }


    public function preRemove() {
        
    }

    public function postRemove() {
        
    }
}

class hottohCmd extends cmd {
    /*     * *************************Attributs****************************** */


    /*     * ***********************Methode static*************************** */


    /*     * *********************Methode d'instance************************* */

    public function execute($_options = array()) {
		$eqlogic = $this->getEqLogic(); //rÃ©cupÃ¨re l'Ã©qqlogic de la commande $this
		switch ($this->getLogicalId()) {	//vÃ©rifie le logicalid de la commande 			
			case 'refresh': // LogicalId de la commande rafraÃ®chir que lâ€™on a crÃ©Ã© dans la mÃ©thode Postsave de la classe vdm . 
            	$eqlogic->Lecture_parametre();
            	break;
            case 'on': // Allumer le poele 
            	log::add('hottoh', 'debug', 'Allumage du poele');
            	$eqlogic->Ecriture_parametre(new Setvalue(Setvalue::PARAM_ON_OFF,1));
				break;
            case 'off': // Etteindre le poele
            	log::add('hottoh', 'debug', 'Arrêt du poele');
            	$eqlogic->Ecriture_parametre(new Setvalue(Setvalue::PARAM_ON_OFF,0));
				break;
            case 'eco_on': // Passage mode ECO a ON 
            	log::add('hottoh', 'debug', 'Passage en mode ECO');
            	$eqlogic->Ecriture_parametre(new Setvalue(Setvalue::PARAM_ECO_MODE,1));
				break;
            case 'eco_off': // Passage mode ECO a OFF 
            	log::add('hottoh', 'debug', 'Arrêt du mode ECO');
            	$eqlogic->Ecriture_parametre(new Setvalue(Setvalue::PARAM_ECO_MODE,0));
				break;
            case 'timer_on': // Passage chrono a ON
            	log::add('hottoh', 'debug', 'Activation du mode chrono');
            	$eqlogic->Ecriture_parametre(new Setvalue(Setvalue::PARAM_CHRONO_ON_OFF,2));
				break;
            case 'timer_off': // Passage chrono a OFF
            	log::add('hottoh', 'debug', 'Arrêt du mode chrono');
            	$eqlogic->Ecriture_parametre(new Setvalue(Setvalue::PARAM_CHRONO_ON_OFF,0));
				break;
            case 'pset_action': // Modification de la commande puissance
            	log::add('hottoh', 'debug', 'Modification de la puissance:'.$_options['slider']);
            	$eqlogic->Ecriture_parametre(new Setvalue(Setvalue::PARAM_NIVEAU_PUISSANCE,$_options['slider']));
				break;
            case 'fan1set': // Modification de la commande ventilateur 1
            	log::add('hottoh', 'debug', 'Modification du ventilateur 1:'.$_options['slider']);
            	$eqlogic->Ecriture_parametre(new Setvalue(Setvalue::PARAM_NIVEAU_FAN_1,$_options['slider']));
				break;
            case 'fan2set': // Modification de la commande ventilateur 2
            	log::add('hottoh', 'debug', 'Modification du ventilateur 2:'.$_options['slider']);
            	$eqlogic->Ecriture_parametre(new Setvalue(Setvalue::PARAM_NIVEAU_FAN_2,$_options['slider']));
				break;
            case 'fan3set': // Modification de la commande ventilateur 3
            	log::add('hottoh', 'debug', 'Modification du ventilateur 3:'.$_options['slider']);
            	$eqlogic->Ecriture_parametre(new Setvalue(Setvalue::PARAM_NIVEAU_FAN_3,$_options['slider']));
				break;
            case 't1set_action': // Modification du thermostat piece 1
            	log::add('hottoh', 'debug', 'Modification du thermostat pièce 1:'.$_options['slider']);
            	$eqlogic->Ecriture_parametre(new Setvalue(Setvalue::PARAM_AMBIANCE_TEMPERATURE_1,$_options['slider']));
				break;
            case 'timert1_action': // Modification du chrono Temperature 1
            	log::add('hottoh', 'debug', 'MODE CHRONO: Modification Température 1:'.$_options['slider']);
            	$eqlogic->Ecriture_parametre(new Setvalue(Setvalue::PARAM_CHRONO_TEMPERATURE_1,$_options['slider']));
				break;
            case 'timert2_action': // Modification du chrono Temperature 2
            	log::add('hottoh', 'debug', 'MODE CHRONO: Modification Température 2:'.$_options['slider']);
            	$eqlogic->Ecriture_parametre(new Setvalue(Setvalue::PARAM_CHRONO_TEMPERATURE_2,$_options['slider']));
				break;
            case 'timert3_action': // Modification du chrono Temperature 3
            	log::add('hottoh', 'debug', 'MODE CHRONO: Modification Température 3:'.$_options['slider']);
            	$eqlogic->Ecriture_parametre(new Setvalue(Setvalue::PARAM_CHRONO_TEMPERATURE_3,$_options['slider']));
				break;
		}
    }
    public function dontRemoveCmd() {
	return true;
    }
}


