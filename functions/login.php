<?php 

class Login{
    
    /* __constructor()
     * Constructor will be called every time Login class is called ($login = new Login())
     */
     public function __construct(){
         
        /* First check if user is logged in. */
        $this->isLoggedIn();
        
        /* If login data is posted call validation function. */
        if (isset($_POST["login"])) {
            $this->validateLogIn();
        }
        
    } /* End __construct() */
    
    
    /* function isLoggedIn()
     * Check if user is already logged in, if not then prompt login form.
     */
    public function isLoggedIn(){
        
    /* Require credentials for DB connection. */
    require ('config/dbconnect.php');

        if(!empty(@$_SESSION['user_id'])){       
            return TRUE;
        } else {                                
            include("views/loginform.php");
        }

    } /* End isLoggedIn() */
    
    
    /* function validateLogIn()
     * Function that validates user login data, cross-checks with database.
     */
    private function validateLogIn(){
    
    /* Require credentials for DB connection. */
    require ('config/dbconnect.php');
        
    /* Check that data has been submited through login form */
    if(isset($_POST['login'])){

        /* User input variables converted to string to prevent SQL injections */
        $user = mysqli_real_escape_string($conn,trim($_POST['username']));
        $userpsw = mysqli_real_escape_string($conn,trim($_POST['password']));


        /* Check that both fields are filled with values */
        if(!empty($user) && !empty($userpsw)){

            /* Query the username from DB, if response is greater than 0 it means that users exists & 
             * we continue to compare the password hash provided by the user side with the DB data. */

            $sql = "SELECT * FROM `users` WHERE username = '$user'";
            $result = mysqli_query($conn, $sql);
            if ($result->num_rows === 1) {
                
                $row = mysqli_fetch_array($result,MYSQLI_ASSOC);
                if (password_verify($userpsw, $row['password'])) {      // Example of password hash : $4x$80$Vcl0Wxr5DNeIg.Y52YiVOOePENcjQPJ88mrEacKP15S9kIhx.u6gy
                    header('Location: index.php');                      // If passwords are correct user is returned to main page.
                    $_SESSION['user_id'] = $user;                       // Username is set as Session user_id for this user.
                } else {
                    header('Location: index.php?login=false');          // If password is incorrect we give URL Get value ?login=false, we can show error message to user with this.
                }   /* /EndIF */
                
            }   /* /EndIF */
            
        }   /* /EndIF */
        } else {
            echo 'Please fill all fields.'; // Prompt user to fill all fields.
        }   /* /EndIF */

    } /* End validateLogIn() */
    
} /* End class Login */

?>

