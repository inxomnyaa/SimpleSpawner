<?php

namespace xenialdan\SimpleSpawner\subcommand;

use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;
use xenialdan\SimpleSpawner\Loader;

class GetSubCommand extends SubCommand {

	public function canUse(CommandSender $sender) {
		return ($sender instanceof Player) and $sender->hasPermission("simplespawner");
	}

	public function getUsage() {
		return "get <type>";
	}

	public function getName() {
		return "get";
	}

	public function getDescription() {
		return "Get a spawner";
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
		if (count($args) < 1) {
			$player->sendMessage(TextFormat::RED . 'Sorry, you need to provide a spawner type');
			return false;
		}
		$type = $args[0];
		$types = Loader::getTypeArray();
		if (!in_array($type, $types)) {
			$player->sendMessage(TextFormat::RED . 'Spawner type ' . $type . ' doesn\'t exist, check /spawner list!');
			return false;
		}
		$revertetArray = array_flip($types);
		$id = $revertetArray[$type];
		$item = Item::get(Item::MONSTER_SPAWNER, $id);
		$item->setCustomName(ucfirst($type) . ' Spawner');
		$player->getInventory()->addItem($item);

		$player->sendMessage(TextFormat::GREEN . 'Spawner type ' . ucfirst($type) . ' received!');
		return true;
	}
}
