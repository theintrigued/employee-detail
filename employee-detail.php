<?php

/**
 * Plugin Name:       Employee Detail
 * Description:       A WordPress Block Pugin to get employee data from the database!
 * Requires at least: 5.9
 * Requires PHP:      7.0
 * Version:           0.1.0
 * Author:            Ali W.
 * License:           Available to share and edit for any means needed by Inpsyde.
 *
 * @package           create-block
 */

class EmployeeDetail
{
    public function __construct()
    {
        add_action('init', [$this, 'create_block_employee_detail_block_init']);
        add_action('rest_api_init', [$this, 'createAPI']);
        
    }

    function createAPI() {
        register_rest_route('employeedetail/v1', 'namesAndIds', [
            'methods' => 'GET',
            'callback' =>  array($this,'getNamesAndIDs'),
        ]);

        register_rest_route('employeedetail/v1', 'preview/(?P<employeeid>\d+)', [
            'methods' => 'GET',
            'callback' =>  array($this,'getPreview'),
        ]);
    }


/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * The Call Back function renders the preview upon completion of the block registration.
 */

    function create_block_employee_detail_block_init()
    {

        register_block_type(__DIR__ . '/build', [
        'render_callback' =>  function ($attributes) {
        return $attributes['employeePreview'];

        },

        ]);
    }


/**
 * Get all 'subscribers' (an assumption), from the database with their required data
 * Uses get_user_meta() to retreieve data.
 * 
 * @return WP_REST_RESPONSE with all names and ids to the api request. 
 */

    function getNamesAndIDs()
    {

        $first_name_array = [];
        $last_name_array = [];
        $combined_names_array = [];

        $employee_user_ids = get_users(
            [
            'fields' => 'id',
            'role' => 'subscriber',
            ]
        );

        foreach ($employee_user_ids as $id) {
            $first_name_array[] = get_user_meta($id, 'first_name', true);
            $last_name_array[] = get_user_meta($id, 'last_name', true);
        }

        for ($i = 0; $i < count($first_name_array); $i++) {
            $combined_names_array[$i] =  $first_name_array[$i] . ' ' . $last_name_array[$i];
        }

        $namesAndIds = ['names' => $combined_names_array, 'ids' => $employee_user_ids];

        $response = new WP_REST_Response($namesAndIds);
        $response->set_status(200);

        return $response;
    }


/**
 * Generate the editor screen preview.
 * Takes all the employee data and makes up a HTML string to send as a response.
 * 
 * @return WP_REST_RESPONSE with the required ready HTML preview.
 */

    function getPreview($request)
    {

    //Make Employee Preview HTML

        $EmployeePreview = 'No Data Available';
        $employeeId = $request['employeeid'];

        if ($employeeId >= 0) {
            $name = $this->getEmployeeName($employeeId);
            $description = $this->getEmployeeDescription($employeeId);
            $avatarURL = $this->getEmployeeAvatar($employeeId);
            $position = $this->getEmployeePosition($employeeId);
            $socialLinks = $this->getEmployeeSocialLinks($employeeId);

            $employeeData = ['name' => $name, 'description' => $description, 'avatarURL' => $avatarURL, 'position' => $position, 'socialLinks' => $socialLinks];

            $linkHTML = '<ul>';
            $counter = 0;

            for ($i = 0; $i < count($socialLinks); $i++) {
                if ($counter == 0) {
                    $linkHTML .= '<li>' . 'Github: ' . $socialLinks[0] . '</li>';
                }
                if ($counter == 1) {
                    $linkHTML .= '<li>' . 'Xing: ' . $socialLinks[1] . '</li>';
                }
                if ($counter == 2) {
                    $linkHTML .= '<li>' . 'Linkedin: ' . $socialLinks[2] . '</li>';
                }
                if ($counter == 3) {
                    $linkHTML .= '<li>' . 'Facebook: ' . $socialLinks[3] . '</li>';
                }
                $counter++;
            };

            $linkHTML .= '</ul>';

             $EmployeePreview = $this->generateHTML($linkHTML, $name, $position, $description, $avatarURL);
        }

        $dataToSend =  [ 'preview' => $EmployeePreview, 'employeeData' => $employeeData ];

        $response = new WP_REST_Response($dataToSend);
        $response->set_status(200);

        return $response;
    }

/**
 * Generate HTML code for the preview function
 * More like a 'helper' function to reduce the size of the preview function
 * 
 * @return string HTML.
 */
    function generateHTML($linkHTML, $name, $position, $description,$avatarURL)
    {

        return <<<HTML
    <div style='position:relative;'>
    <div style='margin-top: 20px;' class= 'wrapper' onclick='document.getElementById("overlay").style.display="block";'>
       <div class= 'innerwrapper'>
           <img height='70px' width=' 70px' src='$avatarURL'>
       </div>   
       <div class= 'innerwrapper'>
           <h4 style='margin:0px'> $name </h4>
           <p style='margin:0px'> $position </p>
       </div> 
       <div class= 'innerwrapper'>
           
       </div> 
   </div>
   <div style='margin-top: 20px;' id='overlay'>
       <h5 style='margin: 0px'> $name </h5>
       <p style='margin: 0px'> $description </p>
       <p style='margin: 0px'> $linkHTML </p>
       <button style='padding: 5px;' onclick='document.getElementById("overlay").style.display="none";' >Close</button>
   </div> 
   </div>     
HTML;
    }

/**
 * Get the employee first and last name from the user_meta table
 * Concatinate them to a single string.
 * 
 * @return string name.
 */

    function getEmployeeName($employeeId)
    {

        $id = $employeeId;

        $first_name;
        $last_name;
        $combined_name;

        $first_name = get_user_meta($id, 'first_name', true);
        $last_name = get_user_meta($id, 'last_name', true);

        $combined_name =  $first_name . ' ' . $last_name;

        return $combined_name;
    }

/**
 * Get the description from the user_meta table
 * 
 * @return string description.
 */

    function getEmployeeDescription($employeeId)
    {

        $id = $employeeId;

        $description = get_user_meta($id, 'description', true);

        return $description;
    }

/**
 * Get the avatar id from the user_meta table.
 * and then get an attachment URL using the id.
 * 
 * @return string avatar URL.
 */

    function getEmployeeAvatar($employeeId)
    {

        $id = $employeeId;

        $avatar_id = get_user_meta($id, 'avatarImageId', true);

        $avatar_url = wp_get_attachment_url($avatar_id);

        return $avatar_url;
    }

/**
 * Get the position from the user_meta table
 * 
 * @return string position.
 */

    function getEmployeePosition($employeeId)
    {

        $id = $employeeId;
        $position = get_user_meta($id, 'positionAtCompany', true);

        return  $position;
    }

/**
 * Get the social links from the user_meta table
 * 
 * @return array social links.
 */

    function getEmployeeSocialLinks($employeeId)
    {

        $id = $employeeId;
        $single_user_links = [];

        $single_user_links[] = get_user_meta($id, 'githubLink', true);
        $single_user_links[] = get_user_meta($id, 'xingLink', true);
        $single_user_links[] = get_user_meta($id, 'linkedinLink', true);
        $single_user_links[] = get_user_meta($id, 'facebookLink', true);

        return $single_user_links;
    }
}


$employeeDetail = new EmployeeDetail;