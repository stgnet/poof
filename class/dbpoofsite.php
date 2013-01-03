<?php

    class dbPoofSite extends dbWhere
    {
        private $record;

        public function __construct()
        {
            global $POOF_DIR;

            $db=dbIni("$POOF_DIR/poofsite.ini");
            $where=array('server'=>$_SERVER['HOSTNAME']);

            $fields=array(
                'server'=>array('type'=>"text",'desc'=>"Server"),
                'host'=>array('type'=>"text",'desc'=>"Host",'disabled'=>true),
                'timezone'=>array('type'=>"text",'desc'=>"Timezone"),
                'email'=>array('type'=>"text",'desc'=>"Owner Email"),
                'pass'=>array('type'=>"password",'desc'=>"Password",'clearonfocus'=>true),
                'erroremail'=>array('type'=>"checkbox",'desc'=>"Email Errors"),
            );

            $db->SetFields($fields,'server');

            parent::__construct($db,$where);

            $this->record=$db->lookup($where);
            if (!$this->record)
            {
                $this->record=array(
                    'server'=>$_SERVER['HOSTNAME'],
                    'email'=>safe($_SERVER['SERVER_ADMIN']),
                    'host'=>safe($_SERVER['HTTP_HOST']),
                );
                $db->insert($this->record);
            }
        }
        public function Get($field)
        {
            return safe($this->record[$field]);
        }
        public function Set($field,$value)
        {
            $this->record[$field]=$value;
            $this->update($this->record);
        }

        public function update($record,$where=NULL)
        {
            // store the password encrypted
            if (!empty($record['pass']))
            {
                $pass=$record['pass'];
                if ($pass[0]!='$')
                {
                    $record['pass']=password_hash($pass,PASSWORD_DEFAULT);
                }
            }
            return parent::update($record);
        }
    }
