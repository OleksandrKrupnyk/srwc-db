<?php


namespace zukr\author;


use zukr\base\Record;

class Author extends Record
{
    public function getTableName()
    {
        return 'autors';
    }


    public $id;
  public $id_u;
  public $suname;
  public $name;
  public $curse;
  public $email;
  public $place;
  public $active;
  public $arrival;
  public $phone;
  public $date;
  public $hash;
  public $email_recive;
  public $email_date;
  public $bprint;






}