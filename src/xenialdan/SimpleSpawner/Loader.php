<?php

namespace xenialdan\SimpleSpawner;

use pocketmine\block\BlockFactory;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Tile;
use xenialdan\SimpleSpawner\block\MonsterSpawner;
use xenialdan\SimpleSpawner\tile\MobSpawner;

class Loader extends PluginBase{

	public function onEnable(){
		$this->getServer()->getCommandMap()->register(Commands::class, new Commands($this));
		BlockFactory::registerBlock(new MonsterSpawner(), true);
		Tile::registerTile(MobSpawner::class);
	}

    public static function getTypeArray(): array
    {
        $list = [];
        foreach (Entity::getKnownEntityTypes() as $className) {
            /** @var Living $className */
            if (is_a($className, Living::class, true) and $className::NETWORK_ID !== -1) {
                try {
                    $list[$className::NETWORK_ID] = (new \ReflectionClass($className))->getShortName();
                } catch (\ReflectionException $e) {
                }
            }
        }
        return $list;
    }
}