<?php

namespace Source\Interfaces;


interface ActiveRecord{
    
    public function store(array $data);
    
    public function show(array $data);
    
    public function delete(array $data);
    
    public function update(array $data);

    public function read();
    
}