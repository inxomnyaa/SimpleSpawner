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
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\Player;
use pocketmine\tile\Tile;
use xenialdan\SimpleSpawner\Loader;
use xenialdan\SimpleSpawner\tile\MobSpawner;

class MonsterSpawner extends \pocketmine\block\MonsterSpawner{

	private $entityid = 0;

	public function __construct(){ }

	public function canBeActivated(): bool{
		return true;
	}

	public function onActivate(Item $item, Player $player = null): bool{
		if ($this->entityid === 0){
			if ($item->getId() === Item::SPAWN_EGG){
				$tile = $this->getLevel()->getTile($this);
				if ($tile instanceof MobSpawner){
					$this->entityid = $item->getDamage();
					$tile->setEntityId($this->entityid);
				}
				return true;
			}
		}
		return false;
	}

	public function place(Item $item, Block $block, Block $target, int $face, Vector3 $facePos, Player $player = null): bool{
		$this->getLevel()->setBlock($block, $this, true, true);
		$nbt = new CompoundTag("", [
			new StringTag("id", 'MobSpawner'),
			new IntTag("x", $block->x),
			new IntTag("y", $block->y),
			new IntTag("z", $block->z),
			new IntTag("EntityId", $this->entityid),
		]);

		Tile::createTile('MobSpawner', $this->getLevel(), $nbt);
		return true;
	}

	public function getDrops(Item $item): array{
		$tile = $this->getLevel()->getTile($this);
		if ($tile instanceof MobSpawner){
			if ($item->hasEnchantment(Enchantment::SILK_TOUCH))
				return [
					[$this->getItemId(), $tile->getEntityId(), 1, $this->getLevel()->getTile($this)->namedtag],
				];
		}
		return [];
	}

	public function getName(): string{
		if ($this->entityid === 0) return "Monster Spawner";
		else{
			$name = ucfirst(Loader::getTypeArray()[$this->entityid]??'monster') . ' Spawner';
			return $name;
		}
	}
}