<?php

namespace TMGPS;

use pocketmine\scheduler\Task;
use TMGPS\TMGPS;
use pocketmine\math\Vector3;

Class TMGPSTimer extends Task {

  private $p;

  public function __construct(TMGPS $plugin) {
    $this->p = $plugin;
  }

  function onRun($currentTick): void{
    foreach ($this->p->getServer()->getOnlinePlayers() as $player) {
      if (isset($this->p->vector[$player->getName()])) {
        if ($player->distance($this->p->vector[$player->getName()]) > 3) {
          $player->sendPopup('Осталось ' . $player->sendTip((int) $player->distance($this->p->vector[$player->getName()]) . ' м.'));
        }else{
          $player->sendMessage('Ты достиг места назначения');
          unset($this->p->vector[$player->getName()]);
        }
      }
    }
  }

}
