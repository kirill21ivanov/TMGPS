<?php
namespace TMGPS;
use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\command\CommandSender;
use pocketmine\command\Command;
use pocketmine\math\Vector3;

Class TMGPS extends PluginBase implements Listener{

  public $settings, $vector;

  function onEnable() {
    $folder = $this->getDataFolder();
    if (!is_dir($folder)) {
      @mkdir($folder);
      $this->saveResource('confing.yml');
    }
    $this->getLogger()->info("GPS TheMuh229 загружены :з");
    $this->getLogger()->info("Автор плагина - vk.com/kivanov20040");
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $this->getScheduler()->scheduleRepeatingTask(new TMGPSTimer($this), 20);
  }

  public function onCommand(CommandSender $sender, Command $command, String $label, array $args) :bool{
    $data = $this->getConfig()->getAll();
    if ($command->getName() == "gps") {
      if (count($args) >= 1) {
        if ($args[0] == "set") {
          $name = $args[1];
          if (strlen($name) > 1) {
            $sender->sendMessage("Нажми на место, где будет находится обьект " . $name);
            $this->settings[$sender->getName()]["name"] = $name;
          }else{$sender->sendMessage("Название обьекта слишком кароткое"); return false;}
        }elseif(isset($data[$args[0]])) {
          $sender->sendMessage('Ты отметил точку назначения: '.$args[0]);
          $vector = new Vector3($data[$args[0]]["x"], $data[$args[0]]["y"], $data[$args[0]]["z"]);
          $this->vector[$sender->getName()] = $vector;
        }else{$sender->sendMessage("Использование: /gps название объекта \ set название объекта"); return false;}
      }else{$sender->sendMessage("Использование: /gps название объекта \ set название объекта"); return false;}
    }
  return false;}

  public function onTap(PlayerInteractEvent $evert) {
    $player = $evert->getPlayer();
    $data = $this->getConfig()->getAll();
    $x = $evert->getBlock()->getFloorX();
    $y = $evert->getBlock()->getFloorY();
    $z = $evert->getBlock()->getFloorZ();
    if (isset($this->settings[$player->getName()])) {
      $player->sendMessage("Ты обозначил место обьекта: ".$this->settings[$player->getName()]["name"]);
      $data[$this->settings[$player->getName()]["name"]]["x"] = $x;
      $data[$this->settings[$player->getName()]["name"]]["y"] = $y;
      $data[$this->settings[$player->getName()]["name"]]["z"] = $z;
      $this->getConfig()->setAll($data);
      $this->getConfig()->save();
      unset($this->settings[$player->getName()]);
    }
  }

}
