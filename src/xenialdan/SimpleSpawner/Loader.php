<?php

namespace xenialdan\SimpleSpawner;

use pocketmine\block\Block;
use pocketmine\item\Item;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Tile;
use xenialdan\SimpleSpawner\block\MonsterSpawner;
use xenialdan\SimpleSpawner\tile\MobSpawner;

class Loader extends PluginBase {
	public static function getTypeArray() {
		return
			[
				80 => 'arrow',
				19 => 'bat',
				43 => 'blaze',
				90 => 'boat',
				40 => 'cavespider',
				10 => 'chicken',
				11 => 'cow',
				33 => 'creeper',
				24 => 'donkey',
				82 => 'egg',
				38 => 'enderman',
				69 => 'experienceorb',
				66 => 'fallingsand',
				77 => 'fishinghook',
				41 => 'ghast',
				49 => 'guardian',
				50 => 'elderguardian',
				23 => 'horse',
				47 => 'husk',
				20 => 'irongolem',
				85 => 'largefireball',
				88 => 'leashknot',
				93 => 'lightning',
				42 => 'magmacube',
				84 => 'minecart',
				98 => 'minecartchest',
				96 => 'minecarthopper',
				97 => 'minecarttnt',
				16 => 'mooshroom',
				25 => 'mule',
				22 => 'ozelot',
				83 => 'painting',
				12 => 'pig',
				36 => 'pigzombie',
				65 => 'primedtnt',
				18 => 'rabbit',
				13 => 'sheep',
				39 => 'silverfish',
				34 => 'skeleton',
				26 => 'skeletonhorse',
				37 => 'slime',
				94 => 'smallfireball',
				81 => 'snowball',
				21 => 'snowgolem',
				35 => 'spider',
				17 => 'squid',
				46 => 'stray',
				87 => 'thrownenderpearl',
				68 => 'thrownexpbottle',
				86 => 'thrownpotion',
				62 => 'tripoidcamera',
				15 => 'villager',
				45 => 'witch',
				52 => 'wither',
				48 => 'witherskeleton',
				89 => 'witherskull',
				14 => 'wolf',
				32 => 'zombie',
				27 => 'zombiehorse',
				44 => 'zombievillager',
			];
	}

	public function onEnable() {
		$this->getServer()->getCommandMap()->register(Commands::class, new Commands($this));

		Block::$list[Block::MONSTER_SPAWNER] = MonsterSpawner::class;
		Item::$list[Block::MONSTER_SPAWNER] = MonsterSpawner::class;//this is because i need to override Item::get()
		Tile::registerTile(MobSpawner::class);
	}
}