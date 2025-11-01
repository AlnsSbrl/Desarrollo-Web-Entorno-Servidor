<?php
    session_start();
    if(!isset($_SESSION["contacts"])){
        $_SESSION["contacts"] = array();
    }
    function doesContactExist($newContactName)
    {
        foreach($_SESSION["contacts"] as $contact=>$phone){
            if (strcasecmp($contact, $newContactName) === 0) {
                return true;
            }
        }
        return false;
    }

    $message="";
    $messageColor="";

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $name=trim($_POST["name"]);
        if(strlen($name)==0){
            $message="Name is required";
            $messageColor="red";
        }else{
            if(!doesContactExist($name) && filter_var($_POST["phone"], FILTER_VALIDATE_INT)) {
                $_SESSION["contacts"][$name] = $_POST["phone"];
                $message = "New contact successfully added";
                $messageColor = "green";
            }elseif(!doesContactExist($name) && (!filter_var($_POST["phone"], FILTER_VALIDATE_INT) || strlen($_POST["phone"]) == 0)){
                $message="A valid phone number is required for a new contact";
                $messageColor="red";
            }elseif(doesContactExist($name)){
                if(strlen($_POST["phone"])==0){
                    unset($_SESSION["contacts"][$name]);
                    $message = "Contact successfully removed";
                    $messageColor="green";
                }elseif (strlen($_POST["phone"])>0&&filter_var($_POST["phone"], FILTER_VALIDATE_INT)){
                    $_SESSION["contacts"][$name]=$_POST["phone"];
                    $message = "Phone number updated for $name";
                    $messageColor="green";
                }
            }
        }
    }
?>

<!DOCTYPE>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Desarrollo Web Entorno Servidor 01</title>
        <link rel="stylesheet" href="main.css">
    </head>
    <body>
        <div class="contacts">
            <h1>Contacts</h1>
            <?php if(count($_SESSION["contacts"])>0):?>
                <table>
                    <tr>
                        <th>Name</th>
                        <th>Phone Number</th>
                    </tr>
                    <?php foreach($_SESSION["contacts"] as $contact=>$phone):?>
                        <tr>
                            <td><?=htmlspecialchars($contact)?></td>
                            <td><?=htmlspecialchars($phone)?></td>
                        </tr>
                    <?php endforeach;?>
                </table>
            <?php else:?>
                <p>No contacts.</p>
            <?php endif;?>
        </div>
        <div class="form">
            <form action="<?php echo $_SERVER["PHP_SELF"];?>" method="post">
                <label for="name">Name</label> <input type="text" name="name" id="name">
                <br>
                <label for="phone">Phone</label> <input type="tel" name="phone" id="phone">
                <br>
                <input type="submit" name="submit" value="Send">
                <br>
                <p style="color: <?=$messageColor?>">
                    <?=htmlspecialchars($message)?>
                </p>
            </form>
        </div>
    </body>
</html>

