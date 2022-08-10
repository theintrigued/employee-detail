<?php
require_once('wp-load.php'); // add wordpress functionality

add_action('wp_enqueue_scripts', 'my_scripts_method');


function my_scripts_method()
{
    wp_enqueue_script(
        'custom-script',
        get_home_url() . '/custom_script.js', #your JS file
        array('jquery') #dependencies
    );
}


add_filter('the_content', 'makeRegistrationForm', 10);


function makeRegistrationForm($content)
{
/*
    $currentUserId = get_current_user_id();

    global $wp_query;

    if( $currentUserId != 0) {
        
    $post_id = $wp_query->post->ID;
    $currentPageId = $post_id;

    if ($currentPageId == 22) {
        $homeurl = get_home_url();
        if ($currentUserId != 0) {
            $avatarId = get_user_meta($currentUserId, 'avatarImageId', true);
           // echo "<img style='width:150px; height:150px'  src='" . wp_get_attachment_url($avatarId) . "'>";
        }

        return $content . <<<HTML
                        
                        
                        <div>
                        
                        <form>
                        <div>
                        <label for="" >Enter Username:</label>
                        </div>
                        <div style="margin-top:10px;">
                        <input type="text" id="usernameInput101" name="promocodeValue" style="width:50%">
                        </div>
                        </div>
                        <div>
                        <label for="" >Enter Password:</label>
                        </div>
                        <div style="margin-top:10px;">
                        <input type="text" id="passwordInput101" name="passValue" style="width:50%">
                        </div>
                        <div style="margin-top:10px;">
                        <div>
                        <label for="" >Enter Position at Company:</label>
                        </div>
                        <input id="jobInput101" type="text" name="position" list="jobs">
                        <datalist id="jobs">
                        <option value="Manager">
                        <option value="CEO">
                        <option value="Technical Manager">
                        <option value="Accountant">
                        </datalist>
                        </div>
                        <div>
                        <label for="" >Upload Avatar Image</label>
                        </div>
                        <div style="margin-top:10px;">
                        <input id="avatarImage" type="file" name="userImage" />
                        </div>
                        <div>
                        <label for="" >Enter Short Description</label>
                        </div>
                        <div style="margin-top:10px;">
                        <input id="shortDescription" type="text" name="userImage" />
                        <div>  
                        <label for="" >First Name</label>
        </div> 
                        <div style="margin-top:10px;">
                        <input id="firstName" type="text" name="userImage" />
                        <div> 
                        <label for="" >Last Name</label>
        </div> 
                        <div style="margin-top:10px;">
                        <input id="lastName" type="text" name="userImage" />
                        <div> 
                        <label for="" >GitHub</label>
        </div> 
                        <div style="margin-top:10px;">
                        <input id="gitHub" type="text" name="userImage" />
                        <div> 
                        <label for="" >Linked In</label>
        </div> 
                        <div style="margin-top:10px;">
                        <input id="linkedIn" type="text" name="userImage" />
                        <div> 
                        <label for="" >Xing</label>
        </div> 
                        <div style="margin-top:10px;">
                        <input id="xing" type="text" name="userImage" />
                        <div> 
                        <label for="" >FaceBook</label>
        </div> 
                        <div style="margin-top:10px;">
                        <input id="faceBook" type="text" name="userImage" />
                        </div> 
                                         
                        
                        
                    
                        <div style="margin-top:10px;">
                        <button class="submitButton12" type="button">Submit</button>
                        </div>
                        <div style="margin-top:10px;">
                        <label class="messageSentAlert" style="display:none; color:#00ff23;">Thank you for registering!</label>
                        </div>
                        <div style="margin-top:10px;">
                        <label class="invalidCodeAlert" style="display:none; color:#00ff23;">Something Went Wrong!</label>
                        </div>
                        </form>
                        </div>
                        
                       
                      
                      
                      
HTML;
    }
    }
    
*/
    return $content;

    
}
