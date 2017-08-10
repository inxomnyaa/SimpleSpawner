<?php

namespace xenialdan\SimpleSpawner\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use xenialdan\SimpleSpawner\Loader;

class ListSubCommand extends SubCommand{
	public function canUse(CommandSender $sender){
		return ($sender instanceof Player) and $sender->hasPermission("simplespawner");
	}

	public function getUsage(){
		return "";
	}

	public function getName(){
		return "list";
	}

	public function getDescription(){
		return "Listing all available spawner types";
	}

	public function getAliases(){
		return [];
	}

	/**
	 * @param CommandSender $sender
	 * @param array $args
	 * @return bool
	 */
	public function execute(CommandSender $sender, array $args){
		$player = $sender->getServer()->getPlayer($sender->getName());
		$player->sendMessage(TextFormat::GREEN . 'These spawners exists:');
		$player->sendMessage(join(', ', Loader::getTypeArray()));
		return true;
	}
}
