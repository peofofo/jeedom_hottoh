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
require 'Setvalue.class.php';

$ip = (empty($_GET['ip'])) ? '192.168.0.251' : $_GET['ip'];
$port = (empty($_GET['port'])) ? '5001' : $_GET['port'];

$socket= new Socket;
$socket->CreateSocket();
$socket->setAdresse($ip);
$socket->setPort($port);
$socket->ConnectSocket();


$setvalue = new Setvalue(Setvalue::PARAM_NIVEAU_FAN_1,3);
if(!$setvalue->geterreur()){
	$requete= new Requete(Setvalue::COMMAND,Requete::WRITE,$setvalue->getparametre());

  	echo"Requete:".$requete->getrequete();

    $socket->setRequete($requete->getrequete());
    $socket->SendReq(); // Envoi
    $socket->ReadReq(); // Reception
    $value=$requete->getResponseValues($socket->getreponse());

    echo"Reponse:".$socket->getreponse();
    //var_dump($value);

    $setvalue->setRepvalue($value);
    if($setvalue->getrepvalue())
        echo "ENVOI OK";
    else
        echo "Erreur d'envoi";
  
} else {
	echo'ERREUR';
}
//echo"ID:".$requete->getResponseId($socket->getreponse());
//echo"COMMANDE:".$requete->getResponseCommand($socket->getreponse());
$socket->CloseSocket();

?>


