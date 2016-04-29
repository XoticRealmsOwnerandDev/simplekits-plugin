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
									foreach($this->getConfig()->get($args[0]) as $i){
										$player->getInventory()->addItem(Item::get($i));
									}
									$this->getLogger()->info("[".$sender->getName()." chose the kit ".$args[0]"]");
									return true;
								}else{
									$sender->sendMessage("You can't use that kit!");
									return true;
								}
							}else{
								$sender->sendMessage("There is no kit by that name!");
								return true;
							}
						}
					}else{
						return false;
					}
				}else{
					$sender->sendMessage(TextFormat::RED."You don't have permission to use that command!");
					return true;
				}
			case "kitadd":
				if($sender->hasPermission("kit") || $sender->hasPermission("kit.add")){
					if(isset($args[0])){
						if(isset($args[1])){
							$player = $this->getServer()->getPlayer($args[0]);
							if($player instanceof Player){
								if(isset($this->getConfig()->get($args[1]))){
									if(file_exists($this->getDataFolder()."Kits/".$args[1].".yml")){
										$this->addPlayer($player, $args[1]);
										$sender->sendMessage("Added ".$player->getName()." to the kit file ".$args[1]);
										if($sender instanceof Player){
											$this->getLogger()->info("[".$sender->getName()." added ".$player->getName()." to the kit file ".$args[1]);
										}
										return true;
									}else{
										$sender->sendMessage("An error has occored. Please ask an admin about this.");
										return true;
									}
								}else{
									$sender->sendMessage($args[1]." isn't a kit!");
									return true;
								}
							}else{
								$sender->sendMessage($args[0]." isn't online!");
								return true;
							}
						}else{
							$sender->sendMessage(TextFormat::YELLOW."You need to specify a kit!");
							return true;
						}
					}else{
						$sender->sendMessage(TextFormat::YELLOW."No sub-commands were given!");
						return false;
					}
				}else{
					$sender->sendMessage(TextFormat::RED."You don't have permission to use that command!");
					return true;
				}
			case "kitremove":
				if($sender->hasPemission("kit") || $sender->hasPemission("kit.remove")){
					if(isset($args[0])){
						if(isset($args[1])){
							$player = $this->getServer()->getPlayer($args[0]);
							if($player instanceof Player){
								if(isset($this->getConfig()->get($args[1]))){
									if(file_exists($this->getDataFolder()."Kits/".$args[1].".yml")){
										$this->removePlayer($player, $args[1]);
										$sender->sendMessage("You have blocked ".$player->getName()." from using the kit ".$args[1]);
										if($sender instanceof Player){
											$this->getLogger()->info("[".$sender->getName()." blocked ".$player->getName()." from  using the kit ".$args[1]);
										}
										return true;
									}else{
										$sender->sendMessage("There is not kit by the name of ".$args[1]);
										return true;
									}
								}
							}
						}
					}
				}
		}
	}
}
