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

$ip = (empty($_GET['ip'])) ? '192.168.0.251' : $_GET['ip'];
$port = (empty($_GET['port'])) ? '5001' : $_GET['port'];

$socket= new Socket;
$socket->CreateSocket();
$socket->setAdresse($ip);
$socket->setPort($port);
$socket->ConnectSocket();

//$requete = "#00016A---0002DATR0;3E71\n";
/*$getstatus0 = new Getstatus0;
$getstatus2 = new Getstatus2;
$getchronotemmperature = new Getchronotemperature;*/

$requete1= new Requete(Getstatus0::COMMAND,Requete::READ,Getstatus0::PARAMETERS);
$requete2= new Requete(Getstatus2::COMMAND,Requete::READ,Getstatus2::PARAMETERS);
$requete3= new Requete(Getchronotemperature::COMMAND,Requete::READ,Getchronotemperature::PARAMETERS);

//Requete GetStatus0
$socket->setRequete($requete1->getrequete());
$socket->SendReq(); // Envoi
$socket->ReadReq(); // Reception
//$values1=$requete1->getResponseValues($socket->getreponse());
$getstatus0=new Getstatus0($requete1->getResponseValues($socket->getreponse()));

//Requete GetStatus2
$socket->setRequete($requete2->getrequete());
$socket->SendReq(); // Envoi
$socket->ReadReq(); // Reception
//$values2=$requete2->getResponseValues($socket->getreponse());
$getstatus2=new Getstatus2($requete2->getResponseValues($socket->getreponse()));

//Requete chronotemperature
$socket->setRequete($requete3->getrequete());
$socket->SendReq(); // Envoi
$socket->ReadReq(); // Reception
//$values3=$requete3->getResponseValues($socket->getreponse());
$getchronotemmperature=new Getchronotemperature($requete3->getResponseValues($socket->getreponse()));
//var_dump($values);





$getstatus0->toString();
$getchronotemmperature->toString();
$getstatus2->toString();


//echo"ID:".$requete->getResponseId($socket->getreponse());
//echo"COMMANDE:".$requete->getResponseCommand($socket->getreponse());
$socket->CloseSocket();

?>


