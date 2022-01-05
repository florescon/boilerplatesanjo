<?php

namespace App\Domains\Auth\Services;

use App\Models\Customer;
use App\Services\BaseService;

/**
 * Class CustomerService.
 */
class CustomerService extends BaseService
{
    /**
     * RoleService constructor.
     *
     * @param  Customer  $customer
     */
    public function __construct(Customer $customer)
    {
        $this->model = $customer;
    }

}