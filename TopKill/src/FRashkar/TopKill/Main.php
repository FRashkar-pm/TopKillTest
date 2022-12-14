<?php

namespace FRashkar\TopKill;

use pocketmine\Server;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginManager;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;

use pocketmine\entity\Entity;
use pocketmine\entity\EntitySizeInfo;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;

use pocketmine\nbt\tag\StringTag;

use pocketmine\utils\Config;
use pocketmine\utils\TextFormat;

use pocketmine\player\Player;

use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDeathEvent;

use pocketmine\event\Listener;

use pocketmine\event\player\PlayerDataSaveEvent;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerEvent;

use pocketmine\world\World;
use pocketmine\world\WorldManager;
use pocketmine\world\particle\FloatingTextParticle;

use pocketmine\math\Vector3;
    
use FRashkar\TopKill\Events\PlayerDeath;

class Main extends PluginBase implements Listener {
    
    public $kill;
    public $death;
    private static $instance;

    public function onEnable() : void {

        @mkdir($this->getDataFolder());
        $this->kill = new Config($this->getDataFolder(). "kill.yml", Config::YAML);
        $this->death = new Config($this->getDataFolder(). "death.yml", Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerDeath($this), $this);
        $this->getServer()->getCommandMap()->register("topkills", new TopKill($this));
        $this->getLogger()->info("Top Kill Actived!");
        $this->saveDefaultConfig();

    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
        switch($cmd->getName()) {
            case "topkill":
                if($sender instanceof Player) {
                    $x = $sender->getFloorX();
                    $y = $sender->getFloorY();
                    $z = $sender->getFloorZ();
                    $text = $texts;
                    $subtitle = $subtitles;
                    $sender->getWorld()->addParticle(new FloatingTextParticle(new Vector3($x, $y, $z), $text, $subtitle));

                } else {
                    $this->updateTopKill();
                }
                break;

        }
        return true;
    }
    public function onDisable() : void {
        $this->kill->save();
        $this->death->save();
    }
    public static function getInstance(){
        return self::$instance;
    }
}
