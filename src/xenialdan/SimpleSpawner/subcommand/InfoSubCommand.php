<?php

namespace xenialdan\SimpleSpawner\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class InfoSubCommand extends SubCommand {
	public function canUse(CommandSender $sender) {
		return ($sender instanceof Player) and $sender->hasPermission("simplespawner");
	}

	public function getUsage() {
		return "";
	}

	public function getName() {
		return "info";
	}

	public function getDescription() {
		return "Information about the plugin";
	}

	public function getAliases() {
		return [];
	}

	/**
	 * @param CommandSender $sender
	 * @param array $args
	 * @return bool
	 */
	public function execute(CommandSender $sender, array $args) {
		$player = $sender->getServer()->getPlayer($sender->getName());
		$player->sendMessage(TextFormat::GOLD . 'SimpleSpawner' . TextFormat::GREEN . ' is a plugin by XenialDan');
		return true;
	}
}
