<?php

/*  PureEntitiesX: Mob AI Plugin for PMMP
    Copyright (C) 2017 RevivalPMMP

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>. */

namespace revivalpmmp\pureentities\entity\monster\walking;

use revivalpmmp\pureentities\components\BreedingComponent;
use revivalpmmp\pureentities\entity\monster\Monster;
use revivalpmmp\pureentities\entity\monster\WalkingMonster;
use pocketmine\entity\Entity;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use revivalpmmp\pureentities\data\Data;
use revivalpmmp\pureentities\PureEntities;
use revivalpmmp\pureentities\utils\MobDamageCalculator;

class PolarBear extends WalkingMonster implements Monster{

	// Base created from Spider.
	// TODO udpate Polar Bear specific methods.

	const NETWORK_ID = Data::NETWORK_IDS["polar_bear"];

	public function initEntity(){
		parent::initEntity();
		$this->width = Data::WIDTHS[self::NETWORK_ID];
		$this->height = Data::HEIGHTS[self::NETWORK_ID];
		$this->speed = 1.13;

		$this->setDamage([0, 2, 2, 3]);
		PureEntities::logOutput($this->getName() . ": created with height of $this->height and width of $this->width.", PureEntities::NORM);
	}

	public function getName() : string{
		return "PolarBear";
	}

	/**
	 * Attack a player
	 *
	 * @param Entity $player
	 */
	public function attackEntity(Entity $player){
		if($this->attackDelay > 10 && (($this->isFriendly() && !($player instanceof Player)) || !$this->isFriendly())){
			$this->attackDelay = 0;

			$ev = new EntityDamageByEntityEvent($this, $player, EntityDamageEvent::CAUSE_ENTITY_ATTACK,
				MobDamageCalculator::calculateFinalDamage($player, $this->getDamage()));
			$player->attack($ev);

			$this->checkTamedMobsAttack($player);
		}
	}

	public function getDrops() : array{
		$drops = [];
		if($this->isLootDropAllowed()){
			if(mt_rand(0, 3) > 0){
				array_push($drops, Item::get(Item::RAW_FISH, 0, mt_rand(0, 2)));
			} else {
				array_push($drops, Item::get(Item::RAW_SALMON, 0, mt_rand(0, 2)));
			}
		}
		return $drops;
	}

	public function getMaxHealth() : int{
		return 30;
	}

	public function getXpDropAmount() : int{
		return 5;
	}


}
