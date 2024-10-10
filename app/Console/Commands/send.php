<?php

namespace App\Console\Commands;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use Illuminate\Console\Command;

class send extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        broadcast(new MessageSent(new ChatMessage()));
    }
}
