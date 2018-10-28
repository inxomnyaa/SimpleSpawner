<?php

namespace xenialdan\SimpleSpawner\block;

use pocketmine\block\Block;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\math\Vector3;
use pocketmine\Player;
use pocketmine\tile\Tile;
use xenialdan\SimpleSpawner\Loader;
use xenialdan\SimpleSpawner\tile\MobSpawner;

class MonsterSpawner extends \pocketmine\block\MonsterSpawner{

	private $entityid = 0;

	public function canBeActivated(): bool{
		return true;
	}

	public function onActivate(Item $item, Player $player = null): bool{
		if ($this->entityid === 0){
			if ($item->getId() === Item::SPAWN_EGG){
				$tile = $this->getLevel()->getTile($this);
				$this->entityid = $item->getDamage();
				if (!$tile instanceof MobSpawner){
					Tile::createTile('MobSpawner', $this->getLevel(), Tile::createNBT($this));
				}
				$tile->setEntityId($this->entityid);
				return true;
			}
		}
		return false;
	}

	public function place(Item $item, Block $block, Block $target, int $face, Vector3 $facePos, Player $player = null): bool{
		$this->getLevel()->setBlock($block, $this, true, true);
		Tile::createTile('MobSpawner', $this->getLevel(), Tile::createNBT($this));
		return true;
	}

	public function getDrops(Item $item): array{
		$tile = $this->getLevel()->getTile($this);
		if ($tile instanceof MobSpawner){
			if ($item->hasEnchantment(Enchantment::SILK_TOUCH))
				return [
                    ItemFactory::get($this->getItemId(), $tile->getEntityId(), 1, $this->getLevel()->getTile($this)->getCleanedNBT())
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