<?php

namespace App\Interfaces;

use Illuminate\Http\Request;

interface UserRepositoryInterface
{
    public function index(Request $request);
    public function getById($id);
    public function store(array $data);
    public function update(array $data,$id);
    public function delete($id);
}
