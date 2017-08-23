<?php

namespace xenialdan\SimpleSpawner;

use pocketmine\block\BlockFactory;
use pocketmine\plugin\PluginBase;
use pocketmine\tile\Tile;
use xenialdan\SimpleSpawner\block\MonsterSpawner;
use xenialdan\SimpleSpawner\tile\MobSpawner;

class Loader extends PluginBase{
	public static function getTypeArray(){
		return [
			10 => "chicken",
			11 => "cow",
			12 => "pig",
			13 => "sheep",
			14 => "wolf",
			15 => "villager",
			16 => "mooshroom",
			17 => "squid",
			18 => "rabbit",
			19 => "bat",
			20 => "iron_golem",
			21 => "snow_golem",
			22 => "ocelot",
			23 => "horse",
			24 => "donkey",
			25 => "mule",
			26 => "skeleton_horse",
			27 => "zombie_horse",
			28 => "polar_bear",
			29 => "llama",
			30 => "parrot",
			32 => "zombie",
			33 => "creeper",
			34 => "skeleton",
			35 => "spider",
			36 => "zombie_pigman",
			37 => "slime",
			38 => "enderman",
			39 => "silverfish",
			40 => "cave_spider",
			41 => "ghast",
			42 => "magma_cube",
			43 => "blaze",
			44 => "zombie_villager",
			45 => "witch",
			46 => "stray",
			47 => "husk",
			48 => "wither_skeleton",
			49 => "guardian",
			50 => "elder_guardian",
			51 => "npc",
			52 => "wither",
			53 => "ender_dragon",
			54 => "shulker",
			55 => "endermite",
			56 => "agent",
			57 => "vindicator",
			61 => "armor_stand",
			62 => "tripod_camera",
			63 => "player",
			64 => "item",
			65 => "tnt",
			66 => "falling_block",
			67 => "moving_block",
			68 => "xp_bottle",
			69 => "xp_orb",
			70 => "eye_of_ender_signal",
			71 => "endercrystal",
			72 => "fireworks_rocket",
			76 => "shulker_bullet",
			77 => "fishing_hook",
			78 => "chalkboard",
			79 => "dragon_fireball",
			80 => "arrow",
			81 => "snowball",
			82 => "egg",
			83 => "painting",
			84 => "minecart",
			85 => "large_fireball",
			86 => "splash_potion",
			87 => "ender_pearl",
			88 => "leash_knot",
			89 => "wither_skull",
			90 => "boat",
			91 => "wither_skull_dangerous",
			93 => "lightning_bolt",
			94 => "small_fireball",
			95 => "area_effect_cloud",
			96 => "hopper_minecart",
			97 => "tnt_minecart",
			98 => "chest_minecart",
			100 => "command_block_minecart",
			101 => "lingering_potion",
			102 => "llama_spit",
			103 => "evocation_fang",
			104 => "evocation_illager",
			105 => "vex"
		];
	}

	public function onEnable(){
		$this->getServer()->getCommandMap()->register(Commands::class, new Commands($this));
		BlockFactory::registerBlock(new MonsterSpawner(), true);
		Tile::registerTile(MobSpawner::class);
	}
}