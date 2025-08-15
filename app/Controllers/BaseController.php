<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

/**
 * Class BaseController
 *
 * BaseController provides a convenient place for loading components
 * and performing functions that are needed by all your controllers.
 * Extend this class in any new controllers:
 *     class Home extends BaseController
 *
 * For security be sure to declare any new methods as protected or private.
 */
abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = [];
    protected $session;
    protected $userRoles = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->session = session();

        if ($this->session->get('user_id')) {
            $db = db_connect();
            $this->userRoles = $db->table('user_roles')
                ->select('user_roles.role')
                ->where('user_roles.user_id', $this->session->get('user_id'))
                ->get()
                ->getResultArray();

            // Set variabel global ke semua view
            service('renderer')->setVar('userRoles', $this->userRoles);
        }
    }
}
