<?php
namespace app\common\command;
use app\common\controller\Recommend;
use app\common\logic\Game;
use app\common\logic\PigFlashBuy;
use think\console\Command;
use think\console\Input;
use think\console\Output;

/**
 * Created by PhpStorm.
 * User: Xgh
 * Date: 2018/12/29
 * Time: 17:19
 */
class ZopenGame extends Command
{
    //定时每晚入队
    protected function configure()
    {
        $this->setName('z_open_game')->setDescription('add_pig_list');
    }

    protected function execute(Input $input, Output $output)
    {
        $pfb = new Game();
        $pfb->ZopenGame();
    }
}