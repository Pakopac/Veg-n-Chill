<?php

namespace Model;

use Cool\DBManager;
use Cool\BaseController;

class AdminManager
{
    public function getTotalOfUsers()
    {
        return count(scandir(ini_get("session.save_path"))) - 2;
    }
}