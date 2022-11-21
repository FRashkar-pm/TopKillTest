<?php

namespace FRashkar\TopKill;

use pocketmine\Server;

use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginBase;
use pocketmine\plugin\PluginManager;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

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

use slapper\events\SlapperCreationEvent;
use slapper\events\SlapperDeletionEvent;

use FRashkar\TopKill\Events\PlayerDeath;

class Main extends PluginBase implements Listener {
    
    public $kill;
    public $death;
    private WorldManager $WorldManager;

    public function onEnable() : void {
        
        $this->getServer()->getPluginManager()->getPlugin("Slapper");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        @mkdir($this->getDataFolder());
        $this->kill = new Config($this->getDataFolder(). "kill.yml", Config::YAML);
        $this->death = new Config($this->getDataFolder(). "death.yml", Config::YAML);
        $this->getServer()->getPluginManager()->registerEvents(new PlayerDeath($this), $this);
        $this->getLogger()->info("Top Kill Actived!");
        $this->saveDefaultConfig();

    }

    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args) : bool {
        switch($cmd->getName()) {
            case "topkill":
                if($sender instanceof Player) {

                } else {
                    $this->updateTopKill();
                }
                break;

        }
        return true;
    }

    public function onSlapperCreate(SlapperCreationEvent $event) {

        $entity = $event->getEntity();
        
        $name = $entity->getNameTag();

        if($name == "topkill") {
            $entity->namedtag->setString("topkill", "topkill");
            $this->updateTopKill();
        }
    }

    public function updateTopKill() {
        $config = $this->plugin->kill;

        arsort($config);

        $config = $config->getAll();

        $config = array_slice($config, 0, 9);

        $top = 1;

        $text = "Top Kill";

        foreach($config as $name => $value) {
            $text .= "\n" . $top . " - " . $name . " - " . $value;
            $top++;
        }

        foreach($this->getServer()->WorldManager->getWorlds() as $level) {
            foreach($level->getEntities() as $entity) {
                if($entity->namedtag->hasTag("topkill", StringTag::class)) {
                    if($entity->namedtag->getString("topmoney") == "topmoney") {
                        $entity->setNameTag($text);
                        $entity->getDataPropertyManager()->setFloat(Entity::DATA_BOUNDING_BOX_HEIGHT, 3);
                        $entity->getDataPropertyManager()->setFloat(Entity::DATA_SCALE, 0.0);
                    }
                }
            }
        }

    }

    public function onDisable() : void {
        $this->kill->save();
        $this->death->save();
    }
}
