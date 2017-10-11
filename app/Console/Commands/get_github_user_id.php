<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ClientException;
use SebastianBergmann\CodeCoverage\Node\Iterator;

class get_github_user_id extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get_github_user_id';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = '获取github用户id';


    /**
     * Execute the console command.
     *
     * @return mixed
     */

    private $users = ['chenleirr', 'zhengyunfeng', 'CycloneAxe', 'appleboy', 'Aufree', 'lifesign',
        'overtrue', 'zhengjinghua', 'NauxLiu'];

    public function handle()
    {
        //
        $this->info('============================');
        $client = new Client();
        $users = $this->users;

//        $res = $client->request('get', '192.168.202.52:8540/v1/group/get_list', ['query' =>
//        [
//            'from' => 300
//        ]]);
//        dd(json_decode($res->getBody()->getContents()));


        $requests = function () use ($users, $client) {
            foreach ($users as $key => $name) {
                $uri = 'https://api.github.com/users/' . $name;
                yield function () use ($uri, $client) {
                    return $client->getAsync($uri);
                };
            }
        };

        if ($requests instanceof \Closure) {
            dd(123);
        }

        $pool = new Pool($client, $requests(), [
            'concurrency' => count($users),
            'fulfilled'   => function ($response, $index){

                $res = json_decode($response->getBody()->getContents());

                $this->info("请求第 $index 个请求，用户 " . $this->users[$index] . " 的 Github ID 为：" .$res->id);

            },
            'rejected' => function ($reason, $index){
                $this->error("rejected" );
                $this->error("rejected reason: " . $reason );
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();
    }
}
