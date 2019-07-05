<?php

namespace Oscarricardosan\_Session_;


abstract class _Session_
{
    const SESION_NAME= 'PHPSESSID';
    const KEY_FLASH_MESSAGES= 'FlashMessages';
    const KEY_FLASH_ERRORS= 'FlashErrors';
    const KEY_USER_DATA= 'UserData_';

    /**
     * @return null
     */
    public static function start()
    {
        session_start();
        if(isset($_COOKIE[self::SESION_NAME])){
            setcookie(self::SESION_NAME, $_COOKIE[self::SESION_NAME], time()+1220000); //la sesion no caduque al cerrar el navegador
        }
        return null;
    }

    /**
     * @return null
     */
    public static function initializeSession()
    {
        if(!isset($_SESSION)){
            self::start();
            self::setAttr(
                self::KEY_FLASH_MESSAGES,
                self::getAttr(self::KEY_FLASH_MESSAGES, [])
            );
            self::setAttr(
                self::KEY_FLASH_ERRORS,
                self::getAttr(self::KEY_FLASH_ERRORS, [])
            );
        }
        return null;
    }

    /**
     * @param $attribute
     * @param $value
     * @return null
     */
    public static function setAttr($attribute, $value)
    {
        self::initializeSession();
        $_SESSION[$attribute]= $value;
        return null;
    }

    /**
     * @param $attribute
     * @param null $default_value
     * @return null
     */
    public static function getAttr($attribute, $default_value= null)
    {
        self::initializeSession();
        if(isset($_SESSION[$attribute])){
            return $_SESSION[$attribute];
        }else
            return $default_value;
    }

    /**
     * @param $key
     * @param $value
     * @return null
     */
    public static function setFlashError($error)
    {
        self::initializeSession();
        $_SESSION[self::KEY_FLASH_ERRORS][]= $error;
        return null;
    }

    /**
     * Return errors and clear var
     * @return array
     */
    public static function getFlashErrors()
    {
        self::initializeSession();
        $errors= $_SESSION[self::KEY_FLASH_ERRORS];
        $_SESSION[self::KEY_FLASH_ERRORS]= [];
        return $errors;
    }

    /**
     * @param $message
     * @return null
     */
    public static function setFlashMessage($message)
    {
        self::initializeSession();
        $_SESSION[self::KEY_FLASH_MESSAGES][]= $message;
        return null;
    }

    /**
     * Return messages and clear var
     * @return array
     */
    public static function getFlashMessages()
    {
        self::initializeSession();
        $messages= $_SESSION[self::KEY_FLASH_MESSAGES];
        $_SESSION[self::KEY_FLASH_MESSAGES]= [];
        return $messages;
    }

    /**
     * @param $attribute
     * @param null $default_value
     * @return null
     */
    public static function getAllAttributes()
    {
        self::initializeSession();
        return $_SESSION;
    }

    /**
     * @return array
     */
    public static function setUserData($user_data)
    {
        self::initializeSession();
        return self::setAttr(self::KEY_USER_DATA, $user_data);
    }

    /**
     * @return mixed
     */
    public static function userData()
    {
        self::initializeSession();
        return self::getAttr(self::KEY_USER_DATA);
    }

    /**
     * @param bool $if_fails_close_session
     * @return bool
     */
    abstract public static function verificateUserSession($if_fails_close_session= true);

    /**
     * @param string $redirect_to
     * @return null
     */
    public static function logout($redirect_to= "/")
    {
        self::destroy(false);
        if(isAjax()){
            echo json_encode(['message'=> 'Sessión cerrada', 'code'=> -101]);
        }else{
            self::destroy(false);
            header('Location: '.$redirect_to);
        }
        exit();
    }

    /**
     * @param bool $clear_flash_vars
     * @return null
     */
    public static function destroy($clear_flash_vars= true)
    {
        self::initializeSession();

        if(!$clear_flash_vars){
            //Manetener los errores y mensajes flash después de destruir la sesión
            $errors= self::getFlashErrors();
            $messages= self::getFlashMessages();
            session_unset();
            self::setAttr(self::KEY_FLASH_MESSAGES, $messages);
            self::setAttr(self::KEY_FLASH_ERRORS, $errors);
        }else{
            session_unset();
            session_destroy();
        }

        return null;
    }

}