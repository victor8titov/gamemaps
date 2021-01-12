<?php

class Model_map extends Model {
  
  public function getData($id) {
    $url = 'files/hillside-house.mcworld';
    if (
      $id === 'Kq3VBaZftaD5FaqbvYdnz') {
      return $url;
    } else {
      return false;
    }
  }
}