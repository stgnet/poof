<?php

    class dbPoofSite extends dbWhere
    {
        public function __construct()
        {
            global $POOF_DIR;

            $db=dbCsv("$POOF_DIR/site-config.csv");
            $where=array('host'=>$_SERVER['HOSTNAME']);

            $fields=array(
                'host'=>array('type'=>"text",'desc'=>"Server"),
                'email'=>array('type'=>"text",'desc'=>"Owner Email"),
                'pass'=>array('type'=>"password",'desc'=>"Password",'clearonfocus'=>true),
                'erroremail'=>array('type'=>"checkbox",'desc'=>"Email Errors"),
            );

            $db->SetFields($fields,'host');

            parent::__construct($db,$where);

            $record=$db->lookup($where);
            if (!$record)
            {
                $record=array(
                    'host'=>$_SERVER['HOSTNAME'],
                    'email'=>$_SERVER['SERVER_ADMIN']
                );
                $db->insert($record);
            }
        }

        public function update($record,$where=NULL)
        {
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
