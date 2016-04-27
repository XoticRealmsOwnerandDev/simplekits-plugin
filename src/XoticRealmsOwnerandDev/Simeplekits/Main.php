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
		$this->getLogger()->info(TextFormat::GREEN."Done!");
	}
	
	public function onCommand(CommandSender $sender, Command $command, $label, array $args){
		if(strtolower($command->getName()) == "kit"){
			if($sender->hasPermission("kit") || $sender->hasPermission("kit.use")){
				if(isset($args[0])){
					if($args[0] == "list"){
						$sender->sendMessage(implode(", ", $this->getConfig()->get("List")));
						return true;
					}else{
						if($sender instanceof Player){
							if($this->getConfig()->get($args[0]) !== null){
								foreach($this->getConfig()->get($args[0]) as $i){
									$item = Item::get($i);
									if($item instanceof Item){
										$sender->getInvntory()->addItem($item);
									}else{
										$this->getLogger()->info(TextFormat::RED.$i." is not an item!!! Please fix this!");
									}
								}
							$sender->sendMessage("You have chosen the kit ".$args[0]);
							$this->getLogger()->info("[".$sender->getName()." is using the kit called ".$args[0]."]");
							return true;
							}else{
								$sender->sendMessage(TextFormat::RED."There is no kit by that name!");
								return true;
							}
						}else{
							$sender->sendMessage(TextFormat::YELLOW."That command must be used in the game!");
							return true;
						}
					}
				}else{
					return false;
				}
			}else{
				$sender->sendMessage(TextFormat::RED."You don't have permission to use this command!");
				return true;
			}
		}
	}
}
