<?php
namespace Pderoubaix\Stack;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use M6Web\Component\Firewall\Firewall as m6fw;

class Firewall implements HttpKernelInterface
{
    /**
     * @var HttpKernelInterface
     */
    private $app;


    /**
     * @param HttpKernelInterface $app
     * @param array               $whiteList
     * @param array               $blackList
     * @param string              $ip
     */
    public function __construct(HttpKernelInterface $app, array $whiteList = array(), array $blackList = array() , $ip)
    {
        $this->app = $app;
        $this->firewall = new m6fw();

        $this->firewall
             ->setDefaultState(false)
             ->addList($whiteList, 'local', true)
             ->addList($blackList, 'localBad', false)
             ->setIpAddress($ip);

    }

    /**
     * {@inheritdoc}
     */
    public function handle(Request $request, $type = self::MASTER_REQUEST, $catch = true)
    {

        $connAllowed = $this->firewall->handle();
        var_dump($connAllowed);
        if (!$connAllowed) {
            var_dump('lll');
            return new Response(sprintf('IP %s is not allowed.', 'll'), 403);
        }

        return $this->app->handle($request, $type, $catch);
    }
}