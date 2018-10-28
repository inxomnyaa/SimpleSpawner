<?php

namespace xenialdan\SimpleSpawner\tile;

use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
use pocketmine\tile\Spawnable;
use xenialdan\SimpleSpawner\Loader;

class MobSpawner extends Spawnable{

    public const TAG_ENTITYID = "EntityId";
    public const TAG_SPAWNCOUNT = "SpawnCount";
    public const TAG_SPAWNRANGE = "SpawnRange";
    public const TAG_MINSPAWNDELAY = "MinSpawnDelay";
    public const TAG_MAXSPAWNDELAY = "MaxSpawnDelay";
    public const TAG_DELAY = "Delay";

    /** @var int */
    public $EntityId;
    /** @var int */
    public $SpawnCount;
    /** @var int */
    public $SpawnRange;
    /** @var int */
    public $MinSpawnDelay;
    /** @var int */
    public $MaxSpawnDelay;
    /** @var int */
    public $Delay;

	public function __construct(Level $level, CompoundTag $nbt){
        parent::__construct($level, $nbt);
		if ($this->getEntityId() > 0){
			$this->scheduleUpdate();
		}
	}

    public function getEntityId(): int
    {
        return $this->EntityId;
	}

	public function setEntityId(int $id){
        $this->EntityId = $id;
		$this->onChanged();
		$this->scheduleUpdate();
	}

	public function getName(): string{
		if ($this->getEntityId() === 0) return "Monster Spawner";
		else{
			$name = ucfirst(Loader::getTypeArray()[$this->getEntityId()]??'monster') . ' Spawner';
			return $name;
		}
	}

	public function onUpdate(): bool{
		if ($this->closed === true){
			return false;
		}

		$this->timings->startTiming();

		if ($this->canUpdate()){
			if ($this->getDelay() <= 0){
				$success = 0;
				for ($i = 0; $i < $this->getSpawnCount(); $i++){
					$pos = $this->add(mt_rand() / mt_getrandmax() * $this->getSpawnRange(), mt_rand(-1, 1), mt_rand() / mt_getrandmax() * $this->getSpawnRange());
					$target = $this->getLevel()->getBlock($pos);
					if ($target->getId() == Item::AIR){
						$success++;
						$entity = Entity::createEntity($this->getEntityId(), $this->getLevel(), Entity::createBaseNBT($target->add(0.5, 0, 0.5), null, lcg_value() * 360, 0));
						$entity->spawnToAll();
					}
				}
				if ($success > 0){
					$this->setDelay(mt_rand($this->getMinSpawnDelay(), $this->getMaxSpawnDelay()));
				}
			} else{
				$this->setDelay($this->getDelay() - 1);
			}
		}

		$this->timings->stopTiming();

		return true;
	}

	public function canUpdate(): bool{
		if ($this->getEntityId() === 0) return false;
		$hasPlayer = false;
		$count = 0;
		foreach ($this->getLevel()->getEntities() as $e){
			if ($e instanceof Player){
				if ($e->distance($this->getBlock()) <= 15) $hasPlayer = true;
			}
			if ($e::NETWORK_ID == $this->getEntityId()){
				$count++;
			}
		}
		if ($hasPlayer and $count < 15){ // Spawn limit = 15
			return true;
		}
		return false;
	}

    /**
     * @return int
     */
    public function getSpawnCount(): int
    {
        return $this->SpawnCount;
    }

    /**
     * @param int $SpawnCount
     */
    public function setSpawnCount(int $SpawnCount): void
    {
        $this->SpawnCount = $SpawnCount;
    }

    /**
     * @return int
     */
    public function getSpawnRange(): int
    {
        return $this->SpawnRange;
    }

    /**
     * @param int $SpawnRange
     */
    public function setSpawnRange(int $SpawnRange): void
    {
        $this->SpawnRange = $SpawnRange;
    }

    /**
     * @return int
     */
    public function getMinSpawnDelay(): int
    {
        return $this->MinSpawnDelay;
    }

    /**
     * @param int $MinSpawnDelay
     */
    public function setMinSpawnDelay(int $MinSpawnDelay): void
    {
        $this->MinSpawnDelay = $MinSpawnDelay;
    }

    /**
     * @return int
     */
    public function getMaxSpawnDelay(): int
    {
        return $this->MaxSpawnDelay;
    }

    /**
     * @param int $MaxSpawnDelay
     */
    public function setMaxSpawnDelay(int $MaxSpawnDelay): void
    {
        $this->MaxSpawnDelay = $MaxSpawnDelay;
    }

    /**
     * @return int
     */
    public function getDelay(): int
    {
        return $this->Delay;
    }

    /**
     * @param int $Delay
     */
    public function setDelay(int $Delay): void
    {
        $this->Delay = $Delay;
    }

    protected function readSaveData(CompoundTag $nbt): void
    {
        $this->setEntityId($nbt->getInt(self::TAG_ENTITYID, 0, true));
        $this->setSpawnCount($nbt->getInt(self::TAG_SPAWNCOUNT, 0, true));
        $this->setSpawnRange($nbt->getInt(self::TAG_SPAWNRANGE, 0, true));
        $this->setMinSpawnDelay($nbt->getInt(self::TAG_MINSPAWNDELAY, 0, true));
        $this->setMaxSpawnDelay($nbt->getInt(self::TAG_MAXSPAWNDELAY, 0, true));
        $this->setDelay($nbt->getInt(self::TAG_DELAY, 0, true));
    }

    protected function writeSaveData(CompoundTag $nbt): void
    {
        $nbt->setInt(self::TAG_ENTITYID, $this->getEntityId());
        $nbt->setInt(self::TAG_SPAWNCOUNT, $this->getSpawnCount());
        $nbt->setInt(self::TAG_SPAWNRANGE, $this->getSpawnRange());
        $nbt->setInt(self::TAG_MINSPAWNDELAY, $this->getMinSpawnDelay());
        $nbt->setInt(self::TAG_MAXSPAWNDELAY, $this->getMaxSpawnDelay());
        $nbt->setInt(self::TAG_DELAY, $this->getDelay());
    }

    protected function addAdditionalSpawnData(CompoundTag $nbt): void
    {
        $nbt->setInt(self::TAG_ENTITYID, $this->getEntityId());
        $nbt->setInt(self::TAG_SPAWNCOUNT, $this->getSpawnCount());
        $nbt->setInt(self::TAG_SPAWNRANGE, $this->getSpawnRange());
        $nbt->setInt(self::TAG_MINSPAWNDELAY, $this->getMinSpawnDelay());
        $nbt->setInt(self::TAG_MAXSPAWNDELAY, $this->getMaxSpawnDelay());
        $nbt->setInt(self::TAG_DELAY, $this->getDelay());
    }
}
