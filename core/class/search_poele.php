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
require 'Socket.class.php';
require 'Requete.class.php';
require 'Getstatus0.class.php';
require 'Getstatus2.class.php';
require 'Getchronotemperature.class.php';

$i=1;
for($i = 9000; $i <= 10000; $i++){
  	echo 'Recherche en cours au port:'.$i.'<br />';
	$connection = @fsockopen("192.168.0.250", $i);
	if (is_resource($connection))
    {
      	echo 'La machine autorise les connexions sur le port : '.$i.'<br />';	
      	fclose($connection);
      	/*$socket= new Socket;
        $socket->CreateSocket();
        $socket->setAdresse("192.168.0.250");
        $socket->setPort($i);
        $socket->ConnectSocket();
        if($socket->getconnecte()){
            $requete1= new Requete(Getstatus0::COMMAND,Requete::READ,Getstatus0::PARAMETERS);
            $socket->setRequete($requete1->getrequete());
            $socket->SendReq(); // Envoi
            $socket->ReadReq(); // Reception
            $getstatus0=new Getstatus0($requete1->getResponseValues($socket->getreponse()));
            if(count($getstatus0->getValues())==36){
                echo 'Poele trouvé au port '.$i.'<br />';
                $socket->CloseSocket();
                //break;
            }
            else{
                $socket->CloseSocket();
                echo 'La machine autorise les connexions sur le port : '.$i.'<br />';
            }
        }
        if($i==9999){
            echo 'Aucun port de trouvé compatible pour le poele, arret de la recherche au port 9999';
        }*/
    }
}
?>


