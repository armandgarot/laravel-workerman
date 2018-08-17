<?php

namespace ArmandGarot\LaravelWorkerman\Commands;

use Illuminate\Console\Command;
use Illuminate\Console\ConfirmableTrait;
use ArmandGarot\LaravelWorkerman\LaravelWorkermanServer;

class WorkermanCommand extends Command
{
    use ConfirmableTrait;

    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'workerman:server {action}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Workerman Server.';

    /**
     * Handle command.
     */
    public function handle()
    {
        switch ($this->argument('action')) {
			case 'start':
				$this->startServer();
				
				break;
			case 'stop':
				$this->stopServer();
			
				break;
			case 'status':
				$this->getServerStatus();
			
				break;
			default:
				$this->error($this->argument('action') . ' action does not exist, try one one thoses : start, stop, status');
				break;
		}
    }
	
	/**
	 * Start SocketIO server.
	 */
	protected function startServer() {
		LaravelWorkermanServer::start();
	}
	
	/**
	 * Stop SocketIO server.
	 */
	protected function stopServer() {
		LaravelWorkermanServer::stop();
	}
	
	/**
	 * Get SocketIO server status.
	 */
	protected function getServerStatus() {
		$status = LaravelWorkermanServer::getStatus();
		
		$this->info($status);
	}
}
