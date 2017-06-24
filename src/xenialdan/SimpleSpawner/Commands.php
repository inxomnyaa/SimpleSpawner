<?php

namespace xenialdan\SimpleSpawner;

use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;
use pocketmine\utils\TextFormat;
use xenialdan\SimpleSpawner\subcommand\GetSubCommand;
use xenialdan\SimpleSpawner\subcommand\InfoSubCommand;
use xenialdan\SimpleSpawner\subcommand\ListSubCommand;
use xenialdan\SimpleSpawner\subcommand\SubCommand;

class Commands extends PluginCommand {
	private $subCommands = [];

	/* @var SubCommand[] */
	private $commandObjects = [];

	public function __construct(Plugin $plugin) {
		parent::__construct("simplespawner", $plugin);
		$this->setAliases(["ss"]);
		$this->setPermission("simplespawner");
		$this->setDescription("The main commands for SimpleSpawner");

		$this->loadSubCommand(new GetSubCommand($plugin));
		$this->loadSubCommand(new InfoSubCommand($plugin));
		$this->loadSubCommand(new ListSubCommand($plugin));
	}

	private function loadSubCommand(SubCommand $command) {
		$this->commandObjects[] = $command;
		$commandId = count($this->commandObjects) - 1;
		$this->subCommands[$command->getName()] = $commandId;
		foreach ($command->getAliases() as $alias) {
			$this->subCommands[$alias] = $commandId;
		}
	}

	public function execute(CommandSender $sender, $alias, array $args) {
		if (!isset($args[0])) {
			return $this->sendHelp($sender);
		}
		$subCommand = strtolower(array_shift($args));
		if (!isset($this->subCommands[$subCommand])) {
			return $this->sendHelp($sender);
		}
		$command = $this->commandObjects[$this->subCommands[$subCommand]];
		$canUse = $command->canUse($sender);
		if ($canUse) {
			if (!$command->execute($sender, $args)) {
				$sender->sendMessage(TextFormat::YELLOW . "Usage: /simplespawner " . $command->getName() . TextFormat::BOLD . TextFormat::DARK_AQUA . " > " . TextFormat::RESET . TextFormat::YELLOW . $command->getUsage());
			}
		} elseif (!($sender instanceof Player)) {
			$sender->sendMessage(TextFormat::RED . "Please run this command in-game.");
		} else {
			$sender->sendMessage(TextFormat::RED . "You do not have permissions to run this command");
		}
		return true;
	}

	private function sendHelp(CommandSender $sender) {
		$sender->sendMessage("===========[SimpleSpawner commands]===========");
		foreach ($this->commandObjects as $command) {
			if ($command->canUse($sender)) {
				$sender->sendMessage(TextFormat::DARK_GREEN . "/simplespawner " . $command->getName() . TextFormat::BOLD . TextFormat::DARK_AQUA . " > " . TextFormat::RESET . TextFormat::DARK_GREEN . $command->getUsage() . ": " .
					TextFormat::WHITE . $command->getDescription()
				);
			}
		}
		return true;
	}
}
