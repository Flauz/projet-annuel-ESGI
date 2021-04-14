<?php

namespace App;

//En fonction de l'url on doit appeler la bonne methode du bon controller
// http://localhost/login -> Security -> Login()

/*
$routes = [
"login"=> ["controller"=>"Security", "action"=>"login"],
"logout"=> ["controller"=>"Security", "action"=>"logout"],
....
]
*/

//Je dois récupérer ici pour commencer l'url de l'internaute
$uri = $_SERVER["REQUEST_URI"];

//PARSER le ficher YAML
$listOfRoutes = yaml_parse_file("routes.yml");

//Si la route dans le ficheir YAML n'existe pas
//alors on récupère les informations pour la route 404
$route = $listOfRoutes[$uri] ?? $listOfRoutes["/404"];

$controller = $route["controller"];
$action = $route["action"];

//EXEMPLE :
//$controller = "Security"
//$action = "login"

if (file_exists("Controllers/" . $controller . ".php")) {
	require "Controllers/" . $controller . ".php";

	$controllerWithNP = "App\\Controllers\\" . $controller;
	if (class_exists('$controllerWithNP')) {
		$cObject = new $controllerWithNP();
		if (method_exists($cObject, $action)) {
			$cObject->$action();
		} else {
			die("la methode");
		}
	} else {
		die("L'action" . $controllerWithNP . "n'existe pas.");
	}
} else {
	die("le fichier Controllers");
}
//Attention si on fait une instance de class dynamique
//comme c'est notre cas ici il faut préciser à la racine 
//du namespace
