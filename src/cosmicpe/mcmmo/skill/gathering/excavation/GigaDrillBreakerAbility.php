<?php

declare(strict_types=1);

namespace cosmicpe\mcmmo\skill\gathering\excavation;

use cosmicpe\mcmmo\customitem\CustomItemFactory;
use cosmicpe\mcmmo\customitem\CustomItemIds;
use cosmicpe\mcmmo\customitem\GigaDrillShovel;
use cosmicpe\mcmmo\player\McMMOPlayer;
use cosmicpe\mcmmo\skill\ability\AbilityRemoveHandler;
use cosmicpe\mcmmo\skill\ability\BuffableAbility;
use cosmicpe\mcmmo\skill\gathering\GatheringSubSkillIds;
use cosmicpe\mcmmo\skill\subskill\SubSkillManager;
use pocketmine\block\BlockToolType;
use pocketmine\item\Item;
use pocketmine\item\Shovel;
use pocketmine\Player;

final class GigaDrillBreakerAbility extends BuffableAbility implements AbilityRemoveHandler{

	public function getToolType() : int{
		return BlockToolType::SHOVEL;
	}

	public function getSubSkillIdentifier() : string{
		return GatheringSubSkillIds::GIGA_DRILL_BREAKER;
	}

	public function handleAdd(Player $player, McMMOPlayer $mcmmo_player, Item $item) : void{
		/** @var Shovel $item */
		/** @var GigaDrillBreaker $sub_skill */
		$sub_skill = SubSkillManager::get($this->getSubSkillIdentifier());
		$player->getInventory()->setItemInHand(CustomItemFactory::get(CustomItemIds::GIGA_DRILL_SHOVEL, $item, $sub_skill->getEnchantmentBuff()));
		$mcmmo_player->getSubSkill($sub_skill)->setCooldown($sub_skill->getCooldown());
	}

	public function handleRemove(McMMOPlayer $mcmmo_player) : void{
		$player = $mcmmo_player->getPlayer();
		if($player !== null){
			$inventory = $player->getInventory();
			$item = $inventory->getItemInHand();
			$custom_item = CustomItemFactory::from($item);
			if($custom_item instanceof GigaDrillShovel){
				$custom_item->clean($item);
				$inventory->setItemInHand($item);
			}
		}
	}
}
