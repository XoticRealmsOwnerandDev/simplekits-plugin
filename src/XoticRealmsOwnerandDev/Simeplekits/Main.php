<?php

namespace XoticRealmsOwnerandDev\Simplekits;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\Player;

class Main extends PluginBase{
	public function onEnable(){
		$this->saveDefaultConfig();
		if(!(s_dir($this->getDataFolder()."Kits/"))){
			mkdir($this->getDataFolder()."Kits/");
			$this->getLogger()->info("Made a folder for the kits...");
		}
		foreach($this->getConfig()->get("List") as $i){
			if(!(file_exists($this->getDataFolder()."Kits/".$i.".yml"))){
				$newFile = new Config($this->getDataFolder()."Kits/".$i.".yml", Config::YAML);
				$this->getLogger()->info("Made a file for the kit ".TextFormat::BLUE.$i);
			}
		}
		$this->getLogger()->info(TextFormat::GREEN."Done!");
	}
	
	public  addPlayer(Player $player, $kit){
		$name = $player->getName();
		$kit = new Config($this->getDataFolder()."Kits/".$kit.".yml", Config::YAML);
		$kit->set($name, "Allowed");
	}
	
	public function removePlayer(Player $player, $kit){
		$name = $player->getName();
		$kit = new Config($this->getDataFolder()."Kits/".$kit.".yml", Config::YAML);
		$kit->set($name, "Removed");
	}
	
	public function checkPlayer(Player $player, $kit){
		$name = $player->getName();
		$kit = new Config($this->getDataFolder()."Kits/".$kit.".yml", Config::YAML);
		if(isset($kit->get($name))){
			if($kit->get($name) == "Allowed"){
				return true;
			}else{
				return false;
			}
		}else{
			return false;
		}
	}
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		switch(strtolower($command->getName())){
			case "kit":
				if($sender->hasPermission("kit") || $sender->hasPermission("kit.use")){
					if(isset($args[0])){
						if($args[0] == "list"){
							foreach($this->getConfig()->get("List") as $i){
								$sender->sendMessage($i);
							}
						}else{
							if(isset($this->getConfig()->get($args[0]))){
								if($this->checkPlayer($sender, $args[0]) == true){
									$sender->sendMessage("You have chosen the kit ".$args[0]);
									// Left off here...
								}
							}
						}
					}
				}
		}
	}
}
