Agnostic php package.

Small package for handling session vars and flash vars. 

It is an abstract class so it must be previously implemented by your own class in order to implement the verificateUserSession method.

Example:

```php
    use Oscarricardosan\_Session_\_Session_;
        
    class _MSession_ extends _Session_
    {
        
        /**
         * @param bool $if_fails_close_session
         * @return bool
         */
        public static function verificateUserSession($if_fails_close_session= true)
        {
            $token_user = self::getAttr('user_id');
            $user_data= // Code-sql that the user obtains from the session data.
            if (password_verify($_POST['password'], $hash)) {
                self::setUserData($user_data);
            }else{
                self::logout('/');
            }
        }
            
    }
    
```

Example to write
```php
    //In normal php

    session_start();
    $_SESSION["newsession"]=$value;
    $_SESSION["name"]= 'Juan';
    
    //With _Session_
    _MSession_::setAttr('newsession', $value);
    _MSession_::setAttr('name', 'Juan');
```

Example to read
```php
    //Normally

    session_start();
    if(isset($_SESSION["name"]))
        echo $_SESSION["name"];
    else 
        echo 'Sin nombre';
        
    $products= isset($_SESSION["products"]) && is_array($_SESSION["products"])?$_SESSION["products"]:[];
    

    //With _Session_
    echo _MSession_::getAttr('name', 'Sin nombre');
    
    $products= _MSession_::getAttr('products', []);
    
```


Accessing the user's data
```php

    _MSession_::verificateUserSession();
    
    print_r(_MSession_::userData());//Al user data 
    
    echo "Hola "._MSession_::userData()['name']; // Attribute in user data
    
```

Control of flash messages and flash errors, when you call getFlashErrors or getFlashMessages the session variable is automatically deleted.

```php

index_product.php

<form action="store_product.php">
    <input type="submit" value="Send">   
</form>

<div> Errores:<div> 
<ul style="color:darkgreen;">
<?php
     foreach(_MSession_::getFlashErrors as $error){
         echo "<li>$error</li>";
     }   
?>
</ul>

<div> Mensajes:<div> 
<ul style="color:darkgreen;">
<?php
     foreach(_MSession_::getFlashMessages as $message){
         echo "<li>$message</li>";
     }   
?>
</ul>




store_product.php

_MSession_::setFlashError('ID no encontrado');
_MSession_::setFlashMessage('Vuelve la proxima ;)');

```


Clean session


```php

    //Close session and redirect to specific page.
    _MSession_::logout('page_to_redirect.php');
    
    //Clears the session but does not delete the flash variables
    _MSession_::destroy(false);
    
    //Destroy the session and delete the flash variables
    _MSession_::destroy(true);

```