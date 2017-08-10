<?php

/*
 *
 *  ____            _        _   __  __ _                  __  __ ____ 
 * |  _ \ ___   ___| | _____| |_|  \/  (_)_ __   ___      |  \/  |  _ \
 * | |_) / _ \ / __| |/ / _ \ __| |\/| | | '_ \ / _ \_____| |\/| | |_) |
 * |  __/ (_) | (__|   <  __/ |_| |  | | | | | |  __/_____| |  | |  __/
 * |_|   \___/ \___|_|\_\___|\__|_|  |_|_|_| |_|\___|     |_|  |_|_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author PocketMine Team
 * @link http://www.pocketmine.net/
 *
 *
*/

namespace xenialdan\SimpleSpawner\block;

use pocketmine\block\Block;
use pocketmine\block\Solid;
use pocketmine\item\Item;
use pocketmine\item\Tool;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Tile;
use xenialdan\SimpleSpawner\Loader;
use xenialdan\SimpleSpawner\tile\MobSpawner;

class MonsterSpawner extends \pocketmine\block\MonsterSpawner{

	public function __construct($meta = 0){
		$this->meta = $meta;
	}

	public function canBeActivated(): bool{
		return true;
	}

	public function onActivate(Item $item, Player $player = null){
		if ($this->getDamage() === 0){
			if ($item->getId() === Item::SPAWN_EGG){
				$tile = $this->getLevel()->getTile($this);
				if ($tile instanceof MobSpawner){
					$this->meta = $item->getDamage(); // Abusing meta as EntityID
					$tile->setEntityId($this->meta);
				}
				return true;
			}
		}
		return false;
	}

	public function place(Item $item, Block $block, Block $target, $face, $fx, $fy, $fz, Player $player = null){
		$this->getLevel()->setBlock($block, $this, true, true);
		$nbt = new CompoundTag("", [
			new StringTag("id", 'MobSpawner'),
			new IntTag("x", $block->x),
			new IntTag("y", $block->y),
			new IntTag("z", $block->z),
			new IntTag("EntityId", $this->getDamage()),
		]);

		Tile::createTile('MobSpawner', $this->getLevel(), $nbt);
		return true;
	}

	public function getDrops(Item $item){
		return [
			[$this->getItemId(), $this->getDamage(), 1, $this->getLevel()->getTile($this)->namedtag],
		];
	}

	public function getName(): string{
		if ($this->meta === 0) return "Monster Spawner";
		else{
			$name = ucfirst(Loader::getTypeArray()[$this->meta]??'monster') . ' Spawner';
			return $name;
		}
	}
}